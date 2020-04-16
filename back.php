<link rel="stylesheet" type="text/css" href="/css/profile.css">
<div id="profile_block">
    <div id="photo_block">
        <div class="photo" style="background:
            url('<?php $image = $data['user_data']['main_photo']['photo_src'];
        !file_exists($image) ? $image = "./images/placeholder.png" : "";
        $image_data = base64_encode(file_get_contents($image));
        print("data: ".mime_content_type($image).";base64,$image_data"); ?>') no-repeat center;
            background-size: cover;" id="<?php print($data['user_data']['main_photo']['photo_token']);?>"></div>
    </div>
    <div id="photo_button">
        <div id="profile_filled">
            <progress id='profile_filled_progress_bar' value="<?php print($data['user_data']['profile_filled']['value']);?>" max="100"></progress>
        </div>
        <?php count($data['user_data']['user_photos']) > 1 ?
            print('<div id="left_arrow" onclick="photo_backward()"><i class="fas fa-arrow-left"></i></div>') :
            print('<div id="left_arrow" onclick="photo_backward()" style="visibility: hidden"><i class="fas fa-arrow-left"></i></div>');?>
        <?php count($data['user_data']['user_photos']) > 1 ?
            print('<div id="right_arrow" onclick="photo_forward()"><i class="fas fa-arrow-right"></i></div>') :
            print('<div id="right_arrow" onclick="photo_forward()" style="visibility: hidden"><i class="fas fa-arrow-right"></i></div>');?>
        <div id="options" <?php
        if(!isset($_SESSION['login'])) print('style="display:none"'); ?>>
            <input type="checkbox" id="options_input">
            <label for="options_input" id="options_label">
                <i class="fas fa-ellipsis-h"></i>
                <span id="options_menu">
                    <?php print('<p id="block_user" onclick="block_user()"><i class="fas fa-user-lock"></i></p>'); ?>
                </span>
            </label>
        </div>
    </div>
    <div id="main_block">
        <div id="connect_status"><i class="fas fa-circle" style="color:<?php print($data['user_data']['online_status']['status'] . '" title="'. $data['user_data']['online_status']['last_online']); ?>"> </i> <span></span></div>
        <div id="name"><?php print($data['user_data']['user_login']); ?></div>
        <div id="sex_preference" style="color:
        <?php print($data['user_data']['user_sex_preference']['sex_preference_color'])?>">
            <?php print($data['user_data']['user_sex_preference']['sex_preference_icon']);?>
            <span class="tooltiptext">
                <?php print($data['user_data']['user_sex_preference']['sex_preference_name']);?></span>
        </div>
        <div id="fame_rating" style="color: <?php print($data['user_data']['user_fame_rating']['fame_rating_color']);?>">
            <?php print($data['user_data']['user_fame_rating']['fame_rating_icon']); ?><span class="tooltiptext">
                        <?php print($data['user_data']['user_fame_rating']['fame_rating_name']); ?></span>
        </div>
    </div>
    <div id="real_name_block">
        <h3><i class="fas fa-user-tie"></i> Can Call Me</h3>
        <?php !$data['user_data']['user_real_name'] ? print('<div id="real_name"><i class="fas fa-user-ninja"></i> I\'m Anon') :
            print('<div id="real_name">' . $data['user_data']['user_real_name']); print("</div>");?>
    </div>
    <div id="info_block">
        <h3><i class="fas fa-info-circle"></i> About Me</h3>
        <?php !$data['user_data']['user_info'] ?
            print('<div id="info"><i class="fas fa-user-ninja"></i> I\'m Very Shy') :
            print('<div id="info">' . $data['user_data']['user_info']); print("</div>");?>
    </div>
    <div id="geo_block">
        <div id="geo">
            <i class="fas fa-location-arrow"></i> <?php print($data['user_data']['user_geo']);?>
        </div>
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
            !$data['user_data']['user_tags'] ? print("<i class=\"fas fa-user-ninja\"></i> My interesting is empty <br> I'm not Interesting") : "";
        }
        ?>
    </div>
</div>
<script src="../../js/profile.js"></script>
<script type="text/javascript">
    load_user_photos(JSON.parse('<?php print(json_encode($data['user_data']['user_photos']))?>'));
    load_token("<?php print($token);?>");
</script>
