<?php
require_once '../includes/auth.php';
require_once '../config/conexao.php';

$clienteEditar = null;

// Cadastrar ou editar cliente
if (isset($_POST['salvar'])) {
    $id = $_POST['idcliente'];
    $nome = trim($_POST['nome']);
    $telefone = trim($_POST['telefone']);
    $email = trim($_POST['email']);
    $endereco = trim($_POST['endereco']);

    if ($id == '') {
        $sql = "INSERT INTO clientes (nome, telefone, email, endereco)
                VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nome, $telefone, $email, $endereco]);
    } else {
        $sql = "UPDATE clientes SET
                nome = ?, telefone = ?, email = ?, endereco = ?
                WHERE idcliente = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nome, $telefone, $email, $endereco, $id]);
    }

    header("Location: clientes.php");
    exit;
}

// Desativar cliente
if (isset($_POST['desativar'])) {
    $stmt = $pdo->prepare(
        "UPDATE clientes SET ativo = 0 WHERE idcliente = ?"
    );
    $stmt->execute([$_POST['desativar']]);

    header("Location: clientes.php");
    exit;
}

// Buscar cliente para edição
if (isset($_GET['editar'])) {
    $stmt = $pdo->prepare(
        "SELECT * FROM clientes WHERE idcliente = ? AND ativo = 1"
    );
    $stmt->execute([$_GET['editar']]);
    $clienteEditar = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Listar clientes
$stmt = $pdo->query(
    "SELECT * FROM clientes WHERE ativo = 1 ORDER BY nome"
);
$clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once '../includes/header.php';
?>

<h1>Clientes</h1>
<p>Cadastre e gerencie os clientes da gráfica.</p>

<div class="card">
    <h2>
        <?php echo $clienteEditar ? 'Editar cliente' : 'Novo cliente'; ?>
    </h2>

    <form method="POST">
        <input type="hidden" name="idcliente"
        value="<?php echo $clienteEditar['idcliente'] ?? ''; ?>">

        <label>Nome</label>
        <input type="text" name="nome" required
        value="<?php echo htmlspecialchars($clienteEditar['nome'] ?? ''); ?>">

        <label>Telefone</label>
        <input type="text" name="telefone"
        value="<?php echo htmlspecialchars($clienteEditar['telefone'] ?? ''); ?>">

        <label>E-mail</label>
        <input type="email" name="email"
        value="<?php echo htmlspecialchars($clienteEditar['email'] ?? ''); ?>">

        <label>Endereço</label>
        <input type="text" name="endereco"
        value="<?php echo htmlspecialchars($clienteEditar['endereco'] ?? ''); ?>">

        <button type="submit" name="salvar">
            <?php echo $clienteEditar ? 'Salvar alterações' : 'Cadastrar cliente'; ?>
        </button>

        <?php if ($clienteEditar): ?>
            <a href="clientes.php">Cancelar edição</a>
        <?php endif; ?>
    </form>
</div>

<div class="card">
    <h2>Clientes cadastrados</h2>

    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>Telefone</th>
                <th>E-mail</th>
                <th>Endereço</th>
                <th>Ações</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($clientes as $cliente): ?>
                <tr>
                    <td><?php echo htmlspecialchars($cliente['nome']); ?></td>
                    <td><?php echo htmlspecialchars($cliente['telefone'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($cliente['email'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($cliente['endereco'] ?? ''); ?></td>
                    <td>
                        <a href="clientes.php?editar=<?php echo $cliente['idcliente']; ?>">
                            Editar
                        </a>

                        <form method="POST" style="display:inline;">
                            <button type="submit" name="desativar"
                            value="<?php echo $cliente['idcliente']; ?>"
                            data-confirm="Deseja desativar este cliente?">
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
