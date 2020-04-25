<?php
class Controller_New_users extends Controller
{
    function __construct(){
        $this->model = new Model_New_users();
    }
    function action_index()
    {
        $this->model->new_users();
    }
}