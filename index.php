<?php
require_once("sistema/conexao.php");
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <link rel="stylesheet preload" href="css/style.css">
  <meta charset="UTF-8">
  <meta name="description" content="Ekomart-Grocery-Store(e-Commerce) HTML Template: A sleek, responsive, and user-friendly HTML template designed for online grocery stores.">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="keywords" content="Grocery, Store, stores">
  <title>Barboseira</title>
  <link rel="shortcut icon" type="image/x-icon" href="assets/images/fav-icon-bar.png">
  <!-- plugins css -->
  <link rel="stylesheet preload" href="assets/css/plugins.css" as="style">
  <link rel="stylesheet preload" href="assets/css/style.css" as="style">
  <style>
    /* grid simples com 3 cards no mobile */
    .category-mobile-highlight {
      display: flex;
      gap: 12px;
      justify-content: space-between;
    }

    .category-mobile-highlight .cat-card {
      flex: 1;
      display: flex;
      flex-direction: column;
      align-items: center;
      text-decoration: none;
      background: #fff;
      border-radius: 10px;
      padding: 10px;
      box-shadow: 0 1px 6px rgba(0, 0, 0, .06);
    }

    .category-mobile-highlight .cat-card img {
      width: 100%;
      height: 72px;
      object-fit: contain;
      display: block;
    }

    .category-mobile-highlight .cat-card span {
      margin-top: 8px;
      font-weight: 600;
      font-size: .95rem;
      color: #222;
      text-align: center;
    }
  </style>
</head>


<?php require_once("./cabecalho.php"); ?>


<!-- Banner -->
<div class="rts-banner-area-one mt--90 mb--30">
  <div class="container-swipe">
    <div class="row">
      <div class="col-lg-12">
        <div class="category-area-main-wrapper-one">
          <div class="swiper mySwiper-category-1 swiper-data">
            <div class="swiper-wrapper">
              <!-- single swiper start -->
              <div class="swiper-slide">
                <div class="banner-bg-image bg_image bg_one-banner  ptb--120 ptb_md--80 ptb_sm--60">
                  <div class="banner-one-inner-content">
                    <h1 class="title">
                      BEBIDAS GELADAS E TABACARIA <br>
                      ENTREGA RÁPIDA REGIÃO DA LAPA
                    </h1>
                    <a href="./categoria-cervejas" class="rts-btn btn-primary radious-sm with-icon">
                      <div class="btn-text">
                        Compre agora!
                      </div>
                      <div class="arrow-icon">
                        <i class="fa-light fa-arrow-right"></i>
                      </div>
                    </a>
                  </div>
                </div>
              </div>
              <!-- single swiper end -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- End of Banner -->
<?php
$slugs_destaque = ['cervejas', 'refrigerantes-e-sucos', 'tabacaria'];

// Consultando as categorias no banco
$query = $pdo->query("SELECT * FROM categorias WHERE ativo = 'Sim'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);

// Separando as categorias em destaque e as que irão no swiper
$cats_destaque = [];
$cats_swiper   = [];

foreach ($res as $categoria) {
  $slug = $categoria['url'];
  if (in_array($slug, $slugs_destaque)) {
    $cats_destaque[] = $categoria;
  } else {
    $cats_swiper[]   = $categoria;
  }
}
?>



<!-- CATEGORIAS (Moved here below the banner) -->
<div class="rts-caregory-area-one">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <!-- Destaques Mobile (apenas mobile) -->
        <div class="d-lg-none mb-3 text-center mt-5">
          <div class="category-mobile-highlight">
            <?php foreach ($cats_destaque as $cat):
              $foto = $cat['foto'];
              $nome = $cat['nome'];
              $url  = $cat['url'];
              $mais_sabores = $cat['mais_sabores'];
              $link = $mais_sabores == 'Sim' ? 'categoria-sabores-' . $url : 'categoria-' . $url;
            ?>
              <a href="<?php echo $link; ?>" class="single-category-one cat-card">
                <img src="sistema/painel/images/categorias/<?php echo $foto; ?>" alt="<?php echo htmlspecialchars($nome); ?>">
                <p class="fs-4"><?php echo $nome; ?></p>
              </a>
            <?php endforeach; ?>
          </div>
        </div>
        <!-- /Destaques Mobile -->
        <!-- Swiper (todos os dispositivos) -->
        <div class="category-area-main-wrapper-one my-5">
          <h2 class="d-lg-none text-center">Categorias</h2>
          <div class="swiper mySwiper-category-1 swiper-data" data-swiper='{
          "spaceBetween":12,
          "slidesPerView":10,
          "loop": true,
          "speed": 1000,
          "breakpoints": {
            "0":   { "slidesPerView": 2, "spaceBetween": 12 },
            "320": { "slidesPerView": 2, "spaceBetween": 12 },
            "480": { "slidesPerView": 3, "spaceBetween": 12 },
            "640": { "slidesPerView": 4, "spaceBetween": 12 },
            "840": { "slidesPerView": 4, "spaceBetween": 12 },
            "1140":{ "slidesPerView": 10, "spaceBetween": 12 }
          }}'>
            <div class="swiper-wrapper">
              <?php foreach ($res as $cat):
                $foto = $cat['foto'];
                $nome = $cat['nome'];
                $url  = $cat['url'];
                $mais_sabores = $cat['mais_sabores'];
                $link = $mais_sabores == 'Sim' ? 'categoria-sabores-' . $url : 'categoria-' . $url;
              ?>
                <div class="swiper-slide <?= in_array($url, $slugs_destaque) ? 'd-none d-lg-block' : '' ?>">
                  <a href="<?php echo $link; ?>" class="single-category-one ">
                    <img src="sistema/painel/images/categorias/<?php echo $foto; ?>" alt="<?php echo htmlspecialchars($nome); ?>">
                    <p><?php echo $nome; ?></p>
                  </a>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
        <!-- /Swiper -->

      </div>
    </div>
  </div>
</div>
<!-- End of CATEGORIAS -->

<!-- rts feature area start -->
<div class="rts-feature-area rts-section-gap">
  <div class="container">
    <h2 class="section-title">Como funciona meu App?</h2>

    <div class="row g-4">
      <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="single-feature-area">
          <div class="icon">
            <img src="assets/images/ilustracao/delivery.png" alt="Variedade">
          </div>
          <div class="content">
            <h2>Chamou, chegou</h2>
            <span>Bebida gelada, em minutos, na porta da sua casa.</span>
          </div>
        </div>
      </div>

      <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="single-feature-area">
          <div class="icon">
            <img src="assets/images/ilustracao/variedade.png" alt="Devolução" width="880">
          </div>
          <div class="content">
            <h2>Variedade de produtos</h2>
            <span>Tem bebidas, petiscos, itens pro churrasco e muito mais!</span>
          </div>
        </div>
      </div>

      <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="single-feature-area">
          <div class="icon">
            <img src="assets/images/ilustracao/descontos.png" alt="Descontos">
          </div>
          <div class="content">
            <h2>Chama a diversão</h2>
            <span>Bora reunir a galera e começar o rolê.</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- rts feature area end -->

<!-- rts grocery feature area start -->
<!-- <div class="rts-grocery-feature-area rts-section-gapBottom">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div class="title-area-between">
          <h2 class="title-left">
            Mais Vendidas
          </h2>
          <div class="next-prev-swiper-wrapper">
            <div class="swiper-button-prev"><i class="fa-regular fa-chevron-left"></i></div>
            <div class="swiper-button-next"><i class="fa-regular fa-chevron-right"></i></div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div class="category-area-main-wrapper-one">
          <div class="swiper mySwiper-category-1 swiper-data" data-swiper='{
                        "spaceBetween":16,
                        "slidesPerView":6,
                        "loop": true,
                        "speed": 700,
                        "navigation":{
                            "nextEl":".swiper-button-next",
                            "prevEl":".swiper-button-prev"
                        },
                        "breakpoints":{
                            "0":{
                                "slidesPerView":1,
                                "spaceBetween":12
                            },
                            "320":{
                                "slidesPerView":1,
                                "spaceBetween":12
                            },
                            "480":{
                                "slidesPerView":2,
                                "spaceBetween":12
                            },
                            "640":{
                                "slidesPerView":2,
                                "spaceBetween":16
                            },
                            "840":{
                                "slidesPerView":3,
                                "spaceBetween":16
                            },
                            "1140":{
                                "slidesPerView":5,
                                "spaceBetween":16
                            },
                            "1540":{
                                "slidesPerView":5,
                                "spaceBetween":16
                            },
                            "1840":{
                                "slidesPerView":6,
                                "spaceBetween":16
                            }
                        }
                    }'>
            <div class="swiper-wrapper">
              <?php
              if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                  // Exibindo os produtos no swiper
                  echo '<div class="swiper-slide">';
                  echo '<div class="single-shopping-card-one">';
                  echo '<div class="image-and-action-area-wrapper">';
                  echo '<a href="shop-details.php?id=' . $row['id'] . '" class="thumbnail-preview">';
                  echo '<div class="badge"><span>25% <br> Off</span><i class="fa-solid fa-bookmark"></i></div>';
                  echo '<img src="assets/images/grocery/' . $row['foto'] . '" alt="' . $row['nome'] . '">';
                  echo '</a>';
                  echo '<div class="action-share-option">';
                  echo '<div class="single-action openuptip message-show-action" data-flow="up" title="Add To Wishlist"><i class="fa-light fa-heart"></i></div>';
                  echo '<div class="single-action openuptip" data-flow="up" title="Compare" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="fa-solid fa-arrows-retweet"></i></div>';
                  echo '<div class="single-action openuptip cta-quickview product-details-popup-btn" data-flow="up" title="Quick View"><i class="fa-regular fa-eye"></i></div>';
                  echo '</div>';
                  echo '</div>';
                  echo '<div class="body-content">';
                  echo '<a href="shop-details.php?id=' . $row['id'] . '"><h4 class="title">' . $row['nome'] . '</h4></a>';
                  echo '<span class="availability">' . $row['descricao'] . '</span>';
                  echo '<div class="price-area"><span class="current">R$' . $row['valor_venda'] . '</span><div class="previous">R$' . $row['valor_venda'] . '</div></div>';
                  echo '<div class="cart-counter-action">';
                  echo '<div class="quantity-edit"><input type="text" class="input" value="1">';
                  echo '<div class="button-wrapper-action"><button class="button"><i class="fa-regular fa-chevron-down"></i></button><button class="button plus">+<i class="fa-regular fa-chevron-up"></i></button></div>';
                  echo '</div>';
                  echo '<a href="#" class="rts-btn btn-primary radious-sm with-icon">';
                  echo '<div class="btn-text">Add</div>';
                  echo '<div class="arrow-icon"><i class="fa-regular fa-cart-shopping"></i></div>';
                  echo '</a>';
                  echo '</div>';
                  echo '</div>';
                  echo '</div>';
                  echo '</div>';
                }
              } else {
                echo 'Nenhum produto encontrado.';
              }
              ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div> -->
<!-- rts grocery feature area end -->

<!-- rts footer one area start -->
<!-- 
<div class="rts-footer-area pt--80 bg_light-1">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div class="footer-main-content-wrapper pb--70 pb_sm--30">
    
          <div class="single-footer-wized">
            <a href="index.html" class="logo-area">
              <img src="assets/images/logo/logo-03-bar.png" alt="logo-main" width="150">
            </a>
            <div class="social-one-wrapper">
              <ul>
                <li><a href="#"><i class="fa-brands fa-facebook-f"></i></a></li>
                <li><a href="#"><i class="fa-brands fa-twitter"></i></a></li>
                <li><a href="#"><i class="fa-brands fa-youtube"></i></a></li>
                <li><a href="#"><i class="fa-brands fa-whatsapp"></i></a></li>
                <li><a href="#"><i class="fa-brands fa-instagram"></i></a></li>
              </ul>
            </div>
          </div>
 
          <div class="single-footer-wized">
            <h3 class="footer-title">Sobre a Barboseira</h3>
            <div class="footer-nav">
              <ul>
                <li><a href="#">Informações</a></li>
                <li><a href="#">Política de privacidade</a></li>
                <li><a href="#">Trabalhe Conosco</a></li>
                <li><a href="#">Cidades atendidas</a></li>
              </ul>
            </div>
          </div>

   
          <div class="single-footer-wized">
            <h3 class="footer-title">Parcerias</h3>
            <div class="footer-nav">
              <ul>
                <li><a href="#">Quero ser parceiro</a></li>
              </ul>
            </div>
          </div>

          <div class="single-footer-wized">
            <h3 class="footer-title">Já baixou o app?</h3>
            <div class="footer-nav-2">
              <a href="#" class="playstore-app-area">
                <img src="assets/images/payment/02.png" alt="">
                <img src="assets/images/payment/03.png" alt="">
              </a>
            </div>
          </div>
         
        </div>
      </div>
    </div>
  </div>
</div> -->
<?php require_once('./footer.php') ?>
<!-- rts footer one area end -->

<!-- plugins js -->
<script defer src="assets/js/plugins.js"></script>

<!-- custom js -->
<script defer src="assets/js/main.js"></script>

</body>

</html>