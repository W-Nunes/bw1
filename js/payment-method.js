function addfunctionButton(text) {
    const paymentButton = document.getElementById('payment-button');
    paymentButton.textContent = text;
    paymentButton.onclick = finalizarPedido;
    paymentButton.classList.remove('disable');
}


function removefunctionButton() {
    const paymentButton = document.getElementById('payment-button');
    paymentButton.textContent = 'Revisar Pagamento';
    paymentButton.onclick = null;
    paymentButton.classList.add('disable');
}

function toggleBoxTroco(method) {
    const boxTroco = document.getElementById('box-troco');
    if (method === 'show') {
        boxTroco.style.display = 'block'
    } else if (method === 'hide') {
        boxTroco.style.display = 'none'
        $('#troco').val('');
    }
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

    // Define a forma de pagamento como Pix
    $('#pagamento').val('Pix');

    // Garante que apenas o Pix esteja marcado
    document.getElementById('radio_credito').checked = false;
    document.getElementById('radio_debito').checked = false;
    document.getElementById('radio_dinheiro').checked = false;

    const paymentButton = document.getElementById('payment-button');
    paymentButton.textContent = 'Pagar com Pix';
    paymentButton.onclick = gerarCodigoPix;
    paymentButton.classList.remove('disable');
    toggleBoxTroco('hide');
}

function gerarCodigoPix() {

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

    var valor = total_compra_final.toFixed(2);
    console.log(valor);
    // $.ajax({
    //     url: 'js/ajax/pix.php',
    //     method: 'POST',
    //     data: {
    //         valor
    //     },
    //     dataType: "html",

    //     success: function (result) {
    //         $('#pix-modal-content').html(result);
    //         $('#pix-modal-overlay').fadeIn(200);
    //     },
    //     error: function () {
    //         alert('Erro ao gerar o PIX. Tente novamente.');
    //     }
    // });
}

function dinheiro() {

    document.getElementById('radio_credito').checked = false;
    document.getElementById('radio_debito').checked = false;
    document.getElementById('radio_pix').checked = false;


    $('#pagamento').val('Dinheiro');

    addfunctionButton('Pagar com Dinheiro');
    toggleBoxTroco('show');

}

function debito() {

    document.getElementById('radio_credito').checked = false;
    document.getElementById('radio_pix').checked = false;
    document.getElementById('radio_dinheiro').checked = false;

    $('#pagamento').val('Cartão de Débito');

    addfunctionButton('Pagar com Cartão de Débito');
    toggleBoxTroco('hide');

}

function credito() {

    document.getElementById('radio_pix').checked = false;
    document.getElementById('radio_debito').checked = false;
    document.getElementById('radio_dinheiro').checked = false;

    $('#pagamento').val('Cartão de Crédito');
    addfunctionButton('Pagar com Cartão de Crédito');
    toggleBoxTroco('hide');

}

function uncheckedMethods() {
    $('#pagamento').val('');
    removefunctionButton();
    document.getElementById('radio_credito').checked = false;
    document.getElementById('radio_pix').checked = false;
    document.getElementById('radio_debito').checked = false;
    document.getElementById('radio_dinheiro').checked = false;
    toggleBoxTroco('hide');

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
        return;
    }

    if (nome == "") {
        alert('Preencha seu Nome');
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
    var cupom = $('#valor-cupom').val();
    var pedido_whatsapp = "<?= $status_whatsapp ?>";

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
            return;
        }
    }



    if (taxa_entrega == "" && id_usuario == "") {
        alert("Digite um CEP válido para receber seu Pedido");
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


    var total_compra = "<? $total_carrinho ?>";
    var pedido_minimo = "<? $pedido_minimo ?>";

    console.log(total_compra);

    if (pedido_minimo > 0) {
        if (parseFloat(total_compra) < parseFloat(pedido_minimo)) {
            alert('Seu pedido precisar ser superior a R$' + pedido_minimo);
            return;
        }
    }

    if (entrega == "") {
        alert('Selecione uma forma de entrega');
        return;
    }



    if (entrega == "Delivery" && rua == "") {
        alert('Preencha o Campo Rua');
        return;
    }

    if (entrega == "Delivery" && numero == "") {
        alert('Preencha o Campo Número');
        return;
    }

    if (entrega == "Delivery" && bairro == "") {
        alert('Escolha um Bairro');
        return;
    }



    if (pagamento == "") {
        alert('Selecione uma forma de pagamento');
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


    // $.ajax({
    //     url: 'js/ajax/inserir-pedido.php',
    //     method: 'POST',
    //     data: {
    //         pagamento,
    //         entrega,
    //         rua,
    //         numero,
    //         bairro,
    //         complemento,
    //         troco,
    //         obs,
    //         nome_cliente,
    //         tel_cliente,
    //         id_cliente,
    //         mesa,
    //         cupom,
    //         codigo_pix,
    //         cep,
    //         cidade,
    //         taxa_entrega
    //     },
    //     dataType: "html",


    //     success: function (mensagem) {
    //         //alert(mensagem)      

    //         if (mensagem == 'Pagamento nao realizado!!') {
    //             alert('Realize o Pagamento antes de Prosseguir!!');
    //             $('#botao_finalizar').show();
    //             $('#div_img').hide();
    //             return;
    //         }

    //         var msg = mensagem.split("*")


    //         $('#mensagem').text('');
    //         $('#mensagem').removeClass()
    //         if (msg[1].trim() == "Pedido Finalizado") {

    //             setTimeout(() => {
    //                 alert('Pedido Finalizado!');
    //                 if (id_usuario == "") {

    //                     //chamar o relatorio da reserva 
    //                     var id = msg[2].trim();
    //                     window.open("sistema/painel/rel/comprovante2_class.php?id=" + id + "&enviar=sim");
    //                     window.location = 'index.php';
    //                 } else {
    //                     window.location = 'sistema/painel/index.php?pagina=novo_pedido';
    //                 }

    //             }, 500);



    //         } else {

    //         }



    //         if (pedido_whatsapp == 'Sim') {
    //             let a = document.createElement('a');
    //             //a.target= '_blank';
    //             a.href = 'http://api.whatsapp.com/send?1=pt_BR&phone=<?= $whatsapp_sistema ?>&text= *Novo Pedido*  %0A Hora: *' + hora + '* %0A Total: R$ *' + total_compra_finalF + '* %0A Entrega: *' + entrega + '* %0A Pagamento: *' + pagamento + '* %0A Cliente: *' + nome_cliente + '* %0A Previsão de Entrega: *' + result + '*';
    //             a.click();
    //         } else if (pedido_whatsapp == 'Api') {
    //             /*
    //              $.ajax({
    //                 url: 'https://api.callmebot.com/whatsapp.php?phone=+553171390746&text=*Novo Pedido*  %0A Hora: *' + hora + '* %0A Total: R$ *' + total_compra_finalF + '* %0A Entrega: *' + entrega + '* %0A Pagamento: *' + pagamento + '* %0A Cliente: *' + nome_cliente + '* %0A Previsão de Entrega: *' + result + '*&apikey=320525',
    //                  method: 'GET',          
                     
    //                 });
    //                 */
    //         } else {

    //         }



    //         $('#botao_finalizar').show();
    //         $('#div_img').hide();

    //     }
    // });

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

