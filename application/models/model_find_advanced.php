<?php
class model_find_advanced extends Model{
    function get_users_data($login){
        $db = new database();
        $users_data = $db->db_read_multiple("SELECT login, photo_src FROM USER_MAIN_PHOTO JOIN USER_PHOTO UP on USER_MAIN_PHOTO.photo_id = UP.photo_id JOIN USERS U on UP.user_id = U.user_id");
        return($users_data);
    }
}
?>