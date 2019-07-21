<?php

namespace PhpMercadoPago\Models;

class Telefone {

    private $area_code;
    private $number;
    private $extension;

    public function setDDD($ddd) {
        $this->area_code = $ddd;
    }

    public function setNumero($number) {
        $this->number = $number;
    }

    public function setExtension($extension) {
        $this->extension = $extension;
    }

    public function getDDD() {
        return $this->area_code;
    }

    public function getNumero(){
        return $this->number;
    }

    public function getExtension(){
        return $this->extension;
    }

}