<?php
@session_start();
require_once("./cabecalho.php");
$sessao = @$_SESSION['sessao_usuario'];
$id_usuario = @$_SESSION['id'];


$total_carrinho = 0;
$total_carrinhoF = 0;
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
    $total_carrinhoF = number_format($total_carrinho, 2, ',', '.');
  }
}


$esconder_opc_delivery = '';
$valor_entrega = '';
$clicar_sim = '#collapseTwo';
$numero_colapse = '4';

$taxa_entregaF = 0;
$taxa_entrega = 0;

$nome_cliente = "";
$tel_cliente = "";
$rua = "";
$numero = "";
$bairro = "";
$complemento = "";


if ($id_usuario != '') {

  $valor_entrega = 'Consumir Local';
  $tel_cliente = 'Mesa: ' . @$mesa_pedido;
  $esconder_opc_delivery = 'ocultar';
  $numero_colapse = '2';
}


?>

<body>

  <div class="main-container container mt--90">
    <div class="checkout-area rts-section-gap">
      <div class="container">
        <div class="row">
          <div class="col-lg-8 pr--40 pr_md--5 pr_sm--5 mt_md--30 mt_sm--30">
            <!-- Identificação -->
            <h3>Finalizar Pedido</h3>
            <div class="information-box">
              <i class="fa-light fa-user"></i>
              <div class="identication">
                <div class="info-box">
                  <span>Nome:</span>
                  <span id="name-span">Não informado</span>
                </div>
                <div class="info-box">
                  <span>Telefone:</span>
                  <span id="phone-span">(00) 0000-0000</span>
                </div>
              </div>
              <i onclick="openModal('modal-info')" class="fa-solid fa-pen-to-square edit-button"></i>
            </div>
            <!-- Modo de Entrega -->
            <div class="shipping-fin">
              <span>Entrega</span>
              <ul>
                <li>
                  <input onchange="entrega()" type="radio" id="radio_entrega" name="selector" value="Delivery">
                  <label for="radio_entrega">Entrega Delivery</label>
                  <div class="check">
                    <div class="inside"></div>
                  </div>
                </li>
                <li>
                  <input hidden onchange="local()" type="radio" name="radio_local" id="radio_local">
                  <input onchange="retirar()" type="radio" id="radio_retirar" name="selector" value="pickup">
                  <label for="radio_retirar">Retirar no Local</label>
                  <div class="check">
                    <div class="inside"></div>
                  </div>
                </li>
              </ul>
            </div>
            <!-- Endereço -->
            <div class="information-box adress-box">
              <i class="fa-solid fa-location-dot"></i>
              <div class="address">
                <span id="adress-span">Receber em: </span>
                <input id="adress-input" readonly type="text" value="Endereço não informado">
              </div>
              <i onclick="openModal('modal-address')" class="fa-solid fa-pen-to-square edit-button"></i>
            </div>
            <!-- Selecionar Metodo de Pagamento -->
            <div class="bottom-cupon-code-cart-area">
              <div>
                <input id="cupom" type="text" placeholder="Código Promocional">
                <button onclick="cupom()" class="rts-btn btn-primary">Aplicar cupom</button>
              </div>
            </div>
            <div class="single-input">
              <label for="obs">Observações*</label>
              <textarea maxlength="300" id="obs" placeholder="Informações para o distribuidor"></textarea>
            </div>
          </div>
          <div class="col-lg-4 order-1 order-xl-2 order-lg-1 order-md-1 order-sm-1 checkout-box">
            <div class="right-card-sidebar-checkout">
              <div class="top-wrapper">
                <div class="product">
                  Qtd
                </div>
                <div class="product">
                  Produtos
                </div>
                <div class="price">
                  Preço
                </div>
              </div>
              <div class="finalizacao-itens">
                <div id="listar-itens-carrinho-finalizacao">

                </div>
              </div>
              <div class="cottom-cart-right-area">
                <div>
                  <a href="./carrinho" class="finalizacao-link">Alterar Produtos</a>
                </div>
                <div class="checkout-total">
                  <span id="area-taxa">
                    <span class="previsao_entrega">Taxa de Entrega: <span class="text-danger">R$ <span id="taxa-entrega"></span> </span></span>
                    <span class="previsao_entrega mx-2">Previsão <?php echo $previsao_entrega ?> Minutos</span>
                  </span>

                  <div>
                    <span><b>TOTAL À PAGAR</b></span>
                    <span class="direita"> <b>R$ <span id="total-carrinho-finalizar"><?php echo $total_carrinhoF ?></span></b></span>
                  </div>
                </div>
                <button onclick="finalizarPedido()" class="rts-btn btn-primary">Selecionar forma de pagamento</button>
              </div>
            </div>
          </div>
        </div>
      </div>


      <!-- Usando na função calcular frete importante -->
      <input type="hidden" id="entrega" value="<?php echo $valor_entrega ?>">
      <input type="hidden" id="valor-cupom">
      <!-- ^^^^^^^^ -->
      <input type="hidden" id="pagamento">
      <input type="hidden" id="taxa-entrega-input">
      <input type="hidden" id="id_cliente">


      <!-- Modal de Feedback -->
      <div id="modal-feedback" class="feedback-modal">
        <div class="feedback-modal-box">
          <span class="feedback-close" onclick="fecharModalFeedback()">&times;</span>
          <div id="feedback-icon" class="feedback-icon"></div>
          <h3 id="feedback-title" class="feedback-title">Título</h3>
          <p id="feedback-message" class="feedback-message">Mensagem do feedback</p>
          <div class="feedback-actions">
            <button class="feedback-btn" onclick="fecharModalFeedback()">Fechar</button>
          </div>
        </div>
      </div>
      <!-- modal info pessoal -->
      <div id="modal-info" class="modal">
        <div class="modal-content">
          <span class="close" onclick="closeModal('modal-info')">&times;</span>
          <h2>Editar Nome e Telefone</h2>
          <form id="form-info">
            <label for="name">Nome:</label>
            <input onclick="buscarNome()" type="text" class="input" name="nome" id="nome" value="" placeholder="Seu Nome" oninput="this.value = this.value.replace(/[^a-zA-Z\s]/g, '')" autocomplete="off">

            <label for="phone">Telefone:</label>
            <input onkeyup="buscarNome()" type="text" class="input telefone_user" name="telefone" id="telefone" value="" placeholder="(00) 00000-0000" autocomplete="off" maxlength="15">
            <span id="erro-personal" style="color: red; font-size: 14px;"></span>
            <button class="btn-primary" type="submit">Salvar</button>
          </form>
        </div>
      </div>

      <!-- modal endereco -->
      <div id="modal-address" class="modal">
        <div class="modal-content">
          <span class="close" onclick="closeModal('modal-address')">&times;</span>
          <h2>Editar Endereço</h2>
          <form id="form-address">

            <?php if ($entrega_distancia == "Sim" and $chave_api_maps != "") { ?>
              <div>
                <label class="label">CEP*</label>
                <input type="text" class="input" name="cep" id="cep" required onblur="calcularFreteDistancia()">
              </div>
            <?php } ?>
            <div>
              <label for="rua">Rua*</label>
              <input id="rua" type="text">
            </div>
            <label for="numero">Número*</label>
            <input id="numero" name="numero" type="text">
            <div>
              <div>
                <label for="">Complemento</label>
                <input type="text" class="input" name="complemento" id="complemento">
              </div>
              <?php if ($entrega_distancia != "Sim" or $chave_api_maps == "") { ?>
                <div>
                  <select class="address-select" name="bairro" id="bairro" onchange="calcularFrete()">
                    <option value="">Selecione um Bairro</option>
                    <?php
                    $query = $pdo->query("SELECT * FROM bairros ORDER BY id asc");
                    $res = $query->fetchAll(PDO::FETCH_ASSOC);
                    $total_reg = @count($res);
                    if ($total_reg > 0) {
                      for ($i = 0; $i < $total_reg; $i++) {
                        foreach ($res[$i] as $key => $value) {
                        }
                        $valor = $res[$i]['valor'];
                        $valorF = 'R$ ' . number_format($valor, 2, ',', '.');

                        if ($res[$i]['nome'] == $bairro) {
                          $classe_bairro = 'selected';
                        } else {
                          $classe_bairro = '';
                        }
                        echo '<option value="' . $res[$i]['nome'] . '" ' . $classe_bairro . '>' . $res[$i]['nome'] . ' - ' . $valorF . '</option>';
                      }
                    } else {
                      echo '<option value="">Cadastre um Bairro</option>';
                    }
                    ?>
                  </select>
                </div>
              <?php } else { ?>
                <div>
                  <label class="label">Bairro*</label>
                  <input type="text" class="input" name="bairro" id="bairro" required>
                </div>
                <div>
                  <input type="text" class="input" name="cidade" id="cidade">
                  <span class="highlight"></span>
                  <span class="bar"></span>
                  <label class="label">Cidade*</label>
                </div>
              <?php } ?>
            </div>
            <span id="erro-endereco" style="color: red; font-size: 14px;"></span>
            <button class="btn-primary" type="submit">Salvar</button>
          </form>
        </div>
      </div>
    </div>
  </div>
        <?php require_once('./footer.php') ?>
</body>
<script defer src="./assets/js/plugins.js"></script>
<!-- custom js -->
<script defer src="./assets/js/main.js"></script>

</html>


<!-- jQery -->
<script src="js/jquery-3.4.1.min.js"></script>

<!-- Mascaras JS -->
<script type="text/javascript" src="js/mascaras.js"></script>

<!-- Ajax para funcionar Mascaras JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.min.js"></script>


<script src="./assets/js/custom.js"></script>

<script type="text/javascript">
  $(document).ready(function() {

    document.getElementById('area-taxa').style.display = "none";
  });


  function retirar() {
    resetarFormaPagamento(); // NOVO: Reseta o pagamento ao mudar a forma de entrega
    document.getElementById('radio_retirar').checked = true;
    document.getElementById('radio_local').checked = false;
    document.getElementById('radio_entrega').checked = false;
    $('#entrega').val('Retirar');

    document.getElementById('area-taxa').style.display = "none";
    calcularFrete();
    // A chamada pix() aqui já não é mais necessária, pois o Pix só será gerado se o cliente clicar nele.
  }

  function local() {
    resetarFormaPagamento(); // NOVO: Reseta o pagamento ao mudar a forma de entrega
    document.getElementById('radio_retirar').checked = false;
    document.getElementById('radio_local').checked = true;
    document.getElementById('radio_entrega').checked = false;
    $('#entrega').val('Consumir Local');
    document.getElementById('area-taxa').style.display = "none";
    calcularFrete();
    // A chamada pix() aqui já não é mais necessária, pois o Pix só será gerado se o cliente clicar nele.
  }


  function entrega() {
    resetarFormaPagamento(); // NOVO: Reseta o pagamento ao mudar a forma de entrega
    document.getElementById('radio_retirar').checked = false;
    document.getElementById('radio_local').checked = false;
    document.getElementById('radio_entrega').checked = true;
    $('#entrega').val('Delivery');

    document.getElementById('area-taxa').style.display = "inline-block";

    var chave_api_maps = "<?= $chave_api_maps ?>";
    var entrega_distancia = "<?= $entrega_distancia ?>";

    if (chave_api_maps == "" || entrega_distancia != "Sim") {
      calcularFrete();
    } else {
      calcularFreteDistancia();
    }

    // setTimeout(pix, 1000); 

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

    var valor = total_compra_final.toFixed(2)

    $.ajax({
      url: 'js/ajax/pix.php',
      method: 'POST',
      data: {
        valor
      },
      dataType: "html",

      success: function(result) {
        $('#listar_pix').html(result);
      }
    });

    // Garante que apenas o Pix esteja marcado
    document.getElementById('radio_credito').checked = false;
    document.getElementById('radio_debito').checked = false;
    document.getElementById('radio_dinheiro').checked = false;

    // Define a forma de pagamento como Pix
    $('#pagamento').val('Pix');

    // Desabilita os outros rádios *imediatamente* após selecionar Pix
    $('#radio_dinheiro').prop('disabled', true);
    $('#radio_credito').prop('disabled', true);
    $('#radio_debito').prop('disabled', true);


    document.getElementById('pagar_pix').style.display = "block";
    document.getElementById('pagar_debito').style.display = "none";
    document.getElementById('pagar_credito').style.display = "none";
    document.getElementById('pagar_dinheiro').style.display = "none";
    document.getElementById('area-obs').style.display = "block";
  }

  function dinheiro() {
    // Habilita todos os rádios (para garantir que possam ser desmarcados ou outros habilitados)
    $('#radio_pix').prop('disabled', false);
    $('#radio_credito').prop('disabled', false);
    $('#radio_debito').prop('disabled', false);

    document.getElementById('radio_credito').checked = false;
    document.getElementById('radio_debito').checked = false;
    document.getElementById('radio_pix').checked = false;

    $('#pagamento').val('Dinheiro');

    document.getElementById('pagar_pix').style.display = "none";
    document.getElementById('pagar_debito').style.display = "none";
    document.getElementById('pagar_credito').style.display = "none";
    document.getElementById('pagar_dinheiro').style.display = "block";
    document.getElementById('area-obs').style.display = "block";
  }

  function debito() {
    // Habilita todos os rádios
    $('#radio_pix').prop('disabled', false);
    $('#radio_credito').prop('disabled', false);
    $('#radio_dinheiro').prop('disabled', false);

    document.getElementById('radio_credito').checked = false;
    document.getElementById('radio_pix').checked = false;
    document.getElementById('radio_dinheiro').checked = false;

    $('#pagamento').val('Cartão de Débito');

    document.getElementById('pagar_pix').style.display = "none";
    document.getElementById('pagar_debito').style.display = "block";
    document.getElementById('pagar_credito').style.display = "none";
    document.getElementById('pagar_dinheiro').style.display = "none";
    document.getElementById('area-obs').style.display = "block";
  }


  function credito() {
    // Habilita todos os rádios
    $('#radio_pix').prop('disabled', false);
    $('#radio_debito').prop('disabled', false);
    $('#radio_dinheiro').prop('disabled', false);

    document.getElementById('radio_pix').checked = false;
    document.getElementById('radio_debito').checked = false;
    document.getElementById('radio_dinheiro').checked = false;

    $('#pagamento').val('Cartão de Crédito');

    document.getElementById('pagar_pix').style.display = "none";
    document.getElementById('pagar_debito').style.display = "none";
    document.getElementById('pagar_credito').style.display = "block";
    document.getElementById('pagar_dinheiro').style.display = "none";
    document.getElementById('area-obs').style.display = "block";
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
      $('#telefone').focus();
      $('#botao_finalizar').show();
      $('#div_img').hide();
      return;
    }


    if (nome == "") {
      alert('Preencha seu Nome');
      $('#nome').focus();
      $('#botao_finalizar').show();
      $('#div_img').hide();
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
    var pedido_whatsapp = "<?= $status_whatsapp ?>";
    var cupom = $('#valor-cupom').val();

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
        $('#cep').focus();
        $('#botao_finalizar').show();
        $('#div_img').hide();
        return;
      }
    }



    if (taxa_entrega == "" && id_usuario == "") {
      alert("Digite um CEP válido para receber seu Pedido");
      $('#botao_finalizar').show();
      $('#div_img').hide();
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


    var total_compra = "<?= $total_carrinho ?>";
    var pedido_minimo = "<?= $pedido_minimo ?>";


    if (pedido_minimo > 0) {
      if (parseFloat(total_compra) < parseFloat(pedido_minimo)) {
        alert('Seu pedido precisar ser superior a R$' + pedido_minimo);
        $('#botao_finalizar').show();
        $('#div_img').hide();
        return;
      }
    }

    if (entrega == "") {
      alert('Selecione uma forma de entrega');
      $('#colapse-2').click();
      $('#botao_finalizar').show();
      $('#div_img').hide();
      return;
    }



    if (entrega == "Delivery" && rua == "") {
      alert('Preencha o Campo Rua');
      $('#colapse-3').click();
      $('#botao_finalizar').show();
      $('#div_img').hide();

      return;
    }

    if (entrega == "Delivery" && numero == "") {
      alert('Preencha o Campo Número');
      $('#colapse-3').click();
      $('#botao_finalizar').show();
      $('#div_img').hide();
      return;
    }

    if (entrega == "Delivery" && bairro == "") {
      alert('Escolha um Bairro');
      $('#colapse-3').click();
      $('#botao_finalizar').show();
      $('#div_img').hide();
      return;
    }



    if (pagamento == "") {
      alert('Selecione uma forma de pagamento');
      $('#botao_finalizar').show();
      $('#div_img').hide();
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
              var id = msg[2].trim();
              window.open("sistema/painel/rel/comprovante2_class.php?id=" + id + "&enviar=sim");
              window.location = 'index.php';
            } else {
              window.location = 'sistema/painel/index.php?pagina=novo_pedido';
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




  function calcularFrete() {

    var bairro = $('#bairro').val();
    var total_compra = "<?= $total_carrinho ?>"; // Valor inicial dos produtos
    var entrega = $('#entrega').val();
    var cupom = $('#valor-cupom').val(); // Pega o valor do cupom

    if (cupom == "") { // Se não houver cupom, define como 0
      cupom = 0;
    }

    $.ajax({
      url: 'js/ajax/calcular-frete.php',
      method: 'POST',
      data: {
        bairro,
        total_compra,
        entrega
      },
      dataType: "html",

      success: function(result) {
        var split = result.split("-");
        var taxa_entrega_nova = parseFloat(split[0].replace(',', '.')); // Converte para número
        var total_carrinho_e_frete_sem_cupom = parseFloat(split[1].replace(',', '.')); // Total (carrinho + frete) sem considerar o cupom

        // Calcula o novo total com o cupom aplicado
        var total_final_com_cupom = total_carrinho_e_frete_sem_cupom - parseFloat(cupom);

        $('#taxa-entrega').text(taxa_entrega_nova.toLocaleString('pt-br', {
          minimumFractionDigits: 2
        }));
        $('#total-carrinho-finalizar').text(total_final_com_cupom.toLocaleString('pt-br', {
          minimumFractionDigits: 2
        })); // ATUALIZA O TOTAL NA TELA COM O CUPOM
        $('#taxa-entrega-input').val(taxa_entrega_nova.toFixed(2)); // Armazena a taxa de entrega formatada para 2 casas decimais

        // SE O PIX ESTIVER SELECIONADO, ATUALIZA O QR CODE
        if ($('#radio_pix').is(':checked')) {
          pix();
        }
      }
    });
  }

  function resetarFormaPagamento() {
    // Desmarca todos os radio buttons de pagamento
    $('#radio_pix').prop('checked', false);
    $('#radio_dinheiro').prop('checked', false);
    $('#radio_credito').prop('checked', false);
    $('#radio_debito').prop('checked', false);

    // Limpa o valor da variável de controle de pagamento
    $('#pagamento').val('');

    // Oculta todas as áreas de detalhe de pagamento
    $('#pagar_pix').hide();
    $('#pagar_dinheiro').hide();
    $('#pagar_credito').hide();
    $('#pagar_debito').hide();
    $('#area-obs').hide();

    // Habilita todos os radio buttons de pagamento para que possam ser selecionados novamente
    $('#radio_pix').prop('disabled', false);
    $('#radio_dinheiro').prop('disabled', false);
    $('#radio_credito').prop('disabled', false);
    $('#radio_debito').prop('disabled', false);

    // Remove qualquer QR Code Pix gerado anteriormente
    $('#listar_pix').html('');
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


<script>
  function copiar() {
    document.querySelector("#chave_pix_copia").select();
    document.querySelector("#chave_pix_copia").setSelectionRange(0, 99999); /* Para mobile */
    document.execCommand("copy");
    //$("#chave_pix_copia").hide();
    alert('Chave Pix Copiada! Use a opção Copie e Cole para Pagar')
  }
</script>



<script type="text/javascript">
  function buscarNome() {

    var tel = $('#telefone').val();

    var nome = $('#nome').val();

    $.ajax({
      url: 'js/ajax/listar-nome.php',
      method: 'POST',
      data: {
        tel
      },
      dataType: "text",

      success: function(result) {
        //alert(result)  
        $('#nome').show();
        var split = result.split("**");

        // if(nome == ""){
        //   $('#nome').val(split[0]); 
        // }

        $('#rua').val(split[1]);
        $('#numero').val(split[2]);
        $('#bairro').val(split[3]).change();
        $('#complemento').val(split[4]);
        $('#taxa-entrega-input').val(split[5]);
        $('#taxa-entrega').text(split[6]);
        $('#id_cliente').text(split[7]);
        $('#cep').val(split[8]);
        $('#cidade').val(split[9]);
      }
    });
  }
</script>




<script type="text/javascript">
  function dados() {
    // $('#nome').show();

    var nome = $('#nome').val();
    var telefone = $('#telefone').val();
    var id_usuario = "<?= $id_usuario ?>";

    if (id_usuario == "") {
      if (telefone.length < 15) {
        alert("Coloque o número de telefone completo");
        return;
      }
    }

    if (telefone == "") {
      alert('Preencha seu Telefone');
      $('#telefone').focus();
      return;
    }

    if (nome == "") {
      alert('Preencha seu Nome');
      $('#telefone').focus();
      return;
    }

    if (id_usuario != "") {
      $('#collapse-4').click();
    } else {
      $('#colapse-2').click();
    }

  }




  function cupom() {
    var total_compra = "<?= $total_carrinho ?>";
    var taxa_entrega = $('#taxa-entrega-input').val();
    if (taxa_entrega == "") {
      taxa_entrega = 0;
    }
    var total_final = parseFloat(total_compra) + parseFloat(taxa_entrega);
    var codigo_cupom = $('#cupom').val();
    if (codigo_cupom == "") {
      alert("Preencha o código do cupom");
      return;
    }

    $.ajax({
      url: 'js/ajax/cupom.php',
      method: 'POST',
      data: {
        total_final,
        codigo_cupom
      },
      dataType: "text",

      success: function(mensagem) {
        var split = mensagem.split('**')

        if (split[0].trim() == '0') {
          alert('Código do Cupom Inválido');
        } else if (split[0].trim() == '1') {
          alert('Este cupom está vencido!');
        } else if (split[0].trim() == '2') {
          alert('Este cupom não é mais válido!');
        } else if (split[0].trim() == '3') {
          alert('Este cupom só é válido para compras acima de R$' + split[1]);
        } else {

          $('#total-carrinho-finalizar').text(split[0]);
          $('#valor-cupom').val(split[1]);
          $('#div_cupom').hide();
          alert('Cupom Inserido!');
          if ($('#radio_pix').is(':checked')) {
            pix();
          }
        }

      },

    });
  }
</script>



<script>
  var restaurantCoords = {
    latitude: "<?= $latitude_rest ?>",
    longitude: "<?= $longitude_rest ?>"
  };


  document.getElementById('cep').addEventListener('input', function(event) {
    var cep = event.target.value.replace(/\D/g, '');

    if (cep.length === 8) {
      fetchAddressData(cep);
    }
  });

  function calcularFreteDistancia() {

    var chave_api = "<?= $chave_api_maps ?>";
    var distancia_km = "<?= $distancia_entrega_km ?>";

    event.preventDefault();
    var cep = document.getElementById('cep').value;
    var address = document.getElementById('rua').value;
    var number = document.getElementById('numero').value;
    var bairro = document.getElementById('bairro').value;
    var cidade = document.getElementById('cidade').value;
    var complement = document.getElementById('complemento').value;

    var total_compra_produtos = "<?= $total_carrinho ?>"; // Valor original dos produtos
    var cupom = $('#valor-cupom').val(); // Pegar o valor do cupom

    if (cupom == "") { // Se não houver cupom, define como 0
      cupom = 0;
    }

    if (cep == "") {
      return;
    }

    var encodedAddress = encodeURIComponent(address + ', ' + number + ', ' + bairro + ', ' + cidade + ', ' + complement);

    fetch('https://maps.googleapis.com/maps/api/geocode/json?address=' + encodedAddress + '&key=' + chave_api)
      .then(function(response) {
        return response.json();
      })
      .then(function(data) {
        if (data.status === 'OK' && data.results.length > 0) {

          var deliveryCoords = {
            latitude: data.results[0].geometry.location.lat,
            longitude: data.results[0].geometry.location.lng
          };

          var distance = calculateDistance(restaurantCoords, deliveryCoords);

          if (distance <= distancia_km) {
            var deliveryCost = calculateDeliveryCost(distance);

            // Calcula o total com a nova taxa de entrega e o desconto do cupom
            var total_final_com_entrega_e_cupom = parseFloat(total_compra_produtos) + deliveryCost - parseFloat(cupom);

            $('#taxa-entrega').text(deliveryCost.toLocaleString('pt-br', {
              minimumFractionDigits: 2
            }));
            $('#taxa-entrega-input').val(deliveryCost.toFixed(2)); // Armazena a taxa de entrega formatada para 2 casas decimais

            $('#total-carrinho-finalizar').text(total_final_com_entrega_e_cupom.toLocaleString('pt-br', {
              minimumFractionDigits: 2
            })); // ATUALIZA O TOTAL NA TELA COM O CUPOM

            // SE O PIX ESTIVER SELECIONADO, ATUALIZA O QR CODE
            if ($('#radio_pix').is(':checked')) {
              pix();
            }

          } else {
            alert('Endereço fora da área de entrega.');
            return;
          }
        } else {
          alert('Endereço inválido ou Chave Api Inexistente.');
          return;
        }
      })
      .catch(function(error) {
        console.error(error);
      });
  }

  function fetchAddressData(cep) {
    fetch('https://viacep.com.br/ws/' + cep + '/json/')
      .then(function(response) {
        return response.json();
      })
      .then(function(data) {
        if (!data.erro) {
          document.getElementById('rua').value = data.logradouro;
          document.getElementById('bairro').value = data.bairro;
          document.getElementById('cidade').value = data.localidade;
          document.getElementById('numero').focus();

        }
      })
      .catch(function(error) {
        console.error(error);
      });
  }

  function calculateDistance(coord1, coord2) {
    var lat1 = toRadians(coord1.latitude);
    var lon1 = toRadians(coord1.longitude);
    var lat2 = toRadians(coord2.latitude);
    var lon2 = toRadians(coord2.longitude);

    var R = 6371; // Raio da Terra em quilômetros

    var dLat = lat2 - lat1;
    var dLon = lon2 - lon1;

    var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
      Math.cos(lat1) * Math.cos(lat2) *
      Math.sin(dLon / 2) * Math.sin(dLon / 2);
    var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

    var distance = R * c;
    return distance;
  }

  function toRadians(degrees) {
    return degrees * (Math.PI / 180);
  }

  function calculateDeliveryCost(distance) {
    var valor_km = "<?= $valor_km ?>";
    if (valor_km == "" || valor_km <= 0) {
      valor_km = 1;
    }
    var roundedDistance = Math.ceil(distance);
    return roundedDistance * valor_km;

  }
</script>


<script type="text/javascript">
  $(document).ready(function() {
    listarCarrinho()
  });

  function listarCarrinho() {

    $.ajax({
      url: 'js/ajax/listar-itens-carrinho-finalizacao.php',
      method: 'POST',
      data: {},
      dataType: 'html',
      success: function(result) {
        $('#listar-itens-carrinho-finalizacao').html(result);

      }
    });
  }
</script>


<script>
  function getDadosPedido() {
    const dadosPedido = {
        nome: $('#nome').val()?.trim(),
        telefone: $('#telefone').val()?.trim(),
        id_cliente: $('#id_cliente').val()?.trim(),
        mesa: $('#mesa').val()?.trim(),
        entrega: $('#entrega').val()?.trim(),
        cep: $('#cep').val()?.trim(),
        rua: $('#rua').val()?.trim(),
        numero: $('#numero').val()?.trim(),
        complemento: $('#complemento').val()?.trim(),
        bairro: $('#bairro').val()?.trim(),
        cidade: $('#cidade').val()?.trim(),
        'taxa-entrega-input': $('#taxa-entrega-input').val()?.trim(),
        cupom: $('#cupom').val()?.trim(),
        'valor-cupom': $('#valor-cupom').val()?.trim(),
        obs: $('#obs').val()?.trim().substring(0,350),
        'total-carrinho-finalizar': $('#total-carrinho-finalizar').text()?.trim()
      }
    
    // Campos obrigatórios
    const camposObrigatorios = [{
        campo: dadosPedido.nome,
        nome: "Nome"
      },
      {
        campo: dadosPedido.telefone,
        nome: "Telefone"
      },
      {
        campo: dadosPedido.entrega,
        nome: "Tipo de Entrega"
      },
      {
        campo: dadosPedido['total-carrinho-finalizar'],
        nome: "Total do Pedido"
      }
    ];

    if (dadosPedido.entrega.toLowerCase() === "delivery") {
      camposObrigatorios.push({
        campo: dadosPedido.rua,
        nome: "Rua"
      }, {
        campo: dadosPedido.numero,
        nome: "Número"
      }, {
        campo: dadosPedido.bairro,
        nome: "Bairro"
      });
    }

    // Verifica campos faltando
    const faltando = camposObrigatorios.filter(item => !item.campo || item.campo === "");

    if (faltando.length > 0) {
      const nomesFaltando = faltando.map(item => item.nome).join(', ');
      return {
        erro: true,
        mensagem: `Os seguintes campos estão faltando: ${nomesFaltando}`,
        campos: faltando.map(item => item.nome)
      };
    }
    
    return {
      erro: false,
      dados: dadosPedido
    };
  }


  async function finalizarPedido() {
    const resultado = getDadosPedido();

    if (resultado.erro) {
      abrirModalFeedback('warning', 'Campos faltando:', resultado.campos.join(', '))
      console.warn("Campos faltando:", resultado.campos);
      return;
    }
    console.log(resultado);
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = './payment-method.php';
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'pedido';
    input.value = JSON.stringify(resultado);
    form.appendChild(input);
    document.body.appendChild(form);
    form.submit();
  }


  function abrirModalFeedback(tipo, titulo, mensagem) {
    const modal = document.getElementById('modal-feedback');
    const icon = document.getElementById('feedback-icon');
    const title = document.getElementById('feedback-title');
    const message = document.getElementById('feedback-message');

    // Reset e atribuição de tipo
    icon.className = 'feedback-icon';
    if (tipo === 'success') {
      icon.classList.add('success');
      icon.innerHTML = '✔';
    } else if (tipo === 'error') {
      icon.classList.add('error');
      icon.innerHTML = '✖';
    } else if (tipo === 'warning') {
      icon.classList.add('warning');
      icon.innerHTML = '⚠';
    }

    title.textContent = titulo;
    message.textContent = mensagem;

    modal.style.display = 'flex';
  }

  function fecharModalFeedback() {
    document.getElementById('modal-feedback').style.display = 'none';
  }

  // Fechar clicando fora da caixa
  window.addEventListener('click', function(event) {
    const modal = document.getElementById('modal-feedback');
    if (event.target === modal) modal.style.display = 'none';
  });


  // Pegando os elementos
  const deliveryRadio = document.getElementById('radio_entrega');
  const pickupRadio = document.getElementById('radio_retirar');

  const addressSpan = document.querySelector('#adress-span');
  const addressBox = document.querySelector('.adress-box');
  const editButton = addressBox.querySelector('.edit-button');
  const addressInput = document.getElementById('adress-input');

  function updateAddressBox() {
    if (pickupRadio.checked) {
      // Pickup selecionado
      addressBox.style.display = 'flex';
      addressSpan.textContent = "Retirar no Local:";
      addressInput.value = "<?php echo $endereco_sistema ?>";

      addressInput.readOnly = true;
      editButton.style.display = 'none';
    } else if (deliveryRadio.checked) {
      // Delivery selecionado
      addressBox.style.display = 'flex';
      addressSpan.textContent = "Receber em:";
      addressInput.value = "Endereço não informado";
      addressInput.readOnly = false;
      editButton.style.display = 'inline-block';
    }
  }

  // Adiciona event listeners aos radios
  deliveryRadio.addEventListener('change', updateAddressBox);
  pickupRadio.addEventListener('change', updateAddressBox);

  // Inicializa a exibição correta ao carregar a página
  updateAddressBox();
</script>