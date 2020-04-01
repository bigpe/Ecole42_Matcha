<?php
class Model_Profile extends Model{
    function get_user_data($login){
        if(isset($_SESSION['login']))
            $this->input_history($_SESSION['login'], $login, 1);
        $user_data = array(
            "main_photo" => $this->get_user_main_photo($login),
            "user_info" => $this->get_user_info($login),
            "user_real_name" => $this->get_user_real_name($login),
            "user_geo" =>$this->get_user_location($login),
            "user_tags" => $this->get_user_tags($login),
            "user_sex_preference" => $this->get_user_sex_preference($login),
            "user_fame_rating" => $this->get_user_fame_rating($login),
            "online_status" => $this->check_online($login),
            "user_login" => $login,
            "user_photos" => $this->get_user_photos($login),
            "ready_to_chat" => (isset($_SESSION['login']) ? $this->check_ready_to_chat($login) : "0"),
            "check_like" => (isset($_SESSION['login']) ? $this->check_like_status($login, $_SESSION['login']) : "0"),
            "profile_filled" => $this->get_profile_filled($login));
        return($user_data);
    }
    function get_user_main_photo($login){
        $db = new database();
        $user_id = $db->db_read("SELECT user_id FROM USERS WHERE login='$login'");
        $user_main_photo_id = $db->db_read("SELECT photo_id FROM USER_MAIN_PHOTO WHERE user_id='$user_id'");
        $user_main_photo_src = $db->db_read("SELECT photo_src FROM USER_PHOTO WHERE photo_id='$user_main_photo_id'");
        $user_main_photo_token = $db->db_read("SELECT photo_token FROM USER_PHOTO WHERE photo_id='$user_main_photo_id'");
        if(!$user_main_photo_token)
            $user_main_photo_token = 1;
        if(!$user_main_photo_src)
            $user_main_photo_src = "./images/placeholder.png";
        return(array("photo_src" => $user_main_photo_src, "photo_token" => $user_main_photo_token));
    }
    function get_user_info($login){
        $db = new database();
        $user_info = $db->db_read("SELECT info FROM USERS WHERE login='$login'");
        return ($user_info);
    }
    function get_user_real_name($login){
        $db = new database();
        $user_real_name = $db->db_read("SELECT full_name FROM USERS WHERE login='$login'");
        return ($user_real_name);
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
    function get_user_not_selected_tags($login){
        $db = new database();
        $user_tags = $db->db_read_multiple("SELECT tag_id
            FROM USER_TAGS JOIN USERS U on USER_TAGS.user_id = U.user_id
            WHERE login='$login'");
        $query = "SELECT DISTINCT tag_name, tag_icon, tag_color
                    FROM TAGS";
        if($user_tags)
            $query = $query . " WHERE ";
        foreach ($user_tags as $user_tag){
            $tag_id = $user_tag['tag_id'];
            $query = "$query tag_id!='$tag_id'";
            if(next($user_tags))
                $query = "$query AND";
        }
        return($db->db_read_multiple($query));
    }
    function get_user_sex_preference($login){
        $db = new database();
        $user_sex = $db->db_read("SELECT sex FROM USERS WHERE login='$login'");
        $user_sex_preference = $db->db_read("SELECT sex_preference from USERS WHERE login='$login'");
        if(!(int)$user_sex_preference)
            return($db->db_read_multiple("SELECT sex_preference_name, sex_preference_icon, sex_preference_color
                FROM SEX_PREFERENCE WHERE sex_preference_id='0'")[0]);
        $math = (int)$user_sex + (int)$user_sex_preference;
        $sex_preference = $db->db_read_multiple("SELECT sex_preference_name, sex_preference_icon, sex_preference_color 
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
    function get_user_photos($login){
        $db = new database();
        return($db->db_read_multiple("SELECT photo_token, photo_src FROM USER_PHOTO 
                                    JOIN USERS U on USER_PHOTO.user_id = U.user_id WHERE login='$login'"));
    }
    function save_settings($settings, $login){
        $db = new database();

        $settings_value = $settings['setting_value'];
        if ($settings['setting_type'] == 1 || $settings['setting_type'] == 9)
            $settings_value = quotemeta(htmlspecialchars($settings_value, ENT_QUOTES));
        if($settings['setting_type'] == 1)
            $query = "UPDATE USERS SET info='$settings_value' WHERE login='$login'";
        if($settings['setting_type'] == 2)
            $query = "UPDATE USERS SET geo='$settings_value' WHERE login='$login'";
        if($settings['setting_type'] == 3){
            $photo_token = $settings['setting_value']['photo_token'];
            $photo_base64 = $settings['setting_value']['photo_base64'];
            $photo_name = md5($photo_token);
            $photo_path = "./images/user_photo/$photo_name.jpg";
            file_put_contents($photo_path, file_get_contents($photo_base64));
            $query = "INSERT INTO USER_PHOTO(photo_token, photo_src, user_id) 
                        SELECT '$photo_token', '$photo_path', user_id FROM USERS WHERE login='$login'";
        }
        if($settings['setting_type'] == 4) {
            $photo_src = $db->db_read("SELECT photo_src FROM USER_PHOTO WHERE photo_token='$settings_value'");
            exec("rm $photo_src");
            $query = "DELETE USER_PHOTO FROM USER_PHOTO 
            JOIN USERS U on USER_PHOTO.user_id = U.user_id WHERE photo_token='$settings_value' AND login='$login'";
        }
        if($settings['setting_type'] == 5) {
            $user_id = $db->db_read("SELECT user_id FROM USERS WHERE login='$login'");
            $photo_id = $db->db_read("SELECT photo_id FROM USER_PHOTO WHERE photo_token='$settings_value'");
            if($db->db_check("SELECT user_id FROM USER_MAIN_PHOTO WHERE user_id='$user_id'"))
                $query = "UPDATE USER_MAIN_PHOTO SET photo_id='$photo_id' WHERE user_id='$user_id'";
            else
                $query = "INSERT INTO USER_MAIN_PHOTO(photo_id, user_id) VALUES ('$photo_id', '$user_id')";
        }
        if($settings['setting_type'] == 6){
            $photo_token = $settings['setting_value']['photo_token'];
            $photo_base64 = $settings['setting_value']['photo_base64'];
            $photo_name = md5($photo_token);
            $photo_path = "./images/user_photo/$photo_name.jpg";
            file_put_contents($photo_path, file_get_contents($photo_base64));
            $db->db_change("INSERT INTO USER_PHOTO(photo_token, photo_src, user_id) 
                        SELECT '$photo_token', '$photo_path', user_id FROM USERS WHERE login='$login'");
            $user_id = $db->db_read("SELECT user_id FROM USERS WHERE login='$login'");
            $photo_id = $db->db_read("SELECT photo_id FROM USER_PHOTO WHERE photo_token='$photo_token'");
            if($db->db_check("SELECT user_id FROM USER_MAIN_PHOTO WHERE user_id='$user_id'"))
                $query = "UPDATE USER_MAIN_PHOTO SET photo_id='$photo_id' WHERE user_id='$user_id'";
            else
                $query = "INSERT INTO USER_MAIN_PHOTO(photo_id, user_id) VALUES ('$photo_id', '$user_id')";
        }
        if($settings['setting_type'] == 7){
            $user_id = $db->db_read("SELECT user_id FROM USERS WHERE login='$login'");
            $query = [];
            foreach ($settings_value as $tag)
                $query[] = "INSERT IGNORE INTO USER_TAGS (user_id, tag_id) 
                SELECT '$user_id', tag_id FROM TAGS WHERE tag_name='$tag'";
        }
        if($settings['setting_type'] == 8)
            $query = "DELETE USER_TAGS FROM USER_TAGS 
            JOIN USERS U on USER_TAGS.user_id = U.user_id JOIN TAGS T on USER_TAGS.tag_id = T.tag_id
            WHERE login='$login' AND tag_name='$settings_value'";
        if($settings['setting_type'] == 9)
            $query = "UPDATE USERS SET full_name='$settings_value' WHERE login='$login'";
        if(isset($query) && !is_array($query))
            $db->db_change($query);
        if(isset($query) && is_array($query)){
            foreach ($query as $q)
                $db->db_change($q);
        }
    }
    function get_profile_filled($login){
        $db = new database();
        $user_profile_filled = array("value" => 0, "add_to_profile" => []);
        $user_base_info = $db->db_read_multiple("SELECT full_name, info FROM USERS WHERE login='$login'")[0];
        if($user_base_info['full_name'])
            $user_profile_filled['value'] += 20;
        else
            $user_profile_filled['add_to_profile'][] = array("value" => "Full Name",
                "icon" => "<i class=\"fas fa-user-tie\"></i>");
        if($user_base_info['info'])
            $user_profile_filled['value'] += 20;
        else
            $user_profile_filled['add_to_profile'][] = array("value" => "Info",
                "icon" => "<i class=\"fas fa-info-circle\"></i>");
        $user_photos = $db->db_read("SELECT COUNT(photo_id) FROM USER_PHOTO 
                            JOIN USERS U on USER_PHOTO.user_id = U.user_id WHERE login='$login' ");
        $user_profile_filled['value'] += $user_photos * 8;
        if($user_photos < 5)
            $user_profile_filled['add_to_profile'][] = array("value" => "Photos",
                "icon" => "<i class=\"fas fa-camera\"></i>");
        $user_tags = $db->db_read("SELECT COUNT(user_tag_id) FROM USER_TAGS 
                        JOIN USERS U on USER_TAGS.user_id = U.user_id WHERE login='$login'");
        if($user_tags >= 1)
            $user_profile_filled['value'] += 20;
        else
            $user_profile_filled['add_to_profile'][] = array("value" => "Tags",
                "icon" => "<i class=\"fas fa-hashtag\"></i>");
        return ($user_profile_filled);
    }
}
