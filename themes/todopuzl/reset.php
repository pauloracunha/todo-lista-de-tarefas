<div class="container">
    <!-- Outer Row -->
    <div class="row justify-content-center">

        <div class="col-xl-10 col-lg-12 col-md-9">

            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <!-- Nested Row within Card Body -->
                    <div class="row">
                        <div class="col-lg-6 d-none d-lg-block bg-password-image"></div>
                        <div class="col-lg-6">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-2">Recupere seu acesso?</h1>
                                </div>
                                <form class="user" action="<?= url("/recuperar/resetar"); ?>" method="post">
                                    <input type="hidden" name="code" value="<?= $code; ?>"/>
                                    <?= csrf_input(); ?>
                                    <div class="form-group">
                                        <input type="password" class="form-control form-control-user"
                                               name="password"
                                               placeholder="Sua nova senha">
                                        <input type="password" class="form-control form-control-user"
                                               name="password_re"
                                               placeholder="Confirme a nova senha">
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-user btn-block">
                                        Alterar senha
                                    </button>
                                </form>
                                <hr>
                                <div class="text-center">
                                    <a class="small" href="<?= url("/cadastrar"); ?>">Criar uma conta!</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>
