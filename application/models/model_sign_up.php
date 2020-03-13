<?php
require_once "mail.php";

class Model_Sign_up extends Model{
    private $err = 0;

    function pre_sign_up($email){
        $server_url = $_SERVER['HTTP_HOST'];
        $token = $this->create_token($email);
        $text = "<p>Registration Confirm</p>
<a href='$server_url/activate?token=$token'>Click to Complete Registration</a>";
        $message = $this->model->create_message($text);

        if(!send_mail($email, $message))
            $this->err = 1; //Письмо не отправлено
        if($this->err != 0)
            $error_name = parent::error_handler($this->err);
        return($error_name);
    }
    function check_email($email){

    }
    function create_token($email){
        $token = hash('md5',"$email" . time());
        return ($token);
    }

}
