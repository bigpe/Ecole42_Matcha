<?php
class Controller_Activate extends Controller
{
    function __construct(){
        parent::__construct();
        $this->model = new Model_Activate();
    }
    function action_index()
    {
        $this->model->error_id = 2; #Error_code 2 - Token is invalid
        $error_code = $this->model->error_id;
        $this->view->generate("auth_view.php", "template_view.php",
            array("error" => $this->model->error_handler($error_code)));
    }

    function action_verify_sign_up(){
        if ($this->check_get_arguments_exists(array("token"))) {
            $token = $_GET['token'];
            $token_type = 1; #Type 1 - Pre-registration token
            $error_id = $this->model->verify_token($token, $token_type);
            if (!($error_id)) #Success
                header("Location: /registration/complete_sign_up/?token=$token");
            else
                $this->view->generate("registration_view.php", "template_view.php",
                    array("error" => $this->model->error_handler($error_id)));
        }
        else{
            $this->model->error_id = 11;
            $this->view->generate("registration_view.php", "template_view.php",
                array("error" => $this->model->error_handler($this->model->error_id)));
        }
    }
    function action_verify_restore_password(){
        if ($this->check_get_arguments_exists(array("token"))) {
            $token = $_GET['token'];
            $token_type = 2; #Type 2 - Restore_password token
            $error_id = $this->model->verify_token($token, $token_type);
            if (!($error_id)) #Success
                $this->view->generate("registration_restore_password_view.php", "template_view.php",
                    array("error" => $this->model->error_handler($error_id)));
            else
                $this->view->generate("registration_view.php", "template_view.php",
                    array("error" => $this->model->error_handler($error_id)));
        }
        else{
            $this->model->error_id = 11;
            $this->view->generate("registration_view.php", "template_view.php",
                array("error" => $this->model->error_handler($this->model->error_id)));
        }
    }
}