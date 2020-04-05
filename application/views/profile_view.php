<link rel="stylesheet" type="text/css" href="/css/profile.css">
<link href="https://cdn.jsdelivr.net/npm/suggestions-jquery@20.2.2/dist/css/suggestions.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/suggestions-jquery@20.2.2/dist/js/jquery.suggestions.min.js"></script>
<h3><?php
    $ini = include('./config/config.php');
    $token = $ini['city_parser']['token'];
    isset($_GET['login']) ? $self_profile = 0 : $self_profile = 1;
    ?>
</h3>

<div id="profile_block">
    <div id="add_to_profile_block">
        <?php
        if($self_profile) {
            $add_to_profile = $data['user_data']['profile_filled']['add_to_profile'];
            foreach ($add_to_profile as $add) {
                $icon = $add['icon'];
                $title = "Add " . $add['value'] . " to Fulfil your profile";
                $id = "add_" . str_replace(" ", "_", strtolower($add['value']));
                print("<span id='$id' class='add_to_profile' title='$title'>$icon</span>");
            }
        }
        ?>
    </div>
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
        <?php if($self_profile)
            count($data['user_data']['user_photos']) > 0 ?
            print('<div id="main_photo_icon" onclick="main_photo_change()"><i class="fas fa-star"></i></div>') :
            print('<div id="main_photo_icon" onclick="main_photo_change()" style="visibility: hidden"><i class="fas fa-star"></i></div>');
        else print('<div id="main_photo_icon" onclick="main_photo_change()" style="visibility: hidden"><i class="fas fa-star"></i></div>')?>
        <div id="options" <?php
        if(!isset($_SESSION['login'])) print('style="display:none"'); ?>>
            <input type="checkbox" id="options_input">
            <label for="options_input" id="options_label">
                <i class="fas fa-ellipsis-h"></i>
                <span id="options_menu">
                    <?php
                    if(!$self_profile) {
                        if(!$data['user_data']['profile_block_status']) //User Not Blocked
                            print('<p id="block_user" onclick="block_user()"><i class="fas fa-user-lock"></i></p>');
                        else //User Blocked
                            print('<p id="block_user" onclick="unblock_user()"><i class="fas fa-lock-open"></i></p>');
                    }
                    ?>
                    <?php if($self_profile)
                        if (count($data['user_data']['user_photos']) > 0)
                            print('<p id="remove_photo" onclick="remove_photo()"><i class="fas fa-trash-alt"></i></p>');
                        else
                            print('<p id="remove_photo" onclick="remove_photo()" style="display: none"><i class="fas fa-trash-alt"></i></p>')?>
                    <?php if($self_profile && count($data['user_data']['user_photos']) < 5)
                        print('<p id="add_photo" onclick="add_photo()"><i class="fas fa-camera"></i></p>');
                    else
                        print('<p id="add_photo" onclick="add_photo()" style="display: none"><i class="fas fa-camera"></i></p>')?>
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
            <?php print($data['user_data']['user_fame_rating']['fame_rating_icon']); ?><span class="tooltiptext"><?php
                print($data['user_data']['user_fame_rating']['fame_rating_name']); ?></span></div>
    </div>
    <?php
    if(!$self_profile && isset($_SESSION['login'])) {
        print('<div id="action_block">');
        $chat_id = $data['user_data']['ready_to_chat'];
        $heart = "<i class=\"fas fa-heart\"></i>";
        $check_like = $data['user_data']['check_like'];
        if($check_like) {
            if($data['user_data']['profile_block_status'])
                print("<div id='like_block'>$heart</div>");
            else
                print("<div id='like_block' onclick='like()' class='like_filled'>$heart</div>");
        }
        else {
            if($data['user_data']['profile_block_status'])
                print("<div id='like_block'>$heart</div>");
            else
                print("<div id='like_block' onclick='like()'>$heart</div>");
        }
        $data['user_data']['ready_to_chat'] ?
            print("<a href='/conversation/chat_view/?id=$chat_id'><div id='chat_block' class='chat_available'><i class='fas fa-comment-dots'></i></a></div>") :
            print('<div id="chat_block"><i class="fas fa-comment-dots"></i></div>');
        if(!$self_profile)
            print('</div>');

    }?>
    <div id="real_name_block">
        <h3><i class="fas fa-user-tie"></i> Can Call Me</h3>
        <?php !$data['user_data']['user_real_name'] ? print('<div id="real_name"><i class="fas fa-user-ninja"></i> I\'m Anon') :
                print('<div id="real_name">' . $data['user_data']['user_real_name']);
        $self_profile ? print('<span id="real_name_change" onclick="change_real_name()"><i class="fas fa-pencil-alt"></i></span>') : "" ?>
        </div>
    </div>
    <div id="info_block">
        <h3><i class="fas fa-info-circle"></i> About Me</h3>
        <?php !$data['user_data']['user_info'] ?
            print('<div id="info"><i class="fas fa-user-ninja"></i> I\'m Very Shy') :
            print('<div id="info">' . $data['user_data']['user_info']); ?>
            <?php $self_profile ? print('<span id="info_change" onclick="change_info()"><i class="fas fa-pencil-alt"></i></span>') : "";?>
        </div>
    </div>
    <div id="geo_block">
        <div id="geo">
            <i class="fas fa-location-arrow"></i> <?php print($data['user_data']['user_geo']);?>
            <?php $self_profile ? print('<span id="geo_change" onclick="change_geo()"><i class="fas fa-pencil-alt"></i></span>') : "" ?>
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
                $self_profile ? print("<input type='checkbox' checked class='tags' onclick='remove_tag()' id='$tag_name' value='$tag_name' name='tags[]'>") :
                    print("<input class='tags' id='$tag_name' value='$i'>");
                $self_profile ?
                print("<label class='tags_labels' for='$tag_name' style='color: $tag_color'>$tag_icon $tag_name</label>") :
                    print("<label class='tags_labels_another' for='$tag_name' style='color: $tag_color'>$tag_icon $tag_name</label>");
                $i++;
            }
            $self_profile ? print("<div onclick='load_tags_button()' id='add_new_tag'><i class=\"fas fa-plus-circle\"></i></div>") : "";
            !$data['user_data']['user_tags'] && !$self_profile ? print("<i class=\"fas fa-user-ninja\"></i> My interesting is empty <br> I'm not Interesting") : "";
        }
        else
            $self_profile ? print("<div onclick='load_tags_button()' id='add_new_tag'><i class=\"fas fa-plus-circle\"></i></div>") : "";
        ?>
    </div>
</div>

<script src="../../js/profile.js"></script>
<script type="text/javascript">
    like_status(<?php print($data['user_data']['check_like']); ?>);
    load_user_photos(JSON.parse('<?php print(json_encode($data['user_data']['user_photos']))?>'));
    load_token("<?php print($token);?>");
</script>