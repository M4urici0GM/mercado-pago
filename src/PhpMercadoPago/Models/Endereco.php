<?php

namespace PhpMercadoPago\Models;

class Endereco{
    
    private $zip;
    private $street_name;
    private $street_number;
    private $city_name;
    private $state_name;

    public function setCEP($cep){
        $this->zip = $cep;
    }

    public function setLogradouro($logradouro){
        $this->street_name = $logradouro;
    }

    public function setNumero($numero) {
        $this->street_number = $numero;
    }

    public function getCEP(){
        return $this->zip;
    }

    public function getLogradouro(){
        return $this->street_name;
    }

    public function getNumero(){
        return $this->street_number;
    }

    public function setEstado($state) {
        $this->state_name = $state;
    }

    public function getEstado(){
        return $this->state_name;
    }

    public function setCidade($cidade){
        $this->city_name = $cidade;
    }

    public function getCidade(){
        return $this->city_name;
    }

}