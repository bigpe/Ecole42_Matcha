<?php
class Model_Activate extends Model{
    public function verify_token($token, $token_type)
    {
        if(!(parent::verify_token($token, $token_type)))
            return (0);
        return(2); #Error_code 2 - Token is invalid
    }
    public function get_email_from_token($token){
        $db = new database();
        $token_id = $db->db_read("SELECT token_id FROM TOKENS WHERE token='$token'");
        $email = $db->db_read("SELECT email FROM USER_TEMP WHERE token_id='$token_id'");
        return ($email);
    }
}
