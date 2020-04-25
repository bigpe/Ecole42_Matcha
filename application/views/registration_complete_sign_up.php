<div id="main_site_block">
    <form method="POST" action="/registration/sign_up" id="auth_form">
        <input readonly hidden value="<?php print($_GET['token']); ?>" name="token">
        <input type="text" value="<?php print($data['email']); ?>" readonly name="email" id="auth_login">
        <input type="text" placeholder="Login" name="login" id="auth_login">
        <input type="password" placeholder="Password" name="password" id="auth_password">
        <input type="password" placeholder="Confirm Password" name="password_confirm" id="auth_password">
        <input type="submit" value="Sign up" id="auth_submit">
    </form>
</div>