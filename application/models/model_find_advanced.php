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
            $query_append = $query_append . " AND geo='$geo'";
        }
        $users_data = $db->db_read_multiple("SELECT login, photo_src FROM USER_MAIN_PHOTO
                    JOIN USER_PHOTO UP on USER_MAIN_PHOTO.photo_id = UP.photo_id 
                    JOIN USERS U on UP.user_id = U.user_id WHERE login!='$login'" . $query_append);
        for ($i = 0; $i < count($users_data); $i++) {
            $users_data[$i]['online_status'] = $this->check_online($users_data[$i]['login']);
            $users_data[$i]['fame_rating'] = $this->get_user_fame_rating($users_data[$i]['login']);
        }
        $users_data_to_delete = [];
        for ($i = 0; $i < count($users_data); $i++) {
            $user_fame_rating = $users_data[$i]['fame_rating']['fame_rating_id'];
            if($user_filters['fame_rating'] != $user_fame_rating && $user_filters['fame_rating'])
                $users_data_to_delete[] = $i;
        }
        if($users_data_to_delete) {
            for($i = 0; $i < count($users_data_to_delete); $i++)
                unset($users_data[$users_data_to_delete[$i]]);
        }
        sort($users_data);
        $user_id = $db->db_read("SELECT user_id FROM USERS WHERE login='$login'");
        $this->input_history($user_id, $user_id, 14);
        return($users_data);
    }
    function get_user_filters($login){
        $db = new database();
        $user_filters = $db->db_read_multiple("SELECT age_from, age_to, USER_FILTERS.geo, fame_rating FROM USER_FILTERS 
                JOIN USERS U on USER_FILTERS.user_id = U.user_id WHERE U.login='$login'")[0];
        return($user_filters);
    }
    function save_filters($user_filters){
        $db = new database();
        $login = $_SESSION['login'];
        if(isset($user_filters['age_filter'])) {
            $age_from = $user_filters['age_filter']['age_from'];
            $age_to = $user_filters['age_filter']['age_to'];
            $db->db_change("UPDATE USER_FILTERS 
                                    JOIN USERS U on USER_FILTERS.user_id = U.user_id 
                                    SET age_from='$age_from', age_to='$age_to' WHERE U.login='$login'");
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
        $users_data = $this->get_users_data($login, $this->get_user_filters($login));
        return($users_data);
    }
    function get_user_location($login){
        $db = new database();
        $user_location = $db->db_read("SELECT USER_FILTERS.geo 
                            FROM USER_FILTERS JOIN USERS U on USER_FILTERS.user_id = U.user_id WHERE login='$login'");
        return($user_location);
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
            $user_fame_rating_id = $db->db_read_multiple("SELECT fame_rating_id, fame_rating_color
                FROM FAME_RATING WHERE '$user_fame_rating_count' >= fame_rating_start AND 
                                        '$user_fame_rating_count' <= fame_rating_end")[0];
        return($user_fame_rating_id);
    }
}
?>