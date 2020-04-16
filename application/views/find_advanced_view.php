<link rel="stylesheet" href="../../css/find_advanced.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/css/ion.rangeSlider.min.css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/js/ion.rangeSlider.min.js"></script>

<details id="filter">
    <summary><i class="fas fa-sliders-h"></i></summary>
    <div id="filter_block">
        <div id="age_block">
            <div id="age_sort_block">
                <input class="age_sort" type="radio" name="age_sort" id="desc_sort_age" value="-2"
                    <?php $data['user_filters']['age_sort'] == -2 ? print("checked") : ""; ?> onclick="change_age_sort()">
                <label class="age_sort_label" for="desc_sort_age"><i class="fas fa-sort-amount-up-alt"></i></label>
                <input class="age_sort" type="radio" name="age_sort" id="asc_sort_age" value="-1"
                    <?php $data['user_filters']['age_sort'] == -1 ? print("checked") : ""; ?> onclick="change_age_sort()">
                <label class="age_sort_label" for="asc_sort_age"><i class="fas fa-sort-amount-down-alt"></i></label>
            </div>
            <input type="text" class="js-range-slider" id="age_sort_slider">
        </div>
        <div id="sex_preference_block">
            <input class="sex_preference" name="sex_preference" type="radio" id="preference_male"
                   <?php $data['user_filters']['sex_preference'] ? print("checked") : ""; ?> value="1" onclick="change_sex_preference()">
            <label class="sex_preference_label" for="preference_male"><i class="fas fa-male"></i></label>
            <input class="sex_preference" name="sex_preference" type="radio" id="preference_female"
                   <?php $data['user_filters']['sex_preference'] == 2 ? print("checked") : ""; ?> value="2" onclick="change_sex_preference()">
            <label class="sex_preference_label" for="preference_female"><i class="fas fa-female"></i></label>
            <input class="sex_preference" name="sex_preference" type="radio" id="preference_bisexual"
                   <?php !$data['user_filters']['sex_preference'] ? print("checked") : ""; ?> value="0" onclick="change_sex_preference()">
            <label class="sex_preference_label" for="preference_bisexual"><i class="fas fa-male"></i> + <i class="fas fa-female"></i></label>
        </div>
        <div id="fame_block">
            <input class="fame_rating" type="radio" name="fame_rating" id="desc_fame_rating" value="-2"
                <?php $data['user_filters']['fame_rating'] == -2 ? print("checked") : ""; ?> onclick="change_fame()">
            <label class="fame_rating_label" for="desc_fame_rating"><i class="fas fa-sort-amount-up-alt"></i></label>
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
            <input class="fame_rating" type="radio" name="fame_rating" id="asc_fame_rating" value="-1"
                <?php $data['user_filters']['fame_rating'] == -1 ? print("checked") : ""; ?> onclick="change_fame()">
            <label class="fame_rating_label" for="asc_fame_rating"><i class="fas fa-sort-amount-down-alt"></i></label>
        </div>
        <div id="geo_block">
            <span id="search_icon"><i class="fas fa-search-location"></i></span>
            <input id="address" name="address" type="text" value="<?php print($data['user_filters']['geo']);?>"/>
            <div id="geo_sort_block">
                <input class="geo_sort" type="radio" name="geo_sort" id="desc_geo_sort" value="-2"
                    <?php $data['user_filters']['age_sort'] == -2 ? print("checked") : ""; ?> onclick="change_geo_sort()">
                <label class="geo_sort_label" for="desc_geo_sort"><i class="fas fa-sort-amount-up-alt"></i></label>
                <input class="geo_sort" type="radio" name="geo_sort" id="asc_geo_sort" value="-1"
                    <?php $data['user_filters']['age_sort'] == -1 ? print("checked") : ""; ?> onclick="change_geo_sort()">
                <label class="geo_sort_label" for="asc_geo_sort"><i class="fas fa-sort-amount-down-alt"></i></label>
            </div>
            <input type="text" class="js-range-slider" id="geo_sort_slider">
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
<div id="main_site_block">
    <div id='system_message' style="display:
    <?php count($data['users_data']) ? print("none") : print("grid"); ?>">
        <h2>Not Users For This Criteria</h2>
    </div>
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
            $distance = $user_data['distance'];
            $distance ? $distance_string = round($distance / 1000) . "km" : $distance_string = "Around you";
            print('<a href="/profile/view/?login=' . $user_data['login'] . '">
            <div class="people" style="background: url(' . $photo_data . ') no-repeat center; background-size: cover;">
                    <span class="name"><i class="fas fa-circle" style="color:'.$user_data['online_status']['status'].'" 
                    title="'.$user_data['online_status']['last_online'].'"> 
                    </i> ' . $user_data['login'] . '</span>');
            print("<span class='distance'><i class=\"fas fa-map-marker-alt\"></i> $distance_string</span>");
            print("</div></a>");
        } ?>
    </div>
</div>
<link href="https://cdn.jsdelivr.net/npm/suggestions-jquery@20.2.2/dist/css/suggestions.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/suggestions-jquery@20.2.2/dist/js/jquery.suggestions.min.js"></script>
<script type="text/javascript" src="../../js/find_advanced.js"></script>
<script type="text/javascript">
    load_slider("age_sort_slider", "18", "50", <?php print($data['user_filters']['age_from']);?>,
        <?php print($data['user_filters']['age_to']);?>, "+");
    load_slider("geo_sort_slider", "1", "50", "1", "50", "km+");
    load_city_input("<?php print($token);?>");
</script>