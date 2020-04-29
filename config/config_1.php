<?php
return array(
    "db" => array(
        'host' => '37.204.240.149',
        'port' => '3307',
        'dbname' => 'Matcha',
        'username' => 'root',
        'password' => 'root'),
    #notification web socket server (ip server)
    "ip_ws" => '192.168.0.191',
    "mail" => array(
        'Username' => 'bigpewm@yandex.ru',
        'Password' => 'ship0123',
        'Subject' => "Matcha System Message",
        'Host' => 'smtp.yandex.ru',
        'Port' => 587,
        'Address' => 'bigpewm@yandex.ru',
        'Name' => 'Matcha'),
    "city_parser" => array(
        "token" => "470bf8c1890ac6915e8ed7b05ea27121a0c324c0"
    ),
    "google"=>array(
        'clientID' =>'158115967922-0g334aa81m1bk7e09a3go97oiquo80cs.apps.googleusercontent.com',
        'clientSecret' => 'A9pF_X-KAWrnfIZwfJLaf_uE',
        'redirectUri' => 'https://matcha.fun/auth/with_google/'
    )

);
?>