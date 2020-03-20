<link rel="stylesheet" href="../../css/find_advanced.css">
<h3>Find Advanced</h3>
<div id="filter_block">
    <div id="age_block">
        <input type="range" min="18" max="99">
    </div>
    <div id="fame_block"></div>
    <div id="geo_block"></div>
    <div id="tags_block"></div>
</div>
<div id="people_block">
    <?php
    $users_data = $data['users_data'];
    foreach($users_data as $user_data){
        $main_photo = $user_data['photo_src'];
        $main_photo_data = base64_encode(file_get_contents($main_photo));
        $photo_data = "'data: ". mime_content_type($main_photo) .";base64,$main_photo_data'";
        print('<a href="/profile/view/?login=' . $user_data['login'] . '"><div class="people" style="background: url(' . $photo_data . ') no-repeat center; 
                background-size: cover;"><span class="name"><i class="fas fa-circle" style="color: #5fe15f"></i> ' . $user_data['login'] . '</span></div></a>');
    }
    ?>
</div>