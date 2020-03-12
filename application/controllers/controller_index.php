<?php
class Controller_Index extends Controller
{
    function __construct(){
        $this->model = new Model_Index();
        $this->view = new View();
    }

    function action_index(){
        $this->view->generate('index_view.php', 'template_view.php');
    }
}
?>