<?php
require 'vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

// CONEXAO COM O RABBITMQ 
$connection = new AMQPStreamConnection('localhost', 5672, 'admin', 'admin');
$channel = $connection->channel();

$sair = false;

while (!$sair) {	
	// DECLARAR A FILA
	$channel->queue_declare('minha_fila', false, false, false, false);
	
	// MENSAGEM PARA A FILA
	echo "Digite sua mensagem: ";		
	$mensagem_str = fgets(STDIN);
	$mensagem = new AMQPMessage($mensagem_str);
	$channel->basic_publish($mensagem, '', 'minha_fila');

	echo "Mensagem enviada para a fila 'minha_fila'\n\n";
}
// FECHAR CONEXOES
$channel->close();
$connection->close();
