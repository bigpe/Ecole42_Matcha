<?php
require_once 'model_registration.php';
class Model_Settings extends Model_Registration
{
    public function change_password($old_pass, $new_pass, $new_pass_conf, $pass_len)
    {
        if(!$this->check_password_match($new_pass, $new_pass_conf))
            return($this->error_id = 6); // Пароли не совпадают.
        if (!$this->check_password_len($pass_len))
            return($this->error_id = 5);
        if (!$this->check_old_pass($old_pass))
            return($this->error_id = 12);
        if (!$this->change_pw($new_pass))
            return ($this->error_id = 13);
        return($this->error_id);
    }

    public function change_email($email)
    {
        if(!$this->check_email($email))
            return($this->error_id = 3);
        if (!$this->check_email_exists($email))
            return($this->error_id = 9);
        $token = $this->create_token($email);
        $token_id = $this->save_token($token, 3);
        $this->save_new_email($token_id, $email);
        $server_url = $_SERVER['HTTP_HOST'];
        $link = "http://$server_url/settings/verify_email/?token=$token";
        $message = $this->create_message("<a href='$link'>To change the email follow the link</a>");
        send_mail($email, $message);
        return ($this->error_id);
    }

    public function check_old_pass($old_pass)
    {
        $login  = $_SESSION['login'];
        $db = new database();
        return($db->db_check("SELECT password FROM USERS WHERE login = '$login' AND password = '$old_pass'"));
    }

    public function change_pw($new_pass)
    {
        $login = $_SESSION['login'];
        $db = new database();
        $db->db_change("UPDATE Matcha.USERS SET password = '$new_pass' WHERE login = '$login'");
        $this->input_history_by_login($login, $login, 7);
        return 1;
    }

    public function change_em($email)
    {
        $login = $_SESSION['login'];
        $db = new database();
        $db->db_change("UPDATE Matcha.USERS SET email = '$email' WHERE login = '$login'");
        $this->input_history_by_login($login, $login, 6);
    }

    public function save_new_email($token_id, $email){
        $db = new database();
        $db->db_change("INSERT INTO USER_TEMP (token_id, email) VALUES ('$token_id', '$email');");
    }
    function get_blocked_users($login){
        $db = new database();
        $blocked_users = $db->db_read_multiple("SELECT UU.login FROM USER_BLACK_LIST 
                    JOIN USERS U on USER_BLACK_LIST.user_id = U.user_id 
                    JOIN USERS UU on USER_BLACK_LIST.user_id_blocked = UU.user_id
                    WHERE U.login='$login'");
        for ($i = 0; $i < count($blocked_users); $i++) {
            $blocked_user_login = $blocked_users[$i]['login'];
            $blocked_users[$i]['photo_src'] = $db->db_read("SELECT photo_src FROM USER_MAIN_PHOTO 
                                JOIN USER_PHOTO UP on USER_MAIN_PHOTO.photo_id = UP.photo_id 
                                JOIN USERS U on UP.user_id = U.user_id WHERE login='$blocked_user_login'");
        }
        return($blocked_users);
    }
    function user_black_list_remove($login, $blocked_user_login){
        $db = new database();
        $user_id = $db->db_read("SELECT user_id FROM USERS WHERE login='$login'");
        $user_id_blocked = $db->db_read("SELECT user_id FROM USERS WHERE login='$blocked_user_login'");
        $db->db_change("DELETE FROM USER_BLACK_LIST WHERE user_id='$user_id' AND user_id_blocked='$user_id_blocked'");
    }
}