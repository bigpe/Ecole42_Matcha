<?php
class Controller_Profile extends Controller
{
    function __construct(){
        $this->model = new Model_Profile();
        $this->view = new View();
    }

    function action_index(){
<<<<<<< HEAD
        session_start();
        $_SESSION['login'] = "ukaron";
        $this->view->generate('profile_view.php', 'template_view.php');
=======
        if($this->model->check_session()) #Success
            if(!$this->model->check_tutorial($_SESSION['login'])){ #Success
                $user_data = $this->model->get_user_data($_SESSION['login']);
                $this->view->generate("profile_view.php", "template_view.php",
                    array("error" => $this->model->error_handler($this->model->error_id),
                        "user_data" => $user_data));
            }
        else
            header("Location: /");
>>>>>>> 79609902b648424b2d63b7df49a7106cb548ae21
    }
}
?>