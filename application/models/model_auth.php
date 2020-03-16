<?php
class Model_Auth extends Model{
    function sign_in($login, $password){
        if(!$this->check_login_password($login, $password))
            return($this->error_id = 10); #Error_code 10 - Login or password incorrect
        if(!$this->error_id){ #Success
            $_SESSION['login'] = $login;
        }
        return($this->error_id);
    }
    function sign_out(){
        $this->clear_session();
        return($this->error_id);
    }
    function clear_session(){
        session_unset();
        session_destroy();
    }
    function check_login_password($login, $password){
        $db = new database();
        return($db->db_check("SELECT user_id FROM USERS WHERE login='$login' AND password='$password'"));
    }
}
