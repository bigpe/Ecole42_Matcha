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
        if($this->model->check_session($this->model)) #Success
            header("Location: /");
        else
            $this->view->generate("auth_view.php", "template_view.php",
                array("error" => $this->model->error_handler($this->model->error_id)));
    }
    function action_sign_in()
    {
        if ($this->check_post_arguments_exists(array("login", "password"))) {
            $login = $_POST['login'];
            $password = md5($_POST['password']);
            $error_code = $this->model->sign_in($login, $password);
            if (!$error_code) #Success
                header("Location: /Profile");
            else
                $this->view->generate("auth_view.php", "template_view.php",
                    array("error" => $this->model->error_handler($this->model->error_id)));
        }
        else {
            $this->model->error_id = 11;
            if($this->model->check_session($this->model)) #Success
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
            header("Location: /Auth");
        }
    }
}
?>