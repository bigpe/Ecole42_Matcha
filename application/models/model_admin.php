<?php
class model_admin extends Model{
    function get_users(){
        $db = new database();
        return ($db->db_read_multiple("SELECT login FROM USERS limit 20"));
    }
    function users_count(){
        $db = new database();
        return ($db->db_read("SELECT COUNT(*) FROM USERS"));
    }
    function users_reported(){
        $db = new database();
        return ($db->db_read_multiple("SELECT DISTINCT login_to AS login FROM USER_REPORT"));
    }
    function user_hijack($login){
        $_SESSION['login'] = $login;
    }
    function create_users(){
        $db = new database();
        $last_user_id = $db->db_read("SELECT user_id FROM USERS ORDER BY user_id DESC limit 1") + 1;
        $tags = $db->db_read_multiple("SELECT tag_id FROM TAGS");
        $tags_count = count($tags);
        $users = [];
        for($i = 0; $i < 50; $i++){
            $random_tag_id_one = $tags[rand(0, $tags_count - 1)]['tag_id'];
            $random_tag_id_two = $tags[rand(0, $tags_count - 1)]['tag_id'];
            $login = "test_user$last_user_id";
            $last_user_id++;
            $random_sex_preference = rand(1, 2);
            $random_sex = rand(1, 2);
            $geo = "Москва";
            $email = "$login@gmail.com";
            $geo_longitude = "37." . rand(0, 9999);
            $geo_latitude = "55." . rand(0, 9999);
            $age = rand(1980, 2002) . "-01-01 00:00:00";
            $db->db_change("INSERT IGNORE INTO USERS (login, email, geo, geo_longitude, geo_latitude, age, sex, sex_preference) 
                                    VALUES ('$login', '$email', '$geo', '$geo_longitude', '$geo_latitude', '$age', '$random_sex', '$random_sex_preference')");
            $user_id = $db->db_read("SELECT user_id FROM USERS WHERE login='$login'");
            $db->db_change("INSERT IGNORE INTO USER_TAGS (user_id, tag_id) VALUES ('$user_id', '$random_tag_id_one')");
            $db->db_change("INSERT IGNORE INTO USER_TAGS (user_id, tag_id) VALUES ('$user_id', '$random_tag_id_two')");
            $token = time() . $last_user_id;
            $db->db_change("INSERT IGNORE INTO USER_PHOTO (user_id, photo_token, photo_src) 
                                    VALUES ('$user_id', '$token', './images/placeholder.png')");
            $photo_id = $db->db_read("SELECT photo_id FROM USER_PHOTO WHERE photo_token='$token'");
            $db->db_change("INSERT IGNORE INTO USER_MAIN_PHOTO (user_id, photo_id) VALUES ('$user_id', '$photo_id')");
            $users[] = array("login" => $login);
        }
        return ($users);
    }
    function delete_user($login){
        $db = new database();
        $db->db_change("DELETE FROM USERS WHERE login='$login'");
    }
    function delete_report($login){
        $db = new database();
        $db->db_change("DELETE FROM USER_REPORT WHERE login_to='$login'");
    }
    function get_all_users(){
        $db = new database();
        return ($db->db_read_multiple("SELECT login FROM USERS limit 1000 offset 20"));
    }
}
?>