<?php
class Model_Conversation extends Model{
    function get_user_data($login){
        $chats = $this->get_chats($login);
        $data = $this->get_last_message($chats);
        return ($data);
    }
    function get_last_message($chats){
        $data = [];
        for ($i = 0; $i < count($chats); $i++){
            $data[$i]['chat_id'] = $chats[$i]['chat_id'];
            $data[$i]['login'] = $this->get_login_chat($chats[$i]['chat_id']);
            $data[$i]['last_message'] = $this->get_messages($chats[$i]['chat_id'], 1);
            $data[$i]['main_photo'] = $this->get_user_main_photo($data[$i]['login']);
            $data[$i]['online_status'] = $this->check_online($data[$i]['login']);
        }
        return $data;
    }
    function get_messages($chat_id, $count_message){
        $login = $_SESSION['login'];
        $db = new database();
        $messages = $db->db_read_multiple("SELECT text_message, user_id_from, status_message, creation_date FROM USER_MESSAGE WHERE chat_id = '$chat_id'
                                                  order by id_message desc LIMIT $count_message");
        for($i = 0; $i < count($messages); $i++){
        $author_id =  $messages[$i]['user_id_from'];
    $author_message = $db->db_read("SELECT login FROM USERS WHERE user_id='$author_id'");
        if ($author_message == $login)
            $messages[$i]['author'] = "You";
        else
            $messages[$i]['author'] = $author_message;
        }
        $messages = array_reverse($messages);
        return($messages);
    }
    function get_login_chat($chat_id){
        $login = $_SESSION['login'];
        $db = new database();
        return $db->db_read("SELECT login FROM CHATS
                      JOIN USERS U on U.user_id=CHATS.user_id_one OR U.user_id=CHATS.user_id_two WHERE chat_id=$chat_id AND U.login!='$login'");

    }
    function get_chats($login){
        $db = new database();
        $user_id = $db->db_read("SELECT user_id FROM USERS WHERE login = '$login'");
        return $db->db_read_multiple("SELECT DISTINCT CHATS.chat_id FROM CHATS
                                        JOIN USER_MESSAGE UM on CHATS.chat_id = UM.chat_id
                                        WHERE user_id_two = $user_id OR  user_id_one = $user_id ORDER BY id_message DESC");
    }
    function get_chat_data($chat_id){
        $login_companion = $this->get_login_chat($chat_id);
        $my_id = $this->get_user_id($_SESSION['login']);
        $companion_id = $this->get_user_id($login_companion);
        $user_data = array(
            "messages" => $this->get_messages( $chat_id, 10),
            "main_photo" => $this->get_user_main_photo($login_companion),
            "online_status" => $this->check_online($login_companion),
            "login" => $login_companion,
            "block_status" => $this->check_block_status($login_companion),
            "like_status" => $this->check_like_exist($my_id, $companion_id),
            "ready_to_chat" => $this->check_ready_to_chat_id($chat_id));
        $this->edit_message_status($chat_id);
        $this->delete_notification($chat_id);
        return($user_data);
    }

    function edit_message_status($chat_id){
        $login = $_SESSION['login'];
        $db = new database();
        $user_id = $db->db_read("SELECT user_id FROM USERS WHERE login = '$login'");
        $db->db_change("UPDATE USER_MESSAGE SET status_message=1 WHERE chat_id='$chat_id' AND user_id_to='$user_id'");
    }


    function get_user_main_photo($login)
    {
        $db = new database();
        $user_id = $db->db_read("SELECT user_id FROM USERS WHERE login='$login'");
        $user_main_photo_id = $db->db_read("SELECT photo_id FROM USER_MAIN_PHOTO WHERE user_id='$user_id'");
        $user_main_photo_src = $db->db_read("SELECT photo_src FROM USER_PHOTO WHERE photo_id='$user_main_photo_id'");
        $user_main_photo_token = $db->db_read("SELECT photo_token FROM USER_PHOTO WHERE photo_id='$user_main_photo_id'");
        return (array("photo_src" => $user_main_photo_src, "photo_token" => $user_main_photo_token));
    }

    function save_message($chat_id, $message){
        if ($this->check_ready_to_chat_id($chat_id) > 0){
            $db = new database();
            $message = quotemeta(htmlspecialchars($message, ENT_QUOTES));
            $login = $_SESSION['login'];
            $user_id_from = $db->db_read("SELECT user_id FROM USERS WHERE login='$login'");
            $user_id_to = $db->db_read("SELECT user_id FROM USERS
                            JOIN CHATS on user_id_one=USERS.user_id OR user_id_two=USERS.user_id
                            WHERE login!='$login' AND chat_id='$chat_id'");
            $db->db_change("INSERT INTO USER_MESSAGE (chat_id, user_id_from, user_id_to, text_message) 
                                    VALUES ('$chat_id', '$user_id_from', '$user_id_to', '$message');");
            $this->input_history($user_id_from, $user_id_to, 11);
            $login_to = $db->db_read("SELECT login FROM USERS WHERE user_id='$user_id_to'");
        }
        return($this->check_online($login_to));
    }

    function handler_message($chat_id, $start_message_from){
        $db = new database();
        $login = $_SESSION['login'];
        $my_id = $db->db_read("SELECT user_id FROM USERS WHERE login='$login'");
        $messages = $db->db_read_multiple("SELECT user_id_from, text_message, status_message, creation_date 
                                    FROM USER_MESSAGE WHERE chat_id='$chat_id' ORDER BY id_message DESC LIMIT $start_message_from, 10");
        for ($i = 0; $i < count($messages); $i++){
            if ($messages[$i]['user_id_from'] === $my_id)
                $messages[$i]['message_from'] = "my";
            else
                $messages[$i]['message_from'] = "other";
        }
        return $messages;
    }

}
