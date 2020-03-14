<?php
class Controller_Index extends Controller
{
    function __construct(){
        $this->model = new Model_Index();
        $this->view = new View();
    }

    function action_index()
    {
        if($this->model->check_session($this->model)) #Success
            header("Location: /Profile");
        else
            header("Location: /Auth");
    }
}
?>