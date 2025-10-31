<?php
@session_start();
require_once("./cabecalho_checkout.php");
$sessao = @$_SESSION['sessao_usuario'];
$id_usuario = @$_SESSION['id'];

if (isset($_POST['pedido'])) {
  $pedidoArray = json_decode($_POST['pedido'], true);
  echo "<div>";

  // Função recursiva para criar inputs
  function criarInputsHidden($arr, $prefixo = '')
  {
    foreach ($arr as $key => $valor) {
      // Monta o nome do input
      $nomeInput = $prefixo ? $prefixo . "[$key]" : $key;

      if (is_array($valor)) {
        criarInputsHidden($valor, $nomeInput);
      } else {
        // Cria input hidden com valor escapado
        echo "<input  type='hidden'" . "id='" . $key . "'" . "name='" . htmlspecialchars($nomeInput, ENT_QUOTES) . "' value='" . htmlspecialchars($valor, ENT_QUOTES) . "'>\n";
      }
    }
  }



  // Chama a função com os dados
  criarInputsHidden($pedidoArray['dados']);
  echo   "<input type='hidden' id='pagamento'>";

  // Botão submit
  echo "</div>";
} else {
  echo "<script>window.location.replace('index.php');</script>";
}


$total_carrinho = 0;
$query = $pdo->query("SELECT * FROM carrinho where sessao = '$sessao' and id_sabor = 0");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);


if ($total_reg == 0) {
  echo "<script>window.location='index'</script>";
  exit();
} else {
  for ($i = 0; $i < $total_reg; $i++) {
    foreach ($res[$i] as $key => $value) {
    }

    $id = $res[$i]['id'];
    $total_item = $res[$i]['total_item'];
    $produto = $res[$i]['produto'];
    $pedido = $res[$i]['pedido'];
    $quantidade_it = $res[$i]['quantidade'];

    $total_item = $total_item * $quantidade_it;

    $total_carrinho += $total_item;
  }
}



?>

<script>
  function addfunctionButton(text) {
    const paymentButton = document.getElementById('payment-button');
    paymentButton.textContent = text;
    paymentButton.onclick = finalizarPedido;
    paymentButton.classList.remove('disable');
  }


  function removefunctionButton() {
    const paymentButton = document.getElementById('payment-button');
    paymentButton.textContent = 'Revisar Pagamento';
    paymentButton.onclick = null;
    paymentButton.classList.add('disable');
  }

  function toggleBoxTroco(method) {
    const boxTroco = document.getElementById('box-troco');
    if (method === 'show') {
      boxTroco.style.display = 'block'
    } else if (method === 'hide') {
      boxTroco.style.display = 'none'
      $('#troco').val('');
    }
  }

  function pix() {
    // NOVO: Validação do bairro para pagamento (replicado da função validarBairroParaPagamento)
    var entrega = $('#entrega').val();

    if (entrega === 'Delivery') {
      var bairroPreenchido = false;
      var chave_api_maps = "<?= $chave_api_maps ?>";
      var entrega_distancia = "<?= $entrega_distancia ?>";

      if (chave_api_maps == "" || entrega_distancia != "Sim") {
        if ($('#bairro').val() !== "") {
          bairroPreenchido = true;
        }
      } else {
        if ($('#bairro').val() !== "" && $('#rua').val() !== "" && $('#numero').val() !== "" && $('#cidade').val() !== "" && $('#cep').val() !== "") {
          bairroPreenchido = true;
        }
      }

      if (!bairroPreenchido) {
        alert('Por favor, preencha seu endereço completo, com o bairro para entrega.');
        $('#radio_pix').prop('checked', false); // Desmarca o Pix se a validação falhar
        return; // Sai da função, impedindo o Pix de ser selecionado
      }
    }
    // FIM DA VALIDAÇÃO DO BAIRRO NO PIX

    // Define a forma de pagamento como Pix
    $('#pagamento').val('Pix');

    // Garante que apenas o Pix esteja marcado
    document.getElementById('radio_credito').checked = false;
    document.getElementById('radio_debito').checked = false;
    document.getElementById('radio_dinheiro').checked = false;

    const paymentButton = document.getElementById('payment-button');
    paymentButton.textContent = 'Pagar com Pix';
    paymentButton.onclick = gerarCodigoPix;
    paymentButton.classList.remove('disable');
    toggleBoxTroco('hide');
  }

  function gerarCodigoPix() {

    var total_compra = "<?= $total_carrinho ?>";
    total_compra = total_compra.replace(",", ".");
    var taxa_entrega = $('#taxa-entrega-input').val();
    taxa_entrega = taxa_entrega.replace(",", ".");
    var cupom = $('#valor-cupom').val();

    if (taxa_entrega == "") {
      taxa_entrega = 0;
    }

    if (cupom == "") {
      cupom = 0;
    }

    var total_compra_final = parseFloat(total_compra) + parseFloat(taxa_entrega) - parseFloat(cupom);

    var valor = total_compra_final.toFixed(2);
    $.ajax({
        url: 'js/ajax/pix.php',
        method: 'POST',
        data: {
            valor
        },
        dataType: "html",

        success: function (result) {
            $('#pix-modal-content').html(result);
            $('#pix-modal-overlay').fadeIn(200);
        },
        error: function () {
            alert('Erro ao gerar o PIX. Tente novamente.');
        }
    });
  }

  function dinheiro() {

    document.getElementById('radio_credito').checked = false;
    document.getElementById('radio_debito').checked = false;
    document.getElementById('radio_pix').checked = false;


    $('#pagamento').val('Dinheiro');

    addfunctionButton('Pagar com Dinheiro');
    toggleBoxTroco('show');

  }

  function debito() {

    document.getElementById('radio_credito').checked = false;
    document.getElementById('radio_pix').checked = false;
    document.getElementById('radio_dinheiro').checked = false;

    $('#pagamento').val('Cartão de Débito');

    addfunctionButton('Pagar com Cartão de Débito');
    toggleBoxTroco('hide');

  }

  function credito() {

    document.getElementById('radio_pix').checked = false;
    document.getElementById('radio_debito').checked = false;
    document.getElementById('radio_dinheiro').checked = false;

    $('#pagamento').val('Cartão de Crédito');
    addfunctionButton('Pagar com Cartão de Crédito');
    toggleBoxTroco('hide');

  }

  function uncheckedMethods() {
    $('#pagamento').val('');
    removefunctionButton();
    document.getElementById('radio_credito').checked = false;
    document.getElementById('radio_pix').checked = false;
    document.getElementById('radio_debito').checked = false;
    document.getElementById('radio_dinheiro').checked = false;
    toggleBoxTroco('hide');

  }



  function finalizarPedido() {

    $('#botao_finalizar').hide();
    $('#div_img').show();

    var codigo_pix = $('#codigo_pix').val();

    var nome = $('#nome').val();
    var telefone = $('#telefone').val();
    var mesa = $('#mesa').val();
    var id_usuario = "<?= $id_usuario ?>";

    if (telefone == "" && id_usuario == "") {
      alert('Preencha seu Telefone');
      return;
    }

    if (nome == "") {
      alert('Preencha seu Nome');
      return;
    }



    var nome_cliente = $('#nome').val();
    var tel_cliente = $('#telefone').val();
    var id_cliente = $('#id_cliente').val();


    var pagamento = $('#pagamento').val();
    var entrega = $('#entrega').val();
    var rua = $('#rua').val();
    var numero = $('#numero').val();
    var complemento = $('#complemento').val();
    var bairro = $('#bairro').val();
    var troco = $('#troco').val();
    var obs = $('#obs').val();
    var taxa_entrega = $('#taxa-entrega-input').val();
    var cupom = $('#valor-cupom').val();
    var pedido_whatsapp = "<?= $status_whatsapp ?>";

    var chave_api_maps = "<?= $chave_api_maps ?>";
    var entrega_distancia = "<?= $entrega_distancia ?>";

    if (chave_api_maps == "" || entrega_distancia != "Sim") {
      var cep = "";
      var cidade = "";
    } else {
      var cep = $('#cep').val();
      var cidade = $('#cidade').val();
    }


    if (entrega == "Delivery" && entrega_distancia == 'Sim') {
      if (cep == "") {
        alert("Preencha o CEP");
        return;
      }
    }



    if (taxa_entrega == "" && id_usuario == "") {
      alert("Digite um CEP válido para receber seu Pedido");
      return;
    }

    if (cupom == "") {
      cupom = 0;
    }

    if (taxa_entrega == "") {
      taxa_entrega = 0;
    }

    var dataAtual = new Date();
    var horas = dataAtual.getHours();
    var minutos = dataAtual.getMinutes();
    var hora = horas + ':' + minutos;


    var total_compra = <?= $total_carrinho ?>;
    var pedido_minimo = <?= $pedido_minimo ?>;


    if (pedido_minimo > 0) {
      if (parseFloat(total_compra) < parseFloat(pedido_minimo)) {
        alert('Seu pedido precisar ser superior a R$' + pedido_minimo);
        return;
      }
    }

    if (entrega == "") {
      alert('Selecione uma forma de entrega');
      return;
    }



    if (entrega == "Delivery" && rua == "") {
      alert('Preencha o Campo Rua');
      return;
    }

    if (entrega == "Delivery" && numero == "") {
      alert('Preencha o Campo Número');
      return;
    }

    if (entrega == "Delivery" && bairro == "") {
      alert('Escolha um Bairro');
      return;
    }



    if (pagamento == "") {
      alert('Selecione uma forma de pagamento');
      return;
    }

    if (pagamento == "Dinheiro" && troco == "") {
      //alert('Digite o total a ser pago para o troco');
      // $('#botao_finalizar').show(); 
      //$('#div_img').hide();
      //return;
    }

    var total_compra_final = parseFloat(total_compra) + parseFloat(taxa_entrega) - parseFloat(cupom);

    var total_compra_finalF = total_compra_final.toFixed(2)

    if (pagamento == "Dinheiro" && troco < total_compra_final) {
      //alert('Digite um valor acima do total da compra!');
      //$('#botao_finalizar').show(); 
      //$('#div_img').hide();
      //return;
    }


    $.ajax({
      url: 'js/ajax/inserir-pedido.php',
      method: 'POST',
      data: {
        pagamento,
        entrega,
        rua,
        numero,
        bairro,
        complemento,
        troco,
        obs,
        nome_cliente,
        tel_cliente,
        id_cliente,
        mesa,
        cupom,
        codigo_pix,
        cep,
        cidade,
        taxa_entrega
      },
      dataType: "html",


      success: function(mensagem) {
        //alert(mensagem)      

        if (mensagem == 'Pagamento nao realizado!!') {
          alert('Realize o Pagamento antes de Prosseguir!!');
          $('#botao_finalizar').show();
          $('#div_img').hide();
          return;
        }

        var msg = mensagem.split("*")


        $('#mensagem').text('');
        $('#mensagem').removeClass()
        if (msg[1].trim() == "Pedido Finalizado") {

          setTimeout(() => {
            alert('Pedido Finalizado!');
            if (id_usuario == "") {

              //chamar o relatorio da reserva 
            //  var id = msg[2].trim();
             // window.open("sistema/painel/rel/comprovante2_class.php?id=" + id + "&enviar=sim");
            //  window.location = 'index.php';
            } else {
             // window.location = 'sistema/painel/index.php?pagina=novo_pedido';
            }

          }, 500);



        } else {

        }



        if (pedido_whatsapp == 'Sim') {
          let a = document.createElement('a');
          //a.target= '_blank';
          a.href = 'http://api.whatsapp.com/send?1=pt_BR&phone=<?= $whatsapp_sistema ?>&text= *Novo Pedido*  %0A Hora: *' + hora + '* %0A Total: R$ *' + total_compra_finalF + '* %0A Entrega: *' + entrega + '* %0A Pagamento: *' + pagamento + '* %0A Cliente: *' + nome_cliente + '* %0A Previsão de Entrega: *' + result + '*';
          a.click();
        } else if (pedido_whatsapp == 'Api') {
          /*
           $.ajax({
              url: 'https://api.callmebot.com/whatsapp.php?phone=+553171390746&text=*Novo Pedido*  %0A Hora: *' + hora + '* %0A Total: R$ *' + total_compra_finalF + '* %0A Entrega: *' + entrega + '* %0A Pagamento: *' + pagamento + '* %0A Cliente: *' + nome_cliente + '* %0A Previsão de Entrega: *' + result + '*&apikey=320525',
               method: 'GET',          

              });
              */
        } else {

        }



        $('#botao_finalizar').show();
        $('#div_img').hide();

      }
    });

  }

  function validarBairroParaPagamento(selectedRadio) {
    var entrega = $('#entrega').val();

    if (entrega === 'Delivery') {
      var bairroPreenchido = false;
      var chave_api_maps = "<?= $chave_api_maps ?>";
      var entrega_distancia = "<?= $entrega_distancia ?>";

      if (chave_api_maps == "" || entrega_distancia != "Sim") {
        if ($('#bairro').val() !== "") {
          bairroPreenchido = true;
        }
      } else {
        if ($('#bairro').val() !== "" && $('#rua').val() !== "" && $('#numero').val() !== "" && $('#cidade').val() !== "" && $('#cep').val() !== "") {
          bairroPreenchido = true;
        }
      }

      if (!bairroPreenchido) {
        alert('Por favor, preencha seu endereço completo, com o bairro para entrega.');
        $(selectedRadio).prop('checked', false);
        return false;
      }
    }
    return true;
  }
</script>


<body class="payment-body">

  <main class="main-container container mt--90 payment-method">
    <div class="container payment">
      <div class="payment-mobile">
        <div class="payment-top">
          <div id="top-online" class="payment-option active" onclick="togglePaymentMethods('online')">
            Pagamento Online
          </div>
          <div id="top-entrega" class="payment-option" onclick="togglePaymentMethods('entrega')">
            Pagamento na Entrega
          </div>
        </div>
        <div class="payment-methods-box">
          <div class="payment-methods" id="box-online">
            <label>
              <input hidden onchange="pix()" type="radio" id="radio_pix" name="payment-method" value="pix">
              <i class="fa-brands fa-pix"></i>
              Pix
            </label>
          </div>
          <div class="payment-methods" id="box-entrega" style="display: none;">
            <label>
              <input hidden onchange="if(validarBairroParaPagamento(this)) dinheiro();" class="form-check-input" type="radio" name="payment-method" id="radio_dinheiro" value="dinheiro">
              <i class="fa-solid fa-money-bill"></i>
              Dinheiro
            </label>
            <label>
              <input hidden onchange="if(validarBairroParaPagamento(this)) debito();" class="form-check-input" type="radio" name="payment-method" id="radio_debito" value="debito">
              <i class="fa-solid fa-credit-card"></i>
              Cartão de Débito
            </label>
            <label>
              <input hidden onchange="if(validarBairroParaPagamento(this)) credito();" class="form-check-input" type="radio" name="payment-method" id="radio_credito" value="credito">
              <i class="fa-regular fa-credit-card"></i>
              Cartão de Crédito
            </label>
          </div>
        </div>
        <div class="d-flex flex-column gap-4">
          <div id="box-troco" style="display: none;">
            <div style="margin-top: -13px">
              <small>Precisa de Troco? </small>
              <div>
                <label class="label">Vou precisar de troco para:</label>
                <input placeholder="0,00" type="text" class="input" name="troco" id="troco">
              </div>
            </div>
          </div>
          <button id="payment-button" class="btn payment-btn disable">Revisar Pagamento</button>
        </div>
      </div>
    </div>
  </main>
  <!-- Modal Pix -->
  <div id="pix-modal-overlay" style="display: none;">
    <div id="pix-modal-box">
      <div id="pix-modal-content">

      </div>
    </div>
  </div>


  <?php require_once('./footer.php') ?>

</body>
<script defer src="./assets/js/plugins.js"></script>
<!-- custom js -->
<script defer src="./assets/js/main.js"></script>

<script>
  function togglePaymentMethods(method) {
    const online = document.getElementById('box-online');
    const toponline = document.getElementById('top-online');
    const entrega = document.getElementById('box-entrega');
    const topentrega = document.getElementById('top-entrega');

    if (method === 'online' && online.style.display === 'none') {
      entrega.style.display = 'none';
      topentrega.classList.remove('active');

      online.style.display = 'block';
      toponline.classList.add('active');
      uncheckedMethods();
    } else if (method === 'entrega' && entrega.style.display === 'none') {
      online.style.display = 'none';
      toponline.classList.remove('active');

      entrega.style.display = 'block';
      topentrega.classList.add('active');
      uncheckedMethods()
    }
  }

  function closePixModal() {
    $('#pix-modal-overlay').fadeOut(200);
    uncheckedMethods();
  }
</script>

</html>


<!-- jQery -->
<script src="js/jquery-3.4.1.min.js"></script>

<!-- Mascaras JS -->
<script type="text/javascript" src="js/mascaras.js"></script>

<!-- Ajax para funcionar Mascaras JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.min.js"></script>



<script>
  function copiar() {
    const chavePix = document.querySelector("#chave_pix_copia").value;

    navigator.clipboard.writeText(chavePix)
      .then(() => {
        alert('Chave Pix Copiada! Use a opção Copie e Cole para Pagar');
      })
      .catch(err => {
        console.error('Erro ao copiar: ', err);
      });
  }
  // document.querySelector("#chave_pix_copia").select();
  // document.querySelector("#chave_pix_copia").setSelectionRange(0, 99999); /* Para mobile */
  // document.execCommand("copy");
  // //$("#chave_pix_copia").hide();
  // alert('Chave Pix Copiada! Use a opção Copie e Cole para Pagar')
</script>