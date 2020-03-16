<?php
class Controller {
    public $model;
    public $view;

    function __construct()
    {
        $this->view = new View();
    }

    function action_index(){
    }
    function check_post_arguments_exists($arguments){
        foreach ($arguments as $argument)
            if(!isset($_POST[$argument]))
                return (0);
        return (1);
    }
    function check_get_arguments_exists($arguments){
        foreach ($arguments as $argument)
            if(!isset($_GET[$argument]))
                return (0);
        return (1);
    }
}
