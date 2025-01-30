<?php
    session_start();

    require '../vendor/autoload.php';
    use PhpAmqpLib\Connection\AMQPStreamConnection;
    use PhpAmqpLib\Message\AMQPMessage;
    
    // Conexão com o RabbitMQ
    $connection = new AMQPStreamConnection('rabbitmq-se-controla-ai', 5672, 'admin', 'admin');
    $channel = $connection->channel();
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = (new DateTime('now', new DateTimeZone('America/Sao_Paulo')))->format('Y-m-d');
        $hora = (new DateTime('now', new DateTimeZone('America/Sao_Paulo')))->format('H:i:s');
    
        $usuario = isset($_POST['usuario']) ? $_POST['usuario'] : '';
        $senha = isset($_POST['senha']) ? $_POST['senha'] : '';
        $identificador = bin2hex(random_bytes(16));
    
        // Criar a mensagem para a fila
        $mensagem_dados = json_encode([
            "usuario" => $usuario,
            "senha" => $senha,
            "identificador" => $identificador,
            "status" => "login",
            "mensagem" => "",
            "data" => $data,
            "hora" => $hora
        ]);
    
        $mensagem = new AMQPMessage($mensagem_dados, [
            'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT,
        ]);
    
        // Enviar mensagem para a fila
        $channel->queue_declare('usuario', false, true, false, false);
        $channel->basic_publish($mensagem, '', 'usuario');
    
        // Armazena o identificador para verificar depois
        $_SESSION['identificador_pendente'] = $identificador;
        $_SESSION['usuario_dado_post'] = $_POST;

        // Redireciona para a página de espera
        header('Location: ./aguardando_login.php');
        exit;
    }
    
    // Fecha conexões
    $channel->close();
    $connection->close();
    