<?php
    session_start();

    if (!isset($_SESSION['logado'])) {
        header('Location: ../index.php?erro=Sistema não está logado');
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Se controla AI</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-800 text-gray-800 flex flex-col min-h-screen">
  <!-- CABECALHO FIXO - LEMBRAR DE ADICIONAR EM TODAS AS TELAS -->
  <header class="bg-gray-900 text-white py-4 shadow fixed top-0 w-full z-10">
    <h1 class="text-center text-2xl font-bold">Se controla AI</h1>
  </header>

  <!-- CONTEUDO DO SITE -->
  <main class="bg-gray-800 max-w-4xl mx-auto py-32 px-4 flex-grow">
    <!-- GRUPO COM OS TOTAIS (SALDO, DESPESAS e CARTEIRAS) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div class="bg-gray-900 p-6 rounded-lg shadow-md">
        <h2 class="text-lg font-semibold text-white">Saldo</h2>
        <p class="text-2xl font-bold text-green-500">R$ 1.200,00</p>
      </div>
      <div class="bg-gray-900 p-6 rounded-lg shadow-md">
        <h2 class="text-lg font-semibold text-white">Despesas</h2>
        <p class="text-2xl font-bold text-red-500">R$ 800,00</p>
      </div>
      <div class="bg-gray-900 p-6 rounded-lg shadow-md">
        <h2 class="text-lg font-semibold text-white">Total nas Carteiras</h2>
        <p class="text-2xl font-bold text-blue-500">R$ 2.000,00</p>
      </div>
    </div>

    <!-- LISTA DE TRANSACOES FEITAS -->
    <section class="mt-8">
      <h2 class="text-xl font-bold text-white">Transações Recentes</h2>
      <ul class="mt-4 space-y-4">
        <li class="bg-gray-900 p-4 rounded-lg shadow flex justify-between items-center">
          <span class="text-white font-semibold">Aluguel</span>
          <span class="text-red-500 font-semibold">- R$ 500,00</span>
        </li>
        <li class="bg-gray-900 p-4 rounded-lg shadow flex justify-between items-center">
          <span class="text-white font-semibold">Mercado</span>
          <span class="text-red-500 font-semibold">- R$ 300,00</span>
        </li>
        <li class="bg-gray-900 p-4 rounded-lg shadow flex justify-between items-center">
          <span class="text-white font-semibold">Salário</span>
          <span class="text-green-500 font-semibold">+ R$ 2.000,00</span>
        </li>
        <li class="bg-gray-900 p-4 rounded-lg shadow flex justify-between items-center">
          <span class="text-white font-semibold">Salário</span>
          <span class="text-green-500 font-semibold">+ R$ 2.000,00</span>
        </li>
        <li class="bg-gray-900 p-4 rounded-lg shadow flex justify-between items-center">
          <span class="text-white font-semibold">Salário</span>
          <span class="text-green-500 font-semibold">+ R$ 2.000,00</span>
        </li>
        <li class="bg-gray-900 p-4 rounded-lg shadow flex justify-between items-center">
          <span class="text-white font-semibold">Salário</span>
          <span class="text-green-500 font-semibold">+ R$ 2.000,00</span>
        </li>
        <li class="bg-gray-900 p-4 rounded-lg shadow flex justify-between items-center">
          <span class="text-white font-semibold">Salário</span>
          <span class="text-green-500 font-semibold">+ R$ 2.000,00</span>
        </li>
      </ul>
    </section>

    <!-- BOTÃO PARA ADICIONAR NOVAS TRANSACOES -->
    <div class="mt-8 text-center">
      <button type="" class="bg-gray-600 text-white px-6 py-2 rounded-lg shadow hover:bg-blue-700" onclick="showForm()">
        Adicionar Transação
      </button>
    </div>
  </main>

  <!-- RODAPÉ - LEMBRAR DE ADICIONAR EM TODAS AS TELAS -->
  <footer class="bg-gray-900 text-white py-4 text-center">
    <p>&copy; 2024 Controle de Gastos. Todos os direitos reservados.</p>
  </footer>


  <div class="fixed hidden inset-0 flex items-center justify-center z-50 backdrop-blur-sm" id="formTransacao">

    <div class="bg-gray-900 p-4 flex rounded-lg shadow w-1/4 h-1/2 flex justify-center items-center">
      
      <form action="" class="formTransacao flex flex-col">
        <div class="flex flex-col"> <!-- Adicionando flex e gap -->
          <label for="descricaoTransacao" class="text-white">Descrição</label>
          <input type="text" class="inputTransacao w-full px-4 py-1 bg-gray-700 border border-gray-600 rounded-lg text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" id="descricaoTransacao" name="descricaoTransacao" required placeholder="Descrição da transação">
        </div>

        <div class="flex flex-col"> <!-- Adicionando flex e gap -->
          <label for="valorTransacao" class="text-white mt-4">Valor (R$)</label>
          <input type="number" class="inputTransacao w-full px-4 py-1 bg-gray-700 border border-gray-600 rounded-lg text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" id="valorTransacao" name="valorTransacao" required placeholder="R$0,00">
        </div>

        <div class="flex mt-4 justify-around"> <!-- Adicionando flex e gap -->
          <div class="border-green-900 border p-2 rounded-lg">
            <label for="adicionar" class="text-white">Adicionar</label>
            <input type="radio" id="adicionar" name="tipoTransacao" value="adicionar">
          </div>

          <div class="border border-red-900 p-2 rounded-lg">
            <label for="depositar" class="text-white">Depositar</label>
            <input type="radio" id="depositar" name="tipoTransacao" value="depositar">
          </div>          
        </div>

        <button type="submit" onclick="hideForm()" class="mt-4 bg-gray-600 text-white px-1 py-1 rounded-lg shadow hover:bg-blue-700">Concluir</button>
      </form>

    </div>
  </div>

  <script src="index.js"></script>
</body>
</html>
