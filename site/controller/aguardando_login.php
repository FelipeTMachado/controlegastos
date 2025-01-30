<?php
    session_start();
    require '../vendor/autoload.php';
    use PhpAmqpLib\Connection\AMQPStreamConnection;
    
    if (!isset($_SESSION['identificador_pendente'])) {
        header('Location: ../view/login.php');
        exit;
    }
    
    $identificador_pendente = $_SESSION['identificador_pendente'];
    
    $connection = new AMQPStreamConnection('rabbitmq-se-controla-ai', 5672, 'admin', 'admin');
    $channel = $connection->channel();
    $channel->queue_declare('usuario_processado_analisado_sucesso', false, true, false, false);
    $channel->queue_declare('usuario_processado_analisado_erro', false, true, false, false);
    
    $dados_usuario = null;
    
    // Consome a fila de sucesso
    $callback = function ($msg) use ($identificador_pendente, &$dados_usuario) {
        $dados = json_decode($msg->body, true);
        
        if ($dados['identificador'] === $identificador_pendente) {
            $dados_usuario = $dados;
        }
    };
    
    $channel->basic_consume('usuario_processado_analisado_sucesso', '', false, true, false, false, $callback);
    $channel->basic_consume('usuario_processado_analisado_erro', '', false, true, false, false, $callback);
    
    // Tenta buscar a resposta por 10 segundos
    $tempo_inicio = time();
    while (time() - $tempo_inicio < 10) {
        $channel->wait(null, false, 2);
        
        if ($dados_usuario) {
            $usuario_dado_post = $_SESSION['usuario_dado_post'];

            if (($dados_usuario['usuario'] === $usuario_dado_post['usuario']) && 
                ($dados_usuario['senha'] === $usuario_dado_post['senha'])) {
                    
                $_SESSION['usuario_dados'] = $dados_usuario;
                $_SESSION['logado'] = true;

                header('Location: ../view/principal.php');
                exit;
            }

            
        }
    }
    
    // Se não houver resposta, retorna erro
    header('Location: ../view/login.php?erro=Tempo de resposta excedido');
    exit;
    