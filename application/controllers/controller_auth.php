<?php
class Controller_Auth extends Controller
{
    function __construct()
    {
        parent::__construct();
        $this->model = new Model_Auth();
    }
    function action_index()
    {
        if($this->model->check_session()) #Success
            header("Location: /");
        else
            $this->view->generate("auth_view.php", "template_view.php",
                array("error" => $this->model->error_handler($this->model->error_id)));
    }
    function action_sign_in()
    {
        if ($this->check_post_arguments_exists(array("login", "password"))) {
            $login = quotemeta(htmlspecialchars($_POST['login'], ENT_QUOTES));
            $password = md5(quotemeta(htmlspecialchars($_POST['password'], ENT_QUOTES)));
            $error_code = $this->model->sign_in($login, $password);
            if (!$error_code) #Success
                header("Location: /");
            else
                $this->view->generate("auth_view.php", "template_view.php",
                    array("error" => $this->model->error_handler($this->model->error_id)));
        }
        else {
            $this->model->error_id = 11;
            if($this->model->check_session()) #Success
                $this->view->generate("main_page_view.php", "template_view.php",
                    array("error" => $this->model->error_handler($this->model->error_id)));
            else
                $this->view->generate("auth_view.php", "template_view.php",
                    array("error" => $this->model->error_handler($this->model->error_id)));
        }
    }
    function action_sign_out()
    {
        $error_code = $this->model->sign_out();
        if (!$error_code) { #Success
            header("Location: /");
        }
    }
    function action_with_google(){
        if (isset($_GET['code'])) {
            $ini = include($_SERVER['DOCUMENT_ROOT'] .'/config/config.php');
            $client = new Google_Client();
            $client->setClientId($ini['google']['clientID']);
            $client->setClientSecret($ini['google']['clientSecret']);
            $client->setRedirectUri($ini['google']['redirectUri']);
            $client->addScope("email");
            $client->addScope("profile");
            $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
            $client->setAccessToken($token['access_token']);
            $google_oauth = new Google_Service_Oauth2($client);
            $google_account_info = $google_oauth->userinfo->get();
            $email = $google_account_info->email;
            $name = $google_account_info->name;
            $login = $this->model->check_email_exist($email);
            if ($login)
            {
                $_SESSION['login'] = $login;
                $this->model->save_session($login);
                header("Location: /");
            }
            else{
                $token = $this->model->create_token($email);
                $token_id = $this->model->save_token($token, 1);
                $this->model->new_tmp_user($email, $token_id);
                header("location: /registration/complete_sign_up/?token=$token");
            }
        }
    }
}
?>