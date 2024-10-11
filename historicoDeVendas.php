<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico de Compras</title>
    <link rel="stylesheet" href="historico.css">
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
            <li><a href="historicoDeVendas.php">Início</a></li>
            <li><a href="index.php">Registrar Vendas</a></li>
            <li><a href="cadastrarItens.php">Cadastrar Itens</a></li>
        </ul>
    </nav>
</header>
<main>
    <section class="content-main">
        <h2>Histórico de Vendas</h2>
        <div class="resultado"></div>
    </section>
</main>

<script>
    function carregarVendas() {
        fetch('vendas.php')
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    exibirVendas(data.vendas);
                } else {
                    console.error(`Erro: ${data.message}`);
                    document.querySelector('.resultado').innerHTML = '<p>Erro ao carregar vendas.</p>';
                }
            })
            .catch(error => {
                console.error('Erro ao carregar vendas:', error);
                document.querySelector('.resultado').innerHTML = '<p>Erro ao carregar vendas.</p>';
            });
    }

    function exibirVendas(vendas) {
        const resultado = document.querySelector('.resultado');
        resultado.innerHTML = '';

        if (!vendas || vendas.length === 0) {
            resultado.innerHTML = '<p>Nenhuma venda registrada.</p>';
            return;
        }

        vendas.forEach(venda => {
            const valoresManuais = venda.valoresManuais;

            const itemHTML = `
                <div class="nota-venda">
                    <div class="header-result">
                        <h2>Venda ${venda.id}</h2>
                        <button onclick="deletarVenda(${venda.id})"></button>
                    </div>
                    <span>${new Date(venda.dataVenda).toLocaleString()}</span>
                    <br><br>
                    <div class="info-venda">
                        <p>Itens: ${venda.nome}</p>
                        <p>Valor da Compra: R$${parseFloat(venda.valorDaCompra).toFixed(2)}</p>
                        <p>Valor Recebido: R$${parseFloat(venda.valorRecebido).toFixed(2)}</p>
                        <p>Troco: R$${parseFloat(venda.troco).toFixed(2)}</p>
                        <p>Valores Manuais: R$${valoresManuais}</p> <!-- Adicionando discriminação de valores manuais -->
                    </div>
                </div>
            `;
            resultado.innerHTML += itemHTML;
        });
    }

    function deletarVenda(id) {
        console.log(`ID a ser deletado: ${id}`);
        if (confirm('Você tem certeza que deseja deletar esta venda?')) {
            fetch('deletarVenda.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id: id })
            })
            .then(response => response.json())
            .then(data => {
                console.log('Dados retornados:', data);
                if (data.status === 'success') {
                    carregarVendas(); 
                } else {
                    console.error(`Erro: ${data.message}`);
                }
            })
            .catch(error => {
                console.error('Erro ao deletar venda:', error);
            });
        }
    }

    document.getElementById('menu-hamburguer').addEventListener('click', () => {
        const nav = document.getElementById('nav');
        nav.classList.toggle('nav-visible');
    });

    window.onload = carregarVendas;
</script>
</body>
</html>
