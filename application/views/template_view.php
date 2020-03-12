<!DOCTYPE HTML PUBLIC «-//W3C//DTD HTML 4.01 Transitional//EN» «http://www.w3.org/TR/html4/loose.dtd»>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="/css/style.css">
        <link href="https://fonts.googleapis.com/css?family=Roboto+Slab&display=swap" rel="stylesheet">
        <title>Camaguru</title>
    </head>
    <body>
        <div class="header">
            <?php
            if(isset($_SESSION['user'])){?>
            <p> <?php echo "Hello ".$_SESSION['user'];}?></p>
            <ul id="nav"  style="--items: 3;">
                <?php
                if(isset($_SESSION['status']))
                {
                    echo "<li><a href='/index/logout'>Logout</a></li>";
                    echo "<li><a href='/profile'>Profile</a></li>";
                    echo "<li><a href='/gallery'>Gallery</a></li>";
                    echo "<li><a href='/feed'>Feed</a></li>";
                }
                else
                        echo '<li><a href="/sign_in">Sign in</a></li>';?>
                <?php if($_SESSION['status'] == 'admin'){
                    echo '<li><a href="/moder">Moderation area</a></li>';
                    echo '<li><a href="/admin">Admin area</a></li>';}?>
                <?php if($_SESSION['status'] == 'moder')
                    echo '<li><a href="/moder">Moderation area</a></li>';?>
                <li><a href="../../index.php">Start page</a></li>
            </ul>

        </div>
        <?php include 'application/views/'.$content_view; ?>

        </html>
    </body>
</html>
