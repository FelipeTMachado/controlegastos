CREATE DATABASE IF NOT EXISTS sistema;

USE sistema;

CREATE TABLE IF NOT EXISTS usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(50) NOT NULL,
    senha VARCHAR(60) NOT NULL,
    nome VARCHAR(80) NOT NULL
);

INSERT INTO usuario (id, usuario, senha, nome) VALUES (DEFAULT, 'felipe', '12345678', 'FELIPE CAUE MACHADO');
INSERT INTO usuario (id, usuario, senha, nome) VALUES (DEFAULT, 'bianca', '87654321', 'BIANCA JUSSARA WOLFF');
-- CREATE TABLE IF NOT EXISTS transacao (
-- );