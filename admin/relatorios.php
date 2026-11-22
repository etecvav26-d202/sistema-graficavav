
<?php
require_once '../includes/auth.php';
require_once '../config/conexao.php';

if (!in_array($_SESSION['nivel_acesso'], ['admin', 'gerente'])) {
    die("Você não tem permissão para acessar esta página.");
}

$filtro = $_GET['periodo'] ?? 'todos';

$where = "";
if ($filtro === 'hoje') {
    $where = "AND DATE(data_pedido) = CURDATE()";
} elseif ($filtro === 'mes') {
    $where = "AND MONTH(data_pedido) = MONTH(CURDATE())
              AND YEAR(data_pedido) = YEAR(CURDATE())";
}

$stmt = $pdo->query("
    SELECT
        COUNT(*) AS quantidade,
        COALESCE(SUM(CASE WHEN status != 'cancelado' THEN total ELSE 0 END), 0) AS vendas
    FROM pedidos
    WHERE 1=1 $where
");
$resumo = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $pdo->query("
    SELECT p.*, c.nome AS cliente
    FROM pedidos p
    LEFT JOIN clientes c ON p.idcliente = c.idcliente
    WHERE 1=1 $where
    ORDER BY p.data_pedido DESC
");
$pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->query("
    SELECT nome, estoque
    FROM produtos
    WHERE ativo = 1 AND estoque <= 5
    ORDER BY estoque ASC
");
$estoqueBaixo = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once '../includes/header.php';
?>

<h1>Relatórios</h1>
<p>Acompanhamento de pedidos, vendas e estoque.</p>

<div class="cards">
    <div class="card">
        <h3>Total de pedidos</h3>
        <p><?= $resumo['quantidade'] ?></p>
    </div>

    <div class="card">
        <h3>Total em vendas</h3>
        <p>R$ <?= number_format($resumo['vendas'], 2, ',', '.') ?></p>
    </div>
</div>

<h2>Filtrar período</h2>

<form method="GET">
    <select name="periodo">
        <option value="todos" <?= $filtro === 'todos' ? 'selected' : '' ?>>Todos</option>
        <option value="hoje" <?= $filtro === 'hoje' ? 'selected' : '' ?>>Hoje</option>
        <option value="mes" <?= $filtro === 'mes' ? 'selected' : '' ?>>Este mês</option>
    </select>

    <button type="submit">Filtrar</button>
</form>

<h2>Pedidos registrados</h2>

<div class="table-container">
<table>
    <thead>
        <tr>
            <th>Cliente</th>
            <th>Data</th>
            <th>Total</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($pedidos as $pedido): ?>
        <tr>
            <td><?= htmlspecialchars($pedido['cliente'] ?? 'Não informado') ?></td>
            <td><?= date('d/m/Y H:i', strtotime($pedido['data_pedido'])) ?></td>
            <td>R$ <?= number_format($pedido['total'], 2, ',', '.') ?></td>
            <td><?= htmlspecialchars($pedido['status']) ?></td>
        </tr>
        <?php endforeach; ?>

        <?php if (empty($pedidos)): ?>
        <tr>
            <td colspan="4">Nenhum pedido encontrado.</td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>
</div>

<h2>Produtos com estoque baixo</h2>

<div class="table-container">
<table>
    <thead>
        <tr>
            <th>Produto</th>
            <th>Quantidade disponível</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($estoqueBaixo as $produto): ?>
        <tr>
            <td><?= htmlspecialchars($produto['nome']) ?></td>
            <td><?= $produto['estoque'] ?></td>
        </tr>
        <?php endforeach; ?>

        <?php if (empty($estoqueBaixo)): ?>
        <tr>
            <td colspan="2">Nenhum produto com estoque baixo.</td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>
</div>

<?php require_once '../includes/footer.php'; ?>
