<?php

// Formata valores monetários
function formatarMoeda($valor) {
    return 'R$ ' . number_format($valor, 2, ',', '.');
}

// Formata datas
function formatarData($data) {
    return date('d/m/Y H:i', strtotime($data));
}

// Protege textos exibidos na página
function limparTexto($texto) {
    return htmlspecialchars($texto ?? '', ENT_QUOTES, 'UTF-8');
}

// Verifica o nível de acesso
function verificarPermissao($niveisPermitidos) {
    if (!isset($_SESSION['nivel_acesso']) ||
        !in_array($_SESSION['nivel_acesso'], $niveisPermitidos)) {
        die("Você não tem permissão para acessar esta página.");
    }
}

?>
