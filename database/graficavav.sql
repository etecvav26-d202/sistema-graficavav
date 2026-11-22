-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 22/11/2026 às 22:19
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `graficavav`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `clientes`
--

CREATE TABLE `clientes` (
  `idcliente` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `endereco` varchar(255) DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `clientes`
--

INSERT INTO `clientes` (`idcliente`, `nome`, `telefone`, `email`, `endereco`, `ativo`) VALUES
(1, 'Amanda Neves', '11930657382', 'amandanevoli@gmail.com', 'Rua João Pinto Machado, 405', 0),
(2, 'Lucas Mendes', '(11) 98845-1234', 'lucas@email.com', 'Rua das Flores, 125 – Centro', 1),
(3, 'Mariana Oliveira', '(11) 97732-4567', 'mariana@email.com', 'Avenida Brasil, 340 – Vila Nova', 1),
(4, 'Gabriel Santos', '(11) 96621-7890', 'gabriel@email.com', 'Rua São Paulo, 78 – Jardim América', 1),
(5, 'Beatriz Costa', '(11) 95543-2109', 'beatriz@email.com', 'Rua das Palmeiras, 210 – Centro', 1),
(6, 'Rafael Almeida', '(11) 94456-3210', 'rafael@email.com', 'Avenida dos Estudantes, 560 – Jardim Europa', 1),
(7, 'Isabela Ferreira', '(11) 93321-6540', 'isabela@email.com', 'Rua Primavera, 95 – Vila São João', 1),
(8, 'Pedro Henrique', '(11) 92234-5678', 'pedro@email.com', 'Rua dos Ipês, 430 – Jardim das Flores', 1),
(9, 'Ana Clara Souza', '(11) 91123-4567', 'anaclara@email.com', 'Avenida Central, 150 – Vila Industrial', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `funcionarios`
--

CREATE TABLE `funcionarios` (
  `idfuncionario` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `cargo` varchar(50) DEFAULT NULL,
  `senha_hash` varchar(255) NOT NULL,
  `nivel_acesso` enum('administrador','gerente','funcionario') NOT NULL DEFAULT 'funcionario',
  `ativo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `funcionarios`
--

INSERT INTO `funcionarios` (`idfuncionario`, `nome`, `email`, `telefone`, `cargo`, `senha_hash`, `nivel_acesso`, `ativo`) VALUES
(1, 'Administrador', 'admin@grafica.com', NULL, 'Administrador', '$2y$10$pmaVAj56Fqg8m/tfFaciluKK8FGYXgG87NNiLuqj90lljEVMR61lC', 'administrador', 1),
(4, 'Amanda Neves', 'amandanevoli@gmail.com', '11930657382', 'Atendente', '$2y$10$viCQ2sEu/nUTTH76Mn5Fre5m6vhbGR6rUkA48SQbmhBIboLyfLQ6y', 'funcionario', 0),
(5, 'João Martins', 'joao.martins@grafica.com', '(11) 98811-2233', 'Atendente', '$2y$10$GgYN2BtvA3AqiNKM2bSbH.Tak9W0WSY9e2Rx0UBTvctQ9NCUkJCUm', 'funcionario', 1),
(6, 'Camila Rocha', 'camila.rocha@grafica.com', '(11) 97722-3344', 'Designer', '$2y$10$2b3xcei6WFA2nkQMZjzsd.OOUGVRGTPokuHzIHZ2voFnuJk/Zgyjy', 'funcionario', 1),
(7, 'Felipe Gomes', 'felipe.gomes@grafica.com', '(11) 96633-4455', 'Gerente de produção', '$2y$10$0p5EXpNUTJdrQ1Blk7qzseJwFTubPz.o2GDOXq/1pB5hAh1mN2vEW', 'gerente', 1),
(8, 'Larissa Alves', 'larissa.alves@grafica.com', '(11) 95544-5566', 'Operadora de impressão', '$2y$10$42GanRU4pH1y8rZRD9HEB.87KL50LH/AHIChXaehCQ0TVVSZ3RHjG', 'funcionario', 1),
(9, 'Bruno Ribeiro', 'bruno.ribeiro@grafica.com', '(11) 94455-6677', 'Supervisor', '$2y$10$9rtIDtqY2WPjUq0dOFbhzOV4990dvssYplploIEtGYl1o9WcR2Ie.', 'gerente', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `itens_pedido`
--

CREATE TABLE `itens_pedido` (
  `iditem` int(11) NOT NULL,
  `idpedido` int(11) NOT NULL,
  `idproduto` int(11) DEFAULT NULL,
  `idservico` int(11) DEFAULT NULL,
  `quantidade` int(11) NOT NULL,
  `preco_unitario` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `itens_pedido`
--

INSERT INTO `itens_pedido` (`iditem`, `idpedido`, `idproduto`, `idservico`, `quantidade`, `preco_unitario`, `subtotal`) VALUES
(1, 1, 2, NULL, 2, 4.00, 8.00),
(2, 2, NULL, 5, 1, 6.00, 6.00),
(3, 3, 43, NULL, 50, 0.10, 5.00),
(4, 4, 3, NULL, 5, 2.00, 10.00);

-- --------------------------------------------------------

--
-- Estrutura para tabela `movimentacoes_estoque`
--

CREATE TABLE `movimentacoes_estoque` (
  `idmovimentacao` int(11) NOT NULL,
  `idproduto` int(11) NOT NULL,
  `idfuncionario` int(11) NOT NULL,
  `tipo` enum('entrada','saida') NOT NULL,
  `quantidade` int(11) NOT NULL,
  `data_movimentacao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `movimentacoes_estoque`
--

INSERT INTO `movimentacoes_estoque` (`idmovimentacao`, `idproduto`, `idfuncionario`, `tipo`, `quantidade`, `data_movimentacao`) VALUES
(1, 2, 1, 'saida', 2, '2026-11-22 18:14:39'),
(2, 43, 1, 'saida', 50, '2026-11-22 18:15:10'),
(3, 3, 5, 'saida', 5, '2026-11-22 18:17:14');

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedidos`
--

CREATE TABLE `pedidos` (
  `idpedido` int(11) NOT NULL,
  `idcliente` int(11) NOT NULL,
  `idfuncionario` int(11) NOT NULL,
  `data_pedido` datetime DEFAULT current_timestamp(),
  `status` enum('pendente','em_producao','pronto','concluido','cancelado') DEFAULT 'pendente',
  `total` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `pedidos`
--

INSERT INTO `pedidos` (`idpedido`, `idcliente`, `idfuncionario`, `data_pedido`, `status`, `total`) VALUES
(1, 9, 1, '2026-11-22 18:14:39', 'pendente', 8.00),
(2, 4, 1, '2026-11-22 18:14:54', 'pendente', 6.00),
(3, 3, 1, '2026-11-22 18:15:10', 'pronto', 5.00),
(4, 5, 5, '2026-11-22 18:17:14', 'concluido', 10.00);

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos`
--

CREATE TABLE `produtos` (
  `idproduto` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  `preco` decimal(10,2) NOT NULL,
  `estoque` int(11) NOT NULL DEFAULT 0,
  `ativo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produtos`
--

INSERT INTO `produtos` (`idproduto`, `nome`, `descricao`, `preco`, `estoque`, `ativo`) VALUES
(1, 'Brownie com Sorvete', '', 8.00, 3, 0),
(2, 'APONTADOR COM RESERVA', '', 4.00, 18, 1),
(3, 'APONTADOR SEM RESERVA', '', 2.00, 10, 1),
(4, 'BASTÃO DE COLA QUENTE', '', 3.50, 58, 1),
(5, 'BORRACHA BRANCA PEQUENA', '', 2.00, 100, 1),
(6, 'CADERNO 10 MATÉRIAS', '', 20.00, 12, 1),
(7, 'CADEADO', '', 8.00, 41, 1),
(8, 'CANETA BIC AZUL', '', 2.00, 230, 1),
(9, 'CANETA BIC PRETA', '', 2.00, 380, 1),
(10, 'CANETA BIC VERMELHA', '', 2.00, 128, 1),
(11, 'CALCULADORA', '', 12.00, 46, 1),
(12, 'COLA BASTÃO PEQUENA', '', 4.00, 67, 1),
(13, 'COLA BASTÃO GRANDE', '', 5.00, 34, 1),
(14, 'COLA LÍQUIDA PEQUENA', '', 4.00, 39, 1),
(15, 'CORRETIVO EM FITA', '', 8.00, 25, 1),
(16, 'DUREX PEQUENO', '', 4.00, 82, 1),
(17, 'DUREX GRANDE', '', 6.00, 55, 1),
(18, 'ENVELOPE PARDO GRANDE', '', 2.00, 140, 1),
(19, 'ENVELOPE PARDO PEQUENO', '', 1.00, 200, 1),
(20, 'ESQUADRO 45º E 60º', '', 1.50, 36, 1),
(21, 'ESPIRAIS PARA ENCARDENAÇÃO', '', 1.00, 128, 1),
(22, 'GRAFITE 0,5MM E 0,7MM', '', 2.00, 270, 1),
(23, 'LÁPIS HB Nº02', '', 1.00, 50, 1),
(24, 'LÁPIS TÉCNICO 2B', '', 4.00, 79, 1),
(25, 'LÁPIS TÉCNICO 4B', '', 4.00, 78, 1),
(26, 'LÁPIS TÉCNICO 6B', '', 4.00, 32, 1),
(27, 'LAPISEIRA 0,5MM E 0,7MM', '', 8.00, 27, 1),
(28, 'PAPEL ALMAÇO', '', 0.50, 450, 1),
(29, 'PAPEL CANSON A2', '', 3.50, 148, 1),
(30, 'PAPEL CANSON A3', '', 1.50, 264, 1),
(31, 'PAPEL CANSON A4', '', 1.00, 570, 1),
(32, 'PAPEL MILIMITRADO A2', '', 1.50, 346, 1),
(33, 'PAPEL MILIMITRADO A3', '', 1.50, 90, 1),
(34, 'PAPEL MILIMITRADO A4', '', 0.50, 564, 1),
(35, 'PAPEL PRANCHETA MARGEADO A2', '', 1.50, 381, 1),
(36, 'PAPEL PRANCHETA MARGEADO A3', '', 1.00, 256, 1),
(37, 'PAPEL PRANCHETA MARGEADO A4', '', 0.50, 590, 1),
(38, 'PAPEL VEGETAL LISO A2', '', 1.50, 230, 1),
(39, 'PAPEL VEGETAL LISO A3', '', 0.70, 118, 1),
(40, 'PAPEL VEGETAL LISO A4', '', 0.50, 301, 1),
(41, 'PAPEL VEGETAL MARGEADO A2', '', 1.50, 176, 1),
(42, 'PAPEL VEGETAL MARGEADO A4', '', 0.50, 159, 1),
(43, 'PAPEL SULFITE A4 BRANCA OU COLORIDA', '', 0.10, 857, 1),
(44, 'PASTA PLÁSTICA COM ELÁSTICO PEQUENA', '', 1.00, 59, 1),
(45, 'POST-IT', '', 6.00, 115, 1),
(46, 'RÉGUA ACRÍLICA 30CM', '', 5.00, 70, 1),
(47, 'MARCA-TEXTO', '', 5.00, 38, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `servicos`
--

CREATE TABLE `servicos` (
  `idservico` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  `preco` decimal(10,2) NOT NULL,
  `ativo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `servicos`
--

INSERT INTO `servicos` (`idservico`, `nome`, `descricao`, `preco`, `ativo`) VALUES
(1, 'CÓPIA A4 COLORIDA', '', 1.00, 1),
(2, 'CÓPIA A4 BRANCA E PRETA', '', 0.50, 1),
(3, 'IMPRESSÃO COLORIDA', '', 1.00, 1),
(4, 'IMPRESSÃO PRETA E BRANCA', '', 0.50, 1),
(5, 'ENCADERNAÇÃO A4 ATÉ 100 FOLHAS', '', 6.00, 1),
(6, 'ENCADERNAÇÃO A3', '', 8.00, 1),
(7, 'PLASTIFICAÇÃO A4', '', 4.00, 1),
(8, 'PLASTIFICAÇÃO DE DOCUMENTOS', '', 2.50, 1);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`idcliente`);

--
-- Índices de tabela `funcionarios`
--
ALTER TABLE `funcionarios`
  ADD PRIMARY KEY (`idfuncionario`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Índices de tabela `itens_pedido`
--
ALTER TABLE `itens_pedido`
  ADD PRIMARY KEY (`iditem`),
  ADD KEY `idpedido` (`idpedido`),
  ADD KEY `idproduto` (`idproduto`),
  ADD KEY `idservico` (`idservico`);

--
-- Índices de tabela `movimentacoes_estoque`
--
ALTER TABLE `movimentacoes_estoque`
  ADD PRIMARY KEY (`idmovimentacao`),
  ADD KEY `idproduto` (`idproduto`),
  ADD KEY `idfuncionario` (`idfuncionario`);

--
-- Índices de tabela `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`idpedido`),
  ADD KEY `idcliente` (`idcliente`),
  ADD KEY `idfuncionario` (`idfuncionario`);

--
-- Índices de tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`idproduto`);

--
-- Índices de tabela `servicos`
--
ALTER TABLE `servicos`
  ADD PRIMARY KEY (`idservico`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `clientes`
--
ALTER TABLE `clientes`
  MODIFY `idcliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `funcionarios`
--
ALTER TABLE `funcionarios`
  MODIFY `idfuncionario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `itens_pedido`
--
ALTER TABLE `itens_pedido`
  MODIFY `iditem` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `movimentacoes_estoque`
--
ALTER TABLE `movimentacoes_estoque`
  MODIFY `idmovimentacao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `idpedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `idproduto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT de tabela `servicos`
--
ALTER TABLE `servicos`
  MODIFY `idservico` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `itens_pedido`
--
ALTER TABLE `itens_pedido`
  ADD CONSTRAINT `itens_pedido_ibfk_1` FOREIGN KEY (`idpedido`) REFERENCES `pedidos` (`idpedido`),
  ADD CONSTRAINT `itens_pedido_ibfk_2` FOREIGN KEY (`idproduto`) REFERENCES `produtos` (`idproduto`),
  ADD CONSTRAINT `itens_pedido_ibfk_3` FOREIGN KEY (`idservico`) REFERENCES `servicos` (`idservico`);

--
-- Restrições para tabelas `movimentacoes_estoque`
--
ALTER TABLE `movimentacoes_estoque`
  ADD CONSTRAINT `movimentacoes_estoque_ibfk_1` FOREIGN KEY (`idproduto`) REFERENCES `produtos` (`idproduto`),
  ADD CONSTRAINT `movimentacoes_estoque_ibfk_2` FOREIGN KEY (`idfuncionario`) REFERENCES `funcionarios` (`idfuncionario`);

--
-- Restrições para tabelas `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`idcliente`) REFERENCES `clientes` (`idcliente`),
  ADD CONSTRAINT `pedidos_ibfk_2` FOREIGN KEY (`idfuncionario`) REFERENCES `funcionarios` (`idfuncionario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
