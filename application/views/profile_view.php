<link rel="stylesheet" type="text/css" href="/css/profile.css">
<script src="/js/profile.js"></script>
<h3><?php
    if(isset($_GET['login'])) {
        if ($_GET['login'] != $_SESSION['login'])
            print(strtoupper($_GET['login']) . "'s Profile");
        else
            print("It's your profile, another people see the same");
    }
    else
        print("It's your profile, another people see the same"); ?>
</h3>
<div id="profile_block">
    <div id="like_block" onclick="like()">
        <img src="https://img.icons8.com/cotton/2x/like--v1.png" >
    </div>
    <div id="chat_block" onclick="chat()">
        <img src="https://img.icons8.com/cotton/2x/chat.png" >
    </div>

    <div id="photo_block">
        <div id="photo" style="background:
            url('<?php $image = $data['user_data']['main_photo_src'];
        $image_data = base64_encode(file_get_contents($image));
        print("data: ".mime_content_type($image).";base64,$image_data"); ?>') no-repeat center;
            background-size: cover;"></div>
    </div>
    <div id="photo_button">
        <div id="left_arrow"><i class="fas fa-arrow-left"></i></div>
        <div id="right_arrow"><i class="fas fa-arrow-right"></i></div>
    </div>
    <div id="main_block">
        <div id="connect_status"><i class="fas fa-circle"></i> <span>Online</span></div>
        <div id="name"><?php print($data['user_data']['user_login']); ?></div>
        <div id="sex_preference">
            <?php print($data['user_data']['user_sex_preference']['sex_preference_icon']);?>
            <span class="tooltiptext"><?php print($data['user_data']['user_sex_preference']['sex_preference_name']);?></span>
        </div>
        <div id="fame_rating"><i class="fas fa-battery-quarter"></i><span class="tooltiptext">Low</span></div>
    </div>
    <div id="info_block">
        <h3>About me</h3>
        <div id="info" contentEditable="true"><?php print($data['user_data']['user_info']); ?></div>
    </div>

    <div id="tags_block">
        <?php
        if(isset($data['user_data']['user_tags'])) {
            $i = 1;
            foreach ($data['user_data']['user_tags'] as $tag) {
                $tag_name = $tag['tag_name'];
                $tag_icon = $tag['tag_icon'];
                $tag_color = $tag['tag_color'];
                print("<input type='checkbox' class='tags' id='$tag_name' value='$i' checked>");
                print("<label class='tags_labels' for='$tag_name' style='color: $tag_color'>$tag_icon $tag_name</label>");
                $i++;
            }
        }
        else
            print("Tags Error!");
        ?>
    </div>
</div>