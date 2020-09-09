<div class="auth-page">
    <div class="container page">
        <div class="row">

            <div class="col-md-6 offset-md-3 col-xs-12">
                <h1 class="text-xs-center">Sign in</h1>
                <p class="text-xs-center">
                    <a href="/register">Need an account?</a>
                </p>

                <?= $this->widget("ErrorMessages", $errorMessages) ?>

                <form method="POST" action="do-login">
                    <fieldset class="form-group">
                        <input 
                            name="email" 
                            class="form-control form-control-lg" 
                            type="text" 
                            placeholder="Email"
                            required
                            value="<?= $filled['email'] ?? "" ?>"
                        >
                    </fieldset>
                    <fieldset class="form-group">
                        <input 
                            name="password" 
                            class="form-control form-control-lg" 
                            type="password" 
                            placeholder="Password"
                            required
                        >
                    </fieldset>
                    <button class="btn btn-lg btn-primary pull-xs-right">
                        Sign in
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
