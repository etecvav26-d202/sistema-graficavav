<?php
require_once 'config/conexao.php';

$nome = 'Administrador';
$email = 'admin@grafica.com';
$senha = password_hash('admin123', PASSWORD_DEFAULT);
$cargo = 'Administrador';

$sql = "INSERT INTO funcionarios
(nome, email, senha_hash, cargo, nivel_acesso, ativo)
VALUES
(:nome, :email, :senha, :cargo, 'administrador', 1)";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    ':nome' => $nome,
    ':email' => $email,
    ':senha' => $senha,
    ':cargo' => $cargo
]);

echo "Administrador cadastrado com sucesso!";
?>
