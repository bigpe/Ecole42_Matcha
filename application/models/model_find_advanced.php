<?php
class model_find_advanced extends Model{
    function get_users_data($login, $user_filters){
        $db = new database();
        $query_append = "";
        if(isset($user_filters['age_from']) && isset($user_filters['age_to'])){
            $age_from = $user_filters['age_from'];
            $age_to = $user_filters['age_to'];
            $query_append = $query_append . " AND (YEAR(CURRENT_TIMESTAMP) - YEAR(age)) BETWEEN '$age_from' AND '$age_to'";
        }
        if(isset($user_filters['geo'])){
            $geo = $user_filters['geo'];
            if($geo)
                $query_append = $query_append . " AND geo='$geo'";
        }
        if(isset($user_filters['tags'])) {
            $tags = unserialize($user_filters['tags']);
            if($tags) {
                $query_append = $query_append . " AND (";
                foreach ($tags as $tag) {
                    $query_append = $query_append . "tag_name='$tag'";
                    if (next($tags))
                        $query_append = $query_append . "OR ";
                }
                $query_append = $query_append . ")";
            }
        }
        if(isset($user_filters['sex_preference'])){
            $sex_preference = $user_filters['sex_preference'];
            if($sex_preference)
                $query_append = "$query_append AND U.sex='$sex_preference'";
        }
        $user_black_list = $this->get_user_black_list($login);
        if($user_black_list){
            foreach ($user_black_list as $ubl) {
                $user_id_blocked = $ubl['user_id_blocked'];
                $query_append = $query_append . " AND U.user_id!='$user_id_blocked'";
            }
        }
        $users_data = $db->db_read_multiple("SELECT DISTINCT login, photo_src, geo_longitude, geo_latitude, 
                (YEAR(CURRENT_DATE) - YEAR(age)) AS age
                    FROM USER_MAIN_PHOTO
                    JOIN USER_PHOTO UP on USER_MAIN_PHOTO.photo_id = UP.photo_id 
                    JOIN USERS U on UP.user_id = U.user_id 
                    JOIN USER_TAGS UT on U.user_id = UT.user_id
                    JOIN TAGS T on UT.tag_id = T.tag_id WHERE login!='$login' $query_append");
        for ($i = 0; $i < count($users_data); $i++) {
            $users_data[$i]['online_status'] = $this->check_online($users_data[$i]['login']);
            $users_data[$i]['fame_rating'] = $this->get_user_fame_rating($users_data[$i]['login']);
        }
        if($user_filters['fame_rating'] >= 0) {
            $users_data_to_delete = [];
            for ($i = 0; $i < count($users_data); $i++) {
                $user_fame_rating = $users_data[$i]['fame_rating']['fame_rating_id'];
                if ($user_filters['fame_rating'] != $user_fame_rating && $user_filters['fame_rating'])
                    $users_data_to_delete[] = $i;
            }
            if ($users_data_to_delete) {
                for ($i = 0; $i < count($users_data_to_delete); $i++)
                    unset($users_data[$users_data_to_delete[$i]]);
            }
            sort($users_data);
        }
        $user_geo_coordinates = $this->get_user_geo_coordinates($login);
        $user_geo_long = $user_geo_coordinates['geo_longitude'];
        $user_geo_lat = $user_geo_coordinates['geo_latitude'];
        $users_data_to_delete = [];
        $i = 0;
        foreach ($users_data as $user_data){
            $user_to_geo_long = $user_data['geo_longitude'];
            $user_to_geo_lat = $user_data['geo_latitude'];
            $distance = $this->calculateTheDistance($user_geo_long, $user_geo_lat, $user_to_geo_long, $user_to_geo_lat);
            if(isset($user_filters['geo_from']) && isset($user_filters['geo_to'])){
                $distance ? $distance_km = round($distance / 1000) : $distance_km = 1;
                if($user_filters['geo_from'] >= 1 && $distance_km < $user_filters['geo_from'])
                    $users_data_to_delete[] = $i;
                if($user_filters['geo_to'] < 50 && $distance_km > $user_filters['geo_to'])
                    $users_data_to_delete[] = $i;
            }
            $users_data[$i]['distance'] = $distance;
            $i++;
        }
        if ($users_data_to_delete) {
            for ($i = 0; $i < count($users_data_to_delete); $i++)
                unset($users_data[$users_data_to_delete[$i]]);
        }
        sort($users_data);
        if($user_filters['age_sort'] == -1) {
            usort($users_data, function ($a, $b) {
                return ($a['age'] - $b['age']);
            });
        }
        else{
            usort($users_data, function ($a, $b) {
                return ($b['age'] - $a['age']);
            });
        }
        if($user_filters['fame_rating'] == -1) {
            usort($users_data, function ($a, $b) {
                return ($a['fame_rating']['fame_rating_id'] - $b['fame_rating']['fame_rating_id']);
            });
        }
        elseif ($user_filters['fame_rating'] == -2){
            usort($users_data, function ($a, $b) {
                return ($b['fame_rating']['fame_rating_id'] - $a['fame_rating']['fame_rating_id']);
            });
        }
        if($user_filters['geo_sort'] == -1) {
            usort($users_data, function ($a, $b) {
                return ($a['distance'] - $b['distance']);
            });
        }
        else{
            usort($users_data, function ($a, $b) {
                return ($b['distance'] - $a['distance']);
            });
        }
        $user_id = $db->db_read("SELECT user_id FROM USERS WHERE login='$login'");
        $this->input_history($user_id, $user_id, 14);
        return($users_data);
    }
    function get_user_filters($login){
        $db = new database();
        $user_filters = $db->db_read_multiple("SELECT age_from, age_to, USER_FILTERS.geo, fame_rating, tags, geo_from, geo_to, 
                sex_preference, age_sort, geo_sort FROM USER_FILTERS JOIN USERS U on USER_FILTERS.user_id = U.user_id WHERE U.login='$login'")[0];
        return($user_filters);
    }
    function save_filters($user_filters){
        $db = new database();
        $login = $_SESSION['login'];
        if(isset($user_filters['slider_filter']['filter_name'])) {
            if ($user_filters['slider_filter']['filter_name'] == "age_filter") {
                $age_from = $user_filters['slider_filter']['from'];
                $age_to = $user_filters['slider_filter']['to'];
                $db->db_change("UPDATE USER_FILTERS 
                                    JOIN USERS U on USER_FILTERS.user_id = U.user_id 
                                    SET age_from='$age_from', age_to='$age_to' WHERE U.login='$login'");
            }
            if ($user_filters['slider_filter']['filter_name'] == "geo_filter") {
                $geo_from = $user_filters['slider_filter']['from'];
                $geo_to = $user_filters['slider_filter']['to'];
                $db->db_change("UPDATE USER_FILTERS 
                                    JOIN USERS U on USER_FILTERS.user_id = U.user_id 
                                    SET geo_from='$geo_from', geo_to='$geo_to' WHERE U.login='$login'");
            }
        }
        if(isset($user_filters['geo_filter'])){
            $geo = $user_filters['geo_filter']['geo'];
            $db->db_change("UPDATE USER_FILTERS 
                                    JOIN USERS U on USER_FILTERS.user_id = U.user_id 
                                    SET USER_FILTERS.geo='$geo' WHERE U.login='$login'");
        }
        if(isset($user_filters['fame_filter'])){
            $fame = $user_filters['fame_filter']['fame_rating'];
            $db->db_change("UPDATE USER_FILTERS 
                                    JOIN USERS U on USER_FILTERS.user_id = U.user_id 
                                    SET fame_rating='$fame' WHERE login='$login'");
        }
        if(isset($user_filters['tags_filter'])) {
            $tags = serialize($user_filters['tags_filter']['tags']);
            $db->db_change("UPDATE USER_FILTERS JOIN USERS U on USER_FILTERS.user_id = U.user_id 
                                    SET tags='$tags' WHERE login='$login'");
        }
        if(isset($user_filters['sex_filter'])) {
            $sex_preference = $user_filters['sex_filter']['sex_preference'];
            $db->db_change("UPDATE USERS SET sex_preference='$sex_preference' WHERE login='$login'");
        }
        if(isset($user_filters['age_filter_sort'])) {
            $age_sort = $user_filters['age_filter_sort']['age_sort'];
            $db->db_change("UPDATE USER_FILTERS JOIN USERS U on USER_FILTERS.user_id = U.user_id 
                                    SET age_sort='$age_sort' WHERE login='$login'");
        }
        if(isset($user_filters['geo_filter_sort'])) {
            $geo_sort = $user_filters['geo_filter_sort']['geo_sort'];
            $db->db_change("UPDATE USER_FILTERS JOIN USERS U on USER_FILTERS.user_id = U.user_id 
                                    SET geo_sort='$geo_sort' WHERE login='$login'");
        }
        $users_data = $this->get_users_data($login, $this->get_user_filters($login));
        return($users_data);
    }
    function get_user_location($login){
        $db = new database();
        $user_location = $db->db_read("SELECT USER_FILTERS.geo 
                            FROM USER_FILTERS JOIN USERS U on USER_FILTERS.user_id = U.user_id WHERE login='$login'");
        return($user_location);
    }
    function get_tags($login){
        $db = new database();
        $user_tags = [];
        $tags = unserialize($db->db_read("SELECT tags FROM USER_FILTERS 
                        JOIN USERS U on USER_FILTERS.user_id = U.user_id WHERE login='$login'"));
        if($tags) {
            foreach ($tags as $tag)
                $user_tags[] = $db->db_read_multiple("SELECT tag_name, tag_icon, tag_color 
                                                            FROM TAGS WHERE tag_name='$tag'")[0];
        }
        return($user_tags);
    }
    function find_tags($keyword){
        $db = new database();
        $tags = $db->db_read_multiple("SELECT tag_name, tag_icon, tag_color 
                                            FROM TAGS WHERE tag_name LIKE '#$keyword%'");
        return($tags);
    }
    function delete_filter($login, $filter){
        $db = new database();
        $db->db_change("UPDATE USER_FILTERS 
                JOIN USERS U on USER_FILTERS.user_id = U.user_id SET tags=null WHERE login='$login'");
        $users_data = $this->get_users_data($login, $this->get_user_filters($login));
        return($users_data);
    }
}
?>