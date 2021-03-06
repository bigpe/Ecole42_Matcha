<?php
class model_first_login extends Model{
    public $tag_limit = 5;
    public $tag_offset = 0;

    function end_tutorial($data, $login){
        $db = new database();
        $user_id = $db->db_read("SELECT user_id FROM USERS WHERE login='$login'");
        $sex = $data['sex'];
        $sex_preference = $data['sex_preference'];
        $user_age = $data['user_age']. " 00:00:00";
        $info = $data['info'];
        $tags = $data['tags'];
        $user_geo = $data['user_geo'];
        $user_geo_longitude = $data['user_geo_longitude'];
        $user_geo_latitude = $data['user_geo_latitude'];
        $user_full_name = $data['user_full_name'];
        $user_main_photo_token = $data['user_main_photo'];
        $user_photo_id = $db->db_read("SELECT photo_id FROM USER_PHOTO WHERE photo_token='$user_main_photo_token'");
        $db->db_change("UPDATE USERS SET sex='$sex', sex_preference='$sex_preference', age='$user_age', 
                 info='$info', geo='$user_geo', geo_longitude='$user_geo_longitude', 
                 geo_latitude='$user_geo_latitude', full_name='$user_full_name' WHERE user_id='$user_id'");
        foreach($tags as $tag) {
            $db->db_change("INSERT IGNORE INTO USER_TAGS (user_id, tag_id) SELECT '$user_id', tag_id FROM TAGS WHERE tag_name='$tag'");
            $db->db_change("UPDATE TAGS SET tag_rate=tag_rate+1 WHERE tag_name='$tag'");
        }
        $db->db_change("INSERT IGNORE INTO USER_MAIN_PHOTO (user_id, photo_id) VALUES ('$user_id', '$user_photo_id')");
        $db->db_change("INSERT IGNORE INTO USER_FILTERS (user_id, age_from, age_to, geo, geo_from, geo_to) VALUES ('$user_id', '18', '30', '$user_geo', '1', '50')");
    }
    function get_tags(){
        $db = new database();
        return($db->db_read_multiple("SELECT tag_name, tag_icon, tag_color FROM TAGS ORDER BY tag_rate DESC 
                LIMIT $this->tag_limit OFFSET $this->tag_offset"));
    }
    function get_all_tags(){
        $db = new database();
        return($db->db_read_multiple("SELECT tag_name, tag_icon, tag_color 
                                FROM TAGS ORDER BY tag_rate DESC LIMIT 100 OFFSET 5"));
    }
    function save_photos($photos, $login){
        $db = new database();
        $photos = json_decode($photos, true);
        $user_id = $db->db_read("SELECT user_id FROM USERS WHERE login='$login'");
        foreach($photos as $photo){
            $photo_name = md5($photo['image_token']);
            $photo_token = $photo['image_token'];
            $photo_path = "./images/user_photo/$photo_name.jpg";
            $db->db_change("INSERT INTO USER_PHOTO (user_id, photo_token, photo_src) 
                                    VALUES ('$user_id', '$photo_token', '$photo_path')");
            file_put_contents($photo_path, file_get_contents($photo['image_base64']));
        }
    }
}
?>