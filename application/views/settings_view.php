<div id="main_site_block">
    <div class="change_pass" id="change_pass">
        <h3>Change password</h3>
        <form method="POST" action="/settings/change_password" id="auth_form">
            <input type="password" name="old_pass" placeholder="Old password" required class="auth_login">
            <input type="password" name="new_pass" placeholder="New password" required class="auth_login">
            <input type="password" name="new_pass_conf" placeholder="Confirm new password" required class="auth_login">
            <input type="submit" value="Change" id="auth_submit">
        </form>
    </div>
    <div class="change_email" id="change_email ">
        <h3>Change email</h3>
        <form method="POST" action="/settings/change_email" id="auth_form1">
            <input type="email" name="new_email" placeholder="New email" required class="auth_login">
            <input type="submit" value="Change" id="auth_submit">
        </form>
    </div>
    <div id="auth_second_block">
        <a href="/settings/black_list">View Black List</a>
    </div>
</div>