<?php
class Controller_Registration extends Controller{
    function __construct(){
        parent::__construct();
        $this->model = new Model_Registration();
    }
    function action_index(){
        if($this->model->check_session()) #Success
            header("Location: /");
        else
            $this->view->generate("registration_view.php", "template_view.php",
                array("error" => $this->model->error_handler($this->model->error_id)));
    }
    function action_pre_sign_up(){
        if($this->model->check_session()) #Success
            header("Location: /");
        else {
            if ($this->check_post_arguments_exists(array("email"))) {
                $email = $_POST['email'];
                if (!($error_id = $this->model->pre_sign_up($email))) #Success
                    $this->view->generate("registration_send_mail_success_view.php", "template_view.php",
                        array("error" => $this->model->error_handler($error_id)));
                else
                    $this->view->generate("registration_view.php", "template_view.php",
                        array("error" => $this->model->error_handler($error_id)));
            } else {
                $this->model->error_id = 11;
                $this->view->generate("registration_view.php", "template_view.php",
                    array("error" => $this->model->error_handler($this->model->error_id)));
            }
        }
    }
    function action_complete_sign_up(){
        if($this->check_get_arguments_exists(array("token"))){ #Success
            $token = $_GET['token'];
            $error_id = $this->model->verify_token($token, 1); # Token Type 1 - Registration Token
            if(!$error_id){ #Success
                $email = $this->model->get_email_from_token($token);
                $this->view->generate("registration_complete_sign_up.php", "template_view.php",
                    array("email" => $email, "error" => $this->model->error_handler($error_id)));
            }
            else
                $this->view->generate("registration_view.php", "template_view.php",
                    array("error" => $this->model->error_handler($error_id)));
        }
        else {
            $this->model->error_id = 11;
            $this->view->generate("registration_view.php", "template_view.php",
                array("error" => $this->model->error_handler($this->model->error_id)));
        }
    }
    function action_sign_up(){
        if($this->model->check_session()) #Success
            header("Location: /");
        else {
            if ($this->check_post_arguments_exists(array("token", "email", "login", "password", "password_confirm"))) {
                $token = $_POST['token'];
                $email = $_POST['email'];
                $login = $_POST['login'];
                $password = md5($_POST['password']);
                $password_confirm = md5($_POST['password_confirm']);
                $password_len = strlen($_POST['password']);
                $error_id = $this->model->sign_up($token, $email, $login, $password, $password_confirm, $password_len);
                if (!$error_id){ #Success
                    $this->model->create_first_login_tutorial($login);
                    header("Location: /");
                }
                else
                    $this->view->generate("registration_complete_sign_up.php", "template_view.php",
                        array("email" => $email, "error" => $this->model->error_handler($error_id)));
            } else {
                $this->model->error_id = 11;
                $this->view->generate("registration_view.php", "template_view.php",
                    array("error" => $this->model->error_handler($this->model->error_id)));
            }
        }
    }
    function action_pre_restore_password(){
        if($this->model->check_session()) #Success
            header("Location: /");
        else
            $this->view->generate("registration_pre_restore_password_view.php", "template_view.php",
                array("error" => $this->model->error_handler($this->model->error_id)));
    }
    function action_restore_password(){
        if($this->model->check_session()) #Success
            header("Location: /");
        else {
            if ($this->check_post_arguments_exists(array("email"))) {
                $email = $_POST['email'];
                $error_id = $this->model->restore_password($email);
                if (!$error_id) #Success
                    $this->view->generate("registration_send_mail_success_view.php", "template_view.php",
                        array("error" => $this->model->error_handler($error_id)));
                else
                    $this->view->generate("registration_pre_restore_password_view.php", "template_view.php",
                        array("error" => $this->model->error_handler($error_id)));
            } else {
                $this->model->error_id = 11;
                $this->view->generate("registration_pre_restore_password_view.php", "template_view.php",
                    array("error" => $this->model->error_handler($this->model->error_id)));
            }
        }
    }
    function action_restore_password_change(){
        if($this->model->check_session()) #Success
            header("Location: /");
        else {
            if ($this->check_post_arguments_exists(array("token", "password", "password_confirm"))) {
                $token = $_POST['token'];
                $password = md5($_POST['password']);
                $password_confirm = md5($_POST['password_confirm']);
                $password_len = strlen($_POST['password']);
                $error_id = $this->model->restore_password_change($token, $password, $password_confirm, $password_len);
                if (!$error_id) #Success
                    $this->view->generate("registration_pre_restore_password_view.php", "template_view.php",
                        array("error" => $this->model->error_handler($error_id)));
                else
                    $this->view->generate("registration_pre_restore_password_view.php", "template_view.php",
                        array("error" => $this->model->error_handler($error_id)));
            } else {
                $this->model->error_id = 11;
                $this->view->generate("registration_pre_restore_password_view.php", "template_view.php",
                    array("error" => $this->model->error_handler($this->model->error_id)));
            }

        }
    }
}
?>