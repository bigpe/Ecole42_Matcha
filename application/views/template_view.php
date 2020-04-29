<html>
<head>
    <script src="/js/jQuery.js"></script>
    <script src="https://kit.fontawesome.com/4c208c6ea5.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../../css/template.css">
    <script src="../../js/template.js"></script>
    <meta name="viewport" content="width=device-width initial-scale=1.0">
    <script src="../../js/notification.js"></script>
    <link rel="icon" href="https://matcha.fun/favicon.ico" type="image/x-icon">

    <link rel="shortcut icon" href="https://matcha.fun/favicon.ico" type="image/x-icon">
</head>
    <body>
    <div id="wrap">
    <header>
        <div id="header_buttons">
            <a href="/" id="logo"><i class="fas fa-heartbeat"></i> Matcha <i class="fas fa-theater-masks"></i></a>
            <?php if(isset($_SESSION['login'])) $notify_message = $data['notification']['notification_message']; if(isset($_SESSION['login'])) $notify_events = $data['notification']['view_notification']?>
            <?php if(isset($_SESSION['login'])) print("<a class='header_buttons' href='/Settings'><i class=\"fas fa-cogs\"></i></a>"); ?>
            <?php isset($_SESSION['login']) ? print('<a class="header_buttons" href="/Matcha"><i class="fas fa-dice"></i></a>') : ""?>
            <?php if(isset($_SESSION['login'])) print("<a class='header_buttons' href='/Find_Advanced'><i class=\"fas fa-search\"></i></a>"); ?>
            <?php if(isset($_SESSION['login'])) print("<a class='header_buttons' href='/Conversation'><i class=\"fas fa-envelope\"></i>");
            if(isset($_SESSION['login'])) $notify_message ? print("<span id='notification_mess'>$notify_message</span></a>") : print("</a>");?>
            <?php if(isset($_SESSION['login'])) print("<a class='header_buttons' id='notification' href='/History'><i class=\"fas fa-bell\"></i>");
            if(isset($_SESSION['login'])) $notify_events ? print("<span id='notification_count'>$notify_events</span></a>") : print("</a>"); ?>
            <?php if(isset($_SESSION['login'])) print("<a class='header_buttons' href='/Auth/Sign_out'><i class=\"fas fa-sign-out-alt\"></i></a>"); ?>
        </div>
    </header>

    <?php include "application/views/" . $content_view;?>
    <div class="notification" id ="notification_block">
        <span class="close" onclick="closeNotifications()"></span>
        <div id="notification_area"></div>
    </div>
        <div id="debug_block">
            <h2>Debug</h2>
            <h3>Data => <?php print_r($data)?></h3>
            <h3>Error => <?php
                if(isset($data['error'])) if(!$data['error']) print("OK");
                else print ($data['error']);
                else print("OK");?></h3>
            <h3>Get => <?php print_r ($_GET);?></h3>
            <h3>Post => <?php print_r ($_POST);?></h3>
            <h3>Session => <?php print_r ($_SESSION);?></h3>
        </div>
        <div id="push"></div>
    </div>
    <footer>
        <a href="/Politics" style="text-decoration: none"><i class="fas fa-user-shield"></i></a>
    </footer>
    </body>
<script type="text/javascript">
    let error_name = "<?php
        if(isset($data['error'])) print($data['error']); else print(""); ?>";
    if(error_name)
        alert(error_name);
</script>
</html>