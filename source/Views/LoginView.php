<?php

/**
 * View class for Login and Signup
 */
class LoginView extends \SharedView
{

    /**
     * Login View
     * 
     * @param type $Options
     */
    public function Login($Options = [])
    {
        ?>
        <div class="container">
            <div class="card border-info shadow">
                <div class="card-header">Login</div>
                <div class="card-body">
                    <form method="POST" action="/Login">
                        <div class="form-group">
                            <label for="validationTooltip01">Email</label>
                            <input name="LoginFormData[Email]" type="text" class="form-control" id="validationTooltip01" placeholder="@ Email here" required>
                            <div class="valid-tooltip"></div>
                        </div>
                        <div class="form-group">
                            <label for="validationTooltip01">Password</label>
                            <input name="LoginFormData[Password]" type="password" class="form-control" id="validationTooltip01" placeholder="********" required>
                            <div class="valid-tooltip"></div>
                        </div>
                        <?php
                                if (array_key_exists('CREDERROR', $Options)) {
                                    echo 'Invalid email and/or password. Try again';
                                }
                                ?>
                        <input type="submit" class="btn btn-outline-primary btn-block text-center btnSubmit" value="Login" />
                    </form>
                </div>
            </div>
        <?php
            }

            /**
             * Login View
             * 
             * @param type $Options
             */
            public function SignUp($Options = array())
            {
                ?>
            <div class="container">
                <div class="card border-info shadow">
                    <div class="card-header">Sign Up</div>
                    <div class="card-body">
                        <form method="POST" action="/Login/Signup">
                            <?php
                                    if (array_key_exists('Error', $Options)) {
                                        echo '<h4>' . $Options['Error'] . ' </h4>';
                                    }
                                    ?>
                            <div class="form-group">
                                <label for="validationTooltip01">Enter your email</label>
                                <input name="SignUpFormData[Email]" type="text" class="form-control" id="validationTooltip01" placeholder="@ Email here" required>
                                <div class="valid-tooltip"></div>
                            </div>

                            <div class="form-group">
                                <label for="validationTooltip01">Pick a password</label>
                                <input name="SignUpFormData[Password]" type="password" class="form-control" id="validationTooltip01" placeholder="********" required>
                                <div class="valid-tooltip"></div>
                            </div>

                            <div class="form-group">
                                <label for="validationTooltip01">Confirm password</label>
                                <input name="SignUpFormData[PasswordConfirm]" type="password" class="form-control" id="validationTooltip01" placeholder="********" required>
                                <div class="valid-tooltip"></div>
                            </div>

                            <div class="form-group">
                                <?php echo '<img src="' . $Options['CaptchaImage'] . '" class="img-fluid" alt="Responsive image">'; ?>
                            </div>
                            <div class="form-group">
                                <label for="validationTooltip01">Enter the captcha code</label>
                                <input name="SignUpFormData[CaptchaCode]" type="text" class="form-control" id="validationTooltip01" placeholder="" required>
                                <div class="valid-tooltip"></div>
                            </div>
                            <input type="submit" class="btn btn-outline-primary btn-block text-center btnSubmit" value="Signup" />
                        </form>
                    </div>
                </div>
            </div>
        </div>
<?php
    }
}
