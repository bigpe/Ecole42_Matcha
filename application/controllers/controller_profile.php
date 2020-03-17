<?php
class Controller_Profile extends Controller
{
    function __construct(){
        $this->model = new Model_Profile();
        $this->view = new View();
    }

    function action_index(){
<<<<<<< HEAD
        session_start();
        $_SESSION['login'] = "ukaron";
        $this->view->generate('profile_view.php', 'template_view.php');
=======
        if($this->model->check_session()) #Success
            if(!$this->model->check_tutorial($_SESSION['login']))
                $this->view->generate("profile_view.php", "template_view.php",
                    $this->model->error_handler($this->model->error_id));
        else
            header("Location: /");
>>>>>>> 79609902b648424b2d63b7df49a7106cb548ae21
    }

    function action_get_data()
    {
        $data = $this->model->get_data();
    }
    function action_change_f_name(){
        $f_name = $_POST['f_name'];
        $this->model->change_f_name($f_name);
    }
    function action_change_l_name(){
        $l_name = $_POST['l_name'];
        $this->model->change_l_name($l_name);
    }
    function action_change_sex(){
        $sex = $_POST['sex'];
        $this->model->change_sex($sex);

    }
    function action_change_sex_pref(){
        $sex_pref = $_POST['sex_pref'];
        $this->model->change_sex_pref($sex_pref);
    }
    function action_change_info(){
        $info = $_POST['info'];
        $this->model->change_info($info);
    }
    function action_change_tags(){
        $tags = $_POST['tags'];
        $this->model->change_tags($tags);
    }
    function action_add_photo()
    {
        $user_pic = $_POST['user_pic'];
        $this->model->add_photo($user_pic);
    }
}
?>