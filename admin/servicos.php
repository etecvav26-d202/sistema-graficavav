
<?php
require_once '../includes/auth.php';
require_once '../config/conexao.php';

$servicoEditar = null;

// Cadastrar ou editar serviço
if (isset($_POST['salvar'])) {
    $id = $_POST['idservico'];
    $nome = trim($_POST['nome']);
    $descricao = trim($_POST['descricao']);
    $preco = str_replace(',', '.', $_POST['preco']);

    if ($id == '') {
        $sql = "INSERT INTO servicos (nome, descricao, preco)
                VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nome, $descricao, $preco]);
    } else {
        $sql = "UPDATE servicos SET
                nome = ?, descricao = ?, preco = ?
                WHERE idservico = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nome, $descricao, $preco, $id]);
    }

    header("Location: servicos.php");
    exit;
}

// Desativar serviço
if (isset($_POST['desativar'])) {
    $stmt = $pdo->prepare(
        "UPDATE servicos SET ativo = 0 WHERE idservico = ?"
    );
    $stmt->execute([$_POST['desativar']]);

    header("Location: servicos.php");
    exit;
}

// Buscar serviço para edição
if (isset($_GET['editar'])) {
    $stmt = $pdo->prepare(
        "SELECT * FROM servicos WHERE idservico = ? AND ativo = 1"
    );
    $stmt->execute([$_GET['editar']]);
    $servicoEditar = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Listar serviços
$stmt = $pdo->query(
    "SELECT * FROM servicos WHERE ativo = 1 ORDER BY nome"
);
$servicos = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once '../includes/header.php';
?>

<h1>Serviços</h1>
<p>Cadastre e gerencie os serviços oferecidos pela gráfica.</p>

<div class="card">
    <h2>
        <?php echo $servicoEditar ? 'Editar serviço' : 'Novo serviço'; ?>
    </h2>

    <form method="POST">
        <input type="hidden" name="idservico"
        value="<?php echo $servicoEditar['idservico'] ?? ''; ?>">

        <label>Nome do serviço</label>
        <input type="text" name="nome" required
        value="<?php echo htmlspecialchars($servicoEditar['nome'] ?? ''); ?>">

        <label>Descrição</label>
        <textarea name="descricao"><?php echo htmlspecialchars($servicoEditar['descricao'] ?? ''); ?></textarea>

        <label>Preço (R$)</label>
        <input type="number" name="preco" step="0.01" min="0" required
        value="<?php echo htmlspecialchars($servicoEditar['preco'] ?? ''); ?>">

        <button type="submit" name="salvar">
            <?php echo $servicoEditar ? 'Salvar alterações' : 'Cadastrar serviço'; ?>
        </button>

        <?php if ($servicoEditar): ?>
            <a href="servicos.php">Cancelar edição</a>
        <?php endif; ?>
    </form>
</div>

<div class="card">
    <h2>Serviços cadastrados</h2>

    <table>
        <thead>
            <tr>
                <th>Serviço</th>
                <th>Descrição</th>
                <th>Preço</th>
                <th>Ações</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($servicos as $servico): ?>
                <tr>
                    <td><?php echo htmlspecialchars($servico['nome']); ?></td>
                    <td><?php echo htmlspecialchars($servico['descricao'] ?? ''); ?></td>
                    <td>R$ <?php echo number_format($servico['preco'], 2, ',', '.'); ?></td>
                    <td>
                        <a href="servicos.php?editar=<?php echo $servico['idservico']; ?>">
                            Editar
                        </a>

                        <form method="POST" style="display:inline;">
                            <button type="submit" name="desativar"
                            value="<?php echo $servico['idservico']; ?>"
                            data-confirm="Deseja desativar este serviço?">
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
