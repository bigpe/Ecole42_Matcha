<?php
class Controller_Sign_up extends Controller{
    function __construct(){
        $this->model = new Model_Sign_up();
        $this->view = new View();
    }
    function action_pre_sign_up()
    {
        $email = $_POST['email'];
        $err = $this->model->pre_sign_up($email);
        $this->view->generate();
    }
}
?>