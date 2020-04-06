<?php
class Controller_Profile extends Controller
{
    function __construct(){
        $this->model = new Model_Profile();
        $this->view = new View();
    }

    function action_index(){
        if($this->model->check_session()) { #Success
            if (!$this->model->check_tutorial($_SESSION['login'])) { #Success
                $user_data = $this->model->get_user_data($_SESSION['login']);
                $this->view->generate("profile_view.php", "template_view.php",
                    array("error" => $this->model->error_handler($this->model->error_id),
                        "user_data" => $user_data));
            }
            else
                header("Location: /");
        }
        else
            header("Location: /");
    }
    function action_view(){
        if($this->check_get_arguments_exists(array("login"))){ #Success
            if(isset($_SESSION['login'])) {
                if ($_GET['login'] == $_SESSION['login'])
                    header("Location: /profile");
            }
            $user_data = $this->model->get_user_data($_GET['login']);
            if(!$user_data) #User Not Found
                header("Location: /profile");
            $this->view->generate("profile_view.php", "template_view.php",
                array("error" => $this->model->error_handler($this->model->error_id),
                    "user_data" => $user_data));

        }
    else
        header("Location: /");
    }

    function action_like()
    {
        if(isset($_POST['login'])) { #Success
            $this->model->put_like($_POST['login']);
            print $this->model->check_ready_to_chat($_POST['login']);
        }
    }

    function action_save_settings(){
        if($this->check_post_arguments_exists(array("settings"))) {
            if($this->model->check_session())
                $this->model->save_settings($_POST['settings'], $_SESSION['login']);
        }
    }
    function action_load_tags(){
        header("Content-Type: application/json");
        if($this->model->check_session())
            print(json_encode($this->model->get_user_not_selected_tags($_SESSION['login'])));
    }
    function action_report_fake(){
        if($this->check_post_arguments_exists(array("login"))) {
            print($this->model->report_user($_POST['login']));
        }
    }
}
?>