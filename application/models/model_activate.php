<?php
class Model_Activate extends Model{
    public function verify_token($token, $token_type)
    {
        if(!(parent::verify_token($token, $token_type)))
            return (0);
        return(2); #Error_code 2 - Token is invalid
    }
}
