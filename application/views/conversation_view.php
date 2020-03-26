<link rel="stylesheet" href="../../css/conversation.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/css/ion.rangeSlider.min.css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/js/ion.rangeSlider.min.js"></script>

<div id="people_block">
    <?php
    $users_data = $data['users_data'];
    foreach($users_data as $user_data){
        $main_photo = $user_data['main_photo']['photo_src'];
        $main_photo_data = base64_encode(file_get_contents($main_photo));
        $photo_data = "'data: ". mime_content_type($main_photo) .";base64,$main_photo_data'";
        print('<div class="people" style="background: url(' . $photo_data . ') no-repeat center; background-size: cover;"><a href="/profile/view/?login=' . $user_data['login'] . '">
            <span class="name"><i class="fas fa-circle" style="color:'.$user_data['online_status']['status'].'" title="'.$user_data['online_status']['last_online'].'"> </i> '
            . $user_data['login'] . '</span></a></div>
        <div class="message"><a href="/conversation/chat_view/?id='.$user_data['chat_id'].'"><span >'.$user_data['last_message'][0]['author'].': '.$user_data['last_message'][0]['text_message'].'</span></a></div>');
    } ?>
</div>
<link href="https://cdn.jsdelivr.net/npm/suggestions-jquery@20.2.2/dist/css/suggestions.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/suggestions-jquery@20.2.2/dist/js/jquery.suggestions.min.js"></script>
