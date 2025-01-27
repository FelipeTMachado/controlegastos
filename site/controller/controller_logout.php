<?php 
    // Inicia a sessão
    session_start();

    // Destrói todas as variáveis de sessão
    session_unset();

    // Destrói a sessão
    session_destroy();

    header('Location: ../view/login.php?erro=Sistema deslogado');
    exit;