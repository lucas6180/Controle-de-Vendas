<?php
include('conexao.php'); // Inclua o seu arquivo de conexão

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $valor = $_POST['valor'];

    // Prepara e executa a inserção
    $stmt = $conn->prepare("INSERT INTO itens (nome, valor) VALUES (?, ?)");
    $stmt->bind_param("sd", $nome, $valor);

    if ($stmt->execute()) {
        header("Location: cadastrarItens.php?status=success");
        exit(); // Adicione exit() para garantir que o script pare aqui
    } else {
        header("Location: cadastrarItens.php?status=error&message=" . urlencode($conn->error));
        exit();
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Controle de Vendas</title>
    <link rel="stylesheet" href="style.css">
    <!-- Font Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
</head>

<body>
<header>
    <h1>VendaSim</h1>
    <div class="menu-hamburguer" id="menu-hamburguer">
        <div class="bar"></div>
        <div class="bar"></div>
        <div class="bar"></div>
    </div>
    <nav id="nav" class="nav-hidden">
        <ul>
            <li><a href="cadastrarItens.php">Início</a></li>
            <li><a href="index.php">Registrar Vendas</a></li>
            <li><a href="historicoDeVendas.php">Histórico de Vendas</a></li>
        </ul>
    </nav>
</header>
    <main>
        <section class="Content-main">
            <form class="cadastro-item" method="POST" action="cadastrarItens.php">
                <div class="title">
                    <h2>Cadastrar Itens</h2>
                </div>
                <div class="campo-cadastro">
                    <div class="nome-item-cadastro">
                        <label class="nome-item-cadastro" for="nome">Nome do Item:</label>
                        <input type="text" id="nome" name="nome" required placeholder="Ex Camisa Nike">
                    </div>
                    <div class="valor-item-cadastro">
                        <label for="valor">Valor:</label>
                        <input type="number" id="valor" name="valor" step="0.01" required placeholder="Ex R$140">
                    </div>
                </div>
                <button type="submit">Cadastrar</button>
            </form>

            <?php
            if (isset($_GET['status'])) {
                if ($_GET['status'] == 'success') {
                    echo "<p>Item cadastrado com sucesso!</p>";
                } elseif ($_GET['status'] == 'error') {
                    echo "<p>Erro: " . htmlspecialchars($_GET['message']) . "</p>";
                }
            }
            ?>
        </section>
    </main>

    <script src="script.js"></script>
    <script>
        document.getElementById('menu-hamburguer').addEventListener('click', () => {
    const nav = document.getElementById('nav');
    nav.classList.toggle('nav-visible'); 
    
});
    </script>
</body>

</html>
