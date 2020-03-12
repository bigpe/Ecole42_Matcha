<?php
class Model_Index extends Model
{
    private $pic;
    private $mask;
    private $path;

    public function get_effects()
    {
        $effects = scandir("./images/resources");
        return $effects;
    }

    public function new_photo($post)
    {
        if (isset($post['photo']) && isset($post['mask']))
        {
            session_start();
            $login = $_SESSION['login'];
            if (!file_exists('./images/user_photo/'))
                mkdir('./images/user_photo/');
            if (!file_exists('./images/user_photo/tmp'))
                mkdir('./images/user_photo/tmp');
            if (!file_exists('./images/user_photo/tmp/'.$login))
                mkdir('./images/user_photo/tmp/'.$login);
            if (!file_exists('./images/user_photo/'.$login))
                mkdir('./images/user_photo/'.$login);
            $this->pic = file_get_contents(base64_decode($post['photo']));
            $this->path = './images/user_photo/'.$login.'/tmp1.png';
            file_put_contents($this->path, $this->pic);
            $this->mask = $post['mask'];
            $pic = imagecreatefrompng($this->path);
            $mask = imagecreatefrompng($this->mask);
            imagealphablending($mask, true);
            imagesavealpha($mask, true);
            imagecopy($pic, $mask, 0, 0, 0, 0, imagesx($pic), imagesy($pic));
            imagepng($pic, './images/user_photo/tmp/'.$login.'/tmp.png');
            echo base64_encode(file_get_contents('./images/user_photo/tmp/'.$login.'/tmp.png'));
        }
    }

    public function save_photo()
    {
        session_start();
        include_once './config/database.php';
        date_default_timezone_set('UTC');
        $login = $_SESSION['login'];
        if ($_POST['photo'])
        {
            $path = './images/user_photo/tmp/'.$login.'/tmp.png';
            $pic = file_get_contents(base64_decode($_POST['photo']));
            file_put_contents($path, $pic);
            $pic = imagecreatefrompng($path);
            imagepng($pic, './images/user_photo/tmp/'.$login.'/tmp.png');
            $tmpPath = './images/user_photo/tmp/'.$login.'/tmp.png';
            $path = './images/user_photo/'.$login.'/'.time().hash(sha256, $login).'.png';
            $connect = new connectBD();
            $connect->connect();
            copy($tmpPath, $path);
            $connect->DBH->query("INSERT INTO cam_users.UserPhoto (path, login) VALUES ('".$path."', '".$login."');");
            $connect->closeConnect();
            return true;
        }
        return false;
    }

    public function get_photo()
    {
        session_start();
        include_once './config/database.php';
        date_default_timezone_set('UTC');
        $login = $_SESSION['login'];
        $connect = new connectBD();
        $connect->connect();
        $query = $connect->DBH->prepare("SELECT path FROM cam_users.UserPhoto WHERE login = ? ORDER BY id DESC");
        $query->execute(array($login));
        $photo = $query->fetchAll();
        return $photo;
    }

}
