<div style="display: grid;
    min-height: calc(100vh - 69px - 100px - 60px);
    align-items: center;
    align-content: center;
    padding-top: 30px;
    padding-bottom: 30px;">
    <h2>Login</h2>
    <form action="/auth/sign_in" method="POST">
        <input type="text" placeholder="Login" name="login"><br>
        <input type="password" placeholder="Password" name="password"><br>
        <input type="submit" value="Sign in">
    </form>
    <a href="/registration/">Sign Up</a>
    <a href="/registration/pre_restore_password">Restore password</a>
    <a href='https://www.facebook.com/v6.0/dialog/oauth?client_id=821000615077304&redirect_uri=https://matcha.fun/Auth/with_FB&state=bda7da561adf35d16563e653e60df8cb'>
        Join with FB
    </a>
</div>
