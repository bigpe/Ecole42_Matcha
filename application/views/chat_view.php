<link rel="stylesheet" href="../../css/chat.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/css/ion.rangeSlider.min.css"/>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/js/ion.rangeSlider.min.js"></script>
    <?php
    $users_data = $data['users_data'];
    $main_photo = $users_data['main_photo']['photo_src'];
    $main_photo_data = base64_encode(file_get_contents($main_photo));
    $messages = $users_data['messages'];
    $class = null;
    $photo_data = "'data: ". mime_content_type($main_photo) .";base64,$main_photo_data'";
    print('<div id="people_block">
        <div class="people" style="background: url(' . $photo_data . ') no-repeat center; background-size: cover;">
        <a href="/profile/view/?login=' . $users_data['login'] . '">
        <span class="name" id="login_from"><i class="fas fa-circle" style="color:'.$users_data['online_status']['status'].'" 
        title="'.$users_data['online_status']['last_online'].'"></i> '
            . $users_data['login'] . '</span></a>
        </div>
        </div>
        <div class="messages">');
    foreach($messages as $message){
        $mini_photo = null;
        if ($message['author'] == "You"){
            $class = "my_message";
            }
        else{
            $class = "other_message";
            $mini_photo =  '<div class="mini_people" id="mini_photo" style="background: url(' . $photo_data . ') no-repeat center; background-size: cover;"></div>';
        }
            print('
            <div class="'.$class.'">'.$mini_photo.'<span>'.$message['text_message'].'</span></div>');
    } ?>
</div>
<div id="message_block"></div>
<div class="input_message">
    <div id="textarea"><textarea id="text" required></textarea></div>
    <div id="send_message" onclick="send_message()"><i class="fas fa-arrow-circle-up"></i></div>
</div>
<link href="https://cdn.jsdelivr.net/npm/suggestions-jquery@20.2.2/dist/css/suggestions.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/suggestions-jquery@20.2.2/dist/js/jquery.suggestions.min.js"></script>
<script src="../../js/chat.js"></script>