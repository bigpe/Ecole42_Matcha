<?php
class Model
{
    public $error_id = 0;
    public $view;
    public $model;

    public function __construct()
    {
        session_start();
    }

    public function get_data()
    {

    }
    function create_message($text){
        $message = "<h2>Matcha Project</h2>
                    $text";
        return ($message);
    }
    function error_handler($error_id){
        $db = new database();
        if($error_id == 0)
            return("OK");
        $error_name = $db->db_read("SELECT error_name FROM ERROR_HANDLER WHERE error_id='$error_id'");
        return($error_name);
    }
    function save_token ($token, $token_type){
        $db = new database();
        $db->db_change("INSERT INTO TOKENS (token, token_type) VALUES ('$token', '$token_type')");
        $token_id = $db->db_read("SELECT token_id FROM TOKENS WHERE token='$token'");
        return($token_id);
    }
    function verify_token ($token, $token_type){
        $db = new database();
        if($db->db_check("SELECT token FROM TOKENS WHERE token_type='$token_type' AND token='$token'"))
            return (0);
        return(2); #Error_code 2 - Token is invalid
    }
    function delete_token ($token){
        $db = new database();
        $db->db_change("DELETE FROM TOKENS WHERE token='$token'");
    }
    function check_session($model){
        $this->view = new View();
        $this->model = $model;
        if(isset($_SESSION['login'])) #Success
            return($this->view->generate("main_page_view.php", "template_view.php",
                $this->model->error_handler($this->error_id)));
        else
            return($this->view->generate("auth_view.php", "template_view.php",
                $this->model->error_handler($this->error_id)));
    }
}
