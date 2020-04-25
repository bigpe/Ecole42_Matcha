<?php
class Model_Matcha extends Model{
    function get_recommend_users($login){
        $db = new database();
        $query_append = "";
        $user_black_list = $this->get_user_black_list($login);
        $user_location = $this->get_user_location($login);
        if($db->db_check("SELECT geo FROM USERS WHERE login!='$login' AND geo='$user_location'"))
            $query_append = $query_append . "AND geo='$user_location'";
        if($user_black_list){
            foreach ($user_black_list as $ubl) {
                $user_id_blocked = $ubl['user_id_blocked'];
                $query_append = $query_append . " AND user_id!='$user_id_blocked'";
            }
        }
        $users_matched = $db->db_read_multiple("SELECT user_id_two FROM USERS_MATCHED 
                                    JOIN USERS U on USERS_MATCHED.user_id_one = U.user_id WHERE login='$login'");
        if($users_matched) {
            foreach ($users_matched as $user_matched) {
                $user_id_matched = $user_matched['user_id_two'];
                $query_append = "$query_append AND user_id!='$user_id_matched'";
            }
        }
        $user_sex_preference = $db->db_read("SELECT sex_preference FROM USERS WHERE login='$login'");
        if($user_sex_preference)
            $query_append = "$query_append AND sex='$user_sex_preference'";
        $query = "SELECT login FROM USERS WHERE login!='$login' $query_append";
        $users = $db->db_read_multiple($query);
        $users_data = [];
        foreach ($users as $user)
            $users_data[] = $this->get_user_data($user['login']);
        $users_data = $this->sort_user_matched($users_data);
        return($users_data);
    }
    function sort_user_matched($users_data){
        usort($users_data, function($a, $b){
            return ($b['profile_filled']['value'] - $a['profile_filled']['value']);
        }); //Low Priority Sorting By Profile Filled
        usort($users_data, function($a, $b){
            return ($b['user_fame_rating']['fame_rating_id'] - $a['user_fame_rating']['fame_rating_id']);
        }); //Medium Priority Sorting By Fame Rating
        usort($users_data, function($a, $b){
            return ($a['distance'] - $b['distance']);
        }); //Medium Priority Soring By Distance
        usort($users_data, function($a, $b){
            return ($b['tags_matched'] - $a['tags_matched']);
        }); //High Priority Sorting By Tags Matched
        return ($users_data);
    }
    function get_tag_matched($login){
        $db = new database();
        $session_login = $_SESSION['login'];
        $session_user_tags = $db->db_read_multiple("SELECT tag_id FROM USER_TAGS 
                        JOIN USERS U on USER_TAGS.user_id = U.user_id WHERE login='$session_login'");
        $user_tags = $db->db_read_multiple("SELECT tag_id FROM USER_TAGS 
                        JOIN USERS U on USER_TAGS.user_id = U.user_id WHERE login='$login'");
        $tags_matched = 0;
        for($i = 0; $i < count($session_user_tags); $i++){
            for($j = 0; $j < count($user_tags); $j++)
                if($session_user_tags[$i]['tag_id'] == $user_tags[$j]['tag_id'])
                    $tags_matched += 1;
        }
        return ($tags_matched);
    }
    function get_user_data($login){
        $user_data = array(
            "main_photo" => $this->get_user_main_photo($login),
            "user_info" => $this->get_user_info($login),
            "user_real_name" => $this->get_user_real_name($login),
            "user_geo" => $this->get_user_location($login),
            "geo" => $this->get_user_geo_coordinates($login),
            "user_tags" => $this->get_user_tags($login),
            "user_sex_preference" => $this->get_user_sex_preference($login),
            "user_fame_rating" => $this->get_user_fame_rating($login),
            "online_status" => $this->check_online($login),
            "user_photos" => $this->get_user_photos($login),
            "profile_filled" => $this->get_profile_filled($login),
            "user_login" => $login,
            "user_age" => $this->get_user_age($login),
            "tags_matched" => $this->get_tag_matched($login));
        $user_geo_coordinates = $this->get_user_geo_coordinates($_SESSION['login']);
        $user_geo_long = $user_geo_coordinates['geo_longitude'];
        $user_geo_lat = $user_geo_coordinates['geo_latitude'];
        $user_to_geo_long = $user_data['geo']['geo_longitude'];
        $user_to_geo_lat = $user_data['geo']['geo_latitude'];
        $user_data['distance'] = $this->calculateTheDistance($user_geo_long, $user_geo_lat,
            $user_to_geo_long, $user_to_geo_lat);
        return ($user_data);
    }
    function check_users_count($login){
        $db = new database();
        $query_append = "";
        $users_matched = $db->db_read_multiple("SELECT user_id_two FROM USERS_MATCHED 
                                    JOIN USERS U on USERS_MATCHED.user_id_one = U.user_id WHERE login='$login'");
        if($users_matched) {
            foreach ($users_matched as $user_matched) {
                $user_id_matched = $user_matched['user_id_two'];
                $query_append = "$query_append AND user_id!='$user_id_matched'";
            }
        }
        $user_black_list = $this->get_user_black_list($login);
        if($user_black_list){
            foreach ($user_black_list as $ubl) {
                $user_id_blocked = $ubl['user_id_blocked'];
                $query_append = $query_append . " AND user_id!='$user_id_blocked'";
            }
        }
        $user_location = $this->get_user_location($login);
        $users_count = $db->db_read("SELECT COUNT(user_id) as users_count FROM USERS 
                    WHERE login!='$login' AND geo='$user_location' $query_append");
        return($users_count);
    }
    function put_geo_user($login, $latitude, $longitude){
        $db = new database();
        $db->db_change("UPDATE USERS SET geo_longitude='$longitude', 
                 geo_latitude='$latitude' WHERE login = '$login'");
    }
}