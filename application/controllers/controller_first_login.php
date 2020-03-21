<?php
class controller_first_login extends Controller{
    function __construct(){
        parent::__construct();
        $this->model = new Model_first_login();
    }
    function action_index()
    {
        if(!$this->model->check_tutorial($_SESSION['login'])) #Success
            header("Location: /");
        else
            $this->view->generate("first_login_view.php", "template_view.php",
                array("tags" => $this->action_get_tags(), "login" => $_SESSION['login']));
    }
    function action_end_tutorial(){
        if($this->check_post_arguments_exists(array("sex", "sex_preference", "user_age", "info",
            "tags", "user_main_photo", "user_geo", "user_full_name"))) { #Success
            $this->model->end_tutorial($_POST, $_SESSION['login']);
            $this->model->remove_tutorial($_SESSION['login'], 1);
            header("Location: /");
        }
        else {
            $this->model->error_id = 11;
            $this->view->generate("first_login_view.php", "template_view.php",
                array("error" => $this->model->error_handler($this->model->error_id)));
        }
    }
    function action_get_tags()
    {
        $tags = $this->model->get_tags();
        return($tags);
    }
    function action_load_tags(){
        header("Content-Type: application/json");
        $this->model->tag_offset = $this->model->tag_limit;
        $tags = $this->model->get_tags();
        print(json_encode($tags));
    }
    function action_save_photos(){
        $this->model->save_photos($_POST['images'], $_SESSION['login']);
    }
}
?>