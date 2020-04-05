<link rel="stylesheet" href="../../css/conversation.css">
<div id="people_block">
    <?php
    $users_data = $data['users_data'];
    $checked = '';
    $not_checked = 'background-color: rgba(90,92,203,0.4)';
    if($users_data){
        foreach($users_data as $user_data) {
            !$user_data['last_message'][0]['status_message'] ? $user_data['last_message'][0]['status_message'] = $not_checked :
                $user_data['last_message'][0]['status_message'] = $checked;
            $main_photo = $user_data['main_photo']['photo_src'];
            !file_exists($main_photo) ? $main_photo = "./images/placeholder.png" : "";
//            $main_photo_data = base64_encode(file_get_contents($main_photo));
//            $photo_data = "'data: " . mime_content_type($main_photo) . ";base64,$main_photo_data'";
            $login = $user_data['login'];
            $online_status = $user_data['online_status']['status'];
            $last_online = $user_data['online_status']['last_online'];
            $status_message = $user_data['last_message'][0]['status_message'];
            $chat_id = $user_data['chat_id'];
            $author = $user_data['last_message'][0]['author'];
            $text_message = $user_data['last_message'][0]['text_message'];
            print("<div class='people'><div class='user_photo' style='background: url($main_photo) no-repeat center; 
                    background-size: cover'></div>
                <span class='name'>
                <a href='/profile/view/?login=$login'>
                <i class='fas fa-circle' style='color: $online_status' title='$last_online'></i> $login</a></span>
                <span class='message' style='$status_message'>
                <a href='/conversation/chat_view/?id=$chat_id'>
                $author:  $text_message</a></span></div>");
        }
    }
    else
        print("<h2>Not Conversation Yet</h2>");
    ?>
</div>