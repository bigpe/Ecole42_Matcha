<link rel="stylesheet" type="text/css" href="/css/profile.css">
<link rel="stylesheet" type="text/css" href="/css/matcha.css">
<script src="https://api-maps.yandex.ru/2.1?apikey=17d0e874-0be4-43ee-bffe-8e36cebfe040&load=package.full&lang=ru_RU"></script>
<?php $users_count = $data['users_count'];?>
<div id="users_wasted" style="display: <?php $users_count ? print("none") : print("grid"); ?>">
    <div id="system_message">
        <h1>Recommend Users is Wasted</h1>
        <h2>Try Another Time <i class="fas fa-sad-cry"></i></h2>
    </div>
</div>
<div id="matcha_load" style="display: <?php !$users_count ? print("none") : print("grid"); ?>">
    <div id="load_block">
        <h2><i class="fas fa-hourglass-half"></i> Throwing the Dice</h2>
    </div>
    <span id="dice_load"><i class="fas fa-dice-four"></i></span>
</div>
<div id="profile_block" style="display: none">
    <div id="miss_match_block">
        <div id="match" onclick="match_user()"><i class="fas fa-heart"></i></div>
        <div id="miss" onclick="miss_user()"><i class="fas fa-times"></i></div>
    </div>
    <div id="photo_block">
        <div class="photo"></div>
    </div>
    <div id="photo_button">
        <div id="profile_filled">
            <progress id='profile_filled_progress_bar' max="100"></progress>
        </div>
        <div id="left_arrow" onclick="photo_backward()" style="visibility: hidden"><i class="fas fa-arrow-left"></i></div>
        <div id="right_arrow" onclick="photo_forward()" style="visibility: hidden"><i class="fas fa-arrow-right"></i></div>
        <div id="options">
            <input type="checkbox" id="options_input">
            <label for="options_input" id="options_label">
                <i class="fas fa-ellipsis-h"></i>
                <span id="options_menu">
                    <p id="block_user" onclick="block_user()"><i class="fas fa-user-lock"></i></p>
                </span>
            </label>
        </div>
    </div>
    <div id="main_block">
        <div id="connect_status"><i class="fas fa-circle"> </i></div>
        <div id="name">
            <span id="login"></span>
            <span id="age"></span>
        </div>
        <div id="sex_preference"></div>
        <div id="fame_rating"></div>
    </div>
    <div id="real_name_block">
        <h3><i class="fas fa-user-tie"></i> Can Call Me</h3>
        <div id="real_name"></div>
    </div>
    <div id="info_block">
        <h3><i class="fas fa-info-circle"></i> About Me</h3>
        <div id="info"></div>
    </div>
    <div id="geo_block">
        <div id="geo">
            <i class="fas fa-location-arrow"></i>
        </div>
        <details><summary>Show on Map</summary><div id="YMapsID"></div></details>
    </div>
    <div id="tags_block">
    </div>
</div>
<script src="../../js/matcha.js"></script>
<script>
    <?php $users_count ? print("load_matcha();") : ""?>
</script>