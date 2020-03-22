<link rel="stylesheet" href="../../css/history.css">
<h3>History</h3>
<div id="people_block">
    <?php
    $users_data = $data['users_data'];
    foreach($users_data as $user_data){
        $main_photo = $user_data['photo_src'];
        $date_time = date("G:i", strtotime($user_data['update_date']));
        $date_one = date("j", strtotime($user_data['update_date']));
        $date_two = date("j", strtotime(next($users_data)['update_date']));
        $main_photo_data = base64_encode(file_get_contents($main_photo));
        $photo_data = "'data: ". mime_content_type($main_photo) .";base64,$main_photo_data'";
        if($date_one != $date_two)
            print("<h4>$date_one</h4>");
        print($date_time);
        print('<a href="/profile/view/?login=' . $user_data['login'] . '">
        <div class="people" style="background: url(' . $photo_data . ') no-repeat center; 
                background-size: cover;"><span class="name"><i class="fas fa-circle" style="color: #5fe15f"></i> '
            . $user_data['login'] . '</span></div></a>');
        print("<label class='tags_labels' >". $user_data['action_icon']."</label>");
    } ?>
</div>

