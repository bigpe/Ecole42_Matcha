<div id="main_site_block">
    <h2>Auth</h2>
    <form action="/auth/sign_in" method="POST" id="auth_form">
        <input type="text" placeholder="Login" name="login" id="auth_login" required>
        <input type="password" placeholder="Password" name="password" id="auth_password" required>
        <input type="submit" value="Sign in" id="auth_submit">
    </form>
    <a href="/registration/">Sign Up</a>
    <a href="/registration/pre_restore_password">Restore password</a>
    <?php
    $params = array(
        'client_id'     => '158115967922-0g334aa81m1bk7e09a3go97oiquo80cs.apps.googleusercontent.com',
        'redirect_uri'  => 'https://matcha.fun/auth/with_google',
        'response_type' => 'code',
        'scope'         => 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile'
    );
    $url = 'https://accounts.google.com/o/oauth2/auth?' . urldecode(http_build_query($params));
    echo '<a href="' . $url . '">Авторизация через Google</a>';
    ?>
</div>
<!--A9pF_X-KAWrnfIZwfJLaf_uE-->