<?php
class Model_History extends Model{
    function get_user_data($login)
    {
        $db = new database();
        $user_id = $db->db_read("SELECT user_id FROM USERS WHERE login='$login'");
        $user_history = $db->db_read_multiple("SELECT DISTINCT alfa_user_id, A.login, omega_user_id, photo_src, USER_HISTORY.action_id,
    COUNT(A.login) AS action_count, action_icon, USER_HISTORY.update_date
    FROM USER_HISTORY
    JOIN USERS A on USER_HISTORY.alfa_user_id = A.user_id
    JOIN USERS O on USER_HISTORY.omega_user_id = O.user_id
    JOIN USER_MAIN_PHOTO UMP on A.user_id = UMP.user_id
    JOIN USER_PHOTO UP on UMP.photo_id = UP.photo_id
    JOIN USER_ACTIONS UA on USER_HISTORY.action_id = UA.action_id
    WHERE (O.login='$login') AND alfa_user_id!=omega_user_id
    GROUP BY action_id, day(USER_HISTORY.update_date)
    ORDER BY USER_HISTORY.update_date DESC");
        for ($i = 0; $i < count($user_history); $i++)
            $user_history[$i]['online_status'] = $this->check_online($user_history[$i]['login']);
        $this->input_history($user_id, $user_id, 16);
        return ($user_history);
    }
}
