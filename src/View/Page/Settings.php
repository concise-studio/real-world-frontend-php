<div class="settings-page">
    <div class="container page">
        <div class="row">
            <div class="col-md-6 offset-md-3 col-xs-12">
                <h1 class="text-xs-center">Your Settings</h1>
                
                <?= $this->widget("ErrorMessages", $errorMessages) ?>
                
                <form method="POST" action="/profile/save-settings">
                    <fieldset>
                        <fieldset class="form-group">
                            <input 
                                name="image"
                                class="form-control" 
                                type="text" 
                                placeholder="URL of profile picture"
                                value="<?= $settings['image'] ?>"
                            >
                        </fieldset>
                        <fieldset class="form-group">
                            <input 
                                name="username"
                                class="form-control form-control-lg" 
                                type="text" 
                                placeholder="Your Name"
                                value="<?= $settings['username'] ?>"
                            >
                        </fieldset>
                        <fieldset class="form-group">
                            <textarea 
                                name="bio"
                                class="form-control form-control-lg" 
                                rows="8" 
                                placeholder="Short bio about you"
                            ><?= $settings['bio'] ?></textarea>
                        </fieldset>
                        <fieldset class="form-group">
                            <input 
                                name="email"
                                class="form-control form-control-lg" 
                                type="text" 
                                placeholder="Email"
                                value="<?= $settings['email'] ?>"
                            >
                        </fieldset>
                        <fieldset class="form-group">
                            <input 
                                name="password"
                                class="form-control form-control-lg" 
                                type="password" 
                                placeholder="Password"
                                value=""
                            >
                        </fieldset>
                        <button class="btn btn-lg btn-primary pull-xs-right">
                            Update Settings
                        </button>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>
