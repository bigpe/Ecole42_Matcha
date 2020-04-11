<?php
class Model_Matcha extends Model{
    function get_users($login){
        $recommend_users = $this->get_recommend_users($login);
        return (array("user_data" => $recommend_users[1]));
    }
    function get_recommend_users($login){
        $db = new database();
        $query_append = "";
        $user_black_list = $this->get_user_black_list($login);
        if($user_black_list){
            foreach ($user_black_list as $ubl) {
                $user_id_blocked = $ubl['user_id_blocked'];
                $query_append = $query_append . " AND user_id!='$user_id_blocked'";
            }
        }
        $users_data = $db->db_read_multiple("SELECT login, geo_longitude, geo_latitude 
                        FROM USERS WHERE login!='$login' $query_append");
        $user_geo_coordinates = $this->get_user_geo_coordinates($login);
        $user_geo_long = $user_geo_coordinates['geo_longitude'];
        $user_geo_lat = $user_geo_coordinates['geo_latitude'];
        $i = 0;
        foreach ($users_data as $user_data){
            $user_login = $users_data[$i]['login'];
            $users_data[$i]['main_photo'] = $this->get_user_main_photo($user_login);
            $users_data[$i]["user_info"] = $this->get_user_info($user_login);
            $users_data[$i]["user_real_name"] = $this->get_user_real_name($user_login);
            $users_data[$i]["user_geo"] = $this->get_user_location($user_login);
            $user_to_geo_long = $users_data[$i]['geo_longitude'];
            $user_to_geo_lat = $users_data[$i]['geo_latitude'];
            $users_data[$i]['distance'] = $this->calculateTheDistance($user_geo_long, $user_geo_lat,
                $user_to_geo_long, $user_to_geo_lat);
            $users_data[$i]["user_tags"] = $this->get_user_tags($user_login);
            $users_data[$i]["user_sex_preference"] = $this->get_user_sex_preference($user_login);
            $users_data[$i]['user_fame_rating'] =  $this->get_user_fame_rating($user_login);
            $users_data[$i]['online_status'] = $this->check_online($user_login);
            $users_data[$i]["user_photos"] = $this->get_user_photos($user_login);
            $users_data[$i]['profile_filled'] = $this->get_profile_filled($user_login);
            $users_data[$i]["user_login"] = $user_login;
            $i++;
        }
        usort($users_data, function($a, $b){
            return ($a['distance'] - $b['distance']);
        });
        usort($users_data, function($a, $b){
            return ($b['user_fame_rating']['fame_rating_id'] - $a['user_fame_rating']['fame_rating_id']);
        });
        usort($users_data, function($a, $b){
            return ($b['profile_filled']['value'] - $a['profile_filled']['value']);
        });
        return ($users_data);
    }
}