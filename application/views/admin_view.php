<link rel="stylesheet" href="../../css/black_list.css">
<div id="main_site_block">
    <div id="system_message">
        <h2>Users Reported: <span id="users_reported_count"><?php print($data['users_reported_count']); ?></span></h2>
    </div>
    <div id="reported_people_block">
        <?php
        foreach($data['users_reported'] as $user){
            $login = $user['login'];
            print("<div class='people'>
                    <h3 class='people_name'>
                        <a href='/profile/view/?login=$login'>$login</a>
                    </h3>
                            <span class='remove' onclick='delete_user.apply(this)' title='Remove User'><i class=\"fas fa-trash-alt\"></i></span>
                            <span class='remove' onclick='delete_report.apply(this)' title='Remove Report Request'><i class=\"fas fa-times\"></i></span>
                    </div>");
        }
        ?>
    </div>
    <div id="system_message">
        <h2>Users: <span id="users_count"><?php print($data['users_count']); ?></span></h2>
    </div>
    <div id="people_block">
        <?php
            foreach($data['users'] as $user){
                $login = $user['login'];
                print("<div class='people'>
                    <h3 class='people_name'>
                        <a href='/profile/view/?login=$login'>$login</a>
                    </h3>
                            <span class='remove' onclick='delete_user.apply(this)' title='Remove User'><i class=\"fas fa-trash-alt\"></i></span>
                            <span class='hijack' onclick='hijack_user.apply(this)' title='Sign in Into'><i class=\"fas fa-sign-in-alt\"></i></span>
                    </div>");
            }
        ?>
        </form>
    </div>
    <div id="people_block">
        <?php
            if($data['users_count'] > count($data['users']))
                print('<span class="auth_submit" onclick="get_all_users.apply(this)">Load All Users</span>');
        ?>
        <span class="auth_submit" onclick="create_users.apply(this)">Create +20 Random Users</span>
    </div>
</div>
<script type="text/javascript" src="../../js/black_list.js"></script>