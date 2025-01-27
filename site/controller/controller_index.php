<?php
    if (isset($_POST['acao'])) {
        $acao = $_POST['acao'];

        if ($acao == 'cadastro') {
            header('Location: ../view/cadastro.php');
        } elseif ($acao = 'login') {
            header('Location: ../view/login.php');   
        }
    } else {
        header('Location: ../index.php');
    }

    
