<?php
    require 'vendor/autoload.php';
    use PhpAmqpLib\Connection\AMQPStreamConnection;
    use PhpAmqpLib\Message\AMQPMessage;
    use Dotenv\Dotenv;

    include "./shared/logs.php";

    saveLogUsuario("Servidor iniciado");

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
        require './shared/variaveis.php';

        while (esperarRabbitMQ($_ENV['RABBITMQ_HOST'], $_ENV['RABBITMQ_PORT'])) {
            
            $conexao = new AMQPStreamConnection($_ENV['RABBITMQ_HOST'], 
                                                $_ENV['RABBITMQ_PORT'], 
                                                $_ENV['RABBITMQ_USER'], 
                                                $_ENV['RABBITMQ_PASSWORD']);


            $canal = $conexao->channel();

            // LE
            $fila_usuario = 'usuario';
            // ESCREVE
            $fila_sucesso = 'usuario_processado_sucesso';
            $fila_erro = 'usuario_processado_erro';

            // echo "ANTES DE DECLARAR AS FILAS";
            $canal->queue_declare($fila_usuario, false, true, false, false);
            $canal->queue_declare($fila_sucesso, false, true, false, false);
            $canal->queue_declare($fila_erro, false, true, false, false);


            $retorno = function($msg) use ($canal, $fila_sucesso, $fila_erro) {
                $mensagem = json_decode($msg->body, true);

                if (strlen($mensagem['senha']) >= 8) {
                    $canal->basic_publish($msg, '', $fila_sucesso); 
                    saveLogUsuario("\n    Usuario: " . $msg->body .  "\n    Movido para a fila: " . $fila_sucesso);
                } else {
                    $mensagem['mensagem'] = "Senha com menos de 8 caracteres";

                    $msg->body = json_encode($mensagem);

                    $canal->basic_publish($msg, '', $fila_erro); 
                    saveLogUsuario("\n    Usuario: " . $msg->body .  "\n    Movido para a fila: " . $fila_erro);
                }
            };

            $canal->basic_consume($fila_usuario, '', false, true, false, false, $retorno);

            while ($canal->callbacks){
                $canal->wait();
            }
        }
    }

    function processoUsuarioAnalisadoSucesso() {
        require './shared/variaveis.php';

        while (esperarRabbitMQ($_ENV['RABBITMQ_HOST'], $_ENV['RABBITMQ_PORT'])) {
            
            $conexao = new AMQPStreamConnection($_ENV['RABBITMQ_HOST'], 
                                                $_ENV['RABBITMQ_PORT'], 
                                                $_ENV['RABBITMQ_USER'], 
                                                $_ENV['RABBITMQ_PASSWORD']);


            $canal = $conexao->channel();

            // LE
            $fila_analisado_sucesso = 'usuario_analisado_sucesso';


            // ESCREVE 
            $fila_analisado_proc_sucesso = "usuario_processado_analisado_sucesso";
            $fila_analisado_proc_erro = "usuario_processado_analisado_erro";
            
            $fila_salvo_proc_sucesso = "usuario_processado_salvo_sucesso";
            $fila_salvo_proc_erro = "usuario_processado_salvo_erro";

            // DECLARA AS FILAS
            $canal->queue_declare($fila_analisado_sucesso, false, true, false, false);
            // $canal->queue_declare($fila_analisado_erro, false, true, false, false);
            $canal->queue_declare($fila_analisado_proc_erro, false, true, false, false);
            $canal->queue_declare($fila_analisado_proc_sucesso, false, true, false, false);


            $retorno = function($msg) use ($canal, $fila_analisado_proc_erro, $fila_analisado_proc_sucesso) {
                $mensagem = json_decode($msg->body, true);

                if ($mensagem['senha'] === $mensagem['senha_banco']){
                    $canal->basic_publish($msg, '', $fila_analisado_proc_sucesso); 
                    saveLogUsuario("\n    Usuario: " . $msg->body .  "\n    Movido para a fila: " . $fila_analisado_proc_sucesso);
                } else {
                    $canal->basic_publish($msg, '', $fila_analisado_proc_erro); 

                    $mensagem['mensagem'] = "Usuario ou senha incorretos";

                    $msg->body = json_encode($mensagem);
                    
                    saveLogUsuario("\n    Usuario: " . $msg->body .  "\n    Movido para a fila: " . $fila_analisado_proc_erro); 
                }


                
                // if (strlen($mensagem['senha']) >= 8) {
                //     $canal->basic_publish($msg, '', $fila_sucesso); 
                //     saveLogUsuario("\n    Usuario: " . $msg->body .  "\n    Movido para a fila: " . $fila_sucesso);
                // } else {
                //     $mensagem['mensagem'] = "Senha com menos de 8 caracteres";

                //     $msg->body = json_encode($mensagem);

                //     $canal->basic_publish($msg, '', $fila_erro); 
                //     saveLogUsuario("\n    Usuario: " . $msg->body .  "\n    Movido para a fila: " . $fila_erro);
                // }
            };

            $canal->basic_consume($fila_analisado_sucesso, '', false, true, false, false, $retorno);

            while ($canal->callbacks){
                $canal->wait();
            }
        }
    }

    function processoUsuarioAnalisadoErro() {
        require './shared/variaveis.php';

        while (esperarRabbitMQ($_ENV['RABBITMQ_HOST'], $_ENV['RABBITMQ_PORT'])) {
            
            // $conexao = new AMQPStreamConnection($_ENV['RABBITMQ_HOST'], 
            //                                     $_ENV['RABBITMQ_PORT'], 
            //                                     $_ENV['RABBITMQ_USER'], 
            //                                     $_ENV['RABBITMQ_PASSWORD']);


            // $canal = $conexao->channel();

            // // LE
            // $fila_analisado_sucesso = 'usuario_analisado_sucesso';


            // // ESCREVE 
            // $fila_analisado_sucesso = "usuario_processado_analisado_sucesso";
            // $fila_analisado_erro = "usuario_processado_analisado_erro";
            
            // $fila_salvo_sucesso = "usuario_processado_salvo_sucesso";
            // $fila_salvo_erro = "usuario_processado_salvo_erro";

            // // DECLARA AS FILAS
            // $canal->queue_declare($fila_analisado_erro, false, true, false, false);
            // $canal->queue_declare($fila_salvo_sucesso, false, true, false, false);
            // $canal->queue_declare($fila_salvo_erro, false, true, false, false);


            // $retorno = function($msg) use ($canal, $fila_, $fila_erro) {
            //     $mensagem = json_decode($msg->body, true);

            //     if (strlen($mensagem['senha']) >= 8) {
            //         $canal->basic_publish($msg, '', $fila_sucesso); 
            //         saveLogUsuario("\n    Usuario: " . $msg->body .  "\n    Movido para a fila: " . $fila_sucesso);
            //     } else {
            //         $mensagem['mensagem'] = "Senha com menos de 8 caracteres";

            //         $msg->body = json_encode($mensagem);

            //         $canal->basic_publish($msg, '', $fila_erro); 
            //         saveLogUsuario("\n    Usuario: " . $msg->body .  "\n    Movido para a fila: " . $fila_erro);
            //     }
            // };

            // $canal->basic_consume($fila_analisado_erro, '', false, true, false, false, $retorno);

            // while ($canal->callbacks){
            //     $canal->wait();
            // }
        }
    }

    function processoTransacao() {

    }

    $pidusuario = pcntl_fork();

    if ($pidusuario == -1) {
        die('Erro ao criar o processo de usuario');
    } elseif ($pidusuario == 0) {
        processoUsuario();
        exit;
    }

    $pidusuarioanalise = pcntl_fork();

    if ($pidusuarioanalise == -1) {
        die('Erro ao criar a analise de usuario');
    } elseif ($pidusuarioanalise == 0) {
        processoUsuarioAnalisadoSucesso();
        exit;
    }


    pcntl_wait($status);




