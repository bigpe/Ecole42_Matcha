<div id="main_site_block">
    <h2>Welcome!</h2>
    <form action="/auth/sign_in" method="POST" id="auth_form">
        <input type="text" placeholder="Login" name="login" class="auth_login" required>
        <input type="password" placeholder="Password" name="password" class="auth_password" required>
        <input type="submit" value="Sign in" id="auth_submit">
    </form>
    <div id="auth_second_block">
        <a href="/registration/">Sign Up</a>
        <a href="/registration/pre_restore_password">Restore password</a>
        <?php
        $ini = include('./config/config.php');
        $client = new Google_Client();
        $client->setClientId($ini['google']['clientID']);
        $client->setClientSecret($ini['google']['clientSecret']);
        $client->setRedirectUri($ini['google']['redirectUri']);
        $client->addScope("email");
        $client->addScope("profile");
        echo "<a href='".$client->createAuthUrl()."'><i class=\"fab fa-google\"></i></a>";
        ?>
    </div>
</div>