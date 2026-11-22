
<?php
require_once '../includes/auth.php';
require_once '../config/conexao.php';

$erro = '';

// Cadastrar pedido
if (isset($_POST['salvar'])) {
    $idcliente = (int) $_POST['idcliente'];
    $tipo = $_POST['tipo'];
    $iditem = (int) $_POST['iditem'];
    $quantidade = (int) $_POST['quantidade'];

    try {
        if ($quantidade <= 0) {
            throw new Exception("Informe uma quantidade válida.");
        }

        $pdo->beginTransaction();

        // Buscar produto ou serviço
        if ($tipo === 'produto') {
            $stmt = $pdo->prepare(
                "SELECT * FROM produtos WHERE idproduto = ? AND ativo = 1"
            );
            $stmt->execute([$iditem]);
            $item = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$item) {
                throw new Exception("Produto não encontrado.");
            }

            if ($item['estoque'] < $quantidade) {
                throw new Exception("Estoque insuficiente para este produto.");
            }

            $preco = $item['preco'];
            $idproduto = $iditem;
            $idservico = null;

        } elseif ($tipo === 'servico') {
            $stmt = $pdo->prepare(
                "SELECT * FROM servicos WHERE idservico = ? AND ativo = 1"
            );
            $stmt->execute([$iditem]);
            $item = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$item) {
                throw new Exception("Serviço não encontrado.");
            }

            $preco = $item['preco'];
            $idproduto = null;
            $idservico = $iditem;

        } else {
            throw new Exception("Selecione um tipo válido.");
        }

        $subtotal = $preco * $quantidade;

        // Verificar cliente
        $stmt = $pdo->prepare(
            "SELECT idcliente FROM clientes WHERE idcliente = ? AND ativo = 1"
        );
        $stmt->execute([$idcliente]);

        if (!$stmt->fetch()) {
            throw new Exception("Selecione um cliente válido.");
        }

        // Criar pedido
        $stmt = $pdo->prepare(
            "INSERT INTO pedidos
            (idcliente, idfuncionario, total, status)
            VALUES (?, ?, ?, 'pendente')"
        );

        $stmt->execute([
            $idcliente,
            $_SESSION['idfuncionario'],
            $subtotal
        ]);

        $idpedido = $pdo->lastInsertId();

        // Registrar item do pedido
        $stmt = $pdo->prepare(
            "INSERT INTO itens_pedido
            (idpedido, idproduto, idservico, quantidade, preco_unitario, subtotal)
            VALUES (?, ?, ?, ?, ?, ?)"
        );

        $stmt->execute([
            $idpedido,
            $idproduto,
            $idservico,
            $quantidade,
            $preco,
            $subtotal
        ]);

        // Atualizar estoque quando for produto
        if ($tipo === 'produto') {
            $stmt = $pdo->prepare(
                "UPDATE produtos SET estoque = estoque - ? WHERE idproduto = ?"
            );
            $stmt->execute([$quantidade, $idproduto]);

            $stmt = $pdo->prepare(
                "INSERT INTO movimentacoes_estoque
                (idproduto, idfuncionario, tipo, quantidade)
                VALUES (?, ?, 'saida', ?)"
            );
            $stmt->execute([
                $idproduto,
                $_SESSION['idfuncionario'],
                $quantidade
            ]);
        }

        $pdo->commit();

        header("Location: pedidos.php");
        exit;

    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        $erro = $e->getMessage();
    }
}

// Alterar status do pedido
if (isset($_POST['alterar_status'])) {
    $statusPermitidos = [
        'pendente',
        'em_producao',
        'pronto',
        'concluido',
        'cancelado'
    ];

    $idpedido = (int) $_POST['idpedido'];
    $novoStatus = $_POST['status'];

    if (in_array($novoStatus, $statusPermitidos, true)) {
        $stmt = $pdo->prepare(
            "UPDATE pedidos SET status = ? WHERE idpedido = ?"
        );
        $stmt->execute([$novoStatus, $idpedido]);
    }

    header("Location: pedidos.php");
    exit;
}

// Buscar dados para os formulários
$clientes = $pdo->query(
    "SELECT idcliente, nome FROM clientes WHERE ativo = 1 ORDER BY nome"
)->fetchAll(PDO::FETCH_ASSOC);

$produtos = $pdo->query(
    "SELECT idproduto, nome, preco, estoque FROM produtos
     WHERE ativo = 1 AND estoque > 0 ORDER BY nome"
)->fetchAll(PDO::FETCH_ASSOC);

$servicos = $pdo->query(
    "SELECT idservico, nome, preco FROM servicos
     WHERE ativo = 1 ORDER BY nome"
)->fetchAll(PDO::FETCH_ASSOC);

// Listar pedidos
$pedidos = $pdo->query(
    "SELECT p.*, c.nome AS cliente, f.nome AS funcionario
     FROM pedidos p
     INNER JOIN clientes c ON p.idcliente = c.idcliente
     INNER JOIN funcionarios f ON p.idfuncionario = f.idfuncionario
     ORDER BY p.data_pedido DESC"
)->fetchAll(PDO::FETCH_ASSOC);

require_once '../includes/header.php';
?>

<h1>Pedidos e Vendas</h1>
<p>Registre pedidos e acompanhe sua produção.</p>

<?php if ($erro): ?>
    <p class="erro"><?php echo htmlspecialchars($erro); ?></p>
<?php endif; ?>

<div class="card">
    <h2>Novo pedido</h2>

    <form method="POST">

        <label>Cliente</label>
        <select name="idcliente" required>
            <option value="">Selecione um cliente</option>
            <?php foreach ($clientes as $cliente): ?>
                <option value="<?php echo $cliente['idcliente']; ?>">
                    <?php echo htmlspecialchars($cliente['nome']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Tipo de item</label>
        <select name="tipo" id="tipoItem" required>
            <option value="">Selecione</option>
            <option value="produto">Produto</option>
            <option value="servico">Serviço</option>
        </select>

        <label>Produto ou serviço</label>
        <select name="iditem" id="itemSelecionado" required>
            <option value="">Primeiro selecione o tipo</option>
        </select>

        <label>Quantidade</label>
        <input type="number" name="quantidade" min="1" value="1" required>

        <button type="submit" name="salvar">Registrar pedido</button>
    </form>
</div>

<div class="card">
    <h2>Pedidos registrados</h2>

    <table>
        <thead>
            <tr>
                <th>Número</th>
                <th>Cliente</th>
                <th>Funcionário</th>
                <th>Data</th>
                <th>Total</th>
                <th>Status</th>
                <th>Ação</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($pedidos as $pedido): ?>
                <tr>
                    <td>#<?php echo $pedido['idpedido']; ?></td>
                    <td><?php echo htmlspecialchars($pedido['cliente']); ?></td>
                    <td><?php echo htmlspecialchars($pedido['funcionario']); ?></td>
                    <td><?php echo date('d/m/Y H:i', strtotime($pedido['data_pedido'])); ?></td>
                    <td>R$ <?php echo number_format($pedido['total'], 2, ',', '.'); ?></td>
                    <td><?php echo ucfirst(str_replace('_', ' ', $pedido['status'])); ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="idpedido"
                            value="<?php echo $pedido['idpedido']; ?>">

                            <select name="status">
                                <?php
                                $statusOpcoes = [
                                    'pendente' => 'Pendente',
                                    'em_producao' => 'Em produção',
                                    'pronto' => 'Pronto',
                                    'concluido' => 'Concluído',
                                    'cancelado' => 'Cancelado'
                                ];
                                ?>

                                <?php foreach ($statusOpcoes as $valor => $nome): ?>
                                    <option value="<?php echo $valor; ?>"
                                    <?php echo $pedido['status'] === $valor ? 'selected' : ''; ?>>
                                        <?php echo $nome; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>

                            <button type="submit" name="alterar_status">
                                Atualizar
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
const produtos = <?php echo json_encode($produtos); ?>;
const servicos = <?php echo json_encode($servicos); ?>;

const tipoItem = document.getElementById('tipoItem');
const itemSelecionado = document.getElementById('itemSelecionado');

tipoItem.addEventListener('change', function () {
    itemSelecionado.innerHTML = '<option value="">Selecione um item</option>';

    let lista = this.value === 'produto' ? produtos : servicos;

    lista.forEach(function (item) {
        const opcao = document.createElement('option');
        opcao.value = item.idproduto || item.idservico;

        if (item.idproduto) {
            opcao.textContent = item.nome + ' - R$ ' +
                Number(item.preco).toFixed(2) +
                ' (Estoque: ' + item.estoque + ')';
        } else {
            opcao.textContent = item.nome + ' - R$ ' +
                Number(item.preco).toFixed(2);
        }

        itemSelecionado.appendChild(opcao);
    });
});
</script>

<?php require_once '../includes/footer.php'; ?>
