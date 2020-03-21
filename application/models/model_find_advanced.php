<?php
class model_find_advanced extends Model{
    function get_users_data($login, $user_filters){
        $db = new database();
        $age_from = $user_filters['age_from'];
        $age_to = $user_filters['age_to'];
        $users_data = $db->db_read_multiple("SELECT login, photo_src FROM USER_MAIN_PHOTO
                    JOIN USER_PHOTO UP on USER_MAIN_PHOTO.photo_id = UP.photo_id 
                    JOIN USERS U on UP.user_id = U.user_id WHERE login!='$login' 
                    AND (YEAR(CURRENT_TIMESTAMP) - YEAR(age)) BETWEEN '$age_from' AND '$age_to'");
        return($users_data);
    }
    function get_user_filters($login){
        $db = new database();
        $user_filters = $db->db_read_multiple("SELECT age_from, age_to FROM USER_FILTERS 
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
        $users_data = $this->get_users_data($login, $this->get_user_filters($login));
        return($users_data);
    }
}
?>