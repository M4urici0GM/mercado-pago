<?php

namespace PhpMercadoPago;

use PhpMercadoPago\Config;
use MercadoPago\SDK;
use MercadoPago\Payment;
use PhpMercadoPago\Models\Pagador;
use Exception;

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
    protected $payment_id;
    private $payment;
    private $mpWebToken;
    private $notificationUrl;

    public function __construct() {
        SDK::setAccessToken(Config::getAccessToken());
        $this->payment = new Payment();
        $this->notificationUrl = Config::getNotificationsUrl();
    }

    public function create() {
        if($this->checkFields()) {
            $this->payment->transaction_amount = round($this->transation_amount, 2);
            $this->payment->description = $this->description;
            $this->payment->shipping_amount = round($this->shipping_amount, 2);
            $this->payment->notification_url = "{$this->notificationUrl}?webtoken={$this->mpWebToken}";
            if ($this->payment_type == 1){
                $this->payment->installments = 1;
            } else if ($this->payment_type == 2) {
                $this->payment->installments = $this->payment_installments;
                $this->payment->token = $this->payment_card_token;
                $this->payment->payment_method_id = $this->payment_method_id;
            }
            $this->payment->payer = array(
                'type'           => 'customer',
                'first_name'     => $this->payer->getNome(),
                'last_name'      => $this->payer->getSobrenome(),
                'identification' => array(
                    'type'   => $this->payer->getIdentidade()->getTipo(),
                    'number' => $this->payer->getIdentidade()->getNumero()
                ),
                'email'     => $this->payer->getEmail(),
                'address'   => array(
                    'zip_code'      => $this->payer->getEndereco()->getCEP(),
                    'street_name'   => $this->payer->getEndereco()->getLogradouro(),
                    'street_number' => $this->payer->getEndereco()->getNumero()
                )
            );
            $this->payment->additional_info = array(
                'payer' => array(
                    'first_name'     => $this->payer->getNome(),
                    'last_name'      => $this->payer->getSobrenome(),
                    'phone'          => array(
                        'area_code' => $this->payer->getTelefone()->getDDD(),
                        'number'    => $this->payer->getTelefone()->getNumero(),
                    ),
                    'address'        => array(
                        'zip_code'      => $this->payer->getEndereco()->getCEP(),
                        'street_name'   => $this->payer->getEndereco()->getLogradouro(),
                        'street_number' => $this->payer->getEndereco()->getNumero()
                    )
                ),
                'items' => $this->items,
                'shipments' => array(
                    'receiver_address' => array(
                        'zip_code'      => $this->payer->getEndereco()->getCEP(),
                        'street_name'   => $this->payer->getEndereco()->getLogradouro(),
                        'state_name'    => $this->payer->getEndereco()->getEstado(),
                        'city_name'     => $this->payer->getEndereco()->getCidade(),
                        'street_number' => $this->payer->getEndereco()->getNumero()
                    )
                )
            );
            $this->payment->save();
            return $this->payment;
        }
    }

    public function getPayment() {
        if (!$this->payment_id)
            throw new Exception("Set the ID with setPaymentId(id) first.");
        $payment = \MercadoPago\Payment.find_by_id($this->payment_id);
        return $payment;
    }

    public function setPaymentId($paymentId){
        $this->payment_id = $paymentId;
    }

    public function setToken($token) {
        $this->payment_card_token = $token;
    }

    public function setWebhookToken($webtoken) {
        $this->mpWebToken = $webtoken;
    }

    public function setParcelas($parcelas) {
        $this->payment_installments = $parcelas;
    }

    public function setItems($items){
        $_items = [];
        foreach($items as $item){
            $_items[] = array(
                'id'          => $item->id,
                'title'       => $item->title,
                'description' => $item->description,
                'category_id' => $item->category,
                'quantity'    => $item->qtd,
                'unit_price'  => $item->price
            );
        }
        $this->items = $_items;
    }

    public function setPedidoId($pedido_id){
        $this->order_id = $pedido_id;
    }

    public function setTipo($pagamentoType) {
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
	return true;
        if (!$this->transation_amount){
            throw new \Exception("O valor da transacao eh obrigatorio!");
            return false;
        } else if (!$this->payer){
            throw new \Exception("O pagador da transacao eh obrigatorio!");
            return false;
        }
    }
}
