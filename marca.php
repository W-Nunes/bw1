<?php
require_once("sistema/conexao.php");

// ---------------- Parâmetros ----------------
$url_categoria = $_GET['url'] ?? '';
$marcaSelecionada = $_GET['marca'] ?? '';

// ---------------- Categoria ----------------
$cat_nome = $cat_descricao = $cat_id = '';

if ($url_categoria !== '') {
  // use prepared statements para evitar SQL injection
  $st = $pdo->prepare("SELECT * FROM categorias WHERE url = :url LIMIT 1");
  $st->execute([':url' => $url_categoria]);
  $cat = $st->fetch(PDO::FETCH_ASSOC);

  if ($cat) {
    $cat_nome = $cat['nome'];
    $cat_descricao = $cat['descricao'];
    $cat_id = $cat['id'];

    @session_start();
    $sessao = @$_SESSION['sessao_usuario'];
    if ($sessao) {
      $stDel = $pdo->prepare("DELETE FROM temp WHERE carrinho = 0 AND sessao = :sessao");
      $stDel->execute([':sessao' => $sessao]);
    }
  }
}

// ---------------- Produtos da categoria ----------------
$resProd = [];
if ($cat_id !== '') {
  $stp = $pdo->prepare("SELECT * FROM produtos WHERE categoria = :cat AND ativo = 'Sim'");
  $stp->execute([':cat' => $cat_id]);
  $resProd = $stp->fetchAll(PDO::FETCH_ASSOC);
}

// ---------------- Helpers de acento/slug/imagem ----------------
function removerAcentos($string) {
  $acentos = [
    'á'=>'a','à'=>'a','ã'=>'a','â'=>'a','ä'=>'a','å'=>'a',
    'é'=>'e','è'=>'e','ê'=>'e','ë'=>'e',
    'í'=>'i','ì'=>'i','î'=>'i','ï'=>'i',
    'ó'=>'o','ò'=>'o','õ'=>'o','ô'=>'o','ö'=>'o',
    'ú'=>'u','ù'=>'u','û'=>'u','ü'=>'u',
    'ç'=>'c','ñ'=>'n',
    'Á'=>'A','À'=>'A','Ã'=>'A','Â'=>'A','Ä'=>'A','Å'=>'A',
    'É'=>'E','È'=>'E','Ê'=>'E','Ë'=>'E',
    'Í'=>'I','Ì'=>'I','Î'=>'I','Ï'=>'I',
    'Ó'=>'O','Ò'=>'O','Õ'=>'O','Ô'=>'O','Ö'=>'O',
    'Ú'=>'U','Ù'=>'U','Û'=>'U','Ü'=>'U','Ç'=>'C','Ñ'=>'N'
  ];
  return strtr($string, $acentos);
}
function normalizarString($string) {
  $string = removerAcentos($string);
  return mb_strtolower($string, 'UTF-8');
}
function slug_marca(string $nome): string {
  $s = normalizarString($nome);
  $s = preg_replace('/\s+/', '-', $s);
  $s = preg_replace('/[^a-z0-9\-]/', '', $s);
  return trim($s, '-');
}
function img_marca(string $marca): string {
  $slug = slug_marca($marca);
  // ajuste o diretório conforme o seu projeto:
  $baseRel = "/imgs/marcas/$slug";
  $baseAbsRoot = rtrim($_SERVER['DOCUMENT_ROOT'] ?? '', '/');
  foreach (['.svg','.png','.jpg','.jpeg','.webp'] as $ext) {
    $abs = $baseAbsRoot . $baseRel . $ext;
    if ($abs && file_exists($abs)) return $baseRel . $ext;
  }
  return "/imgs/marcas/_default.png";
}

// ---------------- Lista de marcas por categoria ----------------
$marcas = [];
if ($url_categoria === 'refrigerantes-e-sucos') {
  $marcas = [
    'Coca Cola','Guaraná Antarctica','H2OH','Pepsi','Soda Antarctica',
    'St. Pierre','Sukita','Kero Coco','Guaravita','Guaraviton','Lipton'
  ];
} elseif ($url_categoria === 'cervejas') {
  $marcas = [
    'Antarctica','Original','Becks','Bohemia','Brahma','Budweiser','Caracu',
    'Colorado','Corona','Heineken','Michelob Ultra','Goose Island',
    'Patagonia','Skol','Spaten','Stella Artois'
  ];
}

// ---------------- Monta $produtosPorMarca ----------------
$produtosPorMarca = [];
if ($marcas && $resProd) {
  foreach ($resProd as $produto) {
    $produtoNomeNorm = normalizarString($produto['nome']);
    foreach ($marcas as $m) {
      $marcaNorm = normalizarString($m);
      if (strpos($produtoNomeNorm, $marcaNorm) !== false) {
        $produtosPorMarca[$m][] = $produto;
      }
    }
  }
}

var_dump($produtosPorMarca);
?>


