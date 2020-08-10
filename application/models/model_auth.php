<?php
class Model_Auth extends Model{
    function sign_in($login, $password){
        if(!$this->check_login_password($login, $password))
            return($this->error_id = 10); #Error_code 10 - Login or password incorrect
        if(!$this->error_id){ #Success
            $_SESSION['login'] = $login;
            $this->save_session($login);
            $this->input_history_by_login($login, $login, 12);
        }
        return($this->error_id);
    }
    function sign_out(){
        $login = $_SESSION['login'];
        $this->input_history_by_login($login, $login, 13);
        $this->clear_session();
        return($this->error_id);
    }
    function clear_session(){
        $this->delete_session_in_db();
        session_unset();
        session_destroy();
    }
    function check_login_password($login, $password){
        $db = new database();
        return($db->db_check("SELECT user_id FROM USERS WHERE login='$login' AND password='$password'"));
    }

    function save_session($login){
        $db = new database();
        $session_id = session_id();
        $user_id= $db->db_read("SELECT user_id FROM USERS WHERE login='$login'");
        if($db->db_check("SELECT session_name FROM USERS_SESSIONS WHERE session_name ='$session_id'"))
            $db->db_change("UPDATE USERS_SESSIONS  SET USERS_SESSIONS.user_id='$user_id'
WHERE session_name='$session_id'");
        else
            $db->db_change("INSERT INTO USERS_SESSIONS (session_name, user_id)
                                SELECT '$session_id', user_id
                                FROM USERS WHERE user_id=user_id AND USERS.login='$login';");
    }

    function delete_session_in_db(){
        $db = new database();
        $session_id = session_id();
        $db->db_change("DELETE FROM USERS_SESSIONS WHERE session_name='$session_id'");
    }
    function check_email_exist($email){
        $db = new database();
        $login = $db->db_read("SELECT login FROM USERS WHERE email = '$email'");
        if (!$login)
            return 0;
        return $login;
    }
}
