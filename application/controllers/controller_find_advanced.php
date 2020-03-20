<?php
class controller_find_advanced extends Controller{
    function __construct()
    {
        $this->model = new Model_Find_advanced();
        $this->view = new View();
    }

    function action_index()
    {
        if($this->model->check_session()){ #Success
            $users_data = $this->model->get_users_data($_SESSION['login']);
            $this->view->generate("find_advanced_view.php", "template_view.php",
                array("error" => $this->model->error_handler($this->model->error_id),
                    "users_data" => $users_data));
        }
        else
            header("Location: /");
    }
}

?>