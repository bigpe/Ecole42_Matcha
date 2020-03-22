<?php
class Model_History extends Model{
    function get_user_data($login)
    {
        $db = new database();
        $user_id = $db->db_read("SELECT user_id FROM USERS WHERE login='$login'");
        $user_history = $db->db_read_multiple("SELECT photo_src, USERS.login, USER_HISTORY.update_date, action_icon
                FROM USER_HISTORY JOIN USERS on USERS.user_id=USER_HISTORY.alfa_user_id
                JOIN USER_MAIN_PHOTO UMP on USERS.user_id = UMP.user_id
                JOIN USER_PHOTO UP on UMP.photo_id = UP.photo_id
                JOIN USER_ACTIONS UA on USER_HISTORY.action_id = UA.action_id
                WHERE omega_user_id='$user_id' AND USER_HISTORY.omega_user_id != USER_HISTORY.alfa_user_id order by USER_HISTORY.update_date DESC");
        $this->input_history($user_id, $user_id, 16);
        return ($user_history);
    }
}
