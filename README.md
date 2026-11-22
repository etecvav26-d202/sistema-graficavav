# 🖨️ Sistema de Gestão Gráfica – EtecVAV

Sistema web desenvolvido para auxiliar no gerenciamento interno de uma gráfica, permitindo organizar funcionários, clientes, produtos, serviços, pedidos, vendas e estoque.

> **Atenção:** os arquivos e atualizações do projeto estão disponíveis exclusivamente na branch `beta` do repositório. A branch `main` não contém a versão atual do sistema.

## 🎯 Objetivo

Desenvolver um sistema de gerenciamento que facilite o controle das atividades de uma gráfica, centralizando informações e tornando os processos administrativos mais organizados e eficientes.

## ⚙️ Funcionalidades

- **Dashboard:** visualização de informações gerais do sistema.
- **Funcionários:** cadastro, edição e desativação de funcionários, com diferentes níveis de acesso.
- **Clientes:** gerenciamento dos dados dos clientes.
- **Produtos:** cadastro e gerenciamento de produtos e seus estoques.
- **Serviços:** cadastro e gerenciamento dos serviços oferecidos pela gráfica.
- **Pedidos e vendas:** registro e acompanhamento de pedidos.
- **Estoque:** controle de entradas, saídas e quantidades disponíveis.
- **Relatórios:** consulta de vendas, pedidos e produtos com estoque baixo.
- **Autenticação:** acesso ao sistema por e-mail e senha.

## 👥 Níveis de acesso

O sistema possui diferentes níveis de permissão:

- **Administrador:** acesso às funções administrativas e gerenciamento de funcionários.
- **Gerente:** acesso às funções de gerenciamento e acompanhamento.
- **Funcionário:** acesso às funcionalidades operacionais permitidas pelo sistema.

## 💻 Tecnologias utilizadas

- PHP
- MySQL
- HTML5
- CSS3
- JavaScript
- XAMPP
- phpMyAdmin

## 🗂️ Estrutura do projeto

```text
sistema-graficavav/
│
├── admin/
│   ├── dashboard.php
│   ├── funcionarios.php
│   ├── clientes.php
│   ├── produtos.php
│   ├── servicos.php
│   ├── pedidos.php
│   ├── estoque.php
│   └── relatorios.php
│
├── assets/
│   ├── css/
│   ├── js/
│   └── img/
│
├── config/
│   └── conexao.php
│
├── database/
│   └── graficavav.sql
│
├── includes/
│   ├── header.php
│   ├── footer.php
│   ├── auth.php
│   └── funcoes.php
│
├── index.php
├── login.php
├── logout.php
└── README.md
```

## 👨‍💻 Autores

| Alunos(as) | Nº Chamada | Série |
| ----- | ------ | ----- |
| [Alice Gimenez Siqueira](https://github.com/alice-gimenez) | 02 | 2º ano D |
| [Alice Rasmussen Rezende Alves](https://github.com/alicerez0703) | 03 | 2º ano D |
| [Amanda Neves Oliveira](https://github.com/amandanevoli) | 04 | 2º ano D |
| [Ana Lívia Takeyama Romanato](https://github.com/liviatakeyama) | 06 | 2º ano D |
| [Isabelli Dias da Silva](https://github.com/isabelbelli) | 19 | 2º ano D |

## 

Projeto acadêmico de prática em desenvolvimento web.