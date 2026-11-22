
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

<body class="login-page">

    <div class="login-container">

        <div class="login-box">

            <div class="login-logo">
                <img src="assets/img/escola-tecnica.png" alt="Logo Etec">
            </div>

            <div class="login-titulo">
                <h1>Gestão Gráfica</h1>
                <span>ETECVAV</span>
                <p>Sistema de gerenciamento interno</p>
            </div>

            <div class="login-divisor"></div>

            <div class="login-formulario">

                <h2>Acesse sua conta</h2>
                <p>Entre com suas credenciais para continuar.</p>

                <?php if ($erro): ?>
                    <div class="login-erro">
                        <?= htmlspecialchars($erro) ?>
                    </div>
                <?php endif; ?>

                <form method="POST">

                    <label for="email">E-mail</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="Digite seu e-mail"
                        required
                    >

                    <label for="senha">Senha</label>
                    <input
                        type="password"
                        id="senha"
                        name="senha"
                        placeholder="Digite sua senha"
                        required
                    >

                    <button type="submit">Entrar no sistema</button>

                </form>

            </div>

            <div class="login-rodape">
                <span>ETECVAV</span>
                <p>Gestão Gráfica &copy; <?= date('Y') ?></p>
            </div>

        </div>

    </div>

</body>
</html>
