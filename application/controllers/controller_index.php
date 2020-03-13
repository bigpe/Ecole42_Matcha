<?php
class Controller_Index extends Controller
{
    function __construct(){
        $this->model = new Model_Index();
        $this->view = new View();
    }

    function action_index()
    {
        $this->view = $this->model->check_session($this->model);
    }
}
?>