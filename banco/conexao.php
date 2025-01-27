<?php
function conectar() {
    $local   = $_ENV['MYSQL_HOST'];
    $banco   = $_ENV['MYSQL_DATABASE'];
    $usuario = $_ENV['MYSQL_USER'];
    $senha   = $_ENV['MYSQL_PASSWORD'];
    $porta   = $_ENV['MYSQL_PORT'];

    $pdo = null;

    if ($pdo === null) {
        try {
            $pdo = new PDO("mysql:host={$local};dbname={$banco};port={$porta}", $usuario, $senha);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erro ao conectar com o banco de dados: " . $e->getMessage());
        }
    }

    return $pdo;
}