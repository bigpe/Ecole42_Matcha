<?php
class Model_Profile extends Model{
    function get_user_data($login){
        $this->put_history_view($login);
        return (array(
            "main_photo_src" => $this->get_user_main_photo($login),
            "user_info" => $this->get_user_info($login),
            "user_tags" => $this->get_user_tags($login),
            "user_sex_preference" => $this->get_user_sex_preference($login),
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

    function put_history_view($omega_user_login){
        $omega_user_id = $this->get_user_id($omega_user_login);
        $alfa_user_login = $_SESSION['login'];
        $alfa_user_id = $this->get_user_id($alfa_user_login);
        if ($alfa_user_id == $omega_user_id)
            return;
        $history_id = $this->check_history_view($alfa_user_id, $omega_user_id);
        if ($history_id)
        {
            if ((float)$this->check_date_history_view($history_id) > 0)
                $this->insert_history_view($alfa_user_id, $omega_user_id);
            else
                $this->update_history_view($history_id);
        }
        else
            $this->insert_history_view($alfa_user_id, $omega_user_id);
    }

    function get_user_id($login){
        $db = new database();
        return($db->db_read("SELECT user_id FROM USERS WHERE login = '$login';"));
    }

    function check_history_view($alfa_user_id, $omega_user_id)
    {
        $db = new database();
        $history_id = $db->db_read("SELECT history_id FROM HISTORY where alfa_user_id = '$alfa_user_id'
                                    AND omega_user_id = '$omega_user_id' AND action = 1 order by update_date DESC;");
        return ($history_id);
    }

    function check_date_history_view($history_id){
        $db = new database();
        $history = $db->db_read("SELECT creation_date FROM HISTORY WHERE history_id = '$history_id'");
        $today = date("Y-m-d ");
        $explode = explode(" ", $history);
        $history_day = $explode[0];
        $d1 = strtotime($history_day);
        $d2 = strtotime($today);
        $diff = $d2-$d1;
        $diff = $diff/(60*60*24);
        $check = floor($diff);
        return $check;
    }

    function insert_history_view($alfa_user_id, $omega_user_id){
        $db = new database();
        $db->db_change("INSERT INTO HISTORY (alfa_user_id, omega_user_id, action) VALUES ('$alfa_user_id', '$omega_user_id', 1);");
    }

    function update_history_view($history_id){
        $db = new database();
        $db->db_change("UPDATE HISTORY SET update_date = CURRENT_TIMESTAMP WHERE history_id = '$history_id';");
    }

    function put_like($omega_user_login)
    {
        $omega_user_id = $this->get_user_id($omega_user_login);
        $alfa_user_login = $_SESSION['login'];
        $alfa_user_id = $this->get_user_id($alfa_user_login);
        $like_id = $this->check_like_exist($alfa_user_id, $omega_user_id);
        if ($like_id)
        {
            $this->delete_like($like_id);
        }
        else
            $this->insert_like($alfa_user_id, $omega_user_id);
    }

    function check_like_exist($alfa_user_id, $omega_user_id)
    {
        $db = new database();
        $like_id = $db->db_read("SELECT history_id FROM HISTORY where alfa_user_id = '$alfa_user_id'
                                    AND omega_user_id = '$omega_user_id' AND action = 2 order by update_date DESC;");
        return ($like_id);
    }

    function insert_like($alfa_user_id, $omega_user_id){
        $db = new database();
        $db->db_change("INSERT INTO HISTORY (alfa_user_id, omega_user_id, action) VALUES ('$alfa_user_id', '$omega_user_id', 2);");
    }

    function delete_like($like_id){
        $db = new database();
        $db->db_change("DELETE FROM HISTORY WHERE history_id = '$like_id';");
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
