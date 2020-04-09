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
</div>
<div id="fb-root"></div>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/ru_RU/sdk.js#xfbml=1&autoLogAppEvents=1&version=v6.0&appId=821000615077304"></script>
<div class="fb-login-button" data-width="" data-size="large" data-button-type="continue_with" data-layout="default" data-auto-logout-link="false" data-use-continue-as="false"></div>
<script>
    window.fbAsyncInit = function() {
        FB.init({
            appId      : '{821000615077304}',
            cookie     : true,
            xfbml      : true,
            version    : 'v6.0'
        });

        FB.AppEvents.logPageView();

    };

    (function(d, s, id){
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement(s); js.id = id;
        js.src = "https://connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>