<?php
// Arquivo: config/db.php

$host = 'localhost';
$banco = 'autocademy';  // Nome do seu banco de dados
$usuario = 'root';      // Usuário do MySQL (no Laragon geralmente é 'root')
$senha = '';            // Senha do MySQL (no Laragon geralmente é vazio)

// Cria a conexão usando as variáveis corretas definidas acima
$conexao = new mysqli($host, $usuario, $senha, $banco);

// Verifica se deu erro na conexão
if ($conexao->connect_error) {
    die("Falha na conexão: " . $conexao->connect_error);
}

// Define o charset para evitar problemas com acentuação
$conexao->set_charset("utf8mb4");

?>  