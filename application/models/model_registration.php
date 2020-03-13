<?php
class Model_Registration extends Model{

    function pre_sign_up($email){
        $server_url = $_SERVER['HTTP_HOST'];
        $token = $this->create_token($email);
        $text = "<p>Registration Confirm</p>
                    <a href='http://$server_url/activate/verify_sign_up/?token=$token'>Click to Complete Registration</a>";
        $message = parent::create_message($text);
        if(!$this->check_email($email))
            return($this->error_id = 3); #Error_code 3 - Mail is invalid
        if(!$this->check_email_exists($email))
            return($this->error_id = 9); #Erorr_code 9 - Mail is already exists
        if(!send_mail($email, $message))
            return($this->error_id = 1); #Error_code 1 - Message server busy
        if(!($this->error_id)) {
            $db = new database();
            if($db->db_check("SELECT email FROM USER_TEMP WHERE email='$email'")){
                $token_id = $db->db_read("SELECT token_id FROM USER_TEMP WHERE email='$email'");
                $db->db_change("UPDATE TOKENS SET token='$token' WHERE token_id='$token_id'");
            }
            else {
                $token_type = 1; #Type 1 - Pre-registration token
                $db->db_change("INSERT INTO TOKENS (token, token_type) VALUE ('$token', '$token_type')");
                $token_id = $db->db_read("SELECT token_id FROM TOKENS WHERE token='$token'");
                $db->db_change("INSERT INTO USER_TEMP (email, token_id) VALUES ('$email', '$token_id')");
            }
        }
        return($this->error_id);
    }
    function sign_up($token, $email, $login, $password, $password_confirm, $password_len){
        if(!$this->check_email_fake($email, $token))
            return($this->error_id = 7); #Error_code 7 - Email not validating
        if(!$this->check_login_len($login))
            return($this->error_id = 4); #Error_code 4 - Login too short
        if(!$this->check_login_exists($login))
            return($this->error_id = 8); #Error_code 8 - Login already exists
        if(!$this->check_password_len($password_len))
            return($this->error_id = 5); #Error_code 5 - Password too short
        if(!$this->check_password_match($password, $password_confirm))
            return($this->error_id = 6); #Error_code 6 - Passwords doesn't match
        if(!$this->error_id){ #Success
            $this->delete_token($token);
            $db = new database();
            $db->db_change("INSERT INTO USERS (login, email, password) VALUES ('$login', '$email', '$password')");
        }
        return ($this->error_id);
    }
    function restore_password($email){
        $db = new database();
        $token = $this->create_token($email);
        $user_id = $db->db_read("SELECT user_id FROM USERS WHERE email='$email'");
        $server_url = $_SERVER['HTTP_HOST'];
        $text = "<p>Restore password Confirm</p>
                    <a href='http://$server_url/activate/verify_restore_password/?token=$token'>Click to Complete Password Restore</a>";
        $this->create_message($text);
        $token_type = 2; #Type 2 - restore_password_token
        if($this->check_email_exists($email))
            return($this->error_id = 3); #Error_code 3 - Email is invalid
        if(!$this->check_email($email))
            return($this->error_id = 3); #Error_code 3 - Email is invalid
        if(!send_mail($email, $text)){
            return($this->error_id = 1); #Error_code 1 - Message server busy
        }
        if(!$this->error_id){ #Success
            if(!$db->db_check("SELECT user_id FROM PASSWORD_TEMP WHERE user_id='$user_id'")) {
                $token_id = $this->save_token($token, $token_type);
                $db->db_change("INSERT INTO PASSWORD_TEMP (user_id, token_id) VALUES ('$user_id', '$token_id')");
            }
            else {
                $token_id = $db->db_read("SELECT token_id FROM PASSWORD_TEMP WHERE user_id='$user_id'");
                $db->db_change("UPDATE TOKENS SET token='$token' WHERE token_id='$token_id'");
            }
        }
        return ($this->error_id);
    }
    function restore_password_change($token, $password, $password_confirm, $password_len){
        if(!$this->check_password_len($password_len))
            return($this->error_id = 5); #Error_code 5 - Password too short
        if(!$this->check_password_match($password, $password_confirm))
            return($this->error_id = 6); #Error_code 6 - Passwords doesn't match
        if(!$this->error_id){ #Success
            $db = new database();
            $token_id = $db->db_read("SELECT token_id FROM TOKENS WHERE token='$token'");
            $user_id = $db->db_read("SELECT user_id FROM PASSWORD_TEMP WHERE token_id='$token_id'");
            $this->delete_token($token);
            $db->db_change("UPDATE USERS SET password='$password' WHERE user_id='$user_id'");
        }
        return ($this->error_id);
    }
    function check_email($email){
        if (!preg_match("/[0-9a-z]+@[a-z]+\.[a-z]/", $email))
            return (0);
        return (1);
    }
    function check_email_exists($email){
        $db = new database();
        if($db->db_check("SELECT email FROM USERS where email='$email'"))
            return (0);
        return (1);
    }
    function create_token($email){
        $token = hash('md5',"$email" . time());
        return ($token);
    }
    function check_email_fake($email){
        $db = new database();
        $token_id = $db->db_read("SELECT token_id FROM USER_TEMP WHERE email='$email'");
        if(!$db->db_check("SELECT token_id FROM TOKENS WHERE token_id='$token_id'"))
            return (0);
        return (1);
    }
    function check_login_len($login){
        if(strlen($login) < 4)
            return (0);
        return (1);
    }
    function check_login_exists($login){
        $db = new database();
        if($db->db_check("SELECT login FROM USERS WHERE login='$login'"))
            return(0);
        return (1);
    }
    function check_password_len($password_len){
        if($password_len < 6)
            return (0);
        return (1);
    }
    function check_password_match($password, $password_confirm){
        if($password != $password_confirm)
            return (0);
        return (1);
    }
}
