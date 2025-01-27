<?php
    require_once __DIR__ . '/../vendor/autoload.php';

    use PhpAmqpLib\Connection\AMQPStreamConnection;
    use PhpAmqpLib\Message\AMQPMessage;
    use Dotenv\Dotenv;
   
    $dotenv = Dotenv::createImmutable(__DIR__);
    $dotenv->load();
