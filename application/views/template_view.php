<html>
<a href="/">Main Page</a>
<?php
if(isset($_SESSION['login']))
    print("<a href='/auth/sign_out'>Sign Out</a>");
?>
<body>

<?php include "application/views/" . $content_view;?>
<h3>Error</h3>
<?php print_r ($data); ?>
<h3>Get</h3>
<?php print_r ($_GET);?>
<h3>Post</h3>
<?php print_r ($_POST);?>
<h3>Session</h3>
<?php print_r ($_SESSION);?>
</body>
</html>