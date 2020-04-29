<link rel="stylesheet" type="text/css" href="/css/first_login.css">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    $( function() {
        $(function () {
            $("#datepicker").datepicker({
                changeMonth: true,
                changeYear: true,
                yearRange: '-100:+0',
                maxDate: new Date(2002, 1 - 1, 1),
                dateFormat: "yy.mm.dd"
            });
        });
    } );
</script>
<div id="first_login_block">
    <div id="system_message">
        <h2><i class="fas fa-info-circle"></i> Hello <?php print($_SESSION['login']) ?>, Welcome to Matcha!</h2>
        <p>This site help you to find your crush!</p>
        <p>At first let we know a little more about yourself</p>
        <p>Fulfil your profile to access to "Find mode"</p>
    </div>
    <form action="/first_login/end_tutorial" method="POST" id="mandatory_form">
        <div id="welcome_block">
            <h3 id="sex_block_pin">What's your sex?</h3>
        </div>
        <div id="sex_block">
            <input class="sex" name="sex" type="radio" id="male" value="1">
            <label class="sex_label" for="male"><i class="fas fa-male"></i></label>
            <input class="sex" name="sex" type="radio" id="female" value="2">
            <label class="sex_label" for="female"><i class="fas fa-female"></i></label>
        </div>
        <div id="welcome_block">
            <h3 id="sex_preference_block_pin">Who do you like?</h3>
        </div>
        <div id="sex_preference_block">
            <input class="sex_preference" name="sex_preference" type="radio" id="preference_male" value="1">
            <label class="sex_preference_label" for="preference_male"><i class="fas fa-male"></i></label>
            <input class="sex_preference" name="sex_preference" type="radio" id="preference_female" value="2">
            <label class="sex_preference_label" for="preference_female"><i class="fas fa-female"></i></label>
            <input class="sex_preference" name="sex_preference" type="radio" id="preference_bisexual" value="0">
            <label class="sex_preference_label" for="preference_bisexual"><i class="fas fa-male"></i> + <i class="fas fa-female"></i></label>
        </div>
        <div id="welcome_block">
            <h3>What's about your age?</h3>
        </div>
        <div id="age_block">
            <i class="fas fa-user-clock"></i>
            <input type="text" id="datepicker" name="user_age" required>
        </div>
        <div id="welcome_block">
            <h3>Few words about you</h3>
        </div>
        <div id="info_block">
            <textarea name="info" placeholder="What do you like to do?" autofocus maxlength="250" required></textarea>
        </div>
        <div id="welcome_block">
            <h3 id="tags_pin">Like this things?</h3>
        </div>
        <div id="tags"><?php
            $ini = include('./config/config.php');
            $token = $ini['city_parser']['token'];
            if(isset($data['tags'])) {
                foreach ($data['tags'] as $tag) {
                    $tag_name = $tag['tag_name'];
                    $tag_icon = $tag['tag_icon'];
                    $tag_color = $tag['tag_color'];
                    print("<input type='checkbox' class='tags' name='tags[]' id='$tag_name' value='$tag_name'>");
                    print("<label class='tags_labels' for='$tag_name' style='color: $tag_color'>$tag_icon $tag_name</label>");
                }
            }
            else
                print("Tags Error!");
            ?>
        </div>
        <div id="load_tags_block"><input type="button" value="Load more #Tags" id="load_tags_button" class="auth_submit"></div>
        <div id="user_photo_block">
            <div id="welcome_block">
                <h3 id="user_photo_block_pin">What about your photo?</h3>
                <p>You can upload until to five photos</p>
            </div>
            <div id="user_photo_tips">
                <p>Choose your main photo</p>
                <p>Delete photo - double click!</p>
            </div>
            <div id="user_photo_button_block">
                <input id="user_photo_input" type="file" accept="image/jpeg" multiple>
                <label for="user_photo_input"><button id="user_photo_button" type="button"><i class="fas fa-plus"></i></button></label>
            </div>
        </div>
        <div id="system_message">
            <h3><i class="fas fa-info-circle"></i> You can make fil additional entry's</h3>
            <p><i class="fas fa-search"></i> Easier to find you</p>
            <p><i class="fas fa-tachometer-alt"></i> Higher search priority</p>
        </div>
        <details>
            <summary><i class="fas fa-info-circle"></i> The more fields are filled, the more fame-rating!</summary>
            <div id="welcome_block">
                <h3>It's your city?</h3>
            </div>
            <div id="geo_block">
                <i class="fas fa-search-location"></i>
                <input id="address" name="user_geo" type="text" required>
                <span id="geo_detect_button"><i class="far fa-compass"></i></span>
                <input id="geo_longitude" type="text" name="user_geo_longitude" required hidden>
                <input id="geo_latitude" type="text" name="user_geo_latitude" required hidden>
            </div>
            <div id="welcome_block">
                <h3>Enter your full name</h3>
            </div>
            <div id="full_name_block">
                <i class="fas fa-user-tie"></i>
                <input id="full_name" type="text" name="user_full_name">
            </div>
        </details>
        <div id="submit_block">
            <input type="submit" id="mandatory_form_submit" value="Send" class="auth_submit">
        </div>
    </form>
</div>
<link href="https://cdn.jsdelivr.net/npm/suggestions-jquery@20.2.2/dist/css/suggestions.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/suggestions-jquery@20.2.2/dist/js/jquery.suggestions.min.js"></script>
<script type="text/javascript" src="../../js/first_login.js"></script>
<script type="text/javascript">
    get_location("<?php print($token); ?>")
</script>
<script>
    load_city_input("<?php print($token);?>");
</script>