<?php 

//VARIAVEIS GLOBAIS
$nome_sistema = "Barboseira Bebidas Sistema PDV";
$email_adm = 'contato@barboseirabebidas.com.br';

// Configuração correta da URL para o domínio "suporteverde.com.br"
$url_sistema = "https://$_SERVER[HTTP_HOST]/site/barboseira-sistema-pdv/";
$url = explode("//", $url_sistema);

if ($url[1] == 'localhost/') {
    // Quando estiver em localhost (para testes locais)
    $url_sistema = "http://$_SERVER[HTTP_HOST]/pdv/";
}



$telefone_sistema = "(21) 37965-239";
$endereco_sistema = " Av. Mem de Sá, 17 - Lapa, Rio de Janeiro ";
$rodape_relatorios = "Sistema Desenvolvido por Suporte verde";
$cnpj_sistema = '39.701.565/0001-31';
$fonte_comprovante = '11';


//VARIAVEIS PARA O BANCO DE DADOS LOCAL
$servidor = 'localhost';
$usuario = 'u428276314_barboseirapdv';
$senha_bd = 'B@boseirapdv123';
$banco = 'u428276314_barboseirapdv';


//VARIAVEIS DE CONFIGURAÇÕES DO SISTEMA

$nivel_estoque_minimo = 8; //A PARTIR DE 8 PRODUTOS ELE COMEÇA A APONTAR COMO ESTOQUE BAIXO NO ALERTA.

$relatorio_pdf = 'Sim'; //Se você utilizar sim ele vai gerar os relatórios utilizando a biblioteca do dompdf configurada para o PHP 8.0, se você utilizar outra versão do PHP ou do DOMPDF pode ser que ele não gere o relatório da forma correta, caso você utilize não ele vai gerar o relatório html.

$cabecalho_img_rel = 'Sim'; // Se você optar por sim, os relatórios serão exibidos com uma imagem de cabeçalho, você precisará editar o arquivo PSD para poder alterar as informações referentes a sua empresa, caso não queira basta deixar como não e ele vai pegar os valores das variaveis globais declaradas acima.


$desconto_porcentagem = 'Sim'; //Se essa variavel receber sim o desconto aplicado na tela de pdv será em %, caso contrário ele será em R$.

$cupom_fiscal = 'Não'; //Se você utilizar sim, ele irá apontar para a api que vai gerar o cupom fiscal(Não configurado nesse curso, você vai precisar utilizar uma api externa para isso um dos modelos pode ser encontrado em https://plugnotas.com.br), caso contrário ele gera o cupom não fiscal já configurado no curso.

$largura_cod_barras = 3; //Usar 2 ou 3, 2 ficará com uma lagura um pouco menor o codigo de barras (só se for um produto muito pequeno);

$altura_cod_barras = 50; //Tamanho padrão de 50, podendo diminuir caso tenha necessidade (Não colocar um tamanho menor que 20)


$etiquetas_por_linha = 5; //5 etiquetas de código de barras por cada linha na pagina

$linhas_etiquetas_pag = 14; // total de linhas por pagina, neste exemplo sendo 5 etiquetas por linha e 14 paginas seriam um total de 70 etiquetas de código impresso

//marca dagua relatorio
$marca_dagua = 'Sim';
 ?>