<?php

namespace PhpMercadoPago;

class Config {

    protected $AccessToken;

    public function getAccessToken() {
        return $this->AccessToken;
    }

    public function setAccessToken($accessToken) {
        $this->AccessToken = $accessToken;
    }

}