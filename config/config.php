<?php
return array(
    #Database Settings
    "db" => array(
        'host' => 'Hostname',
        'port' => 'Port number',
        'dbname' => 'Database Name',
        'username' => 'Username',
        'password' => 'Password'),
    #notification web socket server (ip server)
    "ip_ws" => 'ip_server',
    #Php Mailer Settings
    "mail" => array(
        'Username' => 'Email',
        'Password' => 'Password',
        'Subject' => "Letter Subject",
        'Host' => 'Mail Host',
        'Port' => 587,
        'Address' => 'Email',
        'Name' => 'Letter Name'),
    #City Parser Settings
    "city_parser" => array(
        "token" => "DaData.ru - dev token"
    ),
    #Google Auth API
    "google"=>array(
        'clientID' =>'Client ID',
        'clientSecret' => 'Client Secret Key',
        'redirectUri' => 'Redirect after Auth'
    )
);
?>
