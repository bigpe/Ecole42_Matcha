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
        date_default_timezone_set("Europe/Moscow");
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

    function check_ready_to_chat($omega_user_login){
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
                $main_photo_omega = $this->get_user_main_photo($omega_user_login);
                $main_photo_alfa = $this->get_user_main_photo($alfa_user_login);
                if ($main_photo_omega['photo_token'] === 1 || $main_photo_alfa['photo_token'] === 1)
                    return false;
                if($this->check_both_block_status($omega_user_login, $alfa_user_login))
                    return (false);
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
        $db->db_change("INSERT INTO CHATS (user_id_one, user_id_two) VALUES ($user_id_one, $user_id_two)");
    }
    function create_token($email){
        $token = hash('md5',"$email" . time());
        return ($token);
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

    function calculateTheDistance($long_A, $lat_A, $long_B, $lat_B) {
        $earth_radius = 6372795;
        $lat1 = $lat_A * M_PI / 180;
        $lat2 = $lat_B * M_PI / 180;
        $long1 = $long_A * M_PI / 180;
        $long2 = $long_B * M_PI / 180;

        $cl1 = cos($lat1);
        $cl2 = cos($lat2);
        $sl1 = sin($lat1);
        $sl2 = sin($lat2);
        $delta = $long2 - $long1;
        $cdelta = cos($delta);
        $sdelta = sin($delta);

        $y = sqrt(pow($cl2 * $sdelta, 2) + pow($cl1 * $sl2 - $sl1 * $cl2 * $cdelta, 2));
        $x = $sl1 * $sl2 + $cl1 * $cl2 * $cdelta;

        $ad = atan2($y, $x);
        $distance = ceil($ad * $earth_radius);
        return ($distance);
    }

    function get_user_geo_coordinates($login){
        $db = new database();
        $user_geo_coordinates = $db->db_read_multiple("SELECT geo_latitude, geo_longitude 
                                                    FROM USERS WHERE login='$login'")[0];
        return($user_geo_coordinates);
    }
    function get_user_black_list($login){
        $db = new database();
        return($db->db_read_multiple("SELECT user_id_blocked FROM USER_BLACK_LIST 
                            JOIN USERS U on USER_BLACK_LIST.user_id = U.user_id WHERE login='$login'"));
    }
    function check_block_status($login){
        if (isset($_SESSION['login'])){
            $user_id = $this->get_user_id($login);
            $black_list = $this->get_user_black_list($_SESSION['login']);
            for ($i = 0; $i < count($black_list); $i++){
                if ($user_id === $black_list[$i]['user_id_blocked'])
                    return 1;
            }
            return 0;
        }
    }
    function check_both_block_status($login_src, $login_dst){
        $user_id_src = $this->get_user_id($login_src);
        $user_id_dst = $this->get_user_id($login_dst);
        $black_list_src = $this->get_user_black_list($login_src);
        $black_list_dst = $this->get_user_black_list($login_dst);
        for ($i = 0; $i < count($black_list_src); $i++){
            if ($user_id_dst === $black_list_src[$i]['user_id_blocked'])
                return 1;
        }
        for ($i = 0; $i < count($black_list_dst); $i++){
            if ($user_id_src === $black_list_dst[$i]['user_id_blocked'])
                return 1;
        }
        return 0;
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
            $user_fame_rating_id = 5;
        else
            $user_fame_rating_id = $db->db_read_multiple("SELECT fame_rating_id, fame_rating_color, fame_rating_name, fame_rating_icon
                FROM FAME_RATING WHERE '$user_fame_rating_count' >= fame_rating_start AND 
                                        '$user_fame_rating_count' <= fame_rating_end")[0];
        return($user_fame_rating_id);
    }
    function get_user_info($login){
        $db = new database();
        $user_info = $db->db_read("SELECT info FROM USERS WHERE login='$login'");
        return ($user_info);
    }
    function get_user_age($login){
        $db = new database();
        $user_age = $db->db_read("SELECT (YEAR(CURRENT_DATE) - YEAR(age)) FROM USERS WHERE login='$login'");
        return ($user_age);
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

    function get_user_photos($login){
        $db = new database();
        return($db->db_read_multiple("SELECT photo_token, photo_src FROM USER_PHOTO 
                                    JOIN USERS U on USER_PHOTO.user_id = U.user_id WHERE login='$login'"));
    }
    function user_matched($login_src, $login_dst){
        $db = new database();
        $user_id_src = $db->db_read("SELECT user_id FROM USERS WHERE login='$login_src'");
        $user_id_dst = $db->db_read("SELECT user_id FROM USERS WHERE login='$login_dst'");
        $db->db_change("INSERT IGNORE INTO USERS_MATCHED (user_id_one, user_id_two) VALUES ('$user_id_src', '$user_id_dst')");
    }

    function new_tmp_user($email,$token_id){
        $db = new database();
        $db->db_change("INSERT IGNORE INTO USER_TEMP (email, token_id) VALUES ('$email', '$token_id')");
    }
}
