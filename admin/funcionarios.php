
<?php
require_once '../includes/auth.php';
require_once '../config/conexao.php';

// Somente administradores podem gerenciar funcionários
if ($_SESSION['nivel_acesso'] !== 'administrador') {
    header("Location: dashboard.php");
    exit;
}

$mensagem = '';
$funcionarioEditar = null;

// Cadastrar ou editar funcionário
if (isset($_POST['salvar'])) {

    $id = $_POST['idfuncionario'];
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $telefone = trim($_POST['telefone']);
    $cargo = trim($_POST['cargo']);
    $nivel = $_POST['nivel_acesso'];
    $senha = $_POST['senha'];

    if ($id == '') {

        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        $sql = "INSERT INTO funcionarios
        (nome, email, telefone, cargo, senha_hash, nivel_acesso)
        VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $nome, $email, $telefone, $cargo, $senhaHash, $nivel
        ]);

    } else {

        if ($senha != '') {
            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

            $sql = "UPDATE funcionarios SET
            nome = ?, email = ?, telefone = ?, cargo = ?,
            senha_hash = ?, nivel_acesso = ?
            WHERE idfuncionario = ?";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $nome, $email, $telefone, $cargo,
                $senhaHash, $nivel, $id
            ]);

        } else {
            $sql = "UPDATE funcionarios SET
            nome = ?, email = ?, telefone = ?, cargo = ?,
            nivel_acesso = ?
            WHERE idfuncionario = ?";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $nome, $email, $telefone, $cargo, $nivel, $id
            ]);
        }
    }

    header("Location: funcionarios.php");
    exit;
}

// Desativar funcionário
if (isset($_GET['desativar'])) {
    $id = $_GET['desativar'];

    if ($id != $_SESSION['idfuncionario']) {
        $stmt = $pdo->prepare(
            "UPDATE funcionarios SET ativo = 0 WHERE idfuncionario = ?"
        );
        $stmt->execute([$id]);
    }

    header("Location: funcionarios.php");
    exit;
}

// Buscar funcionário para edição
if (isset($_GET['editar'])) {
    $stmt = $pdo->prepare(
        "SELECT * FROM funcionarios WHERE idfuncionario = ? AND ativo = 1"
    );
    $stmt->execute([$_GET['editar']]);
    $funcionarioEditar = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Listar funcionários
$stmt = $pdo->query(
    "SELECT * FROM funcionarios WHERE ativo = 1 ORDER BY nome"
);
$funcionarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once '../includes/header.php';
?>

<h1>Funcionários</h1>
<p>Cadastre e gerencie os funcionários da gráfica.</p>

<div class="card">
    <h2>
        <?php echo $funcionarioEditar ? 'Editar funcionário' : 'Novo funcionário'; ?>
    </h2>

    <form method="POST">

        <input type="hidden" name="idfuncionario"
        value="<?php echo $funcionarioEditar['idfuncionario'] ?? ''; ?>">

        <label>Nome</label>
        <input type="text" name="nome" required
        value="<?php echo htmlspecialchars($funcionarioEditar['nome'] ?? ''); ?>">

        <label>E-mail</label>
        <input type="email" name="email" required
        value="<?php echo htmlspecialchars($funcionarioEditar['email'] ?? ''); ?>">

        <label>Telefone</label>
        <input type="text" name="telefone"
        value="<?php echo htmlspecialchars($funcionarioEditar['telefone'] ?? ''); ?>">

        <label>Cargo</label>
        <input type="text" name="cargo"
        value="<?php echo htmlspecialchars($funcionarioEditar['cargo'] ?? ''); ?>">

        <label>Nível de acesso</label>
        <select name="nivel_acesso" required>
            <?php
            $nivelAtual = $funcionarioEditar['nivel_acesso'] ?? 'funcionario';
            ?>
            <option value="administrador" <?php echo $nivelAtual == 'administrador' ? 'selected' : ''; ?>>Administrador</option>
            <option value="gerente" <?php echo $nivelAtual == 'gerente' ? 'selected' : ''; ?>>Gerente</option>
            <option value="funcionario" <?php echo $nivelAtual == 'funcionario' ? 'selected' : ''; ?>>Funcionário</option>
        </select>

        <label>
            <?php echo $funcionarioEditar ? 'Nova senha (deixe vazia para manter)' : 'Senha'; ?>
        </label>
        <input type="password" name="senha"
        <?php echo $funcionarioEditar ? '' : 'required'; ?>>

        <button type="submit" name="salvar">
            <?php echo $funcionarioEditar ? 'Salvar alterações' : 'Cadastrar funcionário'; ?>
        </button>

        <?php if ($funcionarioEditar): ?>
            <a href="funcionarios.php">Cancelar edição</a>
        <?php endif; ?>

    </form>
</div>

<div class="card">
    <h2>Funcionários cadastrados</h2>

    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>E-mail</th>
                <th>Cargo</th>
                <th>Acesso</th>
                <th>Ações</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($funcionarios as $funcionario): ?>
                <tr>
                    <td><?php echo htmlspecialchars($funcionario['nome']); ?></td>
                    <td><?php echo htmlspecialchars($funcionario['email']); ?></td>
                    <td><?php echo htmlspecialchars($funcionario['cargo'] ?? ''); ?></td>
                    <td><?php echo ucfirst($funcionario['nivel_acesso']); ?></td>
                    <td>
                        <a href="funcionarios.php?editar=<?php echo $funcionario['idfuncionario']; ?>">Editar</a>

                        <?php if ($funcionario['idfuncionario'] != $_SESSION['idfuncionario']): ?>
                            <a href="funcionarios.php?desativar=<?php echo $funcionario['idfuncionario']; ?>"
                            data-confirm="Deseja realmente desativar este funcionário?">
                                Desativar
                            </a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once '../includes/footer.php'; ?>
