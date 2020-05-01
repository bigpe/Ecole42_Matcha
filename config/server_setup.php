<?php
//require_once "database.php";
//$db = new database();
//$dumps = scandir("./database_dump");
//$dumps_count = count($dumps) - 2;
//$i = 0;
//print("Find $dumps_count dumps, importing..." . PHP_EOL);
//foreach ($dumps as $dump){
//    if($dump != '.' && $dump != '..') {
//        $db->db_import($dump);
//        $i += 1;
//        print("$dump - Imported! ($i/$dumps_count)" . PHP_EOL);
//    }
//}
//print("Set chmod to files..." . PHP_EOL);
//exec("chmod -R 777 ../");
exec("composer install -d ..");
?>