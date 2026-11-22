
<?php
require_once '../includes/auth.php';
require_once '../config/conexao.php';

// Somente administradores e gerentes podem movimentar o estoque
if (!in_array($_SESSION['nivel_acesso'], ['administrador', 'gerente'], true)) {
    header("Location: dashboard.php");
    exit;
}

$erro = '';

// Registrar movimentação
if (isset($_POST['registrar'])) {
    $idproduto = (int) $_POST['idproduto'];
    $tipo = $_POST['tipo'];
    $quantidade = (int) $_POST['quantidade'];

    try {
        if ($quantidade <= 0) {
            throw new Exception("Informe uma quantidade válida.");
        }

        if (!in_array($tipo, ['entrada', 'saida'], true)) {
            throw new Exception("Tipo de movimentação inválido.");
        }

        $pdo->beginTransaction();

        // Buscar produto e bloquear sua linha durante a operação
        $stmt = $pdo->prepare(
            "SELECT estoque FROM produtos
             WHERE idproduto = ? AND ativo = 1 FOR UPDATE"
        );
        $stmt->execute([$idproduto]);
        $produto = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$produto) {
            throw new Exception("Produto não encontrado.");
        }

        if ($tipo === 'saida' && $produto['estoque'] < $quantidade) {
            throw new Exception("Estoque insuficiente.");
        }

        // Atualizar quantidade
        if ($tipo === 'entrada') {
            $sql = "UPDATE produtos SET estoque = estoque + ?
                    WHERE idproduto = ?";
        } else {
            $sql = "UPDATE produtos SET estoque = estoque - ?
                    WHERE idproduto = ?";
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$quantidade, $idproduto]);

        // Registrar movimentação
        $stmt = $pdo->prepare(
            "INSERT INTO movimentacoes_estoque
            (idproduto, idfuncionario, tipo, quantidade)
            VALUES (?, ?, ?, ?)"
        );

        $stmt->execute([
            $idproduto,
            $_SESSION['idfuncionario'],
            $tipo,
            $quantidade
        ]);

        $pdo->commit();

        header("Location: estoque.php");
        exit;

    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        $erro = $e->getMessage();
    }
}

// Buscar produtos
$produtos = $pdo->query(
    "SELECT * FROM produtos WHERE ativo = 1 ORDER BY nome"
)->fetchAll(PDO::FETCH_ASSOC);

// Buscar histórico
$movimentacoes = $pdo->query(
    "SELECT m.*, p.nome AS produto, f.nome AS funcionario
     FROM movimentacoes_estoque m
     INNER JOIN produtos p ON m.idproduto = p.idproduto
     INNER JOIN funcionarios f ON m.idfuncionario = f.idfuncionario
     ORDER BY m.data_movimentacao DESC"
)->fetchAll(PDO::FETCH_ASSOC);

require_once '../includes/header.php';
?>

<h1>Controle de Estoque</h1>
<p>Consulte os produtos e registre entradas e saídas.</p>

<?php if ($erro): ?>
    <p class="erro"><?php echo htmlspecialchars($erro); ?></p>
<?php endif; ?>

<div class="card">
    <h2>Registrar movimentação</h2>

    <form method="POST">

        <label>Produto</label>
        <select name="idproduto" required>
            <option value="">Selecione um produto</option>

            <?php foreach ($produtos as $produto): ?>
                <option value="<?php echo $produto['idproduto']; ?>">
                    <?php echo htmlspecialchars($produto['nome']); ?>
                    (Estoque: <?php echo $produto['estoque']; ?>)
                </option>
            <?php endforeach; ?>
        </select>

        <label>Tipo de movimentação</label>
        <select name="tipo" required>
            <option value="entrada">Entrada</option>
            <option value="saida">Saída</option>
        </select>

        <label>Quantidade</label>
        <input type="number" name="quantidade" min="1" required>

        <button type="submit" name="registrar">
            Registrar movimentação
        </button>

    </form>
</div>

<div class="card">
    <h2>Estoque atual</h2>

    <table>
        <thead>
            <tr>
                <th>Produto</th>
                <th>Quantidade disponível</th>
                <th>Preço</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($produtos as $produto): ?>
                <tr>
                    <td><?php echo htmlspecialchars($produto['nome']); ?></td>
                    <td><?php echo $produto['estoque']; ?></td>
                    <td>
                        R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="card">
    <h2>Histórico de movimentações</h2>

    <table>
        <thead>
            <tr>
                <th>Produto</th>
                <th>Tipo</th>
                <th>Quantidade</th>
                <th>Funcionário</th>
                <th>Data</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($movimentacoes as $movimentacao): ?>
                <tr>
                    <td><?php echo htmlspecialchars($movimentacao['produto']); ?></td>
                    <td><?php echo ucfirst($movimentacao['tipo']); ?></td>
                    <td><?php echo $movimentacao['quantidade']; ?></td>
                    <td><?php echo htmlspecialchars($movimentacao['funcionario']); ?></td>
                    <td>
                        <?php echo date('d/m/Y H:i', strtotime($movimentacao['data_movimentacao'])); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once '../includes/footer.php'; ?>
