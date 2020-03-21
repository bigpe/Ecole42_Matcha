<link rel="stylesheet" href="../../css/find_advanced.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/css/ion.rangeSlider.min.css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/js/ion.rangeSlider.min.js"></script>

<h3>Find Advanced</h3>
<details>
    <div id="filter_block">
        <div id="age_block">
            <h4>Find by Age Range</h4>
            <input type="text" class="js-range-slider">
        </div>
        <div id="fame_block"></div>
        <div id="geo_block">
            <h4>Find by Geo</h4>
            <input id="address" name="address" type="text" value="<?php print($data['user_filters']['geo']);?>"/>
        </div>
        <div id="tags_block"></div>
    </div>
</details>
<div id="people_block">
    <?php
    $ini = include('./config/config.php');
    $token = $ini['city_parser']['token'];
    $users_data = $data['users_data'];
    foreach($users_data as $user_data){
        $main_photo = $user_data['photo_src'];
        $main_photo_data = base64_encode(file_get_contents($main_photo));
        $photo_data = "'data: ". mime_content_type($main_photo) .";base64,$main_photo_data'";
        print('<a href="/profile/view/?login=' . $user_data['login'] . '">
        <div class="people" style="background: url(' . $photo_data . ') no-repeat center; 
                background-size: cover;"><span class="name"><i class="fas fa-circle" style="color: #5fe15f"></i> '
            . $user_data['login'] . '</span></div></a>');
    } ?>
</div>
<link href="https://cdn.jsdelivr.net/npm/suggestions-jquery@20.2.2/dist/css/suggestions.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/suggestions-jquery@20.2.2/dist/js/jquery.suggestions.min.js"></script>
<script type="text/javascript" src="../../js/find_advanced.js"></script>
<script type="text/javascript">
    load_slider(<?php print($data['user_filters']['age_from']);?>, <?php print($data['user_filters']['age_to']);?>);
    load_city_input("<?php print($token);?>");
    //get_location("<?php //print($token);?>//");
</script>