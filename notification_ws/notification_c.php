<?php
require_once ('../config/database.php');
class Notification
{
    public function sendHeaders($headersText, $newSocket, $host, $port)
    {
        $headers = array();
        $lines = preg_split("/\r\n/", $headersText);
        foreach ($lines as $line) {
            $line = chop($line);
            if (preg_match('/\A(\S+): (.*)\z/', $line, $matches)) {
                $headers[$matches[1]] = $matches[2];
            }
        }
        $key = $headers['Sec-WebSocket-Key'];
        $sKey = base64_encode(pack('H*', sha1($key . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')));
        $strHeadr = "HTTP/1.1 101 Web Socket Protocol Handshake\r\n"
            . "Upgrade: websocket\r\n"
            . "Connection: Upgrade\r\n"
            . "WebSocket-Origin: $host\r\n"
            . "WebSocket-Location: ws://$host:$port\r\n"
            . "Sec-WebSocket-Accept:$sKey\r\n\r\n";
        socket_write($newSocket, $strHeadr, strlen($strHeadr));
    }

    function unseal($socketData)
    {
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

    function seal($socketData)
    {
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

    function send($message)
    {
        global $clientSocketArray;
        $messageLength = strlen($message);
        foreach ($clientSocketArray as $clientSocket) {
            @socket_write($clientSocket, $message, $messageLength);
        }
        return true;
    }

    function createChatBoxMessage($user_from_session, $chat_id, $message, $type)
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
        return $this->seal(json_encode($messageArray));
    }

    function createChatBoxStatus($user_from_session, $user_login_to, $type)
    {
        $db = new database();
        $user_to = $db->db_read_multiple("SELECT session_name FROM USERS_SESSIONS
                                                JOIN USERS U on USERS_SESSIONS.user_id = U.user_id
                                                WHERE U.login = '$user_login_to'");
        $user_from = $db->db_read("SELECT login FROM USERS
JOIN USERS_SESSIONS US on USERS.user_id = US.user_id
WHERE US.session_name = '$user_from_session'");
        $messageArray = array('user_from' => $user_from, 'user_to' => $user_to, 'type' => $type);
        return $this->seal(json_encode($messageArray));
    }
}