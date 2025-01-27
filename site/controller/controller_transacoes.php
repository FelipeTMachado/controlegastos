<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start(); // Inicia a sessão apenas se ainda não estiver iniciada
    }

    require '../vendor/autoload.php';
    use PhpAmqpLib\Connection\AMQPStreamConnection;
    use PhpAmqpLib\Message\AMQPMessage;

    if (!isset($_SESSION['logado'])) {
        header('Location: ../view/login.php?erro=Sistema não está logado');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $connection = new AMQPStreamConnection('rabbitmq-se-controla-ai', 5672, 'admin', 'admin');
        $channel = $connection->channel();

        $descricao = isset($_POST["descricaoTransacao"]) ? $_POST['descricaoTransacao'] : '' ;
        $valor = isset($_POST['valorTransacao']) ? $_POST['valorTransacao'] : '' ;
        $tipo = isset($_POST['tipoTransacao']) ? $_POST['tipoTransacao'] : '' ;
        $usuario = $_SESSION['usuario'];
        $mensagem = "Mensagem";
    
        $channel->queue_declare('transacao', false, true, false, false);
    
        $dados_transacao = json_encode(["descricao" => $descricao, "valor" => $valor, "tipo" => $tipo,  "mensagem" => $mensagem, "usuario" => $usuario]);
    
        $msg = new AMQPMessage($dados_transacao, [ 
            'delivery_mode'=> AMQPMessage::DELIVERY_MODE_PERSISTENT,
        ]);
    
        $channel->basic_publish($msg, '', 'transacao');

        $channel->close();
        $connection->close();

        header('Location: ../view/principal.php');
        exit;
    } elseif (debug_backtrace()) {
        // CODIGO QUE LE DA FILA TRANSACAO_PROCESSADO_SALVO_SUCESSO E MOSTRA NA TELA
        $connection = new AMQPStreamConnection('rabbitmq-se-controla-ai', 5672, 'admin', 'admin');
        $channel = $connection->channel();

        $channel->queue_declare('transacao', false, true, false, false);
        
        $transacoes = [];
        while ($msg = $channel->basic_get('transacao', false)) {
            $dados = json_decode($msg->getBody(), true);
            
            // SE O USUARIO FOR O QUE ESTIVER NA SESSAO ELE APAGA OS DADOS DA FILA SE NAO ELE MANTEM
            if ($dados['usuario'] === $_SESSION['usuario']){
                $transacoes[] = $dados;
                $channel->basic_nack($msg->getDeliveryTag());
            }
            // $transacoes[] = $dados;
        }

        $channel->close();
        $connection->close();
    }
