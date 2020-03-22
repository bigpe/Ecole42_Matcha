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
}