<?php

namespace Source\App;

use Source\Core\Controller;
use Source\Models\Auth;
use Source\Models\Category;
use Source\Models\Faq\Question;
use Source\Models\Post;
use Source\Models\Report\Access;
use Source\Models\Report\Online;
use Source\Models\Task;
use Source\Models\User;
use Source\Support\Pager;

/**
 * Web Controller
 * @package Source\App
 */
class Web extends Controller
{
    /* @var User $user*/
    protected $user;
    /**
     * Web constructor.
     */
    public function __construct()
    {
        parent::__construct(__DIR__ . "/../../themes/" . CONF_VIEW_THEME . "/");

        $this->user = Auth::user();
    }

    /**
     * SITE HOME
     */
    public function home(array $data): void
    {
        if(Auth::user() && Auth::user()->id) {
            $uid = Auth::user()->id;
            $tasks = (new Task())->find("user_id=:uid", "uid={$uid}");
            $pager = new Pager(url("/tasks/"));
            $pager->pager($tasks->count(), 25, $data["page"] ?? 1);
            if($tasks) {
                $tasks = $tasks->limit($pager->limit())->offset($pager->offset())->fetch(true);
                $paginator = $pager->render();
            }
        }
        $head = $this->seo->render(
            CONF_SITE_NAME . " - " . CONF_SITE_TITLE,
            CONF_SITE_DESC,
            url(),
            theme("/assets/images/share.jpg")
        );

        echo $this->view->render("home", [
            "head" => $head,
            "user" => $this->user,
            "tasks" => $tasks ?? null,
            "paginator" => $paginator ?? null,
            "page" => $data["page"] ?? 1
        ]);
    }

    public function tasks(array $data, bool $echo = true)
    {
        $uid = Auth::user()->id ?? $data["id"];
        $tasks = (new Task())->find("user_id=:uid", "uid={$uid}");
        $pager = new Pager(url("/tasks/"));
        $pager->pager($tasks->count(), 25, $data["page"] ?? 1);

        $json['content'] = $this->view->render("tasks", [
            "tasks" => $tasks->limit($pager->limit())->offset($pager->offset())->fetch(true),
            "paginator" => $pager->render(),
            "page" => $data["page"] ?? 1
        ]);
        if($echo) {
            echo json_encode($json);
        }
        return $json['content'];
    }

    public function task(array $data)
    {
        if(!$data["id"]){
            return;
        }
        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
        if($data["id"] != "nova"){
            $task = (new Task())->find("id=:i AND user_id=:uid", "i={$data["id"]}&uid={$this->user->id}")->fetch();
            if (!$task) {
                $json["message"] = $this->message->error("Você tentou acessar uma tarefa que não existe!")->render();
                echo json_encode($json);
                return;
            }
        }
        if(isset($data["action"])) {
            if ($data["action"] == "create") {
                $task = (new Task());
                $task->name = $data["name"];
                $task->datetime = $data["datetime"];
                $task->completed = isset($data["completed"]) ? "yes" : "no";
                if (!$task->save()) {
                    $json["message"] = $task->message()->render();
                    echo json_encode($task->data());
                    echo json_encode($json);
                    return;
                }
                $json["message"] = $this->message->success("Tarefa cadastrada com sucesso!")->render();
                $json["content"] = $this->tasks($data, false);
                echo json_encode($json);
                return;
            } else if ($data["action"] == "update") {
                $task->name = $data["name"];
                $task->datetime = $data["datetime"];
                $task->completed = isset($data["completed"]) ? "yes" : "no";
                if (!$task->save()) {
                    $json["message"] = $task->message()->render();
                    echo json_encode($json);
                    return;
                }
                $json["message"] = $this->message->success("Tarefa atualizada com sucesso!")->render();
                $json["content"] = $this->tasks($data, false);
                echo json_encode($json);
                return;
            } else if ($data["action"] == "delete") {
                if (!$task->destroy()) {
                    $json["message"] = $task->message()->render();
                    echo json_encode($json);
                    return;
                }
                $json["message"] = $this->message->success("Tarefa excluída com sucesso!")->render();
                $json["content"] = $this->tasks($data, false);
                echo json_encode($json);
                return;
            } else if ($data["action"] == "completed") {
                $task->completed = "yes";
                if (!$task->save()) {
                    $json["message"] = $task->message()->render();
                    echo json_encode($json);
                    return;
                }
                $json["message"] = $this->message->success("Tarefa completada com sucesso!")->render();
                $json["content"] = $this->tasks($data, false);
                echo json_encode($json);
                return;
            } else if ($data["action"] == "uncompleted") {
                $task->completed = "no";
                if (!$task->save()) {
                    $json["message"] = $task->message()->render();
                    echo json_encode($json);
                    return;
                }
                $json["message"] = $this->message->success("Tarefa editada com sucesso!")->render();
                $json["content"] = $this->tasks($data, false);
                echo json_encode($json);
                return;
            }
        }
        echo json_encode($task->data());
    }

    /**
     * SITE LOGIN
     * @param null|array $data
     */
    public function login(?array $data): void
    {
        if (Auth::user()) {
            $json["message"] = $this->message->success("Bem-vindo(a), {$this->user->last_name}");
            echo json_encode($json);
            return;
        }

        if (!empty($data['csrf'])) {
            if (!csrf_verify($data)) {
                $json['message'] = $this->message->error("Erro ao enviar, favor use o formulário")->render();
                echo json_encode($json);
                return;
            }

            if (request_limit("weblogin", 3, 60 * 5)) {
                $json['message'] = $this->message->error("Você já efetuou 3 tentativas, esse é o limite. Por favor, aguarde 5 minutos para tentar novamente!")->render();
                echo json_encode($json);
                return;
            }

            if (empty($data['email']) || empty($data['password'])) {
                $json['message'] = $this->message->warning("Informe seu email e senha para entrar")->render();
                echo json_encode($json);
                return;
            }

            $save = (!empty($data['save']) ? true : false);
            $auth = new Auth();
            $login = $auth->login($data['email'], $data['password'], $save);

            if ($login) {
                $this->message->success("Seja bem-vindo(a) de volta " . Auth::user()->first_name . "!")->flash();
                $this->tasks($data);
                return;
            } else {
                $json['message'] = $auth->message()->before("Ooops! ")->render();
            }

            echo json_encode($json);
            return;
        }
        $json["fullscreen"] = $this->view->render("login", []);
        echo json_encode($json);
    }

    /**
     * SITE PASSWORD FORGET
     * @param null|array $data
     */
    public function forget(?array $data)
    {
        if (Auth::user()) {
            $this->tasks($data);
        }

        if (!empty($data['csrf'])) {
            if (!csrf_verify($data)) {
                $json['message'] = $this->message->error("Erro ao enviar, favor use o formulário")->render();
                echo json_encode($json);
                return;
            }

            if (empty($data["email"])) {
                $json['message'] = $this->message->info("Informe seu e-mail para continuar")->render();
                echo json_encode($json);
                return;
            }

            if (request_repeat("webforget", $data["email"])) {
                $json['message'] = $this->message->error("Ooops! Você já tentou este e-mail antes")->render();
                echo json_encode($json);
                return;
            }

            $auth = new Auth();
            if ($auth->forget($data["email"])) {
                $json["message"] = $this->message->success("Acesse seu e-mail para recuperar a senha")->render();
            } else {
                $json["message"] = $auth->message()->before("Ooops! ")->render();
            }

            echo json_encode($json);
            return;
        }

        $json['fullscreen'] = $this->view->render("forgot-password", []);
        echo json_encode($json);
    }

    /**
     * SITE FORGET RESET
     * @param array $data
     */
    public function reset(array $data): void
    {
        if (Auth::user()) {
            $this->tasks($data);
        }

        if (!empty($data['csrf'])) {
            if (!csrf_verify($data)) {
                $json['message'] = $this->message->error("Erro ao enviar, favor use o formulário")->render();
                echo json_encode($json);
                return;
            }

            if (empty($data["password"]) || empty($data["password_re"])) {
                $json["message"] = $this->message->info("Informe e repita a senha para continuar")->render();
                echo json_encode($json);
                return;
            }

            list($email, $code) = explode("|", $data["code"]);
            $auth = new Auth();

            if ($auth->reset($email, $code, $data["password"], $data["password_re"])) {
                $this->message->success("Senha alterada com sucesso. Vamos controlar?")->flash();
                $json["redirect"] = url("/entrar");
            } else {
                $json["message"] = $auth->message()->before("Ooops! ")->render();
            }

            echo json_encode($json);
            return;
        }

        $json['fullscreen'] = $this->view->render("reset", ["code" => $data["code"]]);
        echo json_encode($json);
    }

    /**
     * SITE REGISTER
     * @param null|array $data
     */
    public function register(?array $data): void
    {
        if (Auth::user()) {
            $this->tasks($data);
        }

        if (!empty($data['csrf'])) {
            if (!csrf_verify($data)) {
                $json['message'] = $this->message->error("Erro ao enviar, favor use o formulário")->render();
                echo json_encode($json);
                return;
            }

            if (in_array("", $data)) {
                $json['message'] = $this->message->info("Informe seus dados para criar sua conta.")->render();
                echo json_encode($json);
                return;
            }

            $auth = new Auth();
            $user = new User();
            $user->bootstrap(
                $data["first_name"],
                $data["last_name"],
                $data["email"],
                $data["password"]
            );
            $user->datebirth = date('Y-m-d', strtotime($data["datebirth"]));
            if ($auth->register($user)) {
                //$data["id"] = $auth->id;
                $auth->login($data['email'], $data['password']);
                $this->tasks($data);
                return;
            } else {
                $json['message'] = $auth->message()->before("Ooops! ")->render();
            }

            echo json_encode($json);
            return;
        }

        $json['fullscreen'] = $this->view->render("register", []);
        echo json_encode($json);
    }

    /**
     * SITE NAV ERROR
     * @param array $data
     */
    public function error(array $data): void
    {
        $error = new \stdClass();

        switch ($data['errcode']) {
            case "problemas":
                $error->code = "OPS";
                $error->title = "Estamos enfrentando problemas!";
                $error->message = "Parece que nosso serviço não está diponível no momento. Já estamos vendo isso mas caso precise, envie um e-mail :)";
                $error->linkTitle = "ENVIAR E-MAIL";
                $error->link = "mailto:" . CONF_MAIL_SUPPORT;
                break;

            case "manutencao":
                $error->code = "OPS";
                $error->title = "Desculpe. Estamos em manutenção!";
                $error->message = "Voltamos logo! Por hora estamos trabalhando para melhorar nosso conteúdo para você controlar melhor as suas contas :P";
                $error->linkTitle = null;
                $error->link = null;
                break;

            default:
                $error->code = $data['errcode'];
                $error->title = "Ooops. Conteúdo indisponível :/";
                $error->message = "Sentimos muito, mas o conteúdo que você tentou acessar não existe, está indisponível no momento ou foi removido :/";
                $error->linkTitle = "Continue navegando!";
                $error->link = url_back();
                break;
        }

        $head = $this->seo->render(
            "{$error->code} | {$error->title}",
            $error->message,
            url("/ops/{$error->code}"),
            theme("/assets/images/share.jpg"),
            false
        );

        echo $this->view->render("404", [
            "head" => $head,
            "error" => $error
        ]);
    }

    public function logout()
    {
        Auth::logout();
        $json["message"] = $this->message->success("Você saiu com sucesso {$this->user->first_name}.")->render();
        $json["content"] = "";
        $json["fullscreen"] = $this->view->render("login", []);
        echo json_encode($json);
    }
}