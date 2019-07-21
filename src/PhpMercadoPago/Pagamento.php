<?php

use PhpMercadoPago\Config;
use MercadoPago\SDK;
use MercadoPago\Payment;

class Pagamento {

    public const PAGAMENTO_BOLETO = 1;
    public const PAGAMENTO_CARTAO = 2;

    protected $config;
    protected $transation_amount;
    protected $shipping_amount;
    protected $payer;
    protected $description;
    protected $items;
    protected $payment_type;
    protected $payment_installments;
    protected $payment_card_token;
    private $payment;

    public function __construct() {
        SDK::setAccessToken(Config::getAccessToken());
        $this->payment = new Payment();
    }
    
    public function create() {
        if($this->checkFields()) {
            $this->payment->transation_amount = round($this->transation_amount, 2);
            $this->payment->description = $this->description;
            $this->payment->shipping_amount = round($this->shipping_amount, 2);
            if ($this->payment_type == 1){
                $this->payment->installments = 1;
            } else if ($this->payment_type == 2) {
                $this->payment->installments = $this->payment_installments;
                $this->payment->token = $this->payment_card_token;
                $this->payment->payment_method_id = $this->payment_method_id;    
            }
            $this->payment->payer = array(
                'first_name'     => $this->payer->getNome(),
                'last_name'      => $this->payer->getSobrenome(),
                'address'        => array(
                    'zip_code'      => $this->payer->getEndereco()->getCEP(),
                    'street_name'   => $this->payer->getEndereco()->getLogradouro(),
                    'street_number' => $this->payer->getEndereco()->getNumero() 
                ),
                'identification' => array(
                    'type'   => $this->payer->getIdentidade()->getTipo(),
                    'number' => $this->payer->getIdentidade()->getNumero()
                ),
                'email'     => $this->payer->getEmail(),
            );
            $this->payment->save();
            return $this->payment;
        }
    }

    public function setTipo(Pagamento $pagamentoType) {
        $this->payment_type = $pagamentoType;
        if ($pagamentoType == 1)
            $this->payment->payment_method_id = 'bolbradesco';
    }

    public function setIdPagamento($payment_id) {
        $this->payment_method_id = $payment_id;
    }

    public function setPagador(Pagador $payer){
        $this->payer = $payer;
    }

    public function setValor($amount) {
        $this->transation_amount = $amount;
    }

  
    public function setDescricao($description) {
        $this->description = $description;
    }

    public function setValorFrete($valorFrete) {
        $this->shipping_amount = $valorFrete;
    }

    private function checkFields() {
        if (!$this->transation_amount){
            throw new \Exception("O valor da transacao eh obrigatorio!");
            return false;
        } else if (!$this->payer){
            throw new \Exception("O pagador da transacao eh obrigatorio!");
            return false;
        }
    }


}