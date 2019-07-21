<?php

namespace PhpMercadoPago;
class Config {

    protected static $AccessToken;

    public static function getAccessToken() {
        return self::AccessToken;
    }

    public function setAccessToken($accessToken) {
        self::$AccessToken = $accessToken;
    }

}