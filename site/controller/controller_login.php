<?php
    session_start();

    require '../vendor/autoload.php';
    use PhpAmqpLib\Connection\AMQPStreamConnection;
    use PhpAmqpLib\Message\AMQPMessage;


    // CONEXAO COM O RABBITMQ
    $connection = new AMQPStreamConnection('rabbitmq-se-controla-ai', 5672, 'admin', 'admin');
    $channel = $connection->channel();


    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = (new DateTime('now', new DateTimeZone('America/Sao_Paulo')))->format('Y-m-d');
        $hora = (new DateTime('now', new DateTimeZone('America/Sao_Paulo')))->format('H:i:s');

        $usuario       = isset($_POST['usuario']) ? $_POST['usuario'] : '';
        $senha         = isset($_POST['senha']) ? $_POST['senha'] : '';
        $identificador = bin2hex(random_bytes(16)); 
        $status        = 'login';
        $mensagem      = '';

        // DECLARA A FILA
        $channel->queue_declare('usuario', false, true, false, false);

        // CRIAR A MENSAGEM COM OS DADOS DO USUÁRIO E SENHA
        $mensagem_dados = json_encode([
            "usuario" => $usuario, 
            "senha" => $senha, 
            "identificador" => $identificador, 
            "status" => $status, 
            "mensagem" => $mensagem, 
            "data" => $data,
            "hora" => $hora
        ]);
        
        $mensagem = new AMQPMessage($mensagem_dados, [ 
            'delivery_mode'=> AMQPMessage::DELIVERY_MODE_PERSISTENT,
        ]);

        // include 'controller/escritorcontroller.php';

        // enviarParaFila(["usuario" => $usuario, "senha" => $senha, "identificador" => $identificador, "status" => $status, "Mensagem" => $mensagem], 'usuario');
        
        // ENVIA A MENSAGEM PARA A FILA
        $channel->basic_publish($mensagem, '', 'usuario');

        


        if (($usuario === 'felipe' && $senha === '12345678') || ($usuario === 'bianca' && $senha === '1234')) {    
            $_SESSION['logado']  = true;
            $_SESSION['usuario'] = $usuario;
            $_SESSION['identificador'] = $identificador;

            header('Location: ../view/principal.php');
            exit;
        } else {
            session_destroy();
            header('Location: ../view/login.php?erro=Usuário ou senha incorretos');
        }
    }

    // FECHAR CONEXOES
    $channel->close();
    $connection->close();
