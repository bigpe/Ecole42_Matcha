<?php
class Controller_Conversation extends Controller
{
    function __construct()
    {
        $this->model = new Model_Conversation();
        $this->view = new View();
    }

    function action_index(){
        if($this->model->check_session()) { #Success
            if (!$this->model->check_tutorial($_SESSION['login'])) { #Success
                $user_data = $this->model->get_user_data($_SESSION['login']);
                $this->view->generate("conversation_view.php", "template_view.php",
                    array("error" => $this->model->error_handler($this->model->error_id),
                        "users_data" => $user_data));
            }
            else
                header("Location: /");
        }
        else
            header("Location: /");
    }
    function action_chat_view(){
        if($this->check_get_arguments_exists(array("id"))){ #Success
            $user_data = $this->model->get_chat_data($_GET['id']);
            $this->view->generate("chat_view.php", "template_view.php",
                array("error" => $this->model->error_handler($this->model->error_id),
                    "users_data" => $user_data));
        }
        else
            header("Location: /");
    }
}