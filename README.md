# PhpMercadoPago
## Integracao facil do MercadoPago em PHP

# AINDA NAO ESTA PRONTO.
## Usagem
Como quis deixar a implementacao desse modulo da maneira mais simples o possivel, o uso eh extremamente simples.
Primeiro, precisamos obter nossas credenciais do mercadopago, como estou num ambiente de desenvolvimento, 
ela se parece mais ou menos com isso (Nao eh valida): 
TEST-3286625618064909-123445-2b3d53083ffa4452c3ebb748b73f5e6e-362103133
Para obter as suas, entre no link https://www.mercadopago.com/mlb/account/credentials.

Para comercarmos, precisamos instanciar algumas classes, como a de Identidade, Telefone, Endereco, e a classe do Pagador

```php

    $identidade = new PhpMercadoPago\Models\Identidade();
    $identidade->setNumero('12345678912');
    $identidade->setTipo('cpf');

    $telefone = new PhpMercadoPago\Models\Telefone();
    $telefone->setDDD('12');
    $telefone->setNumero('123456789');

    $endereco = new PhpMercadoPago\Models\Endereco();
    $endereco->setCEP('12345678');
    $endereco->setLogradouro('Rua tal tal');
    $endereco->setNumero(106);

    $pagador = new PhpMercadoPago\Models\Pagador();
    $pagador->setNome('Mauricio');
    $pagador->setSobrenome('Barbosa');
    $pagador->setEmail('email@email.com');
    $pagador->setEndereco($endereco);
    $pagador->setIdentidade($identidade);
    $pagador->setTelefone($telefone);
```
Feito isso, vamos instanciar a classe principal de pagamento, e definiremos a variavel $pagador que definimos acima:

```php
  //...
  $pagamento = new PhpMercadoPago\Pagamento();
  pagamento->setPagador($pagador);
```

Aqui entra o pulo do gato, definiremos o tipo de pagamento que iremos criar, se eh boleto, ou cartao:
```php
  //...
  $pagamento->setTipo(Pagamento::PAGAMENTO_BOLETO); //Para boleto
  $pagamento->setTipo(Pagamento::PAGAMENTO_CARTAO); //Para cartao de credito
```
Caso seja Boleto, precisaremos apenas definir o valor, e a descricao do pagamento:
```php
    $pagamento->setValor(round(84.99,2);
    $pagamento->setDescricao("Descricao ou titulo do pagamento");
```
POREM, caso seja cartao, precisaremos integrar algumas coisas do lado do front-end, afim de obtermos uma especie de token,
especificamente para aquela compra, cartao e parcelas, Irei colocar um pedaco desse codigo aqui depois.
Do lado do backend, tudo o que precisamos, eh definir o ID de pagamento (Pode ser 'mastercard', 'visa', etc.., por isso
o motivo de integrar o front-end ajudaria), o Token (que vira do lado do front-end) e em quantas parcelas aquele pagamento 
sera feito:
```php
    //...
    $pagamento->setIdPagamento('idMetodoPagamento');
    $pagamento->setToken('card_token');
    $pagamento->setParcelas(3);
```
E por fim, para salvarmos o pagamento, e realizarmos a troca de dados com o MercadoPago, precisaremos chamar o medodo
create na classe Pagamento.
Esse metodo retornara uma instancia de objeto de acordo com o tipo de pagamento, onde ira conter informacoes sobre o pagamento,
se ele foi aprovado, o link para o boleto, etc.. (ainda nao implementado)
Caso haja algum erro, ele ira retornar um objeto do tipo PhpMercadoPago\Models\Error, que contera um status da requisicao,
a mensagem da requisicao, o erro, e as causas, que eh um array de informacoes.
```php
  $dadosRetorno = $pagamento->create();
```
