<link rel="stylesheet" href="../../css/black_list.css">
<div id="main_site_block">
    <div id="people_block">
        <?php
        if($data['users_data']){
            foreach ($data['users_data'] as $user){
                $user_login = $user['login'];
                $user_photo_src = substr($user['photo_src'], 1);
                !file_exists($user['photo_src']) ? $user_photo_src = "/images/placeholder.png" : "";
                print("<div class='people'>
                        <div class='photo' style='background: url($user_photo_src) no-repeat center;  background-size: cover;'></div>
                        <span class='people_name'>$user_login</span>
                        <span class='unblock_user' onclick='unblock_user.apply(this)'><i class=\"fas fa-trash-alt\"></i></span></div>");
            }
        }
        else
            print("<div id='system_message'><h2>Not Blocked Users</h2></div>");?>
    </div>
</div>
<script type="text/javascript" src="../../js/black_list.js"></script>
