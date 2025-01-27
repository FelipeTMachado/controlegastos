<?php 
    require_once __DIR__ . "/../conexao.php";

    function buscarDadosPorUsuario(string $usuario): array {
        try {
            // Criando a instância da classe Conexao e obtendo a conexão
            $pdo = conectar();

            $sql = "SELECT id, usuario, senha, nome FROM usuario WHERE usuario = :usuario";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam("usuario", $usuario);

            $stmt->execute();

            // Retorna os dados como um array
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $resultados;
        } catch (PDOException $e) {
            return [];
        }
    }