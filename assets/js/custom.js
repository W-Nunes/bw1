// Função para abrir modal
function openModal(id) {

  document.getElementById(id).style.display = "flex";

}

// Função para fechar modal
function closeModal(id) {
  document.getElementById(id).style.display = "none";
}

// Fechar clicando fora do conteúdo
window.onclick = function (event) {
  if (event.target.classList.contains('modal')) {
    event.target.style.display = "none";
  }
}

document.getElementById('form-info').addEventListener('submit', function (e) {
  e.preventDefault();
  const name = document.getElementById('nome').value.trim();
  const phone = document.getElementById('telefone').value.trim();
  const namespan = document.getElementById('name-span');
  const phonespan = document.getElementById('phone-span');
  const erroSpan = document.getElementById('erro-personal');

  let mensagensErro = [];

  if (!name) {
    mensagensErro.push("Nome não informado");
  }
  if (!phone) {
    mensagensErro.push("Telefone não informado");
  }else if(phone.length < 15){
    mensagensErro.push("Telefone imcompleto");
  }

  if (mensagensErro.length > 0) {
    console.log(mensagensErro);
    erroSpan.textContent = mensagensErro.join(" • ");
  } else {
    erroSpan.textContent = "";
    namespan.textContent = name;
    phonespan.textContent = phone;
    closeModal('modal-info');
  }

});

document.getElementById('form-address').addEventListener('submit', function (e) {
  e.preventDefault();
  const rua = document.getElementById('rua').value.trim();
  const numero = document.getElementById('numero').value.trim();
  const complemento = document.getElementById('complemento').value.trim();
  const bairro = document.getElementById('bairro').value.trim();
  const erroSpan = document.getElementById('erro-endereco');

  let mensagensErro = [];

  if (!rua) {
    mensagensErro.push("Rua não informada");
  }
  if (!numero) {
    mensagensErro.push("Número não informado");
  }
  if (!bairro) {
    mensagensErro.push("Bairro não informado");
  }

  if (mensagensErro.length > 0) {
    erroSpan.textContent = mensagensErro.join(" • ");
  } else {
    erroSpan.textContent = "";
    addressInput.value = `${rua}, ${numero}${complemento ? ', ' + complemento : ''} - ${bairro}, Rio de Janeiro`;
    closeModal('modal-address');
  }
});
