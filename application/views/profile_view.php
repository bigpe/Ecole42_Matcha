<link rel="stylesheet" type="text/css" href="/css/profile.css">
<h3>It's your profile, another people see the same</h3>
<div id="profile_block">
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
        <div id="connect_status"><i class="fas fa-circle"></i></div>
        <div id="name"><?php print($_SESSION['login']); ?></div>
        <div id="sex_preference">
            <?php print($data['user_data']['user_sex_preference']['sex_preference_icon']);?>
            <span class="tooltiptext"><?php print($data['user_data']['user_sex_preference']['sex_preference_name']);?></span>
        </div>
        <div id="fame_rating"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 32">
                <path fill="#000" fill-opacity=".12" d="M3.2 27.47A15.22 15.22 0 010 18.21C0 9.6 7.17 2.67 16 2.67S32 9.6 32 18.2c0 3.35-1.15 6.57-3.2 9.26a1.33 1.33 0 11-2.13-1.62 12.57 12.57 0 002.66-7.64c0-7.1-5.96-12.88-13.33-12.88S2.67 11.11 2.67 18.21c0 2.75.94 5.4 2.66 7.64a1.33 1.33 0 11-2.12 1.62z"></path>
                <path fill="#FD7F7E" d="M4.9 25.25l.46.64a1.33 1.33 0 11-2.11 1.62c-.2-.25-.38-.5-.55-.76a1.33 1.33 0 112.2-1.5zm12.35-8l.08.13.06.1a1.64 1.64 0 01-.7 2.33l-7.65 3.46c-.41.18-.9.02-1.13-.38a.89.89 0 01.24-1.17l6.81-4.89a1.63 1.63 0 012.29.42z"></path>
            </svg><span class="tooltiptext">Low</span></div>
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

<!--<svg id="low" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 32">-->
<!--    <path fill="#000" fill-opacity=".12" d="M3.2 28.12a15.9 15.9 0 01-3.2-9.5C0 9.81 7.16 2.67 16 2.67S32 9.8 32 18.62a15.9 15.9 0 01-3.2 9.5 1.33 1.33 0 11-2.14-1.59 13.24 13.24 0 002.67-7.9c0-7.35-5.97-13.3-13.33-13.3a13.31 13.31 0 00-13.33 13.3c0 2.85.95 5.6 2.67 7.9a1.33 1.33 0 11-2.14 1.6z"></path>-->
<!--    <path fill="#FD7F7E" d="M6.56 7.32c.52.52.52 1.37 0 1.89a13.22 13.22 0 00-1.22 17.3A1.33 1.33 0 013.2 28.1 15.89 15.89 0 014.68 7.33a1.33 1.33 0 011.88 0zm4.54 4.14l6.16 6.05a1.67 1.67 0 01-.1 2.47l-.09.07a1.67 1.67 0 01-2.45-.33L9.73 12.6a.9.9 0 011.37-1.14z"></path></g></symbol>-->
<!--</svg>-->