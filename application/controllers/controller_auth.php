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
        $this->view = $this->model->check_session($this->model);
    }
    function action_sign_in()
    {
        if ($this->check_post_arguments_exists(array("login", "password"))) {
            $login = $_POST['login'];
            $password = md5($_POST['password']);
            $error_code = $this->model->sign_in($login, $password);
            if (!$error_code) #Success
                $this->view = $this->model->check_session($this->model);
            else
                $this->view = $this->model->check_session($this->model);
        }
        else {
            $this->model->error_id = 11;
            $this->view = $this->model->check_session($this->model);
        }
    }
    function action_sign_out()
    {
        $error_code = $this->model->sign_out();
        if (!$error_code) #Success
            $this->view = $this->model->check_session($this->model);
        else
            $this->view = $this->model->check_session($this->model);
    }
}
?>