<?php
require_once('../../sistema/conexao.php');
require_once('../../js/ajax/ApiConfig.php');

if ($access_token == "") {
  echo '<b>Chave Pix</b><br>';
  echo '<b>' . $tipo_chave . '</b> : ' . $chave_pix;
  exit();
}

$valor = $_POST['valor'];



$curl = curl_init();

$dados["transaction_amount"]                    = (float)$valor;
$dados["description"]                           = "Venda Delivery";
$dados["external_reference"]                    = "2";
$dados["payment_method_id"]                     = "pix";
$dados["notification_url"]                      = "https://google.com";
$dados["payer"]["email"]                        = "Zedeliverylapa@gmail.com";
$dados["payer"]["first_name"]                   = "Barboseira";
$dados["payer"]["last_name"]                    = "Bebidas";

$dados["payer"]["identification"]["type"]       = "CPF";
$dados["payer"]["identification"]["number"]     = "34152426764";

$dados["payer"]["address"]["zip_code"]          = "06233200";
$dados["payer"]["address"]["street_name"]       = "Av. das Nações Unidas";
$dados["payer"]["address"]["street_number"]     = "3003";
$dados["payer"]["address"]["neighborhood"]      = "Bonfim";
$dados["payer"]["address"]["city"]              = "Osasco";
$dados["payer"]["address"]["federal_unit"]      = "SP";

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.mercadopago.com/v1/payments',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => json_encode($dados),
  CURLOPT_HTTPHEADER => array(
    'accept: application/json',
    'content-type: application/json',
    'X-Idempotency-Key: ' . date('Y-m-d-H:i:s-') . rand(0, 1500),
    'Authorization: Bearer  APP_USR-931149998342970-101717-30423f5d25eb474a0cee04031552e07f-2904936191'
  ),
));
$response = curl_exec($curl);
$resultado = json_decode($response);


$id = $dados["external_reference"];
//var_dump($response);
curl_close($curl);
// ini_set('display_errors', 0);

// if ($resultado->status === 201) {


$codigo_pix = $resultado->point_of_interaction->transaction_data->qr_code;

$id_ref = $resultado->id;

$qrBase64 = $resultado->point_of_interaction->transaction_data->qr_code_base64;

echo
"<div class='pix-image'>
            <img id='base64image' src='data:image/jpeg;base64, $qrBase64'>
          </div>
          <div>
            <div>
              <span> Copie o código abaixo e siga o passo a passo para fazer o pagamento.</span>
              <div class='pix-codearea' aria-label='$codigo_pix'>
                <input
                  id='chave_pix_copia'
                  name='pix-copy-button'
                  value='$codigo_pix'
                   readonly 
                  >
                  <input type='hidden' id='codigo_pix' value='$id_ref'>
                <span onclick='copiar()'>COPIAR</span>
              </div>
            </div>
            <div>
              <div class='pix-infoarea'>
                <span>Como pagar seu pedido Pix</span>
                <ol>
                  <li>Copie o código Pix</li>
                  <li>Selecione a opção Pix Copie e Cola no app onde você tem o Pix habilitado</li>
                  <li>Alguns segundos após o pagamento seu pedido é confirmado!</li>
                </ol>
              </div>
              <div class='pix-buttons'>
                <div class='top-buttons'>
                  <button class='btn-pix blue' type='button'>Copiar código</button>
                  <button onclick='closePixModal()' class='btn-pix red' type='button'>Alterar Pagamento</button>
                </div>
                <button class='btn-pix green' onclick='finalizarPedido()'>Finalizar Pedido</button>
              </div>
            </div>
          </div>"

?>;