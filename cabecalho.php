<?php
require_once("sistema/conexao.php");
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="description" content="Loja de vinho, cerveja e destilados." />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="keywords" content="Loja de vinho, cerveja e destilados" />
  <meta name="author" content="Suporte Verde" />
  <link rel="shortcut icon" type="image/x-icon" href="assets/images/fav-icon-bar.png">
  <title> Delivery - Barboseira Bebidas</title>
  <link rel="shortcut icon" href="./img/favicon.png" type="favicon.png/x-icon">

  <!-- plugins css -->
  <link rel="stylesheet preload" href="./assets/css/plugins.css" as="style">
  <link rel="stylesheet preload" href="./assets/css/style.css" as="style">
  <link rel="stylesheet preload" href="./assets/css/custom.css" as="style">
  <!-- <link rel="stylesheet" type="text/css" href="css/style.css"> -->
  <!-- CSS only -->
  <!-- Meta Pixel Code -->
  <script>
    ! function(f, b, e, v, n, t, s) {
      if (f.fbq) return;
      n = f.fbq = function() {
        n.callMethod ?
          n.callMethod.apply(n, arguments) : n.queue.push(arguments)
      };
      if (!f._fbq) f._fbq = n;
      n.push = n;
      n.loaded = !0;
      n.version = '2.0';
      n.queue = [];
      t = b.createElement(e);
      t.async = !0;
      t.src = v;
      s = b.getElementsByTagName(e)[0];
      s.parentNode.insertBefore(t, s)
    }(window, document, 'script',
      'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '687167810387759');
    fbq('track', 'PageView');
  </script>
  <noscript><img height="1" width="1" style="display:none"
      src="https://www.facebook.com/tr?id=687167810387759&ev=PageView&noscript=1" /></noscript>
  <!-- End Meta Pixel Code -->
  <!-- JavaScript Bundle with Popper -->
  <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous"> -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.3/font/bootstrap-icons.css">
  <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
</head>

<!-- rts header area start -->
<!-- rts header area start -->
<div class="rts-header-one-area-one">
  <div class="search-header-area-main">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="logo-search-category-wrapper">
            <a href="index.php" class="logo-area">
              <img src="assets/images/logo/logo-03-2.png" alt="logo-main" class="logo">
            </a>
            <div class="category-search-wrapper">
              <form action="#" class="search-header">
                <input type="text" placeholder="Pesquise os produtos" required>
                <a href="#" class="rts-btn btn-primary radious-sm with-icon">
                  <div class="btn-text">
                    Pesquise
                  </div>
                  <div class="arrow-icon">
                    <i class="fa-light fa-magnifying-glass"></i>
                  </div>
                  <div class="arrow-icon">
                    <i class="fa-light fa-magnifying-glass"></i>
                  </div>
                </a>
              </form>
            </div>
            <div class="actions-area">
              <div class="search-btn" id="searchs">

                <svg width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M15.75 14.7188L11.5625 10.5312C12.4688 9.4375 12.9688 8.03125 12.9688 6.5C12.9688 2.9375 10.0312 0 6.46875 0C2.875 0 0 2.9375 0 6.5C0 10.0938 2.90625 13 6.46875 13C7.96875 13 9.375 12.5 10.5 11.5938L14.6875 15.7812C14.8438 15.9375 15.0312 16 15.25 16C15.4375 16 15.625 15.9375 15.75 15.7812C16.0625 15.5 16.0625 15.0312 15.75 14.7188ZM1.5 6.5C1.5 3.75 3.71875 1.5 6.5 1.5C9.25 1.5 11.5 3.75 11.5 6.5C11.5 9.28125 9.25 11.5 6.5 11.5C3.71875 11.5 1.5 9.28125 1.5 6.5Z" fill="#1F1F25"></path>
                </svg>

              </div>
              <div class="menu-btn" id="menu-btn">

                <svg width="20" height="16" viewBox="0 0 20 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <rect y="14" width="20" height="2" fill="#1F1F25"></rect>
                  <rect y="7" width="20" height="2" fill="#1F1F25"></rect>
                  <rect width="20" height="2" fill="#1F1F25"></rect>
                </svg>

              </div>
            </div>
            <div class="accont-wishlist-cart-area-header">
              <a href="./sistema/" class="btn-border-only account">
                <i class="fa-light fa-user"></i>
                <span class="accont-span">Conta</span>
              </a>
              <div class="btn-border-only cart category-hover-header">
                <i class="fa-sharp fa-regular fa-cart-shopping"></i>
                <span class="accont-span"></span>
                <span id="numberCartIcon" class="number">0</span>
                <div class="category-sub-menu card-number-show">
                  <h5 class="shopping-cart-number">Carrinho <span id="numberCartText">(00)</span></h5>
                  <div id="listar-itens-carrinho-icone">


                  </div>

                </div>
              </div>
              <!-- <a href="#" class="over_link"></a> -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<script type="text/javascript">
  $(document).ready(function() {
    listarCarrinhoIcone()
  });

  function listarCarrinhoIcone() {

    $.ajax({
      url: 'js/ajax/listar-itens-carrinho-icone.php',
      method: 'POST',
      data: {},
      dataType: "html",

      success: function(result) {
        $("#listar-itens-carrinho-icone").html(result);
        const itens = document.querySelectorAll('.cart-item-1');
        $("#numberCartIcon").text(itens.length);
        $("#numberCartText").text(itens.length);
      }
    });
  }
</script>