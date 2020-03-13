<?php
class Model_Profile extends Model{

    public function get_data()
    {
        session_start();
        $login = $_SESSION['login'];
        $db = new database();
        $data = $db->db_read_multiple("SELECT * FROM USERS WHERE login = '$login'");
        $user_id = $data[0]['user_id'];
        if ($data[0]['sex'] == '1')
            $data[0]['sex'] = "Male";
        if ($data[0]['sex'] == '2')
            $data[0]['sex'] = "Female";
        if ($data[0]['preference'] == '0')
            $data[0]['preference'] = "Bisexual";
        if ($data[0]['preference'] == '1')
            $data[0]['preference'] = "Heterosexual";
        if ($data[0]['preference'] == '2')
            $data[0]['preference'] = "Homosexual";
        $data[0]['tags'] = $db->db_read_multiple("SELECT name_tag FROM USERS_TAGS INNER JOIN TAGS on TAGS.tag_id=USERS_TAGS.tag_id WHERE user_id='$user_id'");
        $data[0]['user_pic'] = $db->db_read("SELECT photo_src FROM USER_PHOTO WHERE user_id = '$user_id' group by photo_src DESC;");
        echo json_encode($data[0]);
    }

    public function change_f_name($f_name)
    {
        session_start();
        $login = $_SESSION['login'];
        $db = new database();
        $db->db_change("UPDATE Matcha.USERS SET f_name = '$f_name' WHERE login = '$login'");
        echo "nice";
    }

    public function change_l_name($l_name)
    {
        session_start();
        $login = $_SESSION['login'];
        $db = new database();
        $db->db_change("UPDATE Matcha.USERS SET l_name = '$l_name' WHERE login = '$login'");
        echo "nice";
    }

    public function change_sex($sex)
    {
        session_start();
        $login = $_SESSION['login'];
        $db = new database();
        if ($sex == "Male")
            $value = 1;
        else
            $value = 2;
        $db->db_change("UPDATE Matcha.USERS SET sex = '$value' WHERE login = '$login'");
        echo "nice";
    }

    public function change_sex_pref($sex_pref)
    {
        session_start();
        $login = $_SESSION['login'];
        $db = new database();
        if ($sex_pref == "Heterosexual")
            $value = 1;
        else if ($sex_pref == "Homosexual")
            $value = 2;
        else
            $value = 0;
        $db->db_change("UPDATE Matcha.USERS SET preference = '$value' WHERE login = '$login'");
        echo "nice";
    }

    public function change_info($info)
    {
        session_start();
        $login = $_SESSION['login'];
        $db = new database();
        $db->db_change("UPDATE Matcha.USERS SET info = '$info' WHERE login = '$login'");
        echo "nice";
    }

    public function change_tags($tags){
        session_start();
        $login = $_SESSION['login'];
        $db = new database();
        $user_id = $db->db_read("SELECT user_id FROM Matcha.USERS WHERE login = '$login';");
        $tag_id = $db->db_read("SELECT tag_id FROM Matcha.TAGS WHERE name_tag = '$tags';");
        if (!$tag_id){
            $db->db_change("INSERT INTO Matcha.TAGS (name_tag) VALUE ('$tags') ;");
            $tag_id = $db->db_read("SELECT user_id FROM Matcha.TAGS WHERE name_tag = '$tags';");
        }
        if (!$db->db_check("SELECT * FROM Matcha.USERS_TAGS WHERE tag_id = '$tag_id' AND user_id = '$user_id'"))
            $db->db_change("INSERT INTO Matcha.USERS_TAGS (tag_id, user_id) VALUES ($tag_id, $user_id);");
        echo "nice";
    }

    public function add_photo($user_pic)
    {
        session_start();
        $login = $_SESSION['login'];
        if (!file_exists('./images'))
            mkdir('./images');
        if (!file_exists('./images/user_photo/'))
            mkdir('./images/user_photo/');
        if (!file_exists('./images/user_photo/'.$login))
            mkdir('./images/user_photo/'.$login);
        $name = $login.time();
        $pic = file_get_contents($user_pic);
        $path = './images/user_photo/'.$login.'/'.$name;
        $db = new database();
        $user_id = $db->db_read("SELECT user_id FROM USERS WHERE login = '$login'");
        $count_photo = $db->db_read("SELECT COUNT(*) FROM USER_PHOTO WHERE user_id = '$user_id'");
        if ($count_photo > 5)
            return ("Max 5 photo");
        else
        {
            file_put_contents($path, $pic);
            $insert = $db->db_change("INSERT INTO USER_PHOTO (photo_src, user_id) VALUE ('$path','$user_id');");

        }

    }
}
