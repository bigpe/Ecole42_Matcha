<html>
<head>
    <script src="/js/jQuery.js"></script>
    <script src="https://kit.fontawesome.com/4c208c6ea5.js" crossorigin="anonymous"></script>
</head>
    <body>
        <a href="/">Main Page</a>
        <?php if(isset($_SESSION['login'])) print("<a href='/settings'>Settings</a>"); ?>
        <?php if(isset($_SESSION['login'])) print("<a href='/auth/sign_out'>Sign Out</a>"); ?>
        <?php include "application/views/" . $content_view;?>
        <div id="debug_block">
            <h2>Debug</h2>
            <h3>Data => <?php print_r($data)?></h3>
            <h3>Error => <?php if(!$data['error']) print("OK"); else print ($data['error']); ?></h3>
            <h3>Get => <?php print_r ($_GET);?></h3>
            <h3>Post => <?php print_r ($_POST);?></h3>
            <h3>Session => <?php print_r ($_SESSION);?></h3>
        </div>
    </body>
<script type="text/javascript">
    let error_name = "<?php print($data['error']); ?>";
    if(error_name)
        alert(error_name);
</script>
</html>