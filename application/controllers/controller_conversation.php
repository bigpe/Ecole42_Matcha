<?php
define('PORT', "1488");
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
        if($this->model->check_session()) { #Success
            if ($this->check_get_arguments_exists(array("id"))) { #Success
                    $user_data = $this->model->get_chat_data($_GET['id']);
                    $this->view->generate("chat_view.php", "template_view.php",
                        array("error" => $this->model->error_handler($this->model->error_id),
                            "users_data" => $user_data));
            }
            else
                header("Location: /");
        }
        else
            header("Location: /");
    }


    function action_save_message(){
        if ($this->check_post_arguments_exists(array("user_to", "message", "type")))
            $user_status = $this->model->save_message($_POST['user_to'], $_POST['message']);
        $user_status['last_online'] == 'Online' ? print (true) : print (false);
    }

    function action_handler_message(){
        if ($this->check_post_arguments_exists(array("chat_id", "start_message")))
            print (json_encode($this->model->handler_message($_POST['chat_id'], $_POST['start_message'])));
    }

    function action_change_message_status(){
        if ($this->check_post_arguments_exists(array("chat_to")))
            $this->model->edit_message_status($_POST['chat_to']);
    }

}