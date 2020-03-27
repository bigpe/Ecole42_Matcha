<?php
class Model_Conversation extends Model{
    function get_user_data($login){
        $chats = $this->get_chats($login);
        $data = $this->get_last_message($chats);
        return ($data);
    }
    function get_last_message($chats){
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
        $messages = $db->db_read_multiple("SELECT text_message, user_id_from FROM USER_MESSAGE WHERE chat_id = '$chat_id'
                                                  order by creation_date desc LIMIT $count_message");
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
        $users = $db->db_read_multiple("SELECT DISTINCT login FROM USERS
                                                JOIN USER_MESSAGE UM on USERS.user_id = UM.user_id_to WHERE chat_id = '$chat_id'");
        if ($users[0]['login'] === $login)
            $login_chat = $users[1]['login'];
        else
            $login_chat = $users[0]['login'];
        return $login_chat;
    }
    function get_chats($login){
        $db = new database();
        $user_id = $db->db_read("SELECT user_id FROM USERS WHERE login = '$login'");
        $chats = $db->db_read_multiple("SELECT DISTINCT CHATS.chat_id FROM CHATS
        JOIN USER_MESSAGE UM on CHATS.chat_id = UM.chat_id WHERE user_id_two = '$user_id' OR  user_id_one = '$user_id'");
        $chats = array_reverse($chats);
        return $chats;
    }
    function get_chat_data($chat_id){
        $my_login = $_SESSION['login'];
        $login_companion = $this->get_login_chat($chat_id);
        $user_data = array(
            "messages" => $this->get_messages( $chat_id, 10),
            "main_photo" => $this->get_user_main_photo($login_companion),
            "online_status" => $this->check_online($login_companion),
            "login" => $login_companion);
        return($user_data);
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
}
