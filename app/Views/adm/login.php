
    <section id="login">
        <div class="container">
            <div class="row justify-content-md-center">

                <div class="col-6 bg-white rounded-3 p-5">
                    <div class="text-center">
                        <img src="<?= base_url('img/logo.png') ?>" class="w-75" alt="<?= session()->get('SEO_title') ?> ADM" />
                    </div>
                    <h1 class="h3 text-center mt-4">Acesso à área administrativa</h1>
                    <?php if (session()->getFlashdata('msg')) : ?>
                        <div class="alert alert-danger"><?= session()->getFlashdata('msg') ?></div>
                    <?php endif; ?>
                    <form action="<?= base_url('login/auth') ?>" method="post" id="formLogin">
                        <div class="mb-3">
                            <label for="InputForEmail" class="form-label">E-mail:</label>
                            <input type="email" name="InputForEmail" class="form-control" id="InputForEmail" value="<?= set_value('email') ?>" required />
                        </div>
                        <div class="mb-3">
                            <label for="InputForPassword" class="form-label">Senha:</label>
                            <input type="password" name="InputForPassword" class="form-control" id="InputForPassword" required />
                        </div>
                        <div class="g-recaptcha" id="grec_formLogin" data-sitekey="<?= $gsitekey ?>"></div>
                        <button type="button" class="btn btn-primary me-5" onclick="submitForm('formLogin')" disabled>Enviar</button>
                        <button type="button" class="btn btn-outline-danger me-5" onclick="resetForm('formLogin')">Limpar</button>
                        <a class="btn btn-outline-warning" onclick="esqueciSenha()">Esqueci a senha</a>
                    </form>
                </div>

            </div>
        </div>
    </section>