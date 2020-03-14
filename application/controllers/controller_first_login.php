<?php
class controller_first_login extends Controller{
    function __construct(){
        parent::__construct();
        $this->model = new Model_first_login();
    }
    function action_index()
    {
        $this->view->generate("first_login_view.php", "template_view.php");
    }
    function action_get_tags()
    {
        header("Content-Type: application/json");
        $tags = $this->model->get_tags();
        print(json_encode($tags));
    }
}
?>