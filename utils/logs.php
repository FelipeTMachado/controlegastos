<?php
    function data(): string {
        return (new DateTime('now', new DateTimeZone('America/Sao_Paulo')))->format('Y-m-d');
    }

    function hora(): string { 
        return (new DateTime('now', new DateTimeZone('America/Sao_Paulo')))->format('H:i:s');
    }

    function dataHoraServer(): string {
        return data() . " - " .  hora() . " - ";
    }

    function verificaLog(string $nome) {
        if (!file_exists($nome)) {
            if (!is_dir(dirname($nome))) {
                mkdir(dirname($nome), 0755, true); 
            }

            touch($nome); 
        }
    }

    function saveLogUsuario(string $mensagem) {
        verificaLog(__DIR__ . "/../logs/usuario.log");

        error_log(dataHoraServer() . $mensagem . "\n", 3, __DIR__ . "/../logs/usuario.log");
    }

    function saveLogTransacao(string $mensagem) {
        verificaLog(__DIR__ . "/../logs/transacao.log");
        error_log(dataHoraServer() . $mensagem . "\n", 3, __DIR__ . "/../logs/transacao.log");
    }