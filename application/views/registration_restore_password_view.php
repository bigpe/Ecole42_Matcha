<div id="main_site_block">
    <h2>Restore Password</h2>
    <form action="/registration/restore_password_change" method="POST" id="auth_form">
        <input hidden readonly type="text" name="token" value="<?php print($_GET['token']); ?>">
        <input class="auth_password" type="password" placeholder="New Password" name="password">
        <input class="auth_password" type="password" placeholder="Password Confirm" name="password_confirm">
        <input id="auth_submit" type="submit" value="Change Password">
    </form>
</div>