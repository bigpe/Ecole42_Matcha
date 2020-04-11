<link rel="stylesheet" href="../../css/settings.css">
<div class="settings">

    <div class="change_pass" id="change_pass">
        <h3>Change password</h3>
        <form method="POST" action="/settings/change_password">
            <input type="password" name="old_pass" placeholder="Input your old password" required><br>
            <input type="password" name="new_pass" placeholder="Input your new password" required><br>
            <input type="password" name="new_pass_conf" placeholder="Input your new password again" required><br>
            <input type="submit" value="Change">
        </form>
    </div>
    <div class="change_email" id="change_email ">
        <h3>Change email</h3>
        <form method="POST" action="/settings/change_email" ><br>
            <input type="email" name="new_email" placeholder="Input your new email" required><br>
            <input type="submit" value="Change"><br>
        </form>
    </div>
    <div id="black_list"><a href="/settings/black_list">View Black List</a></div>
</div>