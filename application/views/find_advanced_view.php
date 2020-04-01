<link rel="stylesheet" href="../../css/find_advanced.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/css/ion.rangeSlider.min.css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/js/ion.rangeSlider.min.js"></script>

<details id="filter">
    <summary><i class="fas fa-sliders-h"></i></summary>
    <div id="filter_block">
        <div id="age_block">
            <input type="text" class="js-range-slider">
        </div>
        <div id="fame_block">
            <input class="fame_rating" type="radio" name="fame_rating" id="any_fame_rating" value="0"
                <?php !$data['user_filters']['fame_rating'] ? print("checked") : ""; ?> onclick="change_fame()">
            <label class="fame_rating_label" for="any_fame_rating"><i class="fas fa-infinity"></i></label>
            <input class="fame_rating" type="radio" name="fame_rating" id="low_fame_rating" value="2" onclick="change_fame()"
                <?php $data['user_filters']['fame_rating'] == 2 ? print("checked") : ""; ?>>
            <label class="fame_rating_label" for="low_fame_rating"><i class="fas fa-battery-quarter"></i></label>
            <input class="fame_rating" type="radio" name="fame_rating" id="medium_fame_rating" value="3" onclick="change_fame()"
                <?php $data['user_filters']['fame_rating'] == 3 ? print("checked") : ""; ?>>
            <label class="fame_rating_label" for="medium_fame_rating"><i class="fas fa-battery-half"></i></label>
            <input class="fame_rating" type="radio" name="fame_rating" id="high_fame_rating" value="4" onclick="change_fame()"
                <?php $data['user_filters']['fame_rating'] == 4 ? print("checked") : ""; ?>>
            <label class="fame_rating_label" for="high_fame_rating"><i class="fas fa-battery-three-quarters"></i></label>
            <input class="fame_rating" type="radio" name="fame_rating" id="famous_fame_rating" value="5" onclick="change_fame()"
                <?php $data['user_filters']['fame_rating'] == 5 ? print("checked") : ""; ?>>
            <label class="fame_rating_label" for="famous_fame_rating"><i class="fas fa-battery-full"></i></label>
        </div>
        <div id="geo_block">
            <i class="fas fa-search-location"></i>
            <input id="address" name="address" type="text" value="<?php print($data['user_filters']['geo']);?>"/>
        </div>
        <div id="tags_block">
            <i class="fas fa-hashtag"></i>
            <?php
            if(isset($data['user_tags'])) {
            foreach ($data['user_tags'] as $tag) {
                $tag_name = $tag['tag_name'];
                $tag_icon = $tag['tag_icon'];
                $tag_color = $tag['tag_color'];
                print("<label class='tags_labels' onclick='remove_tag.apply(this)' id='$tag_name' style='color: $tag_color'>$tag_icon</label>");
            } }?>
            <div id="tags_add">
                <input disabled class="input_anim" id="tags_input">
                <span id="tags_input_button"><i class="fas fa-plus-circle"></i></span>
                <span id="tags_suggestion_block"></span>
            </div>
        </div>
    </div>
</details>
<div id="people_block">
    <?php
    $ini = include('./config/config.php');
    $token = $ini['city_parser']['token'];
    $users_data = $data['users_data'];
    foreach($users_data as $user_data){
        $main_photo = $user_data['photo_src'];
        !file_exists($main_photo) ? $main_photo = "./images/placeholder.png" : "";
        $main_photo_data = base64_encode(file_get_contents($main_photo));
        $photo_data = "'data: ". mime_content_type($main_photo) .";base64,$main_photo_data'";
        print('<a href="/profile/view/?login=' . $user_data['login'] . '">
        <div class="people" style="background: url(' . $photo_data . ') no-repeat center; 
                background-size: cover;">
                <span class="name"><i class="fas fa-circle" style="color:'.$user_data['online_status']['status'].'" title="'.$user_data['online_status']['last_online'].'"> </i> '
            . $user_data['login'] . '</span></div></a>');
    } ?>
</div>
<link href="https://cdn.jsdelivr.net/npm/suggestions-jquery@20.2.2/dist/css/suggestions.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/suggestions-jquery@20.2.2/dist/js/jquery.suggestions.min.js"></script>
<script type="text/javascript" src="../../js/find_advanced.js"></script>
<script type="text/javascript">
    load_slider(<?php print($data['user_filters']['age_from']);?>, <?php print($data['user_filters']['age_to']);?>);
    load_city_input("<?php print($token);?>");
</script>