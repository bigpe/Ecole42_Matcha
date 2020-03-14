<?php
class model_first_login extends Model{
    function get_tags(){
        $db = new database();
        return($db->db_read_multiple("SELECT tag_name, tag_icon, tag_color FROM TAGS"));
    }
}
?>