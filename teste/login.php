<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = isset($_POST['usuario']) ? $_POST['usuario'] : '';
    $senha = isset($_POST['senha']) ? $_POST['senha'] : '';

    
    if ($usuario === 'felipe' && $senha === '1234') {
        $_SESSION['logado']  = true;
        $_SESSION['usuario'] = $usuario;

        header('Location: ./view/principal.php');
        exit;
    } else {
        session_destroy();
        // header('Location: index.php'); 
        header('Location: index.php?erro=Usuário ou senha incorretos');
    }
}
?>