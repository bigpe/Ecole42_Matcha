<?php
return array(
    #Database Settings
    "db" => array(
        'host' => 'Hostname',
        'port' => 'Port number',
        'dbname' => 'Database Name',
        'username' => 'Username',
        'password' => 'Password'),
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
    #Notification web socket server (Host Address)
 	"ip_ws" => 'Host',
    #Google Auth API
    "google"=>array(
        'clientID' =>'Client ID',
        'clientSecret' => 'Client Secret Key',
        'redirectUri' => 'Redirect after Auth'
    )
);
?>
