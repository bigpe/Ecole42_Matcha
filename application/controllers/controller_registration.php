<?php
class Controller_Registration extends Controller{
    function __construct(){
        parent::__construct();
        $this->model = new Model_Registration();
    }
    function action_index(){
        $error_code = $this->model->error_id;
        $this->view->generate("registration_view.php", "template_view.php",
            $this->model->error_handler($error_code));
    }
    function action_pre_sign_up(){
        if ($this->check_post_arguments_exists(array("email"))) {
            $email = $_POST['email'];
            if (!($error_id = $this->model->pre_sign_up($email))) #Success
                $this->view->generate("registration_send_mail_success_view.php", "template_view.php",
                    $this->model->error_handler($error_id));
            else
                $this->view->generate("registration_view.php", "template_view.php",
                    $this->model->error_handler($error_id));
        }
        else{
            $this->model->error_id = 11;
            $this->view->generate("registration_view.php", "template_view.php",
                $this->model->error_handler($this->model->error_id));
        }
    }
    function action_sign_up(){
        if ($this->check_post_arguments_exists(array("token", "email", "login", "password", "password_confirm"))) {
            $token = $_POST['token'];
            $email = $_POST['email'];
            $login = $_POST['login'];
            $password = md5($_POST['password']);
            $password_confirm = md5($_POST['password_confirm']);
            $password_len = strlen($_POST['password']);
            $error_id = $this->model->sign_up($token, $email, $login, $password, $password_confirm, $password_len);
            if (!$error_id) #Success
                $this->view->generate("registration_view.php", "template_view.php",
                    $this->model->error_handler($error_id));
            else
                $this->view->generate("registration_view.php", "template_view.php",
                    $this->model->error_handler($error_id));
        }
        else{
            $this->model->error_id = 11;
            $this->view->generate("registration_view.php", "template_view.php",
                $this->model->error_handler($this->model->error_id));
        }
    }
    function action_pre_restore_password(){
        $error_code = $this->model->error_id;
        $this->view->generate("registration_pre_restore_password_view.php", "template_view.php",
            $this->model->error_handler($error_code));
    }
    function action_restore_password(){
        if ($this->check_post_arguments_exists(array("email"))) {
            $email = $_POST['email'];
            $error_id = $this->model->restore_password($email);
            if (!$error_id) #Success
                $this->view->generate("registration_send_mail_success_view.php", "template_view.php",
                    $this->model->error_handler($error_id));
            else
                $this->view->generate("registration_pre_restore_password_view.php", "template_view.php",
                    $this->model->error_handler($error_id));
        }
        else{
            $this->model->error_id = 11;
            $this->view->generate("registration_pre_restore_password_view.php", "template_view.php",
                $this->model->error_handler($this->model->error_id));
        }
    }
    function action_restore_password_change(){
        if ($this->check_post_arguments_exists(array("token", "password", "password_confirm"))) {
            $token = $_POST['token'];
            $password = md5($_POST['password']);
            $password_confirm = md5($_POST['password_confirm']);
            $password_len = strlen($_POST['password']);
            $error_id = $this->model->restore_password_change($token, $password, $password_confirm, $password_len);
            if (!$error_id) #Success
                $this->view->generate("registration_pre_restore_password_view.php", "template_view.php",
                    $this->model->error_handler($error_id));
            else
                $this->view->generate("registration_pre_restore_password_view.php", "template_view.php",
                    $this->model->error_handler($error_id));
        }
        else{
            $this->model->error_id = 11;
            $this->view->generate("registration_pre_restore_password_view.php", "template_view.php",
                $this->model->error_handler($this->model->error_id));
        }
    }
}
?>