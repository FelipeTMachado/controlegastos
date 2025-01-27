<?php
    require 'vendor/autoload.php';
    use PhpAmqpLib\Connection\AMQPStreamConnection;
    use PhpAmqpLib\Message\AMQPMessage;
    use Dotenv\Dotenv;

    require_once __DIR__ . '/shared/logs.php';
    require_once __DIR__ . '/shared/variaveis.php';
    require_once __DIR__ . '/model/usuario.php';

    function esperarRabbitMQ($host, $port, $tentativas = 10, $intervalo = 2) {
        for ($i = 0; $i < $tentativas; $i++) {
            $conexao = @fsockopen($host, $port);
            if ($conexao) {
                fclose($conexao);
                saveLogUsuario("Conexão com o RabbitMQ bem-sucedida!");
                return true;
            }

            saveLogUsuario("Tentando conectar ao RabbitMQ... ($i de $tentativas)");
            sleep($intervalo);  // Espera 2 segundos entre as tentativas
        }

        
        saveLogUsuario("Não foi possível conectar ao RabbitMQ após $tentativas tentativas");
        return false;
    }

    
    function processoUsuario() {
        while (esperarRabbitMQ($_ENV['RABBITMQ_HOST'], $_ENV['RABBITMQ_PORT'])) {
            
            $conexao = new AMQPStreamConnection($_ENV['RABBITMQ_HOST'], 
                                                $_ENV['RABBITMQ_PORT'], 
                                                $_ENV['RABBITMQ_USER'], 
                                                $_ENV['RABBITMQ_PASSWORD']);

            $canal = $conexao->channel();


            saveLogUsuario('CHEGOU AQUI');
            // LE
            $fila_sucesso = 'usuario_processado_sucesso';

            // ESCREVE
            $fila_salvo_sucesso = 'usuario_salvo_sucesso';
            $fila_salvo_erro = 'usuario_salvo_erro';
            $fila_analisado_sucesso = 'usuario_analisado_sucesso';
            $fila_analisado_erro = 'usuario_analisado_erro';

            // LE 
            $canal->queue_declare($fila_sucesso, false, true, false, false);

            // ESCREVE
            $canal->queue_declare($fila_analisado_erro, false, true, false, false);
            $canal->queue_declare($fila_analisado_sucesso, false, true, false, false);
            $canal->queue_declare($fila_salvo_erro, false, true, false, false);
            $canal->queue_declare($fila_salvo_sucesso, false, true, false, false);

            $retorno = function($msg) use ($canal, $fila_analisado_sucesso, $fila_analisado_erro) {
                $mensagem = json_decode($msg->body, true);

                if ($mensagem['status'] === 'login') {
                    $retornado = buscarDadosPorUsuario($mensagem['usuario']);
                    $retornado = reset($retornado);
                    if (!empty($retornado)) {
                        $mensagem['nome'] = $retornado['nome'];
                        $mensagem['senha_banco'] = $retornado['senha'];
                        
                        $msg->body = json_encode($mensagem);

                        $canal->basic_publish($msg, '', $fila_analisado_sucesso); 
                    } else {
                        $mensagem['mensagem'] = "Nao encontrado no banco de dados";
                        $mensagem['senha_banco'] = '';

                        $msg->body = json_encode($mensagem);

                        $canal->basic_publish($msg, '', $fila_analisado_erro); 
                        saveLogUsuario("\n    Usuario: " . $msg->body .  "\n    Movido para a fila: " . $fila_analisado_erro);
                    }
                    // saveLogUsuario($retornado['nome'] . " " . (json_encode($retornado, true)));   
                    
                    
                    saveLogUsuario("\n    Usuario: " . $msg->body .  "\n    Movido para a fila: " . $fila_analisado_erro);                 
                } elseif ($mensagem['status'] === 'cadastrar') {
                    $mensagem['mensagem'] = "Senha com menos de 8 caracteres";

                    $msg->body = json_encode($mensagem);

                    $canal->basic_publish($msg, '', $fila_analisado_erro); 
                    saveLogUsuario("\n    Usuario: " . $msg->body .  "\n    Movido para a fila: " . $fila_analisado_erro);
                }
            };

            $canal->basic_consume($fila_sucesso, '', false, true, false, false, $retorno);

            while ($canal->callbacks){
                $canal->wait();
            }
        }
    }

    

    function processoTransacao() {

    }

    processoUsuario();

    // $pidusuario = pcntl_fork();

    // if ($pidusuario == -1) {
    //     saveLogUsuario('Erro ao criar o processo de usuario');
    // } elseif ($pidusuario == 0) {
    //     processoUsuario();
    //     exit;
    // }

    // pcntl_wait($status);




