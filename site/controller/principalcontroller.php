<?php
    session_start();

    if (!isset($_SESSION['logado'])) {
        header('Location: ../index.php?erro=Sistema não está logado');
        exit;
    }
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['deslogar'])) {
            $action = $_POST['deslogar'];
            
            if ($action === 'enviar') {
                echo "Formulário enviado!";
            } elseif ($action === 'logout') {
                session_destroy(); 
                header("Location: ../index.php"); 
                exit;
            }
        }
    }
?>