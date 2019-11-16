<?php

//Use Database abstraction libary.
use TinyDatabaseAccessLayer\DatabaseInsert as DBINSERT; 
use TinyDatabaseAccessLayer\DatabaseRetrieve as DBRETRIEVE;

class LoginController {

    /**
    *ControllerView var
    */
    var $ControllerView;

    /**
     * Class constructor.
     */
    public function __construct() {
        $this->ControllerView = new \LoginView();
    }

    /**
     * 
     */
    public function defaultAction() {
        $this->Login();
    }

    /*
     * Accepts a post request and processs it. 
     * If not a post then shows the login view.
     */
    public function Login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
            // $_POST['LoginFormData']['Email'];
            $LoginFormData = filter_input(INPUT_POST, 'LoginFormData', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);
            $UUID = $this->TryLogin($LoginFormData['Email'], $LoginFormData['Password']);
            empty($UUID) ? $this->ControllerView->Login(array('CREDERROR' => true)) : $this->SetLogin($UUID);
        } else {
            $this->ControllerView->Login();
        }
        return;
    }

    /**
     * Set the session login.
     * Show logging in prompt.
     * 
     * @param type $UUID
     * @param type $PromptWindow
     * @return type
     */
    private function SetLogin($UUID, $PromptWindow = null) {
        $_SESSION['Login']['isLOGGEDIN'] = true;
        $_SESSION['Login']['UUID'] = $UUID;
        $PromptWindow ?? $this->ControllerView->PromptWindow('Logging in', null, ['AUTOREDIRECT' => '/Home']);
        return;
    }

    /**
     * Destroys session and logs out.
     * Returns a prompt view
     * 
     * @return type
     */
    public function Logout() {

        session_destroy();
        $this->ControllerView->
                PromptWindow('Thanks for stopping by..', null, ['AUTOREDIRECT' => '/Home', 'AUTOTIME' => 4]);
        return;
    }

    /**
     * Process sign up form if post.
     * Shows signup form if not.
     * 
     * @return type
     */
    public function SignUp() {
        //Check logged in
        //If this is a post deal with that
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {

            $SignUpData = filter_input(INPUT_POST, 'SignUpFormData',
                    FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);

            if ($SignUpData['CaptchaCode'] !== $_SESSION['SignUpCaptchaCode']) {

                $CaptchaData = $this->CaptchaGenerate();
                $_SESSION['SignUpCaptchaCode'] = $CaptchaData['Code'];

                $this->ControllerView->
                        SignUp(array('Error' => 'Opps! incorrect captcha code',
                            'CaptchaImage' => $CaptchaData['ImageData']));

                return;
            }

            if ($this->checkUserEmailExist($SignUpData['Email'])) {

                $CaptchaData = $this->CaptchaGenerate();
                $_SESSION['SignUpCaptchaCode'] = $CaptchaData['Code'];

                //Show signup with email taken error
                $this->ControllerView->
                        SignUp(array('Error' => 'Looks like someone has that email',
                            'CaptchaImage' => $CaptchaData['ImageData']));
            } else {

                //Save data and show welcome prompt
                $UUID = $this->SignUpUser($SignUpData['Email'],
                        $this->getHashedPassword($SignUpData['Password']));

                $this->SetLogin($UUID, false);

                $this->ControllerView->
                        PromptWindow('Welcome to the app!', 'Add weather? Or go to the home page',
                                array('OKBUTTON' => '/Weather', 'Buttons' => array('Home' => '/Home')));
                return;
            }
        } else {

            $CaptchaData = $this->CaptchaGenerate();
            $_SESSION['SignUpCaptchaCode'] = $CaptchaData['Code'];
            $this->ControllerView->SignUp(array('CaptchaImage' => $CaptchaData['ImageData']));
        }
        return;
    }

    //Functions


    private function TryLogin($Email, $Password) {

        $login = new DBRETRIEVE();
        $login->query('Select
                            Users.Email,
                            Users.UUID,
                            UsersPasswords.Password
                        From
                            Users Inner Join
                            UsersPasswords On UsersPasswords.UUID = Users.UUID
                        Where
                            Users.Email = :UserEmail');


        $login->bind(':UserEmail', $Email);
        $login->execute();
        $logindetails = $login->single();

        //  var_dump($logindetails);


        if (!empty($logindetails)) {

            if (password_verify($Password, $logindetails['Password'])) {
                // $this->SetLogin($logindetails['UUID']);

                return $logindetails['UUID'];
                //return true;
            }
        }

        return null;
    }

    private function CaptchaGenerate() {
        $CaptchaData = null;

        $CaptchaCode = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
        $CaptchaCode = substr(str_shuffle($CaptchaCode), 0, 6);

        //Image details
        $fontsize = 25;
        $imgwidth = 140;
        $imgheight = 40;

        $image = imagecreate($imgwidth, $imgheight);
        imagecolorallocate($image, 255, 232, 255);

        $textcolor = imagecolorallocate($image, 0, 0, 0); // set captcha text color
        imagettftext($image, $fontsize, 0, 15, 30, $textcolor, '/var/www/Roboto-Black.ttf', $CaptchaCode);
        ob_start();
        imagejpeg($image); 
        $contents = ob_get_contents();
        ob_end_clean(); 
        $CaptchaData['ImageData'] = "data:image/jpeg;base64," . base64_encode($contents); //return the image as base64.  Magic
        $CaptchaData['Code'] = $CaptchaCode;
      
        return $CaptchaData;
    }

    protected function SignUpUser($Email, $Password) {

        $uuid = uniqid();

        $signup = new DBINSERT();
        $signup->query('Insert into Users (UUID, Email) VALUES (:UUID, :Email)');
        $signup->bind(':UUID', $uuid);
        $signup->bind(':Email', $Email);

        $signup->execute();

        $signup->query('Insert into UsersPasswords (UUID, Password) VALUES (:UUID, :Password)');
        $signup->bind(':UUID', $uuid);
        $signup->bind(':Password', $Password);

        $signup->execute();

        return $uuid;
    }

    /**
     * Returns a hashed string using one way encryption,
     * default to bcrypt.
     * 
     * @param string $Input
     * @return string
     */
    protected function getHashedPassword($Input) {
        $Output = password_hash($Input, PASSWORD_DEFAULT);
        return $Output;
    }

    /**
     * Checks if an email address exists already.
     *  
     * 
     * @param type $Email
     * @return boolean
     */
    protected function checkUserEmailExist($Email) {

        $UserEmail = new DBRETRIEVE();

        $UserEmail->query('SELECT Email FROM Users WHERE Email = :UsersEmail');
        $UserEmail->bind(':UsersEmail', $Email);
        $UserEmail->execute();
        $UserEmail = $UserEmail->single();

        if (empty($UserEmail)) {
            return false;
        }
        return true;
    }

}
