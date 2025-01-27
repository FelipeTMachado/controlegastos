<?php
    session_start();

    if (!isset($_SESSION['logado'])) {
        header('Location: login.php?erro=Sistema não está logado');
        exit;
    }

    // require '../controller/session.php';
    require '../controller/controller_transacoes.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Se controla AI - Transacoes</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-800 text-gray-800 flex flex-col min-h-screen">
    <!-- CABECALHO FIXO - LEMBRAR DE ADICIONAR EM TODAS AS TELAS -->
    <header class="bg-gray-900 text-white py-4 shadow fixed top-0 w-full z-10 flex justify-between items-center px-4">
        <h1 class="text-2xl font-bold">Se controla AI</h1>
        <form action="../controller/controller_logout.php" method="POST">
            <button type="submit" class="text-gray-400 hover:text-white transition-colors">
                Sair do Sistema
            </button>
        </form>
    </header>

    <!-- CONTEUDO DO SITE -->
    <main class="bg-gray-800 max-w-4xl mx-auto py-32 px-4 flex-grow">
        <!-- GRUPO COM OS TOTAIS (SALDO, DESPESAS e CARTEIRAS) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-gray-900 p-6 rounded-lg shadow-md">
                <h2 class="text-lg font-semibold text-white">Saldo</h2>
                <?php 
                    //  var_dump(($transacoes));
                    $saldo = 0.0;

                    foreach ($transacoes as $transacao) { 
                        if ($transacao['usuario'] === $_SESSION['usuario']) {
                            if ($transacao['tipo'] === '1') {
                                $saldo += (float) $transacao['valor'];
                            }
                        }
                    }

                    echo '<p class="text-2xl font-bold text-green-500">' . number_format((float)$saldo, 2, ',', '.') . '</p>';
                ?>
            </div>
            <div class="bg-gray-900 p-6 rounded-lg shadow-md">
                <h2 class="text-lg font-semibold text-white">Despesas</h2>
                <?php 
                    //  var_dump(($transacoes));
                    $despesa = 0.0;

                    foreach ($transacoes as $transacao) { 
                        if ($transacao['usuario'] === $_SESSION['usuario']) {
                            if ($transacao['tipo'] === '2') {
                                $despesa += (float) $transacao['valor'];
                            }
                        }
                    }

                    echo '<p class="text-2xl font-bold text-red-500">' . number_format((float)$despesa, 2, ',', '.') . '</p>';
                ?>
            </div>
            <div class="bg-gray-900 p-6 rounded-lg shadow-md">
                <h2 class="text-lg font-semibold text-white">Total nas Carteiras</h2>

                <?php 
                    //  var_dump(($transacoes));
                    $total = 0.0;

                    foreach ($transacoes as $transacao) { 
                        if ($transacao['usuario'] === $_SESSION['usuario']) {
                            if ($transacao['tipo'] === '2') {
                                $total -= (float) $transacao['valor'];
                            } else {
                                if ($transacao['tipo'] === '1') {
                                    $total += (float) $transacao['valor'];
                                }
                            }
                        }   
                    }

                    echo '<p class="text-2xl font-bold text-blue-500">' . number_format((float)$total, 2, ',', '.') . '</p>';
                ?>
            </div>
        </div>

        <!-- LISTA DE TRANSACOES FEITAS -->
        <section class="mt-8">
            <h2 class="text-xl font-bold text-white">Transações Recentes</h2>
            <ul id="div-transacoes" class="mt-4 space-y-4">
                <!-- CODIGO PHP PARA TRAZER OS DADOS -->
                <?php 
                    // var_dump($transacoes);                
                    foreach ($transacoes as $transacao) {
                        if ($transacao['usuario'] === $_SESSION['usuario']) {
                            // echo json_encode($transacao);
                            echo '<li class="bg-gray-900 p-4 rounded-lg shadow flex justify-between items-center">';
                            echo '<span class="text-white font-semibold">' . $transacao["descricao"] . '</span>';

                            if ($transacao['tipo'] === '2') {
                                echo '<span class="text-red-500 font-semibold">R$' . number_format((float)$transacao["valor"], 2, ',', '.') . '</span>';
                            } else { 
                                echo '<span class="text-green-500 font-semibold">R$' . number_format((float)$transacao["valor"], 2, ',', '.') . '</span>';
                            }
                        }
                    }
                ?>
                </li>

            </ul>
        </section>

        <!-- BOTÃO PARA ADICIONAR NOVAS TRANSACOES -->
        <div class="mt-8 text-center">
            <button type="" class="bg-gray-600 text-white px-6 py-2 rounded-lg shadow hover:bg-blue-700"
                onclick="showForm()">
                Adicionar Transação
            </button>
        </div>
    </main>

    <!-- RODAPÉ - LEMBRAR DE ADICIONAR EM TODAS AS TELAS -->
    <footer class="bg-gray-900 text-white py-4 text-center">
        <p>&copy; 2024 Controle de Gastos. Todos os direitos reservados.</p>
    </footer>


    <div class="fixed hidden inset-0 flex items-center justify-center z-50 backdrop-blur-sm bg-gray-800 bg-opacity-50"
        id="formTransacao">
        <!-- hidden -->
        <div class="bg-gray-900 p-4 rounded-lg shadow w-1/5 h-2/5 flex flex-col justify-bewtween items-center">
            <div class="w-full h-1 flex justify-end">
                <button onclick="hideForm()"
                    class="bg-red-600 text-white w-6 h-6 flex justify-center items-center rounded-full border-2 border-red-800 hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-300 focus:ring-offset-1 transition duration-300 shadow-md">
                    ✕
                </button>
            </div>

            <div class="mt-12">
                <!-- 3rem -->
                <form action="../controller/controller_transacoes.php" class="formTransacao flex flex-col" method="POST">
                    <div class="flex flex-col">
                        <label for="descricaoTransacao" class="text-white">Descrição</label>
                        <input type="text"
                            class="inputTransacao w-full px-2 py-2 bg-gray-700 border border-gray-600 rounded-lg text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            id="descricaoTransacao" name="descricaoTransacao" required
                            placeholder="Descrição da transação">
                    </div>

                    <!-- Valor -->
                    <div class="flex flex-col">
                        <label for="valorTransacao" class="text-white mt-4">Valor (R$)</label>
                        <input type="number"
                            class="inputTransacao w-full px-2 py-2 bg-gray-700 border border-gray-600 rounded-lg text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                            id="valorTransacao" name="valorTransacao" required placeholder="R$0,00">
                    </div>


                    <!-- Seleção da Transação -->
                    <div class="flex mt-4 justify-between">
                        <div id="adicionarDiv"
                            class="border-gray-600 border p-2 rounded-l-lg cursor-pointer w-full hover:border-green-900 text-green-900"
                            onclick="selectValue(1)">
                            <button type="button" class="text-white w-full h-full">Deposito</button>
                        </div>

                        <div id="depositarDiv"
                            class="border-gray-600 p-2 border rounded-r-lg cursor-pointer w-full border-l-0 hover:border-red-900 text-red-900"
                            onclick="selectValue(2)">
                            <button type="button" class="text-white w-full h-full">Debito</button>
                        </div>
                    </div>

                    <!-- Submissão do Formulário -->
                    <button type="submit" onclick="hideForm()"
                        class="mt-4 bg-gray-600 text-white px-1 py-2 rounded-lg shadow hover:bg-blue-700">Concluir
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="principal.js"></script>
</body>

</html>