<?php

namespace PhpMercadoPago;

class Config {

    static $AccessToken;
    static $NotificationsUrl;

    public static function setNotificationsUrl($url){
        self::$NotificationsUrl = $url;
    }

    public static function getNotificationsUrl(){
        return self::$NotificationsUrl;
    }

    public static function getAccessToken() {
        return self::$AccessToken;
    }

    public static function setAccessToken($accessToken) {
        self::$AccessToken = $accessToken;
    }

}