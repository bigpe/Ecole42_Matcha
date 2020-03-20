<?php
class Controller_Settings extends Controller
{
    function __construct(){
        $this->model = new Model_Settings();
        parent::__construct();
    }

    function action_index()
    {
        if($this->model->check_session()) #Success
            header("Location: /");
        else
            $this->view->generate("settings_view.php", "template_view.php",
                array("error" => $this->model->error_handler($this->model->error_id)));
    }

    function action_change_password()
    {
        $new_pass_conf = md5($_POST['new_pass_conf']);
        $new_pass = md5($_POST['new_pass']);
        $old_pass = md5($_POST['old_pass']);
        $pass_len = strlen($_POST['new_pass']);
        $res = $this->model->change_password($old_pass, $new_pass, $new_pass_conf, $pass_len);
        if (!$res)
            header("Location: /");
        else
            $this->view->generate("settings_view.php", "template_view.php", array('error' => $this->model->error_handler($res)));
    }

    function action_change_email()
    {
        $email = $_POST['new_email'];
        $res = $this->model->change_email($email);
        if (!$res)
            $this->view->generate('send_mail_success_view.php',  "template_view.php");
        else
            $this->view->generate("settings_view.php", "template_view.php", array('error' => $this->model->error_handler($res)));
    }

    function action_verify_email()
    {
        $token = $_GET['token'];
        $res = $this->model->verify_token($token, 3);
        if(!$res){
            $email = $this->model->get_email_from_token($token);
            $this->model->change_em($email);
            $this->model->delete_token($token);
            header("Location: /");
        }
        else
            $this->view->generate("settings_view.php", "template_view.php", array('error' => $this->model->error_handler($res)));
    }
}