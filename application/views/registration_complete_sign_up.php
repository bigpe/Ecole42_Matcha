<form method="POST" action="/registration/sign_up">
    <input readonly hidden value="<?php print($_GET['token']); ?>" name="token">
    <input type="text" value="<?php print($data['email']); ?>" readonly name="email">
    <input type="text" placeholder="Login" name="login">
    <input type="password" placeholder="Password" name="password">
    <input type="password" placeholder="Confirm Password" name="password_confirm">
    <input type="submit" value="Sign up">
</form>
