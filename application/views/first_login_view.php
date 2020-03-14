<link rel="stylesheet" type="text/css" href="/css/first_login.css">
<h2>Hello, Welcome to Matcha!</h2>
<p>This site help you to find your crush!</p>
<p>At first let we know a little more about yourself</p>
<p>Fulfil your profile to access to "Find mode"</p>
<h2>Mandatory part</h2>
<form action="/first_login" method="POST">
    <h3>What's your sex?</h3>
    <input class="sex" name="sex" type="radio" id="male" value="1">
    <label class="sex_label" for="male"><i class="fas fa-male"></i></label>
    <input class="sex" name="sex" type="radio" id="female" value="2">
    <label class="sex_label" for="female"><i class="fas fa-female"></i></label>
    <h3>Who do you like?</h3>
    <input class="sex_preference" name="sex_preference" type="radio" id="preference_male" value="1">
    <label class="sex_preference_label" for="preference_male"><i class="fas fa-male"></i></label>
    <input class="sex_preference" name="sex_preference" type="radio" id="preference_female" value="2">
    <label class="sex_preference_label" for="preference_female"><i class="fas fa-female"></i></label>
    <input class="sex_preference" name="sex_preference" type="radio" id="preference_bisexual" value="0">
    <label class="sex_preference_label" for="preference_bisexual"><i class="fas fa-male"></i> + <i class="fas fa-female"></i></label>
    <h3>Few word about you</h3>
    <input type="text" placeholder="What do you like to do?" name="info" style="height: 100px; width: 300px;">
    <h3>What's your favorite?</h3>
    <div id="tags"></div>
    <h3>What about your photo?</h3>
    <p>You can upload until to five photos, first photo will be your user picture</p>
    <div id="user_photo">
        <input id="user_photo_input" type="file" accept="image/jpeg" multiple>
        <label for="user_photo_input"><input id="user_photo_button" type="button" value="Upload"></label>
    </div>
    <br><input type="submit">
</form>
<script type="text/javascript" src="/js/first_login.js"></script>
