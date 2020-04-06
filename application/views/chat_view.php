<link rel="stylesheet" href="../../css/chat.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/css/ion.rangeSlider.min.css"/>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/js/ion.rangeSlider.min.js"></script>
    <?php
    $users_data = $data['users_data'];
    $main_photo = $users_data['main_photo']['photo_src'];
    if(!file_exists($main_photo))
        $main_photo = "./images/placeholder.png";
    $main_photo_data = base64_encode(file_get_contents($main_photo));
    $messages = $users_data['messages'];
    $class = null;
    $photo_data = "'data: ". mime_content_type($main_photo) .";base64,$main_photo_data'";?>
        <div id="people_block">
            <div id="photo_button">
            <div id="options">
            <input type="checkbox" id="options_input">
            <label for="options_input" id="options_label">
            <i class="fas fa-ellipsis-h" ></i>
            <span id="options_menu">
                <p id="dislike_user" onclick="dislike_user()" title="Dislike user"><i class="fas fa-heart-broken"></i></p>
                <p id="block_user" onclick="block_user()" title="Block user"><i class="fas fa-user-lock" ></i></p>
                <p id="report_user" onclick="report_user()" title="Fake account"><i class="fas fa-exclamation"></i></p>
            </span>
            </label>
        </div>
        </div>
<?php print ('<a href=\'/profile/view/?login='.$users_data['login'].'\'><div class="people" style="background: url(' . $photo_data . ') no-repeat center; background-size: cover;">
        <span class="name" id="login_from"><i class="fas fa-circle" style="color:'.$users_data['online_status']['status'].'" 
        title="'.$users_data['online_status']['last_online'].'"></i> '
            . $users_data['login'] . '</span>
        </a>
        </div>
        <div class="more_message" >
            <button id="load_more_message">More message</button>
        </div>
        <div class="messages">');
    foreach($messages as $message){
        $mini_photo = null;
        $status = null;
        if ($message['author'] == "You"){
            $class = "my_message";
            $status = '<i class="fas fa-check" style="color:#2C81B7"></i>';
            if ($message['status_message'] == 0){
                $status = '<i class="fas fa-check" style="color:white" ></i>';
            }
        }
        else{
            $class = "other_message";
            $mini_photo =  '<div class="mini_people" id="mini_photo" style="background: url(' . $photo_data . ') no-repeat center; background-size: cover;"></div>';
        }
            print('
            <div class="'.$class.'" title="'.$message['creation_date'].'">'.$mini_photo.'<span>'.$message['text_message'].'</span>'.$status.'</div>');
    } ?>
        </div>
<div id="message_block"></div>
<div class="input_message">
    <?php
    if ($users_data['ready_to_chat'] > 0)
        print ("<div id=\"textarea\"><textarea id=\"text\" required></textarea></div>
            <div id=\"send_message\" onclick=\"send_message()\"><i class=\"fas fa-arrow-circle-up\"></i></div>");
    else
        print ("<div id=\"textarea\"><textarea id=\"text\" required disabled placeholder='Sorry, this user dont ready to chat'></textarea></div>");
    ?>
</div></div>
<link href="https://cdn.jsdelivr.net/npm/suggestions-jquery@20.2.2/dist/css/suggestions.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/suggestions-jquery@20.2.2/dist/js/jquery.suggestions.min.js"></script>
<script src="../../js/chat.js"></script>