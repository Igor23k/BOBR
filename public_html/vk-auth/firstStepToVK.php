<?php
$client_id = '6439989'; // ID приложения
$client_secret = 'yG4Hu8h6M1TxfxZQc6Vw'; // Защищённый ключ
$redirect_uri = 'http://bobrchess.of.by/vk-auth'; // Адрес сайта

$url = 'http://oauth.vk.com/authorize';

$params = array(
    'client_id'     => $client_id,
    'redirect_uri'  => $redirect_uri,
    'response_type' => 'code'
);

$link = $url.'?'.urldecode(http_build_query($params));

header("Location: $link");