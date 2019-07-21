<?php

namespace PhpMercadoPago\Models;

class Pagador {

    private $first_name;
    private $last_name;
    private $email;
    private $phone;
    private $identification;
    private $address;


    public function setNome($nome){
        $this->first_name = $nome;
    }

    public function setSobrenome($sobrenome){
        $this->last_name = $sobrenome;
    }

    public function setEmail($email){
        $this->email = $email;
    }

    public function setTelefone(Telefone $phone){
        $this->phone = $phone;
    }

    public function setIdentidade(Identidade $identity) {
        $this->identification = $identity;
    }

    public function setEndereco(Endereco $endereco){
        $this->address = $endereco;   
    }

    public function getNome() {
        return $this->first_name;
    }

    public function getSobrenome() {
        return $this->last_name;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getTelefone() {
        return $this->phone;
    }

    public function getIdentidade() {
        return $this->identification;
    }

    public function getEndereco(){
        return $this->address;
    }

}