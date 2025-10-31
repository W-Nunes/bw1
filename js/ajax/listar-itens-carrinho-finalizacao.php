<?php
@session_start();
require_once('../../sistema/conexao.php');

$sessao = @$_SESSION['sessao_usuario'];

$nome_produto2 = '';
$query = $pdo->query("SELECT * FROM carrinho where sessao = '$sessao'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
$total_carrinho = 0;
$itemsHtml = '';
$total_carrinhoF = 0;
if ($total_reg > 0) {

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


        $id_sabor = $res[$i]['id_sabor'];

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

        //busca referente ao produto especifico

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

        $classe_borda = 'ocultar';
        if ($sabores > 0) {
            $nome_produto = $nome_produto_tab;
            $classe_borda = '';
        }


        $query8 = $pdo->query("SELECT * FROM bordas where id = '$borda'");
        $res8 = $query8->fetchAll(PDO::FETCH_ASSOC);
        $total_reg8 = @count($res8);
        if ($total_reg8 > 0) {
            $nome_borda = ' - ' . $res8[0]['nome'];
        } else {
            $nome_borda = '';
        }


        $query2 = $pdo->query("SELECT * FROM temp where carrinho = '$id' and tabela = 'Variação' order by id asc limit 1");
        $res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
        $id_do_item = @$res2[0]['id_item'];

        $query2 = $pdo->query("SELECT * FROM itens_grade where id = '$id_do_item'");
        $res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
        if (@$res2[0]['texto'] != "") {
            $sigla_grade = '<small> (' . @$res2[0]['texto'] . ')</small>';
        } else {
            $sigla_grade = '';
        }

        echo <<<HTML
            <div class="single-shop-list">
                <div>
                    <span>$quantidade</span>
                </div>
                <div class="left-area">
                  <a href="#" class="thumbnail-finalizacao">
                    <img src="./sistema/painel/images/produtos/$foto_produto" alt="">
                  </a>
                  <a href="#" class="title">
                    $nome_produto
                  </a>
                </div>
                <span class="price">R$ $valor_unitF</span>
            </div>
  HTML;
    }
} else {
}
