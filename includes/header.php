
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
    <link rel="stylesheet" href="../assets/style.css">
</head>

<body>

<div class="container">

    <aside class="sidebar">

        <div class="logo">

            <div class="logo-imagem">
                <img src="../assets/img/logo-etec.png"
                     alt="Logo da Etec">
            </div>

            <h2>Gestão Gráfica</h2>
            <span>ETECVAV</span>

            <div class="logo-linha"></div>

        </div>

        <nav class="menu">

            <a href="dashboard.php"
               class="<?= $paginaAtual == 'dashboard.php' ? 'active' : '' ?>">
                <span>⌂</span> Início
            </a>

            <a href="funcionarios.php"
               class="<?= $paginaAtual == 'funcionarios.php' ? 'active' : '' ?>">
                <span>♙</span> Funcionários
            </a>

            <a href="clientes.php"
               class="<?= $paginaAtual == 'clientes.php' ? 'active' : '' ?>">
                <span>♧</span> Clientes
            </a>

            <a href="produtos.php"
               class="<?= $paginaAtual == 'produtos.php' ? 'active' : '' ?>">
                <span>▣</span> Produtos
            </a>

            <a href="servicos.php"
               class="<?= $paginaAtual == 'servicos.php' ? 'active' : '' ?>">
                <span>⚙</span> Serviços
            </a>

            <a href="pedidos.php"
               class="<?= $paginaAtual == 'pedidos.php' ? 'active' : '' ?>">
                <span>▤</span> Pedidos e Vendas
            </a>

            <a href="estoque.php"
               class="<?= $paginaAtual == 'estoque.php' ? 'active' : '' ?>">
                <span>▦</span> Estoque
            </a>

            <a href="relatorios.php"
               class="<?= $paginaAtual == 'relatorios.php' ? 'active' : '' ?>">
                <span>▥</span> Relatórios
            </a>

        </nav>

        <div class="sidebar-footer">
            <a href="../logout.php">
                <span>↪</span> Sair
            </a>
        </div>

    </aside>

    <main class="content">
