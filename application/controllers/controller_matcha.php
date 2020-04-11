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
                $this->model->get_users($_SESSION['login']));
        else
            header("Location: /");
    }
}
?>