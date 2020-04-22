<link rel="stylesheet" href="../../css/history.css">
<h2 class='date_block'>
    <?php if($data['users_data']){
        $date_one = date("j", strtotime($data['users_data'][0]['update_date']));
        $calendar = "<i class=\"far fa-calendar-alt\"></i>";
        print("$calendar $date_one");
    }
    ?>
</h2>
<div id="main_site_block">
    <?php
    $users_data = $data['users_data'];
    if($users_data){
        $calendar = "<i class=\"far fa-calendar-alt\"></i>";
        $date_one = date("j", strtotime($users_data[0]['update_date']));
        $time_icon = "<i class=\"fas fa-clock\"></i>";
        print("<div id=\"people_block\">");
        foreach($users_data as $user_data) {
            $main_photo = $user_data['photo_src'];
            !file_exists($main_photo) ? $main_photo = "./images/placeholder.png" : "";
            $date_time = date("G:i", strtotime($user_data['update_date']));
            $date_one = date("j", strtotime($user_data['update_date']));
            $date_two = date("j", strtotime(next($users_data)['update_date']));
            $main_photo_data = base64_encode(file_get_contents($main_photo));
            $action_count = $user_data['action_count'];
            $action_icon = $user_data['action_icon'];
            $photo_data = "'data: " . mime_content_type($main_photo) . ";base64,$main_photo_data'";
            print("<div class='people'>");
            print("<span class='time_block'>$time_icon $date_time </span>");
            print('<a href="/profile/view/?login=' . $user_data['login'] . '">
            <div class="people" style="background: url(' . $photo_data . ') no-repeat center; 
                    background-size: cover;"><span class="name"><i class="fas fa-circle" style="color: ' . $user_data['online_status']['status'] . '" title ="' . $user_data['online_status']['last_online'] . '"></i> '
                . $user_data['login'] . '</span></div></a>');
            print("<label class='tags_labels' >$action_icon $action_count</label>");
            print("</div>");
            if ($date_one != $date_two && $date_two != 1)
                print("<h2 class='date_block'>$calendar $date_two</h2>");
        }
        print("</div>");
    }
    else
        print("<div id='system_message'><h2>Not Activity Yet</h2></div>");?>
</div>

