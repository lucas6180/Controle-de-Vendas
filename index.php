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
        <nav>
            <a href="">Histórico de Compras</a>
        </nav>
    </header>
    <main>
        <section class="Content-main">
            <div class="container">
                <form id="form" method="POST" action="registrar_venda.php">
                    <div class="title">
                        <h2>Controle de Vendas</h2>
                    </div>
                    <div class="campo-input">
                        <label for="item">Nome do Item:</label>
                        <input type="text" id="item" name="nome" class="nome-item" placeholder="Ex: Produto A" required>
                    </div>

                    <div class="campo-input">
                        <label for="valorCompra">Valor da Compra:</label>
                        <input type="number" id="valorCompra" name="valor" class="valor-compra" placeholder="Ex: 50.00" step="0.01" required>
                    </div>

                    <div class="campo-input">
                        <label for="valorRecebido">Valor Recebido:</label>
                        <input type="number" id="valorRecebido" name="valorRecebido" class="valor-recebido" placeholder="Ex: 60.00" step="0.01" required>
                    </div>

                    <button class="button-register" type="submit">Registrar Venda</button>
                </form>

                <div class="resultado disabled">
                    <!-- Aqui você pode mostrar mensagens de sucesso ou erro -->
                    <?php
                    if (isset($_GET['status'])) {
                        if ($_GET['status'] == 'success') {
                            echo "<p>Venda registrada com sucesso!</p>";
                        } elseif ($_GET['status'] == 'error') {
                            echo "<p>Erro: " . htmlspecialchars($_GET['message']) . "</p>";
                        }
                    }
                    ?>
                </div>
            </div>
        </section>
    </main>

    <script src="script.js"></script>
</body>
</html>
