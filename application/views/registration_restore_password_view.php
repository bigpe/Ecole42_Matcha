<h2>Restore Password</h2>
<form action="/registration/restore_password_change" method="POST">
    <input hidden readonly type="text" name="token" value="<?php print($_GET['token']); ?>">
    <input type="password" placeholder="New Password" name="password">
    <input type="password" placeholder="Password Confirm" name="password_confirm">
    <input type="submit" value="Change Password">
</form>