<?php
require 'vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;

// CONECTA COM O RABBITMQ
$connection = new AMQPStreamConnection('localhost', 5672, 'admin', 'admin');
$channel = $connection->channel();

// DECLARAR A FILA 
$channel->queue_declare('Usuario', false, false, false, false);

// FUNCAO DE CALLBACK PARA PROCESSAR MENSAGENS 
$callback = function ($msg) {
    echo 'Mensagem recebida: ' . $msg->body . "\n";
};

// CONSUMIR MENSAGENS DA FILA
$channel->basic_consume('Usuario', '', false, true, false, false, $callback);

echo "Aguardando mensagens. Pressione CTRL+C para sair.\n";

// LOOP QUE MANTEM O CONSUMER ATIVO
while ($channel->is_consuming()) {
    $channel->wait();
}

