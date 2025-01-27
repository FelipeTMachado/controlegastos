<?php
    // Captura o erro da query string, se existir
    $erro = isset($_GET['erro']) ? $_GET['erro'] : '';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Se controla AI - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-900 text-gray-200">
    <div class="flex items-center justify-center min-h-screen">
        <form class="bg-gray-800 shadow-lg rounded-lg p-8 w-full max-w-sm" action="../controller/controller_login.php" method="POST">
            <h2 class="text-2xl font-bold text-center text-gray-100 mb-6">Conecte-se</h2>
            
            <?php if (isset($erro)): ?>
                <div class="mb-4 text-red-400 text-center">
                    <?= $erro; ?>
                </div>
            <?php endif; ?>

            <div class="mb-4">
                <label class="block text-gray-300 text-sm font-medium mb-2" for="usuario">
                    Usuário
                </label>
                <input class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" id="usuario" name="usuario" type="text" placeholder="Usuário" required>
            </div>

            <div class="mb-6">
                <label class="block text-gray-300 text-sm font-medium mb-2" for="senha">
                    Senha
                </label>
                <input class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" id="senha" name="senha" type="password" placeholder="**********" required>
            </div>

            <button class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50" type="submit">
                Entrar
            </button>
            
            <div class="mt-4 text-center">
                <a class="text-sm text-blue-400 hover:text-blue-500" href="#">Esqueceu sua senha?</a>
            </div>
        </form>
    </div>
</body>
</html>
