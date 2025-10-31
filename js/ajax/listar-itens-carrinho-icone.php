<?php
@session_start();
require_once('../../sistema/conexao.php');

$sessao = @$_SESSION['sessao_usuario'];


$query = $pdo->query("SELECT * FROM carrinho where sessao = '$sessao'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
$total_carrinho = 0;
$total_carrinhoF = 0;
if ($total_reg > 0) {
  echo '<div class="lista-itens-carrinho">';
  for ($i = 0; $i < $total_reg; $i++) {
    foreach ($res[$i] as $key => $value) {
    }

    $id = $res[$i]['id'];
    $total_item = $res[$i]['total_item'];
    $produto = $res[$i]['produto'];
    $quantidade = $res[$i]['quantidade'];
    $obs = $res[$i]['obs'];
    $item = $res[$i]['item'];
    $variacao = $res[$i]['variacao'];
    $nome_produto_tab = $res[$i]['nome_produto'];
    $sabores = $res[$i]['sabores'];
    $borda = $res[$i]['borda'];
    $categoria = $res[$i]['categoria'];
    $valor_unit = $res[$i]['valor_unitario'];

    if ($valor_unit == "") {
      if ($total_item > 0 and $quantidade > 0) {
        $valor_unit = $total_item / $quantidade;
      } else {
        $valor_unit = 0;
      }
    }

    $total_item = $total_item * $quantidade;
    $total_carrinho += $total_item;


    $total_itemF = number_format($total_item, 2, ',', '.');
    $valor_unitF = number_format($valor_unit, 2, ',', '.');
    $total_carrinhoF = number_format($total_carrinho, 2, ',', '.');

    $query2 = $pdo->query("SELECT * FROM variacoes where id = '$variacao'");
    $res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
    if (@count(@$res2) > 0) {
      $sigla_variacao = '(' . $res2[0]['sigla'] . ')';
    } else {
      $sigla_variacao = '';
    }


    $query2 = $pdo->query("SELECT * FROM produtos where id = '$produto'");
    $res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
    if (@count(@$res2) > 0) {
      $nome_produto = $res2[0]['nome'];
      $foto_produto = $res2[0]['foto'];
    } else {
      $nome_produto = $nome_produto_tab;
      $foto_produto = "";
    }


    if ($obs == '') {
      $classe_obs = 'text-warning';
    } else {
      $classe_obs = 'text-danger';
    }

    if ($sabores > 0) {
      $nome_produto = $nome_produto_tab;
    }

    echo <<<HTML

		<div class="cart-item-1 border-top">
                    <div class="img-name">
                      <div class="thumbanil">
                        <img src="sistema/painel/images/produtos/{$foto_produto}" alt="">
                      </div>
                      <div class="details">
                        <a href="shop-details.html">
                          <h5 class="title">{$nome_produto}</h5>
                        </a>
                        <div class="number">
                          {$quantidade}
                          <i class="fa-regular fa-x"></i>
                          <span>{$total_itemF}</span>
                        </div>
                      </div>
                    </div>
                    <div class="close-c1">
                      <i onclick="excluirCarrinhoIcone('{$id}')" class="fa-regular fa-x"></i>
                    </div>
                  </div>
HTML;
  }
  echo '</div>';

  echo <<<HTML
      <div class="sub-total-cart-balance">
          <div class="bottom-content-deals mt--10">
              <div class="top">
                  <span>Sub Total:</span>
                        <span class="number-c">R$  $total_carrinhoF </span>
                      </div>
                    </div>
              </div>
              <div id="cart-buttons" class="button-wrapper d-flex align-items-center justify-content-between">
                    <a href="./carrinho.php" class="rts-btn btn-primary button-cart-mobile ">Ver Carrinho</a>
                    <a href="./finalizar.php" class="rts-btn btn-primary border-only button-cart-mobile ">Finalizar Pedido</a>
              </div>
HTML;
} else {
  echo 'Nenhum item adicionado!';
}


?>

<script type="text/javascript">
  $("#total-carrinho-icone").text("<?= $total_carrinhoF ?>");
  $("#total-itens-carrinho").text("<?= $total_reg ?>");
  $("#total-carrinho-finalizar").text("<?= $total_carrinhoF ?>");


  function excluirCarrinhoIcone(id) {

    $.ajax({
      url: 'js/ajax/excluir-carrinho.php',
      method: 'POST',
      data: {
        id
      },
      dataType: "text",

      success: function(mensagem) {

        if (mensagem.trim() == "Excluido com Sucesso") {
          listarCarrinhoIcone();
        }

      },

    });
  }
</script>