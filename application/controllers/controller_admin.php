<?php
class Controller_Admin extends Controller{
    function __construct(){
        $this->view = new View();
        $this->model = new Model_Admin();
    }
    function action_index(){
       if($this->model->check_session()){
           if($this->model->check_admin($_SESSION['login'])) {
               $users = $this->model->get_users();
               $users_reported = $this->model->users_reported();
               $this->view->generate("admin_view.php", "template_view.php", array(
                   "users" => $users,
                   "users_count" => $this->model->users_count(),
                   "users_reported" => $users_reported,
                   "users_reported_count" => count($users_reported)));
               }
           else
               header("Location: /");
       }
       else
           header("Location: /");
    }
    function action_hijack(){
        if($this->model->check_admin($_SESSION['login']))
            if($this->check_get_arguments_exists(array("login")))
                $this->model->user_hijack($_GET['login']);
            header("Location: /");
    }
    function action_create_users(){
        if($this->model->check_admin($_SESSION['login']))
            print(json_encode($this->model->create_users()));
    }
    function action_delete_user(){
        if($this->model->check_admin($_SESSION['login']))
            if($this->check_post_arguments_exists(array("login")))
                $this->model->delete_user($_POST['login']);
    }
    function action_delete_report(){
        if($this->model->check_admin($_SESSION['login']))
            if($this->check_post_arguments_exists(array("login")))
                $this->model->delete_report($_POST['login']);
    }
    function action_get_all_users(){
        if($this->model->check_admin($_SESSION['login']))
            print(json_encode($this->model->get_all_users()));
    }
}
?>