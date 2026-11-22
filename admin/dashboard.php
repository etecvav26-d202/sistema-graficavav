
<?php
require_once '../includes/auth.php';
require_once '../config/conexao.php';

$totalClientes = $pdo->query("SELECT COUNT(*) FROM clientes WHERE ativo = 1")->fetchColumn();
$totalProdutos = $pdo->query("SELECT COUNT(*) FROM produtos WHERE ativo = 1")->fetchColumn();
$totalPedidos = $pdo->query("SELECT COUNT(*) FROM pedidos")->fetchColumn();
$totalFuncionarios = $pdo->query("SELECT COUNT(*) FROM funcionarios WHERE ativo = 1")->fetchColumn();

require_once '../includes/header.php';
?>

<h1>Painel Principal</h1>
<p>Bem-vindo(a), <?php echo htmlspecialchars($_SESSION['nome']); ?>!</p>

<div class="cards">
    <div class="card">
        <h3>Clientes</h3>
        <p><?php echo $totalClientes; ?></p>
    </div>

    <div class="card">
        <h3>Produtos</h3>
        <p><?php echo $totalProdutos; ?></p>
    </div>

    <div class="card">
        <h3>Pedidos</h3>
        <p><?php echo $totalPedidos; ?></p>
    </div>

    <div class="card">
        <h3>Funcionários</h3>
        <p><?php echo $totalFuncionarios; ?></p>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
