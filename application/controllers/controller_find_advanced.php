<?php
class controller_find_advanced extends Controller{
    function __construct()
    {
        $this->model = new Model_Find_advanced();
        $this->view = new View();
    }

    function action_index()
    {
        if($this->model->check_session()){ #Success
            $user_filters = $this->model->get_user_filters($_SESSION['login']);
            $users_data = $this->model->get_users_data($_SESSION['login'], $user_filters);
            $user_tags = $this->model->get_tags($_SESSION['login']);
            $this->view->generate("find_advanced_view.php", "template_view.php",
                array("error" => $this->model->error_handler($this->model->error_id),
                    "users_data" => $users_data, "user_filters" => $user_filters, "user_tags" => $user_tags));
        }
        else
            header("Location: /");
    }
    function action_save_filters(){
        if($this->model->check_session()) { #Success
            $users_data = $this->model->save_filters($_POST);
            print_r(json_encode($users_data));
        }
    }
    function action_find_tags(){
        if($this->model->check_session() && $this->check_post_arguments_exists(array("keyword"))) { #Success
            $tags = $this->model->find_tags($_POST['keyword']);
            print_r(json_encode($tags));
        }
    }
    function action_delete_filter(){
        if($this->model->check_session() && $this->check_post_arguments_exists(array("filter_to_delete"))){ #Success
            $users_data = $this->model->delete_filter($_SESSION['login'], $_POST['filter_to_delete']);
            print_r(json_encode($users_data));
        }
    }
}

?>