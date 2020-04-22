<div id="main_site_block">
    <h2>Welcome!</h2>
    <form action="/auth/sign_in" method="POST" id="auth_form">
        <input type="text" placeholder="Login" name="login" id="auth_login" required>
        <input type="password" placeholder="Password" name="password" id="auth_password" required>
        <input type="submit" value="Sign in" id="auth_submit">
    </form>
    <div id="auth_second_block">
        <a href="/registration/">Sign Up</a>
        <a href="/registration/pre_restore_password">Restore password</a>
    </div>
</div>
