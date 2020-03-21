<link rel="stylesheet" href="../../css/history.css">
<h3>History</h3>
<div id="people_block">
    <?php
    $users_data = $data['users_data'];
    foreach($users_data as $user_data){
        $main_photo = $user_data['photo_src'];
        $main_photo_data = base64_encode(file_get_contents($main_photo));
        $photo_data = "'data: ". mime_content_type($main_photo) .";base64,$main_photo_data'";
        print('<h4>'.$user_data['update_date'].'</h4>
        <a href="/profile/view/?login=' . $user_data['login'] . '">
        <div class="people" style="background: url(' . $photo_data . ') no-repeat center; 
                background-size: cover;"><span class="name"><i class="fas fa-circle" style="color: #5fe15f"></i> '
            . $user_data['login'] . '</span></div></a>');
        print("<label class='tags_labels' >".$user_data['action_icon']."</label>");
    } ?>
</div>

