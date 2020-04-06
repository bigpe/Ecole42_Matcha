<?php
class Model_Matcha extends Model{
    function get_users($login){
        $recommend_users = $this->get_recommend_users($login);
        $users_data = array();
        return ($users_data);
    }
    function get_recommend_users($login){
        $db = new database();
        $db->db_read_multiple("SELECT login, info FROM USERS WHERE login!='$login'");
    }
}