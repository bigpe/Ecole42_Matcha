<?php
class Controller_Matcha extends Controller
{
    function __construct(){
        $this->model = new Model_Matcha();
        $this->view = new View();
    }
    function action_index(){
        if($this->model->check_session())
            $this->view->generate("matcha_view.php", "template_view.php",
                array("users_count" => $this->model->check_users_count($_SESSION['login'])));
        else
            header("Location: /");
    }
    function action_get_matcha_users(){
        if($this->model->check_session())
            print(json_encode($this->model->get_recommend_users($_SESSION['login'])));
    }
    function action_user_matched(){
        if($this->model->check_session()){
            if($this->check_post_arguments_exists(array("login")))
                $this->model->user_matched($_SESSION['login'], $_POST['login']);
        }
    }
    function action_put_geo_users(){
        if($this->model->check_session()){
            if($this->check_post_arguments_exists(array("latitude"))){
                $this->model->put_geo_user($_SESSION['login'], $_POST['latitude'], $_POST['longitude']);
            }
        }
    }
}
?>