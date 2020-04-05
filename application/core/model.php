<?php
class Model
{
    public $error_id = 0;
    public $view;
    public $model;

    public function __construct()
    {

    }

    public function get_data()
    {

    }
    function create_message($text){
        $message = "<h2>Matcha Project</h2>
                    $text";
        return ($message);
    }
    function error_handler($error_id){
        $db = new database();
        $error_name = $db->db_read("SELECT error_name FROM ERROR_HANDLER WHERE error_id='$error_id'");
        return($error_name);
    }
    function save_token ($token, $token_type){
        $db = new database();
        $db->db_change("INSERT INTO TOKENS (token, token_type) VALUES ('$token', '$token_type')");
        $token_id = $db->db_read("SELECT token_id FROM TOKENS WHERE token='$token'");
        return($token_id);
    }
    function verify_token ($token, $token_type){
        $db = new database();
        if($db->db_check("SELECT token FROM TOKENS WHERE token_type='$token_type' AND token='$token'"))
            return (0);
        return(2); #Error_code 2 - Token is invalid
    }
    function delete_token ($token){
        $db = new database();
        $db->db_change("DELETE FROM TOKENS WHERE token='$token'");
    }
    public function get_email_from_token($token){
        $db = new database();
        $token_id = $db->db_read("SELECT token_id FROM TOKENS WHERE token='$token'");
        $email = $db->db_read("SELECT email FROM USER_TEMP WHERE token_id='$token_id'");
        return ($email);
    }
    function check_session(){
        if(isset($_SESSION['login']) && $this->check_login_exists($_SESSION['login'])) #Success
            return (1);
        return (0);
    }
    function check_tutorial($login){
        $db = new database();
        $user_id = $db->db_read("SELECT user_id FROM USERS WHERE login='$login'");
        $tutorial_id = $db->db_read("SELECT tutorial_id FROM USER_TUTORIAL WHERE user_id='$user_id'");
        if($tutorial_id)
            return ($tutorial_controller = $db->db_read("SELECT tutorial_controller FROM TUTORIALS WHERE tutorial_id='$tutorial_id'"));
        return (0);
    }
    function remove_tutorial($login, $tutorial_id){
        $db = new database();
        $user_id = $db->db_read("SELECT user_id FROM USERS WHERE login='$login'");
        $db->db_change("DELETE FROM USER_TUTORIAL WHERE tutorial_id='$tutorial_id' AND user_id='$user_id'");
    }
    function check_login_exists($login){
        $db = new database();
        if($db->db_check("SELECT login FROM USERS WHERE login='$login'"))
            return(1);
        session_unset();
        session_destroy();
        return (0);
    }

    function input_history($alfa_user_id, $omega_user_id, $action)
    {
        if ($alfa_user_id != 0 && $omega_user_id != 0 && isset($action)){
        $db = new database();
        if ($alfa_user_id != $omega_user_id && $action != 11)
            $this->input_notification($alfa_user_id, $omega_user_id, $action);
        $db->db_change("INSERT INTO USER_HISTORY(alfa_user_id, omega_user_id, action_id) VALUES ('$alfa_user_id', '$omega_user_id', '$action')");
        }
    }
    function input_history_by_login($alfa_user_login, $omega_user_login, $action)
    {
        if (isset($alfa_user_login)  && isset($omega_user_login) && isset($action)) {
            $db = new database();
            $alfa_user_id= $db->db_read("SELECT user_id FROM USERS WHERE login='$alfa_user_login'");
            $omega_user_id = $db->db_read("SELECT user_id FROM USERS WHERE login='$omega_user_login'");
            $db->db_change("INSERT INTO USER_HISTORY(alfa_user_id, omega_user_id, action_id) VALUES ('$alfa_user_id', '$omega_user_id', '$action')");
            if ($alfa_user_id != $omega_user_id && $action != 11)
                $this->input_notification($alfa_user_id, $omega_user_id, $action);
        }
    }
    function check_online($login)
    {
        $db = new database();
        $user_id = $db->db_read("SELECT user_id FROM USERS WHERE login='$login'");
        $last = $db->db_read("SELECT update_date FROM USER_HISTORY WHERE alfa_user_id = '$user_id' order by update_date desc;");
        $today = date("Y-m-d H:i:s");
        $d1 = strtotime($last);
        $d2 = strtotime($today);
        $diff = $d2-$d1;
        $diff = ceil($diff/(60));
        if ($diff > 10)
        {
            if ($diff < 60)
                return (array("status"=>"Red",
                "last_online" => "Was online ".$diff." minutes ago "));
            elseif ($diff < 1440 and $diff >= 60)
                return (array("status"=>"Red",
                    "last_online" => "Was online ". ceil($diff/60) ." hour ago "));
            else
                return (array("status"=>"Red",
                    "last_online" => "Last online ". $last));
        }
        else
            return (array("status"=>"green",
                "last_online" => "Online"));
    }
    function check_ready_to_chat_id($chat_id){
        $omega_user_login = $this->get_chat_users($chat_id);
        return ($this->check_ready_to_chat($omega_user_login));
    }
    function get_chat_users($chat_id){
        $db = new database();
        $login = $_SESSION['login'];
        return  $db->db_read("SELECT DISTINCT login FROM USERS
JOIN CHATS C on C.user_id_one = USERS.user_id OR C.user_id_two = USERS.user_id 
WHERE C.chat_id=$chat_id AND USERS.login !='$login';");

    }
    function get_user_id($login){
        $db = new database();
        return($db->db_read("SELECT user_id FROM USERS WHERE login = '$login'"));
    }

    function check_like_exist($alfa_user_id, $omega_user_id)
    {
        $db = new database();
        $like_id = $db->db_read("SELECT history_id FROM USER_HISTORY WHERE alfa_user_id='$alfa_user_id'
                                      AND omega_user_id='$omega_user_id' AND action_id=2");
        return ($like_id);
    }

    function  check_ready_to_chat($omega_user_login){
        if (isset($omega_user_login)){
            $omega_user_id = $this->get_user_id($omega_user_login);
            $alfa_user_login = $_SESSION['login'];
            $alfa_user_id = $this->get_user_id($alfa_user_login);
            $like = $this->check_like_exist($alfa_user_id, $omega_user_id);
            $like_back = $this->check_like_exist($omega_user_id, $alfa_user_id);
            if ($like && $like_back){
                $chat_id = $this->search_chat($alfa_user_id, $omega_user_id);
                if (!$chat_id){
                    $this->create_chat($alfa_user_id, $omega_user_id);
                    $chat_id = $this->search_chat($alfa_user_id, $omega_user_id);
                }
                return ($chat_id);
            }
            else
                return false;
        }
        else
            return false;
    }

    function search_chat($user_id_one, $user_id_two){
        $db = new database();
        return $db->db_read("SELECT chat_id FROM CHATS WHERE user_id_one=$user_id_one 
                                                        AND user_id_two=$user_id_two OR
                                                        user_id_one=$user_id_two AND 
                                                        user_id_two=$user_id_one ");
    }
    function create_chat($user_id_one, $user_id_two){
        $db = new database();
        $id_chat = $db->db_read("SELECT chat_id FROM CHATS WHERE user_id_one=$user_id_one 
                                                        AND user_id_two=$user_id_two OR
                                                        user_id_one=$user_id_two AND 
                                                        user_id_two=$user_id_one ");
        if (!$id_chat)
            return $db->db_change("INSERT INTO CHATS (user_id_one, user_id_two) VALUES ($user_id_one, $user_id_two)");
        else
            return $id_chat;
    }

    function check_like_status($omega_login, $alpha_login){
        $db = new database();
        if($db->db_read("SELECT action_id FROM USER_HISTORY
    JOIN USERS O on USER_HISTORY.omega_user_id = O.user_id
    JOIN USERS A on USER_HISTORY.alfa_user_id = A.user_id
    WHERE (O.login='$omega_login' AND A.login='$alpha_login') and action_id=2"))
            return(1);
        return(0);
    }

    function input_notification($user_id_from, $user_id_to, $action){
        $db = new database();
        $db->db_change("INSERT INTO NOTIFICATIONS (user_id_to, user_id_from, action) VALUES('$user_id_to', '$user_id_from', '$action')");
    }

    function delete_notification($login){
        $db = new database();
        $db->db_change("DELETE NOTIFICATIONS FROM NOTIFICATIONS
                            JOIN USERS U on U.user_id=user_id_to
                            WHERE login='$login'");
    }
}
