<?php
class Model_Profile extends Model{
    function get_user_data($login){
        $this->put_history_view($login);
        return (array(
            "main_photo_src" => $this->get_user_main_photo($login),
            "user_info" => $this->get_user_info($login),
            "user_geo" =>$this->get_user_location($login),
            "user_tags" => $this->get_user_tags($login),
            "user_sex_preference" => $this->get_user_sex_preference($login),
            "user_fame_rating" => $this->get_user_fame_rating($login),
            "online_status" => $this->check_online($login),
            "user_login" => $login));
    }
    function get_user_main_photo($login){
        $db = new database();
        $user_id = $db->db_read("SELECT user_id FROM USERS WHERE login='$login'");
        $user_main_photo_id = $db->db_read("SELECT photo_id FROM USER_MAIN_PHOTO WHERE user_id='$user_id'");
        $user_main_photo_src = $db->db_read("SELECT photo_src FROM USER_PHOTO WHERE photo_id='$user_main_photo_id'");
        return($user_main_photo_src);
    }
    function get_user_info($login){
        $db = new database();
        $user_info = $db->db_read("SELECT info FROM USERS WHERE login='$login'");
        return ($user_info);
    }
    function get_user_location($login){
        $db = new database();
        $user_location = $db->db_read("SELECT geo FROM USERS WHERE login='$login'");
        return($user_location);
    }
    function get_user_tags($login){
        $db = new database();
        $user_id = $db->db_read("SELECT user_id FROM USERS WHERE login='$login'");
        $user_tags = $db->db_read_multiple("SELECT tag_name, tag_icon, tag_color 
                FROM USER_TAGS JOIN TAGS T on USER_TAGS.tag_id = T.tag_id 
                WHERE user_id='$user_id' ORDER BY tag_rate DESC");
        return($user_tags);
    }
    function get_user_sex_preference($login){
        $db = new database();
        $user_sex = $db->db_read("SELECT sex FROM USERS WHERE login='$login'");
        $user_sex_preference = $db->db_read("SELECT sex_preference from USERS WHERE login='$login'");
        if(!(int)$user_sex_preference)
            return($db->db_read_multiple("SELECT sex_preference_name, sex_preference_icon 
                FROM SEX_PREFERENCE WHERE sex_preference_id='0'")[0]);
        $math = (int)$user_sex + (int)$user_sex_preference;
        $sex_preference = $db->db_read_multiple("SELECT sex_preference_name, sex_preference_icon 
                FROM SEX_PREFERENCE WHERE sex_preference_id='$math'")[0];
        return($sex_preference);
    }
    function get_user_fame_rating($login){
        $db = new database();
        $user_fame_rating_count = $db->db_read("SELECT COUNT(DISTINCT A.user_id,O.user_id, 
            DAY(USER_HISTORY.creation_date), USER_HISTORY.action_id) as COUNT
                                                FROM USER_HISTORY
                                                JOIN USERS A on alfa_user_id=A.user_id
                                                JOIN USERS O on omega_user_id=O.user_id
                                                JOIN USER_ACTIONS UA on USER_HISTORY.action_id = UA.action_id
            WHERE (O.login='$login' AND USER_HISTORY.action_id=1 OR
                USER_HISTORY.action_id=2 AND O.login='$login') AND day(USER_HISTORY.creation_date) 
                    BETWEEN DAY(CURRENT_TIMESTAMP) AND (DAY(CURRENT_TIMESTAMP) + 3) 
                    ORDER BY USER_HISTORY.creation_date DESC");
        if($user_fame_rating_count >= 999)
            $user_fame_rating = $db->db_read_multiple("SELECT fame_rating_name, fame_rating_icon FROM FAME_RATING 
                WHERE fame_rating_end=999")[0];
        else
            $user_fame_rating = $db->db_read_multiple("SELECT fame_rating_name, fame_rating_icon, fame_rating_color 
                FROM FAME_RATING WHERE '$user_fame_rating_count' >= fame_rating_start AND 
                                        '$user_fame_rating_count' <= fame_rating_end")[0];
        return($user_fame_rating);
    }
    function put_history_view($omega_user_login){
        $omega_user_id = $this->get_user_id($omega_user_login);
        $alfa_user_login = $_SESSION['login'];
        $alfa_user_id = $this->get_user_id($alfa_user_login);
        if ($alfa_user_id == $omega_user_id)
            return;
        $this->insert_history_view($alfa_user_id, $omega_user_id);
    }
    function get_user_id($login){
        $db = new database();
        return($db->db_read("SELECT user_id FROM USERS WHERE login = '$login'"));
    }
    function insert_history_view($alfa_user_id, $omega_user_id){
        $db = new database();
        $db->db_change("INSERT INTO USER_HISTORY (alfa_user_id, omega_user_id, action_id) 
                                VALUES ('$alfa_user_id', '$omega_user_id', 1)");
    }
    function put_like($omega_user_login)
    {
        $omega_user_id = $this->get_user_id($omega_user_login);
        $alfa_user_login = $_SESSION['login'];
        $alfa_user_id = $this->get_user_id($alfa_user_login);
        $like_id = $this->check_like_exist($alfa_user_id, $omega_user_id);
        if ($like_id)
            $this->delete_like($like_id);
        else
            $this->insert_like($alfa_user_id, $omega_user_id);
    }
    function check_like_exist($alfa_user_id, $omega_user_id)
    {
        $db = new database();
        $like_id = $db->db_read("SELECT history_id FROM USER_HISTORY WHERE alfa_user_id='$alfa_user_id'
                                      AND omega_user_id='$omega_user_id' AND action_id=2");
        return ($like_id);
    }
    function insert_like($alfa_user_id, $omega_user_id){
        $db = new database();
        $db->db_change("INSERT INTO USER_HISTORY(alfa_user_id, omega_user_id, action_id) 
                                VALUES ('$alfa_user_id', '$omega_user_id', 2)");
    }
    function delete_like($like_id){
        $db = new database();
        $db->db_change("UPDATE USER_HISTORY SET action_id=3 WHERE history_id = '$like_id';");
    }
    function  check_ready_to_chat($omega_user_login){
        $omega_user_id = $this->get_user_id($omega_user_login);
        $alfa_user_login = $_SESSION['login'];
        $alfa_user_id = $this->get_user_id($alfa_user_login);
        $like = $this->check_like_exist($alfa_user_id, $omega_user_id);
        $like_back = $this->check_like_exist($omega_user_id, $alfa_user_id);
        if ($like && $like_back)
            return true;
        else
            return false;
    }
}
