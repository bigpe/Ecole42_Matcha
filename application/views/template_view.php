<html>
<head>
    <script src="/js/jQuery.js"></script>
    <script src="https://kit.fontawesome.com/4c208c6ea5.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="/css/template.css">
    <meta name="viewport" content="width=device-width initial-scale=1.0">
</head>
    <body>
    <header>
        <a href="/">Main Page</a>
        <?php if(isset($_SESSION['login'])) print("<a href='/settings'>Settings</a>"); ?>
        <?php if(isset($_SESSION['login'])) print("<a href='/find_advanced'>Find what You Want</a>"); ?>
        <?php if(isset($_SESSION['login'])) print("<a href='/history'>History</a>"); ?>
        <?php if(isset($_SESSION['login'])) print("<a href='/auth/sign_out'>Sign Out</a>"); ?>
    </header>
    <?php include "application/views/" . $content_view;?>
    <footer>
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
    </footer>
    </body>
<script type="text/javascript">
    let error_name = "<?php
        if(isset($data['error'])) print($data['error']); else print(""); ?>";
    if(error_name)
        alert(error_name);
</script>
</html>