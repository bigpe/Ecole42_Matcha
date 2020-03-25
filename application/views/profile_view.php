<link rel="stylesheet" type="text/css" href="/css/profile.css">
<h3><?php
    $self_profile = 1;
    if(isset($_GET['login'])){
        $self_profile = 0;
        if(isset($_SESSION['profile'])) {
            if ($_SESSION['login'] != $_GET['login'])
                print(strtoupper($_GET['login']) . "'s Profile");
        }
        else {
            print(strtoupper($_GET['login']) . "'s Profile");
        }
    }
    else
        print("It's your profile, another people be look's same");
    ?>
</h3>
<div id="profile_block">
    <div id="photo_block">
        <div class="photo" style="background:
            url('<?php $image = $data['user_data']['main_photo']['photo_src'];
        $image_data = base64_encode(file_get_contents($image));
        print("data: ".mime_content_type($image).";base64,$image_data"); ?>') no-repeat center;
            background-size: cover;" id="<?php print($data['user_data']['main_photo']['photo_token']);?>"></div>
    </div>
    <div id="photo_button">
        <?php count($data['user_data']['user_photos']) > 1 ?
            print('<div id="left_arrow" onclick="photo_backward()"><i class="fas fa-arrow-left"></i></div>') : "";?>
        <?php count($data['user_data']['user_photos']) > 1 ?
            print('<div id="right_arrow" onclick="photo_forward()"><i class="fas fa-arrow-right"></i></div>') : "";?>
        <div id="options" <?php
        if(!isset($_SESSION['login'])) print('style="display:none"'); ?>>
            <input type="checkbox" id="options_input">
            <label for="options_input" id="options_label">
                <i class="fas fa-ellipsis-h"></i>
                <span id="options_menu">
                    <?php if(!$self_profile) print('<p><i class="fas fa-user-lock"></i></p>'); ?>
                    <?php if($self_profile) print('<p><i class="fas fa-trash-alt"></i></p>'); ?>
                </span>
            </label>
        </div>
    </div>
    <div id="main_block">
        <div id="connect_status"><i class="fas fa-circle" style="color:<?php print($data['user_data']['online_status']['status']); ?>"> </i> <span></span></div>
        <div id="name"><?php print($data['user_data']['user_login']); ?></div>
        <div id="sex_preference">
            <?php print($data['user_data']['user_sex_preference']['sex_preference_icon']);?>
            <span class="tooltiptext"><?php print($data['user_data']['user_sex_preference']['sex_preference_name']);?></span>
        </div>
        <div id="fame_rating" style="color: <?php print($data['user_data']['user_fame_rating']['fame_rating_color']);?>">
            <?php print($data['user_data']['user_fame_rating']['fame_rating_icon']); ?><span class="tooltiptext"><?php
                print($data['user_data']['user_fame_rating']['fame_rating_name']); ?></span></div>
    </div>
    <?php
    if(!$self_profile && isset($_SESSION['login'])) {
        print('<div id="action_block">');
        if($data['user_data']['check_like'])
            $heart = "<i class=\"fas fa-heart\"></i>";
        else
            $heart = "<i class=\"far fa-heart\"></i>";
        print("<div id='like_block' onclick='like()'>$heart</div>");
        if($data['user_data']['ready_to_chat'])
            print('<div id="chat_block" onclick="chat()"><i class="fas fa-comment-dots"></i></div>');
        if(!$self_profile)
            print('</div>');
    }?>
    <div id="info_block">
        <h3><i class="fas fa-info-circle"></i> About Me</h3>
        <div id="info"><?php print($data['user_data']['user_info']); ?></div>
    </div>
    <div id="geo_block">
        <div id="geo"><i class="fas fa-location-arrow"></i> <?php print($data['user_data']['user_geo']);?></div>
    </div>
    <div id="tags_block">
        <?php
        if(isset($data['user_data']['user_tags'])) {
            $i = 1;
            foreach ($data['user_data']['user_tags'] as $tag) {
                $tag_name = $tag['tag_name'];
                $tag_icon = $tag['tag_icon'];
                $tag_color = $tag['tag_color'];
                print("<input class='tags' id='$tag_name' value='$i'>");
                print("<label class='tags_labels_another' for='$tag_name' style='color: $tag_color'>$tag_icon $tag_name</label>");
                $i++;
            }
        }
        else
            print("Tags Error!");
        ?>
    </div>
</div>
<script src="../../js/profile.js"></script>
<script type="text/javascript">
    like_status(<?php print($data['user_data']['check_like']); ?>);
    load_user_photos(JSON.parse('<?php print(json_encode($data['user_data']['user_photos']))?>'));
</script>