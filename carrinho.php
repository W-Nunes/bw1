<?php
@session_start();
require_once("cabecalho.php");

$sessao = @$_SESSION['sessao_usuario'];

?>

<style type="text/css">
  body {
    background: #f2f2f2;
  }
</style>

<div class="rts-cart-area rts-section-gap bg_light-1 mt--90">
  <div class="container">
    <div class="row g-5">
      <div class="col-xl-9 col-lg-8 col-md-12 col-12 ">
        <div class="cart-area-main-wrapper">
          <!-- <div class="cart-top-area-note">
            <p>Add <span>R$59.69</span> to cart and get free shipping</p>
            <div class="bottom-content-deals mt--10">
              <div class="single-progress-area-incard">
                <div class="progress">
                  <div class="progress-bar wow fadeInLeft" role="progressbar" style="width: 80%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
              </div>
            </div>
          </div> -->
        </div>
        <div class="rts-cart-list-area">
          <div class="single-cart-area-list head">
            <div class="product-main">
              <p>Produto</p>
            </div>
            <div class="price">
              <p>Preço</p>
            </div>
            <div class="quantity">
              <p>Quantidade</p>
            </div>
            <div class="subtotal">
              <p>SubTotal</p>
            </div>
          </div>
          <div id="listar-itens-carrinho">

          </div>
      
        </div>
      </div>
      <div class="col-xl-3 col-lg-4 col-md-12 col-12">
        <div class="cart-total-area-start-right">
          <h5 class="title">Total carrinho</h5>
          <div class="subtotal">
            <span>Subtotal</span>
            <h6 class="price">R$ <span class="price" id="total-do-pedido-1"></span></h6>
          </div>
          
          <div class="bottom">
            <div class="button-area">
              <a href="./finalizar.php">
                <button class="rts-btn btn-primary">Finalizar compra</button>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>



<?php require_once('./footer.php')?>

</body>

</html>



<script type="text/javascript">
  $(document).ready(function() {
    listarCarrinho()
  });

  function listarCarrinho() {

    $.ajax({
      url: 'js/ajax/listar-itens-carrinho.php',
      method: 'POST',
      data: {},
      dataType: 'html',
      success: function(result) {
        $('#listar-itens-carrinho').html(result);

      }
    });
  }
</script>
<script type="text/javascript">
  function excluirCarrinho() {

    $.ajax({
      url: 'js/ajax/excluir-carrinho.php',
      method: 'POST',
      data: {},
      dataType: 'text',
      success: function(result) {
        listarCarrinho();
        listarCarrinhoIcone();
      }
    });
  }
</script>


<!-- <script type="text/javascript">
  $("#form-obs").submit(function() {

    event.preventDefault();
    var formData = new FormData(this);

    $.ajax({
      url: 'js/ajax/editar-obs-carrinho.php',
      type: 'POST',
      data: formData,

      success: function(mensagem) {
        $('#mensagem-obs').text('');
        $('#mensagem-obs').removeClass()
        if (mensagem.trim() == "Salvo com Sucesso") {
          $('#btn-fechar-obs').click();
          listarCarrinho();

        } else {

          $('#mensagem-obs').addClass('text-danger')
          $('#mensagem-obs').text(mensagem)
        }


      },

      cache: false,
      contentType: false,
      processData: false,

    });

  });
</script> -->



<script>
  function finalizarCompra(event) {
    event.preventDefault(); // Impede o link ou form de recarregar a página

    // Pegando o valor do cupom
    const cupom = document.getElementById('cupom').value.trim();

    // Pegando o método de entrega selecionado
    const metodoEntrega = document.querySelector('input[name="selector"]:checked');

    if (!metodoEntrega) {
      alert('Por favor, selecione um método de entrega.');
      return;
    }

    const metodoSelecionado = metodoEntrega.value;

    fetch('./finalizar.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams({
          cupom: cupom,
          entrega: metodoSelecionado
        })
      })
      .then(response => response.text())
      .then(data => {
        console.log("Resposta do servidor:", data);

      })
      .catch(error => console.error('Erro:', error));
  }
</script>


<script>
  function cupom() {
    const fb = document.querySelector('#cupomReturn');
    var total_compra = "<?= $total_carrinho ?>";
    var taxa_entrega = $('#taxa-entrega-input').val();
    if (taxa_entrega == "") {
      taxa_entrega = 0;
    }
    var total_final = parseFloat(total_compra) + parseFloat(taxa_entrega);
    var codigo_cupom = $('#cupom').val();
    if (codigo_cupom == "") {
      fb.textContent = "Preencha o código do cupom";
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
          fb.textContent = 'Código do Cupom Inválido';
        } else if (split[0].trim() == '1') {
          fb.textContent = 'Este cupom está vencido!';
        } else if (split[0].trim() == '2') {
          fb.textContent = 'Este cupom não é mais válido!';
        } else if (split[0].trim() == '3') {
          fb.textContent = 'Este cupom só é válido para compras acima de R$' + split[1];
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