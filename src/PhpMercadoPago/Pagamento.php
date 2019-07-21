<?php

namespace PhpMercadoPago;

use PhpMercadoPago\Config;
use MercadoPago\SDK;
use MercadoPago\Payment;
use PhpMercadoPago\Models\Pagador;
use PhpMercadoPago\Models\Error;

class Pagamento {

    public const PAGAMENTO_BOLETO = 1;
    public const PAGAMENTO_CARTAO = 2;

    protected $config;
    protected $transaction_amount;
    protected $shipping_amount;
    protected $payer;
    protected $description;
    protected $items;
    protected $payment_type;
    protected $payment_installments;
    protected $payment_card_token;
    protected $payment;

    public function __construct() {
        SDK::setAccessToken(Config::getAccessToken());
        $this->payment = new Payment();
        $this->payment->notification_url = Config::getNotificationsUrl();
    }
    
    public function create() {
        if(true) {    //Fazendo a checagem dos campos
            //Aqui definimos o valor do pagamento (Obrigatorio)
            $this->payment->transaction_amount = round($this->transaction_amount, 2);
            //Definimos a descricao
            $this->payment->description = $this->description;
            //O Valor do frete, caso nao tenha, defina como 0
            $this->payment->shipping_amount = round($this->shipping_amount, 2);

            if ($this->payment_type == 1){
                //Boleto so tem uma parcela
                $this->payment->installments = 1;
            } else if ($this->payment_type == 2) {
                //Aqui definimos quantas parcelas a compra sera parcelada no cartao
                $this->payment->installments = $this->payment_installments;

                //Esse token precisa ser gerado no front-end junto a API JS do mercadolivre
                $this->payment->token = $this->payment_card_token;

                //ID de pagamanto, visa, mastercard, etc.. (tambem passado via frontend)
                //Aparamente, Boletos nao precisa passsar esse parametro. (Testar melhor)
                $this->payment->payment_method_id = $this->payment_method_id;    
            }

            //Aqui passaremos alguns dados do pagador, lembrando que, caso o email ja 
            //exista no mercadopago, ele automaticamente pegara as informacoes constadas la.
            $this->payment->payer = array(
                'first_name'     => $this->payer->getNome(),
                'last_name'      => $this->payer->getSobrenome(),
                'email'     => $this->payer->getEmail(),

                //De novo, caso o email exista no sistema, ele substituria o 'address' e o 'identification'
                'address'        => array( 
                    'zip_code'      => $this->payer->getEndereco()->getCEP(),
                    'street_name'   => $this->payer->getEndereco()->getLogradouro(),
                    'street_number' => $this->payer->getEndereco()->getNumero() 
                ),
                'identification' => array(
                    'type'   => $this->payer->getIdentidade()->getTipo(),
                    'number' => $this->payer->getIdentidade()->getNumero()
                )
            );

            $this->payment->additional_info = array(
                'shipments' => array(
                    //Endereco de entrega do pedido
                    'receiver_address' => array(
                        'zip_code'      => $this->payer->getEndereco()->getCEP(),
                        'street_name'   => $this->payer->getEndereco()->getLogradouro(),
                        'street_number' => $this->payer->getEndereco()->getNumero() 
                    )
                ),
                //TODO: Items do pedido
                'items' => array()
            );
            
            //Aqui finalizamos a configuracao e obtemos a resposta final da API dos correios
            $this->payment->save();

            //Aqui eu armazeno os dados retornados num array, afim de checar se existe, erros, etc..
            $returnData = $this->payment->getAttributes();
            
            if ($returnData['error'] !== null){
                return new Error($returnData['error']);
            }
            
            //TODO: Implementar a classe de resposta, para boletos e pagamento com cartoes.
        }
    }

    public function setTipo($pagamentoType) {
        $this->payment_type = $pagamentoType;
        if ($pagamentoType == 1)
            $this->payment->payment_method_id = 'bolbradesco';
    }

    public function setIdPagamento($payment_id) {
        $this->payment_method_id = $payment_id;
    }

    public function setParcelas($parcelas) {
        $this->payment_installments = $parcelas;
    }

    public function setPagador(Pagador $payer){
        $this->payer = $payer;
    }

    public function setToken($token) {
        $this->payment_card_token = $token;
    }


    public function setValor($amount) {
        $this->transaction_amount = $amount;
    }

  
    public function setDescricao($description) {
        $this->description = $description;
    }

    public function setValorFrete($valorFrete) {
        $this->shipping_amount = $valorFrete;
    }

    private function checkFields() {
        if (!$this->transaction_amount){
            throw new \Exception("O valor da transacao eh obrigatorio!");
            return false;
        } else if (!$this->payer){
            throw new \Exception("O pagador da transacao eh obrigatorio!");
            return false;
        }
    }
}