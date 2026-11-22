
<?php
require_once '../includes/auth.php';
require_once '../config/conexao.php';

$produtoEditar = null;

// Cadastrar ou editar produto
if (isset($_POST['salvar'])) {
    $id = $_POST['idproduto'];
    $nome = trim($_POST['nome']);
    $descricao = trim($_POST['descricao']);
    $preco = str_replace(',', '.', $_POST['preco']);
    $estoque = (int) $_POST['estoque'];

    if ($id == '') {
        $sql = "INSERT INTO produtos (nome, descricao, preco, estoque)
                VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nome, $descricao, $preco, $estoque]);
    } else {
        $sql = "UPDATE produtos SET
                nome = ?, descricao = ?, preco = ?, estoque = ?
                WHERE idproduto = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nome, $descricao, $preco, $estoque, $id]);
    }

    header("Location: produtos.php");
    exit;
}

// Desativar produto
if (isset($_POST['desativar'])) {
    $stmt = $pdo->prepare(
        "UPDATE produtos SET ativo = 0 WHERE idproduto = ?"
    );
    $stmt->execute([$_POST['desativar']]);

    header("Location: produtos.php");
    exit;
}

// Buscar produto para edição
if (isset($_GET['editar'])) {
    $stmt = $pdo->prepare(
        "SELECT * FROM produtos WHERE idproduto = ? AND ativo = 1"
    );
    $stmt->execute([$_GET['editar']]);
    $produtoEditar = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Listar produtos
$stmt = $pdo->query(
    "SELECT * FROM produtos WHERE ativo = 1 ORDER BY nome"
);
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once '../includes/header.php';
?>

<h1>Produtos</h1>
<p>Cadastre e gerencie os produtos da gráfica.</p>

<div class="card">
    <h2>
        <?php echo $produtoEditar ? 'Editar produto' : 'Novo produto'; ?>
    </h2>

    <form method="POST">
        <input type="hidden" name="idproduto"
        value="<?php echo $produtoEditar['idproduto'] ?? ''; ?>">

        <label>Nome do produto</label>
        <input type="text" name="nome" required
        value="<?php echo htmlspecialchars($produtoEditar['nome'] ?? ''); ?>">

        <label>Descrição</label>
        <textarea name="descricao"><?php echo htmlspecialchars($produtoEditar['descricao'] ?? ''); ?></textarea>

        <label>Preço (R$)</label>
        <input type="number" name="preco" step="0.01" min="0" required
        value="<?php echo htmlspecialchars($produtoEditar['preco'] ?? ''); ?>">

        <label>Quantidade em estoque</label>
        <input type="number" name="estoque" min="0" required
        value="<?php echo htmlspecialchars($produtoEditar['estoque'] ?? '0'); ?>">

        <button type="submit" name="salvar">
            <?php echo $produtoEditar ? 'Salvar alterações' : 'Cadastrar produto'; ?>
        </button>

        <?php if ($produtoEditar): ?>
            <a href="produtos.php">Cancelar edição</a>
        <?php endif; ?>
    </form>
</div>

<div class="card">
    <h2>Produtos cadastrados</h2>

    <table>
        <thead>
            <tr>
                <th>Produto</th>
                <th>Preço</th>
                <th>Estoque</th>
                <th>Ações</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($produtos as $produto): ?>
                <tr>
                    <td><?php echo htmlspecialchars($produto['nome']); ?></td>
                    <td>R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></td>
                    <td><?php echo $produto['estoque']; ?></td>
                    <td>
                        <a href="produtos.php?editar=<?php echo $produto['idproduto']; ?>">
                            Editar
                        </a>

                        <form method="POST" style="display:inline;">
                            <button type="submit" name="desativar"
                            value="<?php echo $produto['idproduto']; ?>"
                            data-confirm="Deseja desativar este produto?">
                                Desativar
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once '../includes/footer.php'; ?>
