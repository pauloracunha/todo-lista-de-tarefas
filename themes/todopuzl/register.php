<div class="container">

    <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
                <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
                <div class="col-lg-7">
                    <div class="p-5">
                        <div class="text-center">
                            <h1 class="h4 text-gray-900 mb-4">Crie sua conta!</h1>
                        </div>
                        <form class="user" action="<?= url("/cadastrar") ?>" method="post">
                            <?= csrf_input(); ?>
                            <div class="form-group row">
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <input type="text" class="form-control form-control-user" name="first_name"
                                           placeholder="Primeiro nome">
                                </div>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control form-control-user" name="last_name"
                                           placeholder="Último nome">
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="email" class="form-control form-control-user" name="email"
                                       placeholder="Seu E-mail">
                            </div>
                            <div class="form-group">
                                <label>Data de nascimento</label>
                                <input type="date" class="form-control form-control-user" name="datebirth"
                                       placeholder="Data de Nascimento">
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <input type="password" class="form-control form-control-user"
                                           name="password" placeholder="Senha">
                                </div>
                                <div class="col-sm-6">
                                    <input type="password" class="form-control form-control-user"
                                           name="confirm_password" placeholder="Confirme sua senha">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-user btn-block">
                                Cadastrar-se
                            </button>
                            <hr>
                            <!--<a href="_theme.php" class="btn btn-google btn-user btn-block">
                                <i class="fab fa-google fa-fw"></i> Cadastrar com Google
                            </a>
                            <a href="_theme.php" class="btn btn-facebook btn-user btn-block">
                                <i class="fab fa-facebook-f fa-fw"></i> Cadastrar com Facebook
                            </a>-->
                        </form>
                        <hr>
                        <div class="text-center">
                            <a class="small" href="<?= url("/recuperar") ?>">Esqueceu a senha?</a>
                        </div>
                        <div class="text-center">
                            <a class="small" href="<?= url("/entrar") ?>">Já tem uma conta? Faça login!</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>