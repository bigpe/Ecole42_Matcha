<?php
class Model_Profile extends Model{
    function get_user_data($login){
        return (array(
            "main_photo_src" => $this->get_user_main_photo($login),
            "user_info" => $this->get_user_info($login),
            "user_tags" => $this->get_user_tags($login),
            "user_sex_preference" => $this->get_user_sex_preference($login)));
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
                FROM USERS_TAGS JOIN TAGS T on USERS_TAGS.tag_id = T.tag_id 
                WHERE user_id='$user_id' ORDER BY tag_rate DESC");
        return($user_tags);
    }
    function get_user_sex_preference($login){
        $db = new database();
        $user_sex = $db->db_read("SELECT sex FROM USERS WHERE login='$login'");
        $user_sex_preference = $db->db_read("SELECT sex_preference from USERS WHERE login='$login'");
        if(!(int)$user_sex_preference)
            return($db->db_read("SELECT sex_preference_name 
                FROM SEX_PREFERENCE WHERE sex_preference_id='0'"));
        $math = (int)$user_sex + (int)$user_sex_preference;
        $sex_preference = $db->db_read_multiple("SELECT sex_preference_name, sex_preference_icon 
                FROM SEX_PREFERENCE WHERE sex_preference_id='$math'")[0];
        return($sex_preference);
    }
}
