<?php
class Controller_History extends Controller
{
    function __construct(){
        $this->model = new Model_History();
        $this->view = new View();
    }

    function action_index(){
        if($this->model->check_session()) { #Success
            if (!$this->model->check_tutorial($_SESSION['login'])) { #Success
                $data = $this->model->get_user_data($_SESSION['login']);
                $this->view->generate("history_view.php", "template_view.php",
                    array("error" => $this->model->error_handler($this->model->error_id),
                        "users_data" => $data));
            }
        }
        else
            header("Location: /");
    }
}
?>