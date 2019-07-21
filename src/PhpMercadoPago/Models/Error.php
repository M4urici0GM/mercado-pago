<?php

namespace PhpMercadoPago\Models;

class Error{
    protected $message;
    protected $status;
    protected $error;
    protected $causes;

    public function __construct($errorArr){
        $this->message = $errorArr['message'];
        $this->status = $errorArr['status'];
        $this->error = $errorArr['error'];
        $this->causes = $errorArr['causes'];
    }
}