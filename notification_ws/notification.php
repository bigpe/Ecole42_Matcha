<?php
require_once('notification_c.php');
$chat = new Notification();
$null = null;
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if (!is_resource($socket))
    echo 'Не могу создать сокет: '. socket_strerror(socket_last_error()) . PHP_EOL;
if (!socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1))
    echo 'Не могу установить опцию на сокете: '. socket_strerror(socket_last_error()) . PHP_EOL;
if (!socket_bind($socket, 0, 6969))
    echo 'Не могу привязать сокет: '. socket_strerror(socket_last_error()) . PHP_EOL;
if(!socket_listen($socket))
    echo "error listen";
$clientSocketArray = array($socket);

while (true){
    $newSocketArray = $clientSocketArray;
    socket_select($newSocketArray, $null, $null, 0, 10);
    if (in_array($socket, $newSocketArray)){
        $newSocket = socket_accept($socket);
        $clientSocketArray[] = $newSocket;
        $header = socket_read($newSocket, 1024);
        $server_name = $argv[1];
        $chat->sendHeaders($header, $newSocket, "$server_name/notification_ws", 6969);
        $newSocketIndex = array_search($socket, $newSocketArray);
        unset($newSocketArray[$newSocketIndex]);
    }
    foreach ($newSocketArray as $newSocketArrayResource){
        while (socket_recv($newSocketArrayResource, $socketData, 1024, 0 ) >= 1){
            $socketMessage = $chat->unseal($socketData);
            $messageObj = json_decode($socketMessage);
            if (isset($messageObj)){
                if ($messageObj->type == 11)
                    $chat_box_message = $chat->createChatBoxMessage($messageObj->user_from, $messageObj->user_to, $messageObj->message,
                        $messageObj->type);
                if ($messageObj->type != 11)
                    $chat_box_message = $chat->createChatBoxStatus($messageObj->user_from, $messageObj->user_to, $messageObj->type);

                $chat->send($chat_box_message);
            }
            break 2;
        }
        $socketData = @socket_read($newSocketArrayResource, 1024, PHP_NORMAL_READ);
        if ($socketData === false){
            $newSocketIndex = array_search($newSocketArrayResource, $clientSocketArray);
            unset($clientSocketArray[$newSocketIndex]);
        }
    }
}
socket_close($socket);
?>