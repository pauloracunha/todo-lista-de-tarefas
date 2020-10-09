<!-- Page Heading -->
<header class="d-flex mb-3">
    <h1 class="h3 mb-2 text-gray-800 col mr-auto">Minhas Atividades</h1>
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#registerTask">Cadastrar tarefa</button>
</header>
<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <?php if($tasks) : ?>
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Atividade</th>
                        <th>Data</th>
                        <th>Hora</th>
                        <th></th>
                    </tr>
                </thead>
                <tfoot>
                <tr>
                    <th>Atividade</th>
                    <th>Data</th>
                    <th>Hora</th>
                    <th></th>
                </tr>
                </tfoot>
                <tbody>
                <?php foreach($tasks as $task) : ?>
                    <tr id="task_<?= $task->id ?>" class="<?= ($task->completed=="yes" ?
                            'bg-success text-white' :
                            ($task->datetime()->daysuntil->invert==1 ?
                                'bg-danger text-white' :
                                ($task->datetime()->daysuntil->days<1 ?
                                    'bg-warning text-dark' :
                                    '')))
                    ?>">
                        <td><?= $task->name ?></td>
                        <td><?= $task->datetime()->date ?></td>
                        <td><?= $task->datetime()->time ?></td>
                        <td>
                            <?php if($task->completed!="yes") : ?>
                                <a class="btn btn-success icon-notext icon-check" href="<?= url("/task/{$task->id}") ?>" title="Marcar como feito" data-post="true" data-action="completed" data-page="<?= $page ?>"></a>
                            <?php else : ?>
                                <a class="btn btn-danger icon-notext icon-error" href="<?= url("/task/{$task->id}") ?>" title="Marcar como não feito" data-post="true" data-action="uncompleted" data-page="<?= $page ?>"></a>
                            <?php endif; ?>
                            <a class="btn btn-info icon-notext icon-pencil ml-2" href="<?= url("/task/{$task->id}") ?>" title="Editar tarefa" data-toggle="modal" data-target="#registerTask"></a>
                            <a class="btn btn-danger icon-notext icon-trash ml-2" href="<?= url("/task/{$task->id}") ?>" title="Deletar tarefa" data-post="true" data-action="delete" data-page="<?= $page ?>" data-confirm="Tem certeza que deseja deletar esta tarefa?"></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php else : ?>
                <p><b>Você ainda não tem tarefas cadastradas!</b></p>
            <?php endif; ?>
            <div class="d-flex">
                <?= $paginator ?? "" ?>
                <button type="button" class="btn btn-primary my-auto" data-toggle="modal" data-target="#registerTask">Cadastrar tarefa</button>
            </div>
        </div>
    </div>
</div>