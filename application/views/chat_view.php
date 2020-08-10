<link rel="stylesheet" href="../../css/chat.css">
    <?php
    $users_data = $data['users_data'];
    $main_photo = $users_data['main_photo']['photo_src'];
    if(!file_exists($main_photo))
        $main_photo = "./images/placeholder.png";
    $main_photo_data = base64_encode(file_get_contents($main_photo));
    $messages = $users_data['messages'];
    if ($users_data['like_status'] > 0)
        $i_like = "fas fa-heart-broken";
    else
        $i_like = "fas fa-heart";
    if ($users_data['block_status'] > 0) {
        $i_block = 'fas fa-lock-open';
        $block_on_click = "unblock_user()";
       }
    else{
        $i_block = 'fas fa-user-lock';
        $block_on_click = "block_user()";
}
    $class = null;
    $photo_data = "'data: ". mime_content_type($main_photo) .";base64,$main_photo_data'";?>
        <div id="people_block">
            <div id="photo_button">
                <div id="options">
                    <input type="checkbox" id="options_input">
                    <label for="options_input" id="options_label">
                        <i class="fas fa-ellipsis-h" ></i>
                        <span id="options_menu">
                            <p id="dislike_user" onclick="dislike_user()" title="Dislike user"><i class="<?php print($i_like);?>"></i></p>
                            <p id="block_user" onclick="<?php print($block_on_click);?>"><i class="<?php print($i_block);?>" ></i></p>
                            <p id="report_user" onclick="report_user()" title="Fake account"><i class="fas fa-exclamation"></i></p>
                        </span>
                    </label>
                </div>
            </div>
<?php print ('<a href=\'/profile/view/?login='.$users_data['login'].'\'><div class="people" style="background: 
                url(' . $photo_data . ') no-repeat center; background-size: cover;">
        <span class="name" id="login_from"><i class="fas fa-circle" style="color:'.$users_data['online_status']['status'].'" 
        title="'.$users_data['online_status']['last_online'].'"></i>'
            .$users_data['login'] . '</span>
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
            $status = '<i class="fas fa-check" style="color:#16b713"></i>';
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
<div id="message_block">
<div class="input_message">
    <?php
    if ($users_data['ready_to_chat'] > 0)
        print ("<div id=\"textarea\"><textarea id=\"text\"></textarea></div>
            <div id=\"send_message\" onclick=\"send_message()\"><i class=\"fas fa-arrow-circle-up\"></i></div>");
    else
        print ("<div id=\"textarea\"><textarea id=\"text\" required disabled placeholder='Sorry, you need mutual sympathy to activate chat. And at least one real photo.'></textarea></div>
        <div id=\"send_message\" onclick=\"send_message()\" style='visibility:hidden'><i class=\"fas fa-arrow-circle-up\" id='send_buttom'></i></div>");
    ?>
</div></div>
<script src="../../js/chat.js"></script>