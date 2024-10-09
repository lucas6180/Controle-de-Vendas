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
        <nav>
            <a href="">Voltar</a>
        </nav>
    </header>
    <main>
        <section class="content-main">
            <h2>Histórico de vendas</h2>
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
                    }
                })
                .catch(error => {
                    console.error('Erro ao carregar vendas:', error);
                });
        }

        function exibirVendas(vendas) {
            const resultado = document.querySelector('.resultado');
            resultado.innerHTML = '';

            vendas.forEach(venda => {
                const itemHTML = `
                    <div class="nota-venda">
                        <div class="header-result">
                            <button onclick="deletarVenda(${venda.id})">Deletar</button>
                            <span>${new Date(venda.dataVenda).toLocaleString()}</span>
                            <h2>Venda ${venda.id}</h2>
                        </div>
                        <div class="info-venda">
                            <p>Nome: ${venda.nome}</p>
                            <p>Valor da Compra: R$${parseFloat(venda.valorDaCompra).toFixed(2)}</p>
                            <p>Valor Recebido: R$${parseFloat(venda.valorRecebido).toFixed(2)}</p>
                            <p>Troco: R$${parseFloat(venda.troco).toFixed(2)}</p>
                        </div>
                    </div>
                `;
                resultado.innerHTML += itemHTML;
            });
        }

        function deletarVenda(id) {
            console.log(`ID a ser deletado: ${id}`);
            if (confirm('Você tem certeza que deseja deletar esta venda?')) {
                fetch(`deletar_venda.php`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `id=${id}`
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

        window.onload = carregarVendas;
    </script>
</body>
</html>
