const form = document.getElementById('formTransacao');
const adicionar = document.getElementById('adicionar');
const depositar = document.getElementById('depositar');
let tipoTransacao = "";

function showForm() {
    form.classList.remove('hidden');
}

function hideForm() {
    form.classList.add('hidden');
    removeColors();
}

function removeColors() {
    document.getElementById('adicionarDiv').classList.remove('bg-green-600');
    document.getElementById('depositarDiv').classList.remove('bg-red-600');
}

function selectValue(valor) {

    removeColors();

    if (valor === 1) {
        // document.getElementById('adicionarDiv').classList.remove('bg-green-900');
        document.getElementById('adicionarDiv').classList.add('bg-green-600');
        tipoTransacao = 1;  // Armazenar a transação selecionada 
    } else if (valor === 2) {
        // document.getElementById('depositarDiv').classList.remove('bg-red-900');
        document.getElementById('depositarDiv').classList.add('bg-red-600');
        tipoTransacao = 2;  // Armazenar a transação selecionada (DEPOSITAR)
    }
}

document.querySelector('.formTransacao').addEventListener('submit', function(e) {
    const inputTipoTransacao = document.createElement('input');
    inputTipoTransacao.type = 'hidden';
    inputTipoTransacao.name = 'tipoTransacao';
    inputTipoTransacao.value = tipoTransacao;
    this.appendChild(inputTipoTransacao);
});

