<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$paginaAtual = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão Gráfica | EtecVAV</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>

    <div class="container">

        <aside class="sidebar">

            <div class="logo">
                <h2>Gestão Gráfica</h2>
                <span>EtecVAV</span>
            </div>

            <nav>
                <a href="dashboard.php">Início</a>
                <a href="funcionarios.php">Funcionários</a>
                <a href="clientes.php">Clientes</a>
                <a href="produtos.php">Produtos</a>
                <a href="servicos.php">Serviços</a>
                <a href="pedidos.php">Pedidos e Vendas</a>
                <a href="estoque.php">Estoque</a>
                <a href="relatorios.php">Relatórios</a>
            </nav>

            <div class="sidebar-footer">
                <a href="logout.php">Sair do sistema</a>
            </div>

        </aside>

        <main class="content">
