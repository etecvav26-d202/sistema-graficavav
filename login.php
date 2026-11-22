<?php
session_start();
require_once 'config/conexao.php';

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM funcionarios WHERE email = :email AND ativo = 1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':email', $email);
    $stmt->execute();

    $funcionario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($funcionario && password_verify($senha, $funcionario['senha_hash'])) {
        $_SESSION['idfuncionario'] = $funcionario['idfuncionario'];
        $_SESSION['nome'] = $funcionario['nome'];
        $_SESSION['nivel_acesso'] = $funcionario['nivel_acesso'];

        header("Location: admin/dashboard.php");
        exit;
    } else {
        $erro = "E-mail ou senha incorretos.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Gestão Gráfica</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<div class="login-container">
    <div class="login-box">
        <h1>Gestão Gráfica</h1>
        <p>EtecVAV</p>

        <?php if ($erro): ?>
            <p class="erro"><?php echo $erro; ?></p>
        <?php endif; ?>

        <form method="POST">
            <label>E-mail</label>
            <input type="email" name="email" required>

            <label>Senha</label>
            <input type="password" name="senha" required>

            <button type="submit">Entrar</button>
        </form>
    </div>
</div>

</body>
</html>
