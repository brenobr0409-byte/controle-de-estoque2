<?php
$servidor = "localhost";
$usuario = "root";
$senha = "";
$banco = "estoque";

// Conexão com o banco de dados usando PDO
try {
    $conexao = new PDO("mysql:host=$servidor; dbname=$banco; charset=utf8", $usuario, $senha);

    $conexao-> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //echo "Conexão com o banco de dados estabelecida com sucesso";
    // Configura o modo de erro do PDO para exceção
} catch (\Throwable $erro) {
    
    die("Erro ao conectar com o banco de dados: " . $erro ->getMessage());

    
}