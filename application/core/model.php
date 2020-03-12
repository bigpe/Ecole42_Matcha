<?php
class Model
{
    public function get_data()
    {

    }
    function create_message($text){
        $message = "<h2>Matcha Project</h2>
                    <p>$text</p>";
        return ($message);
    }
    function error_handler($err){
        $db = new database();
        $error_name = $db->db_read("SELECT error_name FROM ERROR_HANDLER WHERE error_id='$err'");
    }
}
