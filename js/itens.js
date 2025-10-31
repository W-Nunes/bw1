document.addEventListener('DOMContentLoaded', () => {
  // Seleciona todos os inputs
  const inputs = document.querySelectorAll('.input[data-id]');

  inputs.forEach(input => {
    const prodId = input.getAttribute('data-id');

    // Botão de diminuir
    const minusButton = document.querySelector(
      `.button-wrapper-action .button[data-id="${prodId}"]:not(.plus)`
    );

    // Botão de aumentar
    const plusButton = document.querySelector(
      `.button-wrapper-action .button.plus[data-id="${prodId}"]`
    );

    // Listener para input (quando o valor for alterado manualmente)
    input.addEventListener('input', () => {
      console.log(`Produto ${prodId} - Novo valor: ${input.value}`);
    });

    // Listener para diminuir
    if (minusButton) {
      minusButton.addEventListener('click', (e) => {
        e.preventDefault();
        let valorAtual = parseInt(input.value) || 1;
        if (valorAtual > 1) {
          input.value = valorAtual - 1;
          console.log(`Produto ${prodId} - Diminuído para: ${input.value}`);
        }
      });
    }

    // Listener para aumentar
    if (plusButton) {
      plusButton.addEventListener('click', (e) => {
        e.preventDefault();
        let valorAtual = parseInt(input.value) || 1;
        input.value = valorAtual + 1;
        console.log(`Produto ${prodId} - Aumentado para: ${input.value}`);
      });
    }
  });
});
