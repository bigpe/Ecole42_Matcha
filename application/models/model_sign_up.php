<?php
require_once "mail.php";

class Model_Sign_up extends Model{

    function pre_sign_up($email){
        $token = $this->create_token($email);
        $text = "Registration Confirm";
        $message = $this->model->create_message($text);
        $err = 0;
        if(!send_mail($email, $message))
            $err = 1; //Письмо не отправлено
        $error_name = parent::error_handler($err);
        return($error_name);
    }
    function check_email($email){

    }
    function create_token($email){
        $token = hash('md5',"$email" . time());
        return ($token);
    }

}
