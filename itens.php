<?php
// conexão + head básico do seu site (como já usava)
require_once("sistema/conexao.php");

// ===== LÓGICA ORIGINAL DO itens.php (mantida) =====
$url_categoria = $_GET['url'] ?? '';

$cat_nome = '';
$cat_descricao = '';
$cat_id = '';

if ($url_categoria != '') {
  $q = $pdo->query("SELECT * FROM categorias WHERE url = '$url_categoria'");
  $r = $q->fetchAll(PDO::FETCH_ASSOC);
  if (@count($r) > 0) {
    $cat_nome = $r[0]['nome'];
    $cat_descricao = $r[0]['descricao'];
    $cat_id = $r[0]['id'];

    @session_start();
    $sessao = @$_SESSION['sessao_usuario'];
    $pdo->query("DELETE FROM temp WHERE carrinho = 0 AND sessao = '$sessao'");
  }
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <link rel="stylesheet preload" href="css/style.css">

  <meta charset="UTF-8">
  <meta name="description" content="Listagem de produtos">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $cat_nome ? htmlspecialchars($cat_nome) . " - " : ""; ?>Loja</title>

  <!-- CSS do template shop-grid -->
  <link rel="shortcut icon" type="image/x-icon" href="assets/images/fav.png">
  <link rel="stylesheet preload" href="assets/css/plugins.css" as="style">
  <link rel="stylesheet preload" href="assets/css/style.css" as="style">
</head>


<?php // mantém seu cabeçalho padrão, se ele injeta scripts/menus globais
require_once("./cabecalho.php"); ?>
<script>
  function openMarca(marca) {
    $('#marca').slideUp(150); // esconde o menu de marcas
    $('[data-marca]').hide(); // esconde todos os cards
    $(`[data-marca="${marca}"]`).fadeIn(150); // mostra só os da marca
    $('#voltar').fadeIn(150);
  }

  // voltar: esconde todos os cards e volta a exibir o menu
  function voltarMarcas() {
    $('[data-marca]').hide();
    $('#marca').fadeIn(150);
    $('#voltar').hide();

  }
</script>

<body class="shop-grid-sidebar">

  <!-- breadcrumb / título simples -->
  <div class="rts-navigation-area-breadcrumb">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <?php if (!empty($cat_nome)) { ?>
            <div class="navigator-breadcrumb-wrapper" style="padding: 16px 0">
              <a href="index">Início</a>
              <i class="fa-regular fa-chevron-right"></i>
              <span class="current"><?php echo htmlspecialchars($cat_nome); ?></span>
            </div>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>

  <div class="section-seperator">
    <div class="container">
      <hr class="section-seperator">
    </div>
  </div>

  <!-- CATEGORIAS (mesmo visual do shop-grid) -->
  <div class="mt--60">

    <div class="rts-caregory-area-one">
      <div class="container mt-5">
        <div class="row">
          <div class="col-lg-12">
            <div class="category-area-main-wrapper-one">
              <div class="swiper mySwiper-category-1 swiper-data" data-swiper='{
                                "spaceBetween":12,
                                "slidesPerView":10,
                                "loop": true,
                                "speed": 1000,
                                "breakpoints": {
                                "0": { "slidesPerView": 2, "spaceBetween": 12 },
                                "320": { "slidesPerView": 2, "spaceBetween": 12 },
                                "480": { "slidesPerView": 3, "spaceBetween": 12 },
                                "640": { "slidesPerView": 4, "spaceBetween": 12 },
                                "840": { "slidesPerView": 4, "spaceBetween": 12 },
                                "1140": { "slidesPerView": 10, "spaceBetween": 12 }
                            }}'>
                <div class="swiper-wrapper">
                  <?php
                  $qCat = $pdo->query("SELECT * FROM categorias WHERE ativo = 'Sim'");
                  $resCat = $qCat->fetchAll(PDO::FETCH_ASSOC);
                  if (count($resCat) > 0) {
                    foreach ($resCat as $cat) {
                      $cat_foto = $cat['foto'];
                      $cat_nome2 = $cat['nome'];
                      $cat_url2  = $cat['url'];
                      $mais_sabores = $cat['mais_sabores'];
                      $link_categoria = ($mais_sabores == 'Sim') ? 'categoria-sabores-' . $cat_url2 : 'categoria-' . $cat_url2;
                  ?>
                      <div class="swiper-slide">
                        <a href="<?php echo $link_categoria; ?>" class="single-category-one">
                          <img src="sistema/painel/images/categorias/<?php echo htmlspecialchars($cat_foto); ?>" alt="category">
                          <p><?php echo htmlspecialchars($cat_nome2); ?></p>
                        </a>
                      </div>
                  <?php
                    }
                  }
                  ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div><!--/my-60-->

  <!-- GRID DE PRODUTOS (visual do shop-grid + função do itens.php) -->
  <div class="container">
    <div class="tab-content" id="myTabContent">
      <div class="product-area-wrapper-shopgrid-list mt--20 mb--20 tab-pan fade show active" id="home-tab-pane"
        role="tabpanel" aria-labelledby="home-tab" tabindex="0">
        <div class="row g-4">
          <?php
          // --- Consulta de produtos da categoria selecionada ---
          $qProd = $pdo->query("SELECT * FROM produtos WHERE categoria = '$cat_id' AND ativo = 'Sim'");
          $resProd = $qProd->fetchAll(PDO::FETCH_ASSOC);
          $totalProd = @count($resProd);

          // ---- Funções utilitárias para normalização ----
          function removerAcentos($string)
          {
            // Mapa simples de acentos mais comuns
            $acentos = [
              'á' => 'a',
              'à' => 'a',
              'ã' => 'a',
              'â' => 'a',
              'ä' => 'a',
              'å' => 'a',
              'é' => 'e',
              'è' => 'e',
              'ê' => 'e',
              'ë' => 'e',
              'í' => 'i',
              'ì' => 'i',
              'î' => 'i',
              'ï' => 'i',
              'ó' => 'o',
              'ò' => 'o',
              'õ' => 'o',
              'ô' => 'o',
              'ö' => 'o',
              'ú' => 'u',
              'ù' => 'u',
              'û' => 'u',
              'ü' => 'u',
              'ç' => 'c',
              'ñ' => 'n',
              'Á' => 'A',
              'À' => 'A',
              'Ã' => 'A',
              'Â' => 'A',
              'Ä' => 'A',
              'Å' => 'A',
              'É' => 'E',
              'È' => 'E',
              'Ê' => 'E',
              'Ë' => 'E',
              'Í' => 'I',
              'Ì' => 'I',
              'Î' => 'I',
              'Ï' => 'I',
              'Ó' => 'O',
              'Ò' => 'O',
              'Õ' => 'O',
              'Ô' => 'O',
              'Ö' => 'O',
              'Ú' => 'U',
              'Ù' => 'U',
              'Û' => 'U',
              'Ü' => 'U',
              'Ç' => 'C',
              'Ñ' => 'N'
            ];
            return strtr($string, $acentos);
          }

          function normalizarString($string)
          {
            $string = removerAcentos($string ?? '');
            return mb_strtolower($string, 'UTF-8');
          }
          // -----------------------------------------------------------------------------
          // BLOCO DE CATEGORIAS COM MARCAS (refrigerantes-e-sucos / cervejas)
          // -----------------------------------------------------------------------------
          if ($url_categoria === 'refrigerantes-e-sucos' || $url_categoria === 'cervejas') {

            $baseRefri   = "./img/marcas/refri";
            $baseCerveja = "./img/marcas/cervejas";
            $ext         = "webp";

            $marcasRefri = [
              ["name" => "Coca Cola",           "src" => "$baseRefri/coca-cola.svg"],
              ["name" => "Guaraná Antarctica",  "src" => "$baseRefri/guarana-antarctica.svg"],
              ["name" => "H2OH",                "src" => "$baseRefri/h2oh.jpg"],
              ["name" => "Pepsi",               "src" => "$baseRefri/pepsi.svg"],
              ["name" => "Soda Antarctica",     "src" => "$baseRefri/soda.jpg"],
              ["name" => "St. Pierre",          "src" => "$baseRefri/st-pierre.jpeg"],
              ["name" => "Sukita",              "src" => "$baseRefri/sukita.jpeg"],
              ["name" => "Kero Coco",           "src" => "$baseRefri/kero-coco.png"],
              ["name" => "Guaravita",           "src" => "$baseRefri/guaravita.png"],
              ["name" => "Guaraviton",          "src" => "$baseRefri/guaraviton.png"],
              ["name" => "Lipton",              "src" => "$baseRefri/lipton.png"],
            ];

            $marcasCervejas = [
              ["name" => "Antarctica",      "src" => "$baseCerveja/antarctica.svg"],
              ["name" => "Original",        "src" => "$baseCerveja/original.webp"],
              ["name" => "Becks",           "src" => "$baseCerveja/becks.svg"],
              ["name" => "Bohemia",         "src" => "$baseCerveja/bohemia.webp"],
              ["name" => "Brahma",          "src" => "$baseCerveja/brahma.webp"],
              ["name" => "Budweiser",       "src" => "$baseCerveja/budweiser.webp"],
              ["name" => "Caracu",          "src" => "$baseCerveja/caracu.svg"],
              ["name" => "Colorado",        "src" => "$baseCerveja/colorado.png"],
              ["name" => "Corona",          "src" => "$baseCerveja/corona.webp"],
              ["name" => "Heineken",        "src" => "$baseCerveja/heineken.svg"],
              ["name" => "Michelob Ultra",  "src" => "$baseCerveja/michelob-ultra.svg"],
              ["name" => "Goose Island",    "src" => "$baseCerveja/goose-island.svg"],
              ["name" => "Patagonia",       "src" => "$baseCerveja/patagonia.svg"],
              ["name" => "Skol",            "src" => "$baseCerveja/skol.webp"],
              ["name" => "Spaten",          "src" => "$baseCerveja/spaten.webp"],
              ["name" => "Stella Artois",   "src" => "$baseCerveja/stella-artois.webp"],
            ];

            // Usa a lista de marcas conforme a categoria
            $marcas = ($url_categoria === 'refrigerantes-e-sucos') ? $marcasRefri : $marcasCervejas;

            // (1) Pré-normaliza marcas para performance
            $marcasNorm = [];
            foreach ($marcas as $m) {
              $marcasNorm[] = [
                'name' => $m['name'],
                'src'  => $m['src'],
                'norm' => normalizarString($m['name']),
              ];
            }

            // (1.1) Balde de fallback para itens sem marca reconhecida
            // Ajuste o caminho do ícone genérico, se desejar.
            $fallbackMarcaNome = 'Outras marcas';
            $fallbackMarcaIcon = './img/marcas/outras.webp';
            $produtosSemMarca  = [
              'src' => $fallbackMarcaIcon,
              'produtos' => []
            ];

            // (2) Agrupa produtos por marca + fallback
            $produtosPorMarca = []; // ["Marca" => ["src" => "...", "produtos" => [...]]]

            foreach ($resProd as $produto) {
              $produtoNomeNormalizado = normalizarString($produto['nome'] ?? '');
              $adicionado = false;

              foreach ($marcasNorm as $m) {
                if ($m['norm'] !== '' && strpos($produtoNomeNormalizado, $m['norm']) !== false) {
                  $key = $m['name'];
                  if (!isset($produtosPorMarca[$key])) {
                    $produtosPorMarca[$key] = [
                      'src' => $m['src'],
                      'produtos' => []
                    ];
                  }
                  $produtosPorMarca[$key]['produtos'][] = $produto;
                  $adicionado = true;
                  break; // já casou com uma marca conhecida; evita checar as demais
                }
              }

              // Se não casou com nenhuma marca conhecida, vai para "Outras marcas"
              if (!$adicionado) {
                $produtosSemMarca['produtos'][] = $produto;
              }
            }

            // Anexa o grupo "Outras marcas" se houver itens
            if (!empty($produtosSemMarca['produtos'])) {
              $produtosPorMarca[$fallbackMarcaNome] = $produtosSemMarca;
            }

            // (3) Renderização por marcas
            if ($totalProd > 0) {

              echo '<div id="marca" class="container d-lg-none" style="display:flex;flex-direction:column;gap:12px;">';
              foreach ($produtosPorMarca as $marca => $dados) {
                $logoSrc   = isset($dados['src']) ? $dados['src'] : './img/fechado.png';
                $marcaJson = json_encode($marca, JSON_UNESCAPED_UNICODE);

                echo '<div class="d-flex gap-5 align-items-center mb-2 border-bottom"
             data-marca="' . htmlspecialchars($marca, ENT_QUOTES, 'UTF-8') . '"
             onclick="openMarca(' . htmlspecialchars($marcaJson, ENT_QUOTES, 'UTF-8') . ')">
            <img src="' . htmlspecialchars($logoSrc, ENT_QUOTES, 'UTF-8') . '"
                 style="width:64px;height:64px;object-fit:contain;"
                 alt="' . htmlspecialchars($marca, ENT_QUOTES, 'UTF-8') . '">
            <h3 class="m-0">' . htmlspecialchars($marca, ENT_QUOTES, 'UTF-8') . '</h3>
          </div>';
              }

              echo '</div>';
              echo "<button class='mb-5 d-lg-none' id='voltar' type='button' onclick='voltarMarcas()' style='display:none'>Selecionar outra Marca</button>";
              // ---------- DESKTOP (LG+): estrutura para o slider ----------
              echo '<div class="col-12">';


              $config = [
                'spaceBetween' => 12,
                'slidesPerView' => 10,
                'loop' => true,
                'speed' => 1000,
                'breakpoints' => [
                  "0"    => ['slidesPerView' => 2,  'spaceBetween' => 12],
                  "320"  => ['slidesPerView' => 2,  'spaceBetween' => 12],
                  "480"  => ['slidesPerView' => 3,  'spaceBetween' => 12],
                  "640"  => ['slidesPerView' => 4,  'spaceBetween' => 12],
                  "840"  => ['slidesPerView' => 6,  'spaceBetween' => 12],
                  "1140" => ['slidesPerView' => 10, 'spaceBetween' => 12],
                ],
              ];

              echo '<div id="marca-slider" class="swiper mySwiper-category-1 swiper-data d-none d-lg-block" data-swiper="'
                . htmlspecialchars(json_encode($config), ENT_QUOTES, 'UTF-8')
                . '">';
              echo '  <div class="swiper-wrapper">';

              foreach ($produtosPorMarca as $marca => $dados) {
                $logoSrc   = isset($dados['src']) ? $dados['src'] : './img/fechado.png';
                $marcaJson = json_encode($marca, JSON_UNESCAPED_UNICODE);

                echo '    <div class="swiper-slide">
                <button type="button"
                        class="d-flex align-items-center justify-content-center"
                        style="gap:12px;padding:10px;border:1px solid #eee;border-radius:10px;background:#fff;"
                        data-marca="' . htmlspecialchars($marca, ENT_QUOTES, 'UTF-8') . '"
                        onclick="openMarca(' . htmlspecialchars($marcaJson, ENT_QUOTES, 'UTF-8') . ')"
                        aria-label="Abrir marca ' . htmlspecialchars($marca, ENT_QUOTES, 'UTF-8') . '">
                  <img src="' . htmlspecialchars($logoSrc, ENT_QUOTES, 'UTF-8') . '"
                       style="width:100px;height:100px;object-fit:contain;"
                       alt="' . htmlspecialchars($marca, ENT_QUOTES, 'UTF-8') . '">
                </button>
              </div>';
              }

              echo '  </div>';
              echo '</div>';
              echo '</div>';
              // Loop por marca e produtos
              foreach ($produtosPorMarca as $marca => $dados) {
                $produtos = $dados['produtos'];

                foreach ($produtos as $p) {
                  // ----------------- CARD DO PRODUTO (igual ao seu) -----------------
                  $prod_id      = $p['id'];
                  $foto         = $p['foto'];
                  $nome         = $p['nome'];
                  $descricao    = $p['descricao'];
                  $slug         = $p['url'];
                  $estoque      = $p['estoque'];
                  $tem_estoque  = $p['tem_estoque']; // 'Sim' / 'Não'
                  $valor        = $p['valor_venda'];
                  $valorF       = number_format($valor, 2, ',', '.');
                  $promocao     = $p['promocao'];     // 'Sim' / 'Não'

                  $sem_estoque = ($tem_estoque == 'Sim' && $estoque <= 0);

                  $badge_html = '';
                  if ($sem_estoque) {
                    $badge_html = '<div class="badge"><span>ESGOTADO</span><i class="fa-solid fa-bookmark"></i></div>';
                  } else {
                    if ($promocao == 'Sim') {
                      $badge_html = '<div class="badge"><span>Promo</span><i class="fa-solid fa-bookmark"></i></div>';
                    }
                  }

                  if ($sem_estoque) {
                    $url_produto = '#';
                  } else {
                    $qG = $pdo->query("SELECT * FROM grades WHERE produto = '$prod_id' AND ativo = 'Sim'");
                    $rG = $qG->fetchAll(PDO::FETCH_ASSOC);
                    if (@count($rG) > 0) {
                      $url_produto = 'adicionais-' . $slug;
                    } else {
                      $url_produto = 'observacoes-' . $slug;
                    }
                  }

                  $variacoes = [];
                  $primeiro_preco_variacao = null;
                  $qVar = $pdo->query("SELECT * FROM grades WHERE produto = '$prod_id' AND tipo_item = 'Variação' AND ativo = 'Sim' ORDER BY id ASC");
                  $rVar = $qVar->fetchAll(PDO::FETCH_ASSOC);
                  if (@count($rVar) > 0) {
                    $id_grade = $rVar[0]['id'];
                    $qItens = $pdo->query("SELECT * FROM itens_grade WHERE grade = '$id_grade' AND ativo = 'Sim' ORDER BY id ASC");
                    $rItens = $qItens->fetchAll(PDO::FETCH_ASSOC);
                    if (@count($rItens) > 0) {
                      $iidx = 0;
                      foreach ($rItens as $ig) {
                        $nome_item    = $ig['texto'];
                        $valor_item   = $ig['valor'];
                        $valor_itemF  = number_format($valor_item, 2, ',', '.');
                        $variacoes[]  = '(' . $nome_item . ') R$ ' . $valor_itemF;
                        if ($iidx == 0) {
                          $primeiro_preco_variacao = $valor_itemF;
                        }
                        $iidx++;
                      }
                    }
                  }

                  $tem_variacoes = count($variacoes) > 0;
                  $preco_current = '';
                  if ($tem_variacoes) {
                    $preco_current = 'R$ ' . $primeiro_preco_variacao;
                  } else {
                    if ($valor > 0) {
                      $preco_current = 'R$ ' . $valorF;
                    }
                  }

                  $btn_attrs = $sem_estoque ? 'href="#" style="pointer-events:none;opacity:.5;"' : 'href="' . $url_produto . '"';
          ?>
                  <div data-marca="<?= htmlspecialchars($marca, ENT_QUOTES, 'UTF-8') ?>" class="col-lg-3 col-md-6 col-sm-6 col-12" style="display:none;">
                    <div class="single-shopping-card-one">
                      <div class="image-and-action-area-wrapper">
                        <a href="<?php echo htmlspecialchars($url_produto); ?>" class="thumbnail-preview">
                          <?php echo $badge_html; ?>
                          <img src="sistema/painel/images/produtos/<?php echo htmlspecialchars($foto); ?>"
                            alt="<?php echo htmlspecialchars($nome); ?>">
                        </a>
                      </div>

                      <div class="body-content">
                        <a href="<?php echo htmlspecialchars($url_produto); ?>">
                          <h4 class="title"><?php echo htmlspecialchars($nome); ?></h4>
                        </a>

                        <?php if ($tem_variacoes) { ?>
                          <span class="availability" style="font-size: 12px;">
                            <?php echo implode(' / ', $variacoes); ?>
                          </span>
                        <?php } else { ?>
                          <span class="availability"><?php echo htmlspecialchars($descricao); ?></span>
                        <?php } ?>

                        <div class="price-area">
                          <?php if (!empty($preco_current)) { ?>
                            <span class="current"><?php echo $preco_current; ?></span>
                          <?php } ?>
                        </div>

                        <div class="cart-counter-action">
                          <a <?php echo $btn_attrs; ?> class="rts-btn btn-primary radious-sm with-icon">
                            <div class="btn-text"><?php echo $sem_estoque ? 'Indisponível' : 'Adicionar'; ?></div>
                            <div class="arrow-icon"><i class="fa-regular fa-cart-shopping"></i></div>
                          </a>
                        </div>
                      </div>
                    </div>
                  </div>
              <?php
                  // ----------------- FIM DO CARD -----------------
                }
              }
            } else {
              ?>
              <div class="col-12">
                <div class="alert alert-warning" role="alert" style="margin: 24px 0;">
                  Nenhum produto encontrado nesta categoria.
                </div>
              </div>
              <?php
            }
            // -----------------------------------------------------------------------------
            // BLOCO PADRÃO (outras categorias sem agrupamento por marca)
            // -----------------------------------------------------------------------------
          } else {

            if ($totalProd > 0) {
              foreach ($resProd as $p) {
                // ----------------- CARD DO PRODUTO (igual ao seu) -----------------
                $prod_id      = $p['id'];
                $foto         = $p['foto'];
                $nome         = $p['nome'];
                $descricao    = $p['descricao'];
                $slug         = $p['url'];
                $estoque      = $p['estoque'];
                $tem_estoque  = $p['tem_estoque']; // 'Sim' / 'Não'
                $valor        = $p['valor_venda'];
                $valorF       = number_format($valor, 2, ',', '.');
                $promocao     = $p['promocao'];     // 'Sim' / 'Não'

                $sem_estoque = ($tem_estoque == 'Sim' && $estoque <= 0);

                $badge_html = '';
                if ($sem_estoque) {
                  $badge_html = '<div class="badge"><span>ESGOTADO</span><i class="fa-solid fa-bookmark"></i></div>';
                } else {
                  if ($promocao == 'Sim') {
                    $badge_html = '<div class="badge"><span>Promo</span><i class="fa-solid fa-bookmark"></i></div>';
                  }
                }

                if ($sem_estoque) {
                  $url_produto = '#';
                } else {
                  $qG = $pdo->query("SELECT * FROM grades WHERE produto = '$prod_id' AND ativo = 'Sim'");
                  $rG = $qG->fetchAll(PDO::FETCH_ASSOC);
                  if (@count($rG) > 0) {
                    $url_produto = 'adicionais-' . $slug;
                  } else {
                    $url_produto = 'observacoes-' . $slug;
                  }
                }

                $variacoes = [];
                $primeiro_preco_variacao = null;
                $qVar = $pdo->query("SELECT * FROM grades WHERE produto = '$prod_id' AND tipo_item = 'Variação' AND ativo = 'Sim' ORDER BY id ASC");
                $rVar = $qVar->fetchAll(PDO::FETCH_ASSOC);
                if (@count($rVar) > 0) {
                  $id_grade = $rVar[0]['id'];
                  $qItens = $pdo->query("SELECT * FROM itens_grade WHERE grade = '$id_grade' AND ativo = 'Sim' ORDER BY id ASC");
                  $rItens = $qItens->fetchAll(PDO::FETCH_ASSOC);
                  if (@count($rItens) > 0) {
                    $iidx = 0;
                    foreach ($rItens as $ig) {
                      $nome_item    = $ig['texto'];
                      $valor_item   = $ig['valor'];
                      $valor_itemF  = number_format($valor_item, 2, ',', '.');
                      $variacoes[]  = '(' . $nome_item . ') R$ ' . $valor_itemF;
                      if ($iidx == 0) {
                        $primeiro_preco_variacao = $valor_itemF;
                      }
                      $iidx++;
                    }
                  }
                }

                $tem_variacoes = count($variacoes) > 0;
                $preco_current = '';
                if ($tem_variacoes) {
                  $preco_current = 'R$ ' . $primeiro_preco_variacao;
                } else {
                  if ($valor > 0) {
                    $preco_current = 'R$ ' . $valorF;
                  }
                }

                $btn_attrs = $sem_estoque ? 'href="#" style="pointer-events:none;opacity:.5;"' : 'href="' . $url_produto . '"';
              ?>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                  <div class="single-shopping-card-one">
                    <div class="image-and-action-area-wrapper">
                      <a href="<?php echo htmlspecialchars($url_produto); ?>" class="thumbnail-preview">
                        <?php echo $badge_html; ?>
                        <img src="sistema/painel/images/produtos/<?php echo htmlspecialchars($foto); ?>"
                          alt="<?php echo htmlspecialchars($nome); ?>">
                      </a>
                    </div>

                    <div class="body-content">
                      <a href="<?php echo htmlspecialchars($url_produto); ?>">
                        <h4 class="title"><?php echo htmlspecialchars($nome); ?></h4>
                      </a>

                      <?php if ($tem_variacoes) { ?>
                        <span class="availability" style="font-size: 12px;">
                          <?php echo implode(' / ', $variacoes); ?>
                        </span>
                      <?php } else { ?>
                        <span class="availability"><?php echo htmlspecialchars($descricao); ?></span>
                      <?php } ?>

                      <div class="price-area">
                        <?php if (!empty($preco_current)) { ?>
                          <span class="current"><?php echo $preco_current; ?></span>
                        <?php } ?>
                      </div>

                      <div class="cart-counter-action">
                        <a <?php echo $btn_attrs; ?> class="rts-btn btn-primary radious-sm with-icon">
                          <div class="btn-text"><?php echo $sem_estoque ? 'Indisponível' : 'Adicionar'; ?></div>
                          <div class="arrow-icon"><i class="fa-regular fa-cart-shopping"></i></div>
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
              <?php
                // ----------------- FIM DO CARD -----------------
              }
            } else {
              ?>
              <div class="col-12">
                <div class="alert alert-warning" role="alert" style="margin: 24px 0;">
                  Nenhum produto encontrado nesta categoria.
                </div>
              </div>
          <?php
            }
          } // fim else categorias sem marca
          ?>
        </div>


      </div>
    </div>
  </div><!-- /.container -->

  <!-- rts footer one area start -->
  <?php require_once('./footer.php') ?>
  <!-- rts footer one area end -->

  <!-- scripts do template (se necessários) -->
  <script src="assets/js/plugins.js"></script>
  <script src="assets/js/main.js"></script>
  <script src="./js/itens.js"></script>
</body>

</html>