<?php
class Controller_Index extends Controller
{
    function __construct()
    {
        session_start();
        if (isset($_SESSION['status']))
        {
            $this->model = new Model_Index();
            $this->view = new View();
        }
        else
            header('Location:/sign_in');
    }

    function action_index()
    {
        session_start();
        if (isset($_SESSION['status']))
        {
            $data['effects'] = $this->model->get_effects();
            $data['photo'] = $this->model->get_photo();
            $this->view->generate('index_view.php', 'template_view.php',$data);
        }
        else
            header('Location:/sign_in');
    }

    function action_camshoot()
    {
        if (isset($_POST['photo']))
        {
            $this->model = new Model_Index();
            $this->model->new_photo($_POST);
        }
    }
    function action_save_photo()
    {
        if (isset($_POST['photo']))
            $this->model->save_photo();
        header('Location:/index');
    }

    function action_logout()
    {
        session_start();
        session_destroy();
        header('Location:/');
    }

    function action_no_camshoot()
    {
        $data = $_FILES;
        $this->view->generate('test_view.php', 'template_view.php', $data);
    }

}
?>