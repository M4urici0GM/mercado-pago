<?php

namespace PhpMercadoPago\Models;

class Identidade {

    private $type;
    private $number;

    public function setTipo($tipo){
        $this->type = $tipo;
    }

    public function setNumero($numero) {
        $this->numero = $numero;
    } 

    public function getTipo(){
        return $this->type;
    }
    
    public function getNumero() {
        return $this->number;
    }
}