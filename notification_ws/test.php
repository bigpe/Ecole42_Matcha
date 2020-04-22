<?php
if (!empty($_GET['code'])) {
    $params = array(
    'client_id'     => '158115967922-0g334aa81m1bk7e09a3go97oiquo80cs.apps.googleusercontent.com',
    'client_secret' => 'A9pF_X-KAWrnfIZwfJLaf_uE',
    'redirect_uri'  => 'https://matcha.fun/Auth/with_google',
    'grant_type'    => 'authorization_code',
    'code'          => $_GET['code']
    );

    $ch = curl_init('https://accounts.google.com/o/oauth2/token');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HEADER, false);
    $data = curl_exec($ch);
    curl_close($ch);
    $data = json_decode($data, true);
    if (!empty($data['access_token'])) {
        // Токен получили, получаем данные пользователя.
        $params = array(
        'access_token' => $data['access_token'],
        'id_token'     => $data['id_token'],
        'token_type'   => 'Bearer',
        'expires_in'   => 3599
        );

        $info = file_get_contents('https://www.googleapis.com/oauth2/v1/userinfo?' . urldecode(http_build_query($params)));
        $info = json_decode($info, true);
        var_dump($info);
    }
}
