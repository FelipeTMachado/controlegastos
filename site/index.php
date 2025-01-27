<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Se controla AI</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body >
    <form method="POST"  action="./controller/controller_index.php" class="bg-gray-800 text-gray-200 flex flex-col min-h-screen">
        <!-- CABECALHO -->
        <header class="bg-gray-900 text-white py-4 shadow fixed top-0 w-full z-10">
            <h1 class="text-center text-2xl font-bold">Se controla AI</h1>
        </header>

        <!-- CONTEUDO PRINCIPAL -->
        <main class="bg-gray-800 max-w-4xl mx-auto py-32 px-4 flex-grow flex flex-col items-center justify-center text-center">
            <h2 class="text-3xl font-bold text-white mb-6">Gerencie suas finanças de forma simples e eficiente</h2>
            <p class="text-lg text-gray-300 mb-8">Descubra como é fácil acompanhar suas despesas e manter o controle do seu dinheiro com o <span class="text-blue-500">Se controla AI</span>.</p>
        

            <!-- <button class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50" type="submit">
                Entrar
            </button> -->

            <!-- Botão para acessar o sistema -->
            <button type="submit" name="acao" value="login" 
                class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-8 py-4 border border-transparent rounded-lg shadow-lg hover:from-blue-600 hover:to-blue-700 transform hover:scale-105 transition duration-300 mb-6">
                Acessar o Sistema
            </button>

            <!-- Botão para se cadastrar -->
            <button type="submit" name="acao" value="cadastro"
                class="bg-gradient-to-r from-green-500 to-green-600 text-white px-8 py-4 border border-transparent rounded-lg shadow-lg hover:from-green-600 hover:to-green-700 transform hover:scale-105 transition duration-300">
                Cadastre-se
            </button>
        </main>
        

        <!-- RODAPÉ -->
        <footer class="bg-gray-900 text-white py-4 text-center">
            <p>&copy; 2024 Controle de Gastos. Todos os direitos reservados.</p>
        </footer>
    </form>
</body>

</html>
