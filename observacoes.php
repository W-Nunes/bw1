<?php
@session_start();
require_once("./cabecalho.php");

$url_completa = $_GET['url'];
$sabores = @$_GET['sabores'];
$id_usuario = @$_SESSION['id'];

if (@$_SESSION['sessao_usuario'] == "") {
  $sessao = date('Y-m-d-H:i:s-') . rand(0, 1500);
  $_SESSION['sessao_usuario'] = $sessao;
} else {
  $sessao = $_SESSION['sessao_usuario'];
}

$texto_botao = 'Adicionar ao Carrinho';

if (@$_SESSION['id'] != "") {
  $id_usuario = $_SESSION['id'];
} else {
  $id_usuario = '';
}

$query = $pdo->query("SELECT * FROM carrinho where sessao = '$sessao'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if ($total_reg > 0) {
  $id_cliente = $res[0]['cliente'];
  $mesa_carrinho = $res[0]['mesa'];
  $nome_cli_pedido = $res[0]['nome_cliente'];


  $query = $pdo->query("SELECT * FROM clientes where id = '$id_cliente'");
  $res = $query->fetchAll(PDO::FETCH_ASSOC);
  if (@count($res) > 0) {
    $nome_cliente = $res[0]['nome'];
    $telefone_cliente = $res[0]['telefone'];
  } else {
    $nome_cliente = $nome_cli_pedido;
    $telefone_cliente = '';
  }
}

$separar_url = explode("_", $url_completa);
$url = $separar_url[0];
$item = @$separar_url[1];


$query = $pdo->query("SELECT * FROM produtos where url = '$url'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if ($total_reg > 0) {
  $nome = $res[0]['nome'];
  $descricao = $res[0]['descricao'];
  $foto = $res[0]['foto'];
  $id_prod = $res[0]['id'];
  $valor = $res[0]['valor_venda'];
  $valorF = number_format($valor, 2, ',', '.');
  $categoria = $res[0]['categoria'];
}

$query = $pdo->query("SELECT * FROM categorias where id = '$categoria'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if ($total_reg > 0) {
  $url_cat = $res[0]['url'];
}

$valor_item = $valor;
$valor_item_before = $valor * 1.1;

$valor_itemF = number_format($valor_item, 2, ',', '.');
$valor_itemF_before = number_format($valor_item_before, 2, ',', '.');

$src_foto = 'sistema/painel/images/produtos/' . $foto;

if ($id_usuario == '') {
  if ($status_estabelecimento == "Fechado") {
    echo "<script>window.alert('$texto_fechamento')</script>";
    echo "<script>window.location='index.php'</script>";
    exit();
  }


  $data = date('Y-m-d');
  //verificar se está aberto hoje
  $diasemana = array("Domingo", "Segunda-Feira", "Terça-Feira", "Quarta-Feira", "Quinta-Feira", "Sexta-Feira", "Sábado");
  $diasemana_numero = date('w', strtotime($data));
  $dia_procurado = $diasemana[$diasemana_numero];

  //percorrer os dias da semana que ele trabalha
  $query = $pdo->query("SELECT * FROM dias where dia = '$dia_procurado'");
  $res = $query->fetchAll(PDO::FETCH_ASSOC);
  if (@count($res) > 0) {
    echo "<script>window.alert('Estamos Fechados! Não funcionamos Hoje!')</script>";
    echo "<script>window.location='index.php'</script>";
    exit();
  }

  // $hora_atual = date('H:i:s');

  // //nova verificação de horarios
  // $start = strtotime( date('Y-m-d' .$horario_abertura) );
  // $end = strtotime( date('Y-m-d' . $horario_fechamento) );
  // $now = time();


  // if ( $start <= $now && $now <= $end ) {

  // }else{
  // 	echo "<script>window.alert('$texto_fechamento_horario')</script>";
  // 	echo "<script>window.location='index.php'</script>";
  // 	exit();
  // }

  // }


  $hora_atual = date('H:i:s');

  //nova verificação de horarios
  $start = strtotime(date('Y-m-d' . $horario_abertura));
  $end = strtotime(date('Y-m-d' . $horario_fechamento));
  $now = time();

  if ($start <= $now && $now <= $end) {
  } else {
    if ($end < $start) {
      if ($now > $start) {
      } else {
        if ($now < $end) {
        } else {
          echo "<script>window.alert('$texto_fechamento_horario')</script>";
          echo "<script>window.location='index.php'</script>";
          exit();
        }
      }
    } else {
      echo "<script>window.alert('$texto_fechamento_horario')</script>";
      echo "<script>window.location='index.php'</script>";
      exit();
    }
  }
}
?>
<style type="text/css">
  body {
    background: #f2f2f2;
  }
</style>


<div class="main-container" style="background:#fff;">
  <!--
	<nav class="navbar bg-light fixed-top" style="box-shadow: 0px 3px 5px rgba(0, 0, 0, 0.20);">
		<div class="container-fluid">
			<div class="navbar-brand" >

               <a href="categoria-<?php echo $url_cat ?>"><big><i class="bi bi-arrow-left"></i></big></a>

				<span style="margin-left: 15px; font-size:14px">Resumo do Item</span>
			</div>

			<?php require_once("icone-carrinho.php") ?>

		</div>
	</nav> -->



  <!-- <div class="destaque mt--100">
    <b><?php echo mb_strtoupper($nome); ?></b>
  </div> -->
  <!-- 
  <?php if ($sabores != 2 and $sabores != 1) { ?>
    <div class="destaque-qtd">
      <b>QUANTIDADE</b>
      <span class="direita">
        <big>
          <span><a href="#" onclick="diminuirQuant()"><i class="bi bi-dash-circle-fill text-danger"></i></a></span>
          <span><a href="#" onclick="aumentarQuant()"><i class="bi bi-plus-circle-fill text-success"></i></a></span>
        </big>
      </span>
    </div>
  <?php } ?> -->




  <!-- <div class="destaque-qtd">
    <b>OBSERVAÇÕES</b>
    <div class="form-group mt-3">
      <textarea maxlength="255" class="form-control" type="text" name="obs" id="obs"></textarea>
    </div>
  </div> -->

</div>

<!-- 
<div class="total-pedido">
  <span><b>TOTAL</b></span>
  <span class="direita"> <b>R$ <span id="total_item"><?php echo $valor_itemF ?></span></b></span>
</div>


<div class="d-grid gap-2 mt-4 abaixo">
  <a href='#' onclick="addCarrinho()" class="btn btn-primary botao-carrinho"><?php echo $texto_botao ?></a>
</div> -->

<div class="rts-chop-details-area rts-section-gap bg_light-1">
  <div class="container">
    <div class="shopdetails-style-1-wrapper">
      <div class="row g-5">
        <div class="col-lg-9">
          <div class="product-details-popup-wrapper in-shopdetails">
            <div class="rts-product-details-section rts-product-details-section2 product-details-popup-section">
              <div class="product-details-popup">
                <div class="details-product-area">

                  <div class="show-product-area-details">
                    <div class="product-thumb-filter-group left">
                      <div class="thumb-filter filter-btn active" data-show=".one"><img src="<?php echo $src_foto; ?>" alt="product-thumb-filter"></div>
                    </div>
                    <div class="product-thumb-area">
                      <div class="cursor"></div>
                      <div class="thumb-wrapper one filterd-items figure">
                        <div class="product-thumb zoom" onmousemove="zoom(event)" style="background-image: url(<?php echo $src_foto ?>); background-size:150%;">
                          <img src="<?php echo $src_foto; ?>" alt="product-thumb">
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="contents w-100">
                    <h2 class="product-title"><?php echo $nome ?></h2>
                    <p class="mt--20 mb--20">
                      <?php echo $descricao ?>
                    </p>
                    <span class="product-price mb--15 d-block" style="color: #DC2626; font-weight: 600;"> R$ <?php echo $valor_itemF ?><span class="old-price ml--15"><?php echo $valor_itemF_before ?></span></span>
                    <div class="product-bottom-action">
                      <input type="hidden" id="quantidade" value="1">
                      <input type="hidden" id="total_item_input" value="<?php echo $valor_item ?>">
                      <div class="cart-edits">
                        <div class="quantity-edit action-item">
                          <button onclick="diminuirQuant()" class="button"><i class="fal fa-minus minus"></i></button>
                          <span id="quant" class="m-0 fs-3">1</span>
                          <button onclick="aumentarQuant()" class="button plus">+<i class="fal fa-plus plus"></i></button>
                        </div>
                        <div>
                          <span>Total:<span id="total_item">R$ <?php echo $valor_itemF ?></span></span>
                        </div>
                      </div>
                      <a href="#" onclick="addCarrinho()" class="rts-btn btn-primary radious-sm with-icon">
                        <div class="btn-text">
                          Add carrinho
                        </div>
                        <div class="arrow-icon">
                          <i class="fa-regular fa-cart-shopping"></i>
                        </div>
                        <div class="arrow-icon">
                          <i class="fa-regular fa-cart-shopping"></i>
                        </div>
                      </a>
                    </div>
                    <!-- <div class="product-uniques">
                      <span class="sku product-unipue mb--10"><span style="font-weight: 400; margin-right: 10px;">SKU: </span> BO1D0MX8SJ</span>
                      <span class="catagorys product-unipue mb--10"><span style="font-weight: 400; margin-right: 10px;">Categories: </span> T-Shirts, Tops, Mens</span>
                      <span class="tags product-unipue mb--10"><span style="font-weight: 400; margin-right: 10px;">Tags: </span> fashion, t-shirts, Men</span>
                      <span class="tags product-unipue mb--10"><span style="font-weight: 400; margin-right: 10px;">LIFE:: </span> 6 Months</span>
                      <span class="tags product-unipue mb--10"><span style="font-weight: 400; margin-right: 10px;">Type: </span> original</span>
                      <span class="tags product-unipue mb--10"><span style="font-weight: 400; margin-right: 10px;">Category: </span> Beverages, Dairy & Bakery</span>
                    </div> -->
                    <!-- <div class="share-option-shop-details">
											<div class="single-share-option">
												<div class="icon">
													<i class="fa-regular fa-heart"></i>
												</div>
												<span>Add To Wishlist</span>
											</div>
											<div class="single-share-option">
												<div class="icon">
													<i class="fa-solid fa-share"></i>
												</div>
												<span>Share On social</span>
											</div>
											<div class="single-share-option">
												<div class="icon">
													<i class="fa-light fa-code-compare"></i>
												</div>
												<span>Compare</span>
											</div>
										</div> -->
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- <div class="product-discription-tab-shop mt--50">
						<ul class="nav nav-tabs" id="myTab" role="tablist">
							<li class="nav-item" role="presentation">
								<button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Product Details</button>
							</li>
							<li class="nav-item" role="presentation">
								<button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Additional Information</button>
							</li>
							<li class="nav-item" role="presentation">
								<button class="nav-link" id="profile-tabt" data-bs-toggle="tab" data-bs-target="#profile-tab-panes" type="button" role="tab" aria-controls="profile-tab-panes" aria-selected="false">Customer Reviews (01)</button>
							</li>
						</ul>
						<div class="tab-content" id="myTabContent">
							<div class="tab-pane fade   show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
								<div class="single-tab-content-shop-details">
									<p class="disc">
										Uninhibited carnally hired played in whimpered dear gorilla koala depending and much yikes off far quetzal goodness and from for grimaced goodness unaccountably and meadowlark near unblushingly crucial scallop tightly neurotic hungrily some and dear furiously this apart.
									</p>
									<div class="details-row-2">
										<div class="left-area">
											<img src="assets/images/shop/06.jpg" alt="shop">
										</div>
										<div class="right">
											<h4 class="title">All Natural Italian-Style Chicken Meatballs</h4>
											<p class="mb--25">
												Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. ibero sit amet quam egestas semperAenean ultricies mi vitae est Mauris placerat eleifend.
											</p>
											<ul class="bottom-ul">
												<li>Elementum sociis rhoncus aptent auctor urna justo</li>
												<li>Habitasse venenatis gravida nisl, sollicitudin posuere</li>
											</ul>
										</div>
									</div>
								</div>
							</div>
							<div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
								<div class="single-tab-content-shop-details">
									<p class="disc">
										Uninhibited carnally hired played in whimpered dear gorilla koala depending and much yikes off far quetzal goodness and from for grimaced goodness unaccountably and meadowlark near unblushingly crucial scallop tightly neurotic hungrily some and dear furiously this apart.
									</p>
									<div class="table-responsive table-shop-details-pd">
										<table class="table">
											<thead>
												<tr>
													<th>Kitchen Fade Defy</th>
													<th>5KG</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>PRAN Full Cream Milk Powder</td>
													<td>3KG</td>
												</tr>
												<tr>
													<td>Net weight</td>
													<td>8KG</td>
												</tr>
												<tr>
													<td>Brand</td>
													<td>Reactheme</td>
												</tr>
												<tr>
													<td>Item code</td>
													<td>4000000005</td>
												</tr>
												<tr>
													<td>Product type</td>
													<td>Powder milk</td>
												</tr>
											</tbody>
										</table>
									</div>
									<p class="cansellation mt--20">
										<span> Return/cancellation:</span> No change will be applicable which are already delivered to customer. If product quality or quantity problem found then customer can return/cancel their order on delivery time with presence of delivery person.
									</p>
									<p class="note">
										<span>Note:</span> Product delivery duration may vary due to product availability in stock.
									</p>
								</div>
							</div>
							<div class="tab-pane fade" id="profile-tab-panes" role="tabpanel" aria-labelledby="profile-tabt" tabindex="0">
								<div class="single-tab-content-shop-details">
									<div class="product-details-review-product-style">
										<div class="average-stars-area-left">
											<div class="top-stars-wrapper">
												<h4 class="review">
													5.0
												</h4>
												<div class="rating-disc">
													<span>Average Rating</span>
													<div class="stars">
														<i class="fa-solid fa-star"></i>
														<i class="fa-solid fa-star"></i>
														<i class="fa-solid fa-star"></i>
														<i class="fa-solid fa-star"></i>
														<i class="fa-solid fa-star"></i>
														<span>(1 Reviews & 0 Ratings)</span>
													</div>
												</div>
											</div>
											<div class="average-stars-area">
												<h4 class="average">66.7%</h4>
												<span>Recommended
													(2 of 3)</span>
											</div>
											<div class="review-charts-details">
												<div class="single-review">
													<div class="stars">
														<i class="fa-solid fa-star"></i>
														<i class="fa-solid fa-star"></i>
														<i class="fa-solid fa-star"></i>
														<i class="fa-solid fa-star"></i>
														<i class="fa-solid fa-star"></i>
													</div>
													<div class="single-progress-area-incard">
														<div class="progress">
															<div class="progress-bar wow fadeInLeft" role="progressbar" style="width: 80%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
														</div>
													</div>
													<span class="pac">100%</span>
												</div>
												<div class="single-review">
													<div class="stars">
														<i class="fa-solid fa-star"></i>
														<i class="fa-solid fa-star"></i>
														<i class="fa-solid fa-star"></i>
														<i class="fa-solid fa-star"></i>
														<i class="fa-regular fa-star"></i>
													</div>
													<div class="single-progress-area-incard">
														<div class="progress">
															<div class="progress-bar wow fadeInLeft" role="progressbar" style="width: 80%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
														</div>
													</div>
													<span class="pac">80%</span>
												</div>
												<div class="single-review">
													<div class="stars">
														<i class="fa-solid fa-star"></i>
														<i class="fa-solid fa-star"></i>
														<i class="fa-solid fa-star"></i>
														<i class="fa-regular fa-star"></i>
														<i class="fa-regular fa-star"></i>
													</div>
													<div class="single-progress-area-incard">
														<div class="progress">
															<div class="progress-bar wow fadeInLeft" role="progressbar" style="width: 60%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
														</div>
													</div>
													<span class="pac">60%</span>
												</div>
												<div class="single-review">
													<div class="stars">
														<i class="fa-solid fa-star"></i>
														<i class="fa-solid fa-star"></i>
														<i class="fa-regular fa-star"></i>
														<i class="fa-regular fa-star"></i>
														<i class="fa-regular fa-star"></i>
													</div>
													<div class="single-progress-area-incard">
														<div class="progress">
															<div class="progress-bar wow fadeInLeft" role="progressbar" style="width: 80%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
														</div>
													</div>
													<span class="pac">40%</span>
												</div>
												<div class="single-review">
													<div class="stars">
														<i class="fa-solid fa-star"></i>
														<i class="fa-regular fa-star"></i>
														<i class="fa-regular fa-star"></i>
														<i class="fa-regular fa-star"></i>
														<i class="fa-regular fa-star"></i>
													</div>
													<div class="single-progress-area-incard">
														<div class="progress">
															<div class="progress-bar wow fadeInLeft" role="progressbar" style="width: 80%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
														</div>
													</div>
													<span class="pac">30%</span>
												</div>
											</div>
										</div>
										<div class="submit-review-area">
											<form action="#" class="submit-review-area">
												<h5 class="title">Submit Your Review</h5>
												<div class="your-rating">
													<span>Your Rating Of This Product :</span>
													<div class="stars">
														<i class="fa-solid fa-star"></i>
														<i class="fa-solid fa-star"></i>
														<i class="fa-solid fa-star"></i>
														<i class="fa-solid fa-star"></i>
														<i class="fa-solid fa-star"></i>
													</div>
												</div>
												<div class="half-input-wrapper">
													<div class="half-input">
														<input type="text" placeholder="Your Name*">
													</div>
													<div class="half-input">
														<input type="text" placeholder="Your Email *">
													</div>
												</div>
												<textarea name="#" id="#" placeholder="Write Your Review" required></textarea>
												<button class="rts-btn btn-primary">SUBMIT REVIEW</button>
											</form>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div> -->
        </div>
        <!-- <div class="col-lg-3  rts-sticky-column-item">
          <div class="theiaStickySidebar">
            <div class="shop-sight-sticky-sidevbar  mb--20">
              <h6 class="title">Available offers</h6>
              <div class="single-offer-area">
                <div class="icon">
                  <img src="assets/images/shop/01.svg" alt="icon">
                </div>
                <div class="details">
                  <p>Get %5 instant discount for the 1st Flipkart Order using Ekomart UPI T&C</p>
                </div>
              </div>
              <div class="single-offer-area">
                <div class="icon">
                  <img src="assets/images/shop/02.svg" alt="icon">
                </div>
                <div class="details">
                  <p>Flat R$250 off on Citi-branded Credit Card EMI Transactions on orders of R$30 and above T&C</p>
                </div>
              </div>
              <div class="single-offer-area">
                <div class="icon">
                  <img src="assets/images/shop/03.svg" alt="icon">
                </div>
                <div class="details">
                  <p>Free Worldwide Shipping on all
                    orders over R$100</p>
                </div>
              </div>
            </div>
            <div class="our-payment-method">
              <h5 class="title">Guaranteed Safe Checkout</h5>
              <img src="assets/images/shop/03.png" alt="">
            </div>
          </div>
        </div> -->
      </div>
    </div>
  </div>
</div>

<div id="anywhere-home" class="anywere"></div>

<div class="progress-wrap">
  <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
    <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98"
      style="transition: stroke-dashoffset 10ms linear 0s; stroke-dasharray: 307.919, 307.919; stroke-dashoffset: 307.919;">
    </path>
  </svg>
</div>

<script defer src="./assets/js/plugins.js"></script>
<script defer src="./assets/js/main.js"></script>

<?php require_once('./footer.php') ?>


</body>

</html>




<script type="text/javascript">
  $(document).ready(function() {
    var quant = $("#quantidade").val();
    $("#quant").text(quant);

  });

  function aumentarQuant() {
    var quant = $("#quantidade").val();
    var novo_valor = parseInt(quant) + parseInt(1);
    $("#quant").text(novo_valor)
    $("#quantidade").val(novo_valor);


    var total_quant = parseInt(quant) + parseInt(1);
    var total_inicial = '<?= $valor_item ?>';
    var total_it = parseFloat(total_inicial) * parseFloat(total_quant);

    $("#total_item").text(`R$ ${total_it.toFixed(2).replace('.', ',')}`);
    $("#total_item_input").val(total_it);
  }

  function diminuirQuant() {
    var quant = $("#quantidade").val();
    if (quant > 1) {
      var novo_valor = parseInt(quant) - parseInt(1);
      $("#quant").text(novo_valor)
      $("#quantidade").val(novo_valor)

      var total_quant = parseInt(quant) - parseInt(1);
      var total_inicial = '<?= $valor_item ?>';
      var total_it = parseFloat(total_inicial) * parseFloat(total_quant);
      $("#total_item").text(`R$ ${total_it.toFixed(2).replace('.', ',')}`);
      $("#total_item_input").val(total_it);
    }

  }
</script>





<script type="text/javascript">
  function addCarrinho() {
    var quantidade = $('#quantidade').val();
    var total_item = $('#total_item_input').val();
    var produto = "<?= $id_prod ?>";
    //var obs = $('#obs').val();


    total_item = parseFloat(total_item) / parseFloat(quantidade);

    if (total_item == "") {
      return;
    }

    $.ajax({
      url: 'js/ajax/add-carrinho.php',
      method: 'POST',
      data: {
        quantidade,
        total_item,
        produto,
      },
      dataType: "text",

      success: function(mensagem) {


        if (mensagem.trim() == "Inserido com Sucesso") {
          listarCarrinhoIcone();
          opencart();

        } else {
          console.log(mensagem);
          alert(mensagem)

        }

      },

    });
  }

  function opencart() {
    $('.category-hover-header .category-sub-menu').addClass('open-cart');
  }

  function closecart() {
    $('.category-hover-header .category-sub-menu').removeClass('open-cart');
  }

  $(document).on('click', function(e) {
    if ($(e.target).closest('.category-hover-header .category-sub-menu').length === 0 &&
      $(e.target).closest('.icone-carrinho').length === 0) {
      closecart();
      console.log(e);
    }
  })

  $carrinho.on('click', function(e) {
    e.stopPropagation();
  });
</script>