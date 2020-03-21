<link rel="stylesheet" type="text/css" href="/css/first_login.css">
<h2>Hello <?php print($data['login']) ?>, Welcome to Matcha!</h2>
<p>This site help you to find your crush!</p>
<p>At first let we know a little more about yourself</p>
<p>Fulfil your profile to access to "Find mode"</p>
<h2>Mandatory part</h2>
<form action="/first_login/end_tutorial" method="POST" id="mandatory_form">
    <h3 id="sex_block_pin">What's your sex?</h3>
    <div id="sex_block">
        <input class="sex" name="sex" type="radio" id="male" value="1">
        <label class="sex_label" for="male"><i class="fas fa-male"></i></label>
        <input class="sex" name="sex" type="radio" id="female" value="2">
        <label class="sex_label" for="female"><i class="fas fa-female"></i></label>
    </div>
    <h3 id="sex_preference_block_pin">Who do you like?</h3>
    <div id="sex_preference_block">
        <input class="sex_preference" name="sex_preference" type="radio" id="preference_male" value="1">
        <label class="sex_preference_label" for="preference_male"><i class="fas fa-male"></i></label>
        <input class="sex_preference" name="sex_preference" type="radio" id="preference_female" value="2">
        <label class="sex_preference_label" for="preference_female"><i class="fas fa-female"></i></label>
        <input class="sex_preference" name="sex_preference" type="radio" id="preference_bisexual" value="0">
        <label class="sex_preference_label" for="preference_bisexual"><i class="fas fa-male"></i> + <i class="fas fa-female"></i></label>
    </div>
    <h3>Few words about you</h3>
    <div id="info_block">
        <textarea name="info" placeholder="What do you like to do?" autofocus maxlength="250" required></textarea>
    </div>
    <h3 id="tags_pin">Like this things?</h3>
    <div id="tags"><?php
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
    <div id="load_tags_block"><input type="button" value="Load more #Tags" id="load_tags_button"></div>
    <div id="user_photo_block">
        <h3 id="user_photo_block_pin">What about your photo?</h3>
        <p>You can upload until to five photos</p>
        <div id="user_photo_tips">
            <p>Choose your main photo</p>
            <p>Delete photo - double click!</p>
        </div>
        <div id="user_photo_button_block">
            <input id="user_photo_input" type="file" accept="image/jpeg" multiple>
            <label for="user_photo_input"><button id="user_photo_button" type="button"><i class="fas fa-plus"></i></button></label>
        </div>
    </div>
    <br>
    <div id="submit_block"><input type="submit" id="mandatory_form_submit"></div>
</form>
<script type="text/javascript" src="/js/first_login.js"></script>
