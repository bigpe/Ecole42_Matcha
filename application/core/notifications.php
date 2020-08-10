<?php
class Notifications{
    public function __construct()
    {

    }

    function get_notification($login){
        return(array('notification_message' => $this->get_new_message($login),
            'view_notification' => $this->get_view_notification($login)
        ));
    }

    function get_new_message($login){
        $db = new database();
        $notification = $db->db_read("SELECT COUNT(*) FROM USER_MESSAGE
JOIN USERS U on USER_MESSAGE.user_id_to = U.user_id
WHERE login='$login' AND status_message=0");
        if ($notification > 0)
            return $notification;
        else
            return "";
    }

    function get_view_notification($login){
        $db = new database();
        $notification = $db->db_read("SELECT COUNT(user_id_to) FROM NOTIFICATIONS
JOIN USERS U on user_id=user_id_to
WHERE U.login='$login'");
        if ($notification > 0)
            return $notification;
        else
            return "";
    }

}