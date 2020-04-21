<?php
ob_implicit_flush();
require_once('../config/database.php');
$errstr = null;
$errno = null;
$context = stream_context_create();

stream_context_set_option($context, 'ssl', 'local_cert', '/etc/ssl/key.pem');
stream_context_set_option($context, 'ssl', 'passphrase', '');
stream_context_set_option($context, 'ssl', 'allow_self_signed', true);
stream_context_set_option($context, 'ssl', 'verify_peer', false);
$socket = stream_socket_server("ssl://192.168.0.191:6969", $errno, $errstr, STREAM_SERVER_BIND|STREAM_SERVER_LISTEN, $context);
if (!$socket) {
    echo ("socket unavailable<br />");
    die($errstr . "(" .$errno. ")\n");
}
$connects_info = array();
$connects = array();
while (true) {
    $read = $connects;
    $read [] = $socket;
    $write = $except = null;
    if (!stream_select($read, $write, $except, null)) {//ожидаем сокеты доступные для чтения (без таймаута)
        break;
    }

    if (in_array($socket, $read)) {
        if (($connect = stream_socket_accept($socket, null)) && $info = handshake($connect)) {
            $connects[] = $connect;
            $connects_info [] = array('socket' => $connect, 'info' => explode('=',$info["Cookie"])[1]);
            onOpen($connect, $info);
        }
        unset($read[ array_search($socket, $read) ]);
    }
    foreach($read as $connect) {
        $data = fread($connect, 10000000);
        if (!$data) { //соединение было закрыто
            fclose($connect);
            unset($connects[ array_search($connect, $connects) ]);
            onClose($connect, $info);//вызываем пользовательский сценарий
            continue;
        }
       onMessage($connects, $data, $connects_info);//вызываем пользовательский сценарий
    }
}
fclose($socket);

function handshake($connect) { //Функция рукопожатия
    $info = array();
    $line = fgets($connect);
    $header = explode(" ", $line);
    $info['method'] = $header[0];
    $info['uri'] = $header[1];
    while ($line = rtrim(fgets($connect))) {
        if (preg_match('/\A(\S+): (.*)\z/', $line, $matches)) {
            $info[$matches[1]] = $matches[2];
        } else {
            break;
        }
    }
    $address = explode(":", stream_socket_get_name($connect, true)); //получаем адрес клиента
    $info['ip'] = $address[0];
    $info['port'] = $address[1];
    if (empty($info["Sec-WebSocket-Key"])) {
        return false;
    }
    $SecWebSocketAccept = base64_encode(pack('H*', sha1($info['Sec-WebSocket-Key'] . "258EAFA5-E914-47DA-95CA-C5AB0DC85B11")));
    $upgrade = "HTTP/1.1 101 Web Socket Protocol Handshake\r\n" .
                "Upgrade: websocket\r\n" .
                "Connection: Upgrade\r\n" .
                "Sec-WebSocket-Accept:".$SecWebSocketAccept."\r\n\r\n";
    fwrite($connect, $upgrade);
    return $info;
}

function encode($socketData, $type = "text", $masked = false){
    $b1 = 0x80 | (0x1 & 0x0f);
    $length = strlen($socketData);

    if ($length <= 125)
        $header = pack('CC', $b1, $length);
    elseif ($length > 125 && $length < 65536)
        $header = pack('CCn', $b1, 126, $length);
    elseif ($length >= 65536)
        $header = pack('CCNN', $b1, 127, $length);
    return $header . $socketData;
}

function decode($socketData){
    $length = ord($socketData[1]) & 127;
    if ($length == 126) {
        $masks = substr($socketData, 4, 4);
        $data = substr($socketData, 8);
    } elseif ($length == 127) {
        $masks = substr($socketData, 10, 4);
        $data = substr($socketData, 14);
    } else {
        $masks = substr($socketData, 2, 4);
        $data = substr($socketData, 6);
    }
    $socketData = "";
    for ($i = 0; $i < strlen($data); ++$i) {
        $socketData .= $data[$i] ^ $masks[$i % 4];
    }
    return $socketData;
}

function onOpen($connect, $info) {
    echo $info['Cookie'] .' connect ok' . PHP_EOL;
}

function onClose($connect, $info) {
    echo $info['Cookie'] ." close OK" . PHP_EOL;
}

function onMessage($connect, $data, $info) {
    if ($data) {
        $f = decode($data);
        $messageObj = json_decode($f);
        if (isset($messageObj)) {
            if ($messageObj->type == 1 || $messageObj->type == 2 || $messageObj->type == 3) {
                $chat_box_message = createNewNotification($messageObj->user_from, $messageObj->user_to, $messageObj->type, $messageObj->message);
                foreach ($info as $users) {
                    foreach ($chat_box_message['user_to'] as $cookie) {
                        if ($cookie['session_name'] === $users['info'] && is_resource($users['socket'])) {
                            fwrite($users['socket'], encode(json_encode($chat_box_message)));
                        }
                    }
                }
            }
            if ($messageObj->type == 19 || $messageObj->type == 18) {
                $chat_box_message = createChatBoxStatus1($messageObj->user_from, $messageObj->user_to, $messageObj->type);
                foreach ($info as $users) {
                    foreach ($chat_box_message['user_to'] as $cookie) {
                        if ($cookie['session_name'] === $users['info'] && is_resource($users['socket'])) {
                            fwrite($users['socket'], encode(json_encode($chat_box_message)));
                        }
                    }
                }
            }
            if ( $messageObj->type == 11) {
                $chat_box_message = createChatBoxMessage($messageObj->user_from, $messageObj->user_to, $messageObj->message, $messageObj->type);
                foreach ($info as $users) {
                    foreach ($chat_box_message['user_to'] as $cookie) {
                        if ($cookie['session_name'] === $users['info'] && is_resource($users['socket'])) {
                            fwrite($users['socket'], encode(json_encode($chat_box_message)));
                        }
                    }
                }
            }
            if ($messageObj->type == 13 ) {
                $chat_box_message = createChatNotification($messageObj->user_from, $messageObj->user_to, $messageObj->message, $messageObj->type);
                foreach ($info as $users) {
                    foreach ($chat_box_message['user_to'] as $cookie) {
                        if ($cookie['session_name'] === $users['info'] && is_resource($users['socket'])) {
                            fwrite($users['socket'], encode(json_encode($chat_box_message)));
                        }
                    }
                }
            }
        }
    }
}

function createChatNotification($user_from_session, $chat_id, $message, $type)
{
    $db = new database();
    $user_from = $db->db_read("SELECT login FROM USERS
                                    JOIN USERS_SESSIONS US on USERS.user_id = US.user_id
                                    WHERE US.session_name = '$user_from_session'");
    $user_to = $db->db_read_multiple("SELECT session_name FROM USERS_SESSIONS
                                JOIN CHATS C on user_id_one = USERS_SESSIONS.user_id
                                OR C.user_id_two=USERS_SESSIONS.user_id
                                WHERE  C.chat_id = $chat_id AND USERS_SESSIONS.session_name !='$user_from_session'
                                ");
    $messageArray = array('user_from' => $user_from, 'user_to' => $user_to,'chat_id' => $chat_id, 'message' => $message, 'type' => $type);
    return $messageArray;
}

function createNewNotification($user_from_session, $user_login_to, $type, $message)
{
    $db = new database();
    $user_to = $db->db_read_multiple("SELECT session_name FROM USERS_SESSIONS
                                            JOIN USERS U on USERS_SESSIONS.user_id = U.user_id
                                            WHERE U.login = '$user_login_to'");
    $user_from = $db->db_read("SELECT login FROM USERS
                                    JOIN USERS_SESSIONS US on USERS.user_id = US.user_id
                                    WHERE US.session_name = '$user_from_session'");
    $messageArray = array('user_from' => $user_from, 'user_to' => $user_to, 'type' => $type, 'message' => $message);
    return $messageArray;
}

function createChatBoxMessage($user_session, $chat_id, $message, $type)
{
    $db = new database();
    $user_to = $db->db_read_multiple("SELECT session_name FROM USERS_SESSIONS
                                JOIN CHATS C on user_id_one = USERS_SESSIONS.user_id
                                OR C.user_id_two=USERS_SESSIONS.user_id
                                WHERE  C.chat_id = $chat_id AND USERS_SESSIONS.session_name !='$user_session'
                                ");
    $messageArray = array('user_from' => $chat_id, 'user_to' => $user_to, 'message' => $message, 'type' => $type);
    return $messageArray;
}

function createChatBoxStatus1($user_session, $chat_id, $type)
{
    $db = new database();
    $user_to = $db->db_read_multiple("SELECT session_name FROM USERS_SESSIONS
                                JOIN CHATS C on user_id_one = USERS_SESSIONS.user_id
                                OR C.user_id_two=USERS_SESSIONS.user_id
                                WHERE  C.chat_id = $chat_id AND USERS_SESSIONS.session_name !='$user_session' 
                                ");

    $messageArray = array('user_from' => $chat_id, 'user_to' => $user_to, 'type' => $type);
    return $messageArray;
}

