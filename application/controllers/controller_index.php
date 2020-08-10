<?php
class Controller_Index extends Controller
{
    function __construct(){
        $this->model = new Model_Index();
        $this->view = new View();
    }

    function action_index()
    {
        if($this->model->check_session()) { #Success
            if(!$controller_name = $this->model->check_tutorial($_SESSION['login']))
                header("Location: /Profile");
            else #Tutorial not Submit
                header("Location: $controller_name");
        }
        else
            header("Location: /Auth");
    }
}
?>