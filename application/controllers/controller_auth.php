<?php
class Controller_Auth extends Controller
{
    function __construct()
    {
        parent::__construct();
        $this->model = new Model_Auth();
    }
    function action_index()
    {
        if($this->model->check_session()) #Success
            header("Location: /");
        else
            $this->view->generate("auth_view.php", "template_view.php",
                array("error" => $this->model->error_handler($this->model->error_id)));
    }
    function action_sign_in()
    {
        if ($this->check_post_arguments_exists(array("login", "password"))) {
            $login = $_POST['login'];
            $password = md5($_POST['password']);
            $error_code = $this->model->sign_in($login, $password);
            if (!$error_code) #Success
                header("Location: /");
            else
                $this->view->generate("auth_view.php", "template_view.php",
                    array("error" => $this->model->error_handler($this->model->error_id)));
        }
        else {
            $this->model->error_id = 11;
            if($this->model->check_session()) #Success
                $this->view->generate("main_page_view.php", "template_view.php",
                    array("error" => $this->model->error_handler($this->model->error_id)));
            else
                $this->view->generate("auth_view.php", "template_view.php",
                    array("error" => $this->model->error_handler($this->model->error_id)));
        }
    }
    function action_sign_out()
    {
        $error_code = $this->model->sign_out();
        if (!$error_code) { #Success
            header("Location: /");
        }
    }

    function action_with_google(){
        print_r($_GET);
        if (!empty($_GET['code'])) {
            $params = array(
                'client_id'     => '158115967922-0g334aa81m1bk7e09a3go97oiquo80cs.apps.googleusercontent.com',
                'client_secret' => 'A9pF_X-KAWrnfIZwfJLaf_uE',
                'redirect_uri'  => 'https://matcha.fun/Auth/with_google',
                'grant_type'    => 'authorization_code',
                'code'          => $_GET['code']
            );

            $ch = curl_init('https://accounts.google.com/o/oauth2/token');
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HEADER, false);
            $data = curl_exec($ch);
            curl_close($ch);

            $data = json_decode($data, true);
            if (!empty($data['access_token'])) {
                // Токен получили, получаем данные пользователя.
                $params = array(
                    'access_token' => $data['access_token'],
                    'id_token'     => $data['id_token'],
                    'token_type'   => 'Bearer',
                    'expires_in'   => 3599
                );
                $info = file_get_contents('https://www.googleapis.com/oauth2/v1/userinfo?' . urldecode(http_build_query($params)));
                $info = json_decode($info, true);
                var_dump($info);

            }
        }
        $this->view->generate("test_view.php", "template_view.php", $info);
    }
}
?>