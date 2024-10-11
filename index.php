<?php
include('conexao.php');
$result = $conn->query("SELECT nome, valor FROM itens");

$itens = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $itens[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Controle de Vendas</title>
    <link rel="stylesheet" href="style.css">
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
            <li><a href="index.php">Início</a></li>
            <li><a href="historicoDeVendas.php">Histórico de Vendas</a></li>
            <li><a href="cadastrarItens.php">Cadastrar Itens</a></li>
        </ul>
    </nav>
</header>

<main>
    <section class="Content-main">
        <div class="container">
            <form id="form" method="POST" action="registrar_venda.php">
                <div class="title">
                    <h2>Controle de Vendas</h2>
                </div>
                <div class="campo-itens" id="campo-itens">
                    <div class="campo">
                        <label for="item">Itens:</label>
                        <br>
                        <select id="item" name="nome[]" class="nome-item">
                            <option value="">Selecione um item</option>
                            <?php
                            foreach ($itens as $item) {
                                echo "<option value='" . htmlspecialchars($item['valor']) . "'>" . htmlspecialchars($item['nome']) . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="add-item">
                        <img src="icons8-adicionar-60.png" alt="">
                        <p>Adicionar outro Item</p>
                    </div>
                </div>

                <div class="campo-input">
                    <div class="item-sem-cadastro">
                        <label for="valorCompraInput">Adicionar valor:</label>
                        <input type="number" id="valorCompraInput" placeholder="Ex: 50.00" step="0.01">
                        <button type="button" id="adicionarValor">Adicionar</button>
                    </div>
                </div>

                <div class="valor-compra">
                    <p class="valorDaCompra">Total: R$ 0.00</p>
                </div>
                <div class="campo-valor-recebido">
                    <label for="valor-recebido">Valor Recebido</label>
                    <input name="valor-recebido" class="valor-recebido" type="number" required>
                </div>
                <button class="button-register" type="submit">Registrar Venda</button>
            </form>

            <div class="resultado disabled">
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

<script>
    const vendas = [];
    const valorDaCompraDisplay = document.querySelector(".valorDaCompra");
    const valorRecebido = document.querySelector(".valor-recebido");
    const result = document.querySelector(".resultado");
    let totalValorManual = 0; 

    const form = document.querySelector('form');

    function calcularValorCompra() {
        const selects = document.querySelectorAll(".nome-item");
        let total = totalValorManual;

        selects.forEach(select => {
            if (select.value) {
                total += parseFloat(select.value);
            }
        });

        return total;
    }

    function atualizarValorDisplay() {
        valorDaCompraDisplay.textContent = 'Total: R$ ' + calcularValorCompra().toFixed(2);
    }

    document.getElementById('adicionarValor').addEventListener('click', () => {
const valorInput = document.getElementById('valorCompraInput').value;

if (valorInput) {
    const valor = parseFloat(valorInput);
    totalValorManual += valor; 
    document.getElementById('valorCompraInput').value = ''; 
    atualizarValorDisplay(); 
}

    });

    function validarCampos() {
        const valorCompra = calcularValorCompra();
        const valorRecebidoNum = Number(valorRecebido.value);
        
        if (valorCompra === 0) {
            alert("Por favor, selecione pelo menos um item ou adicione um valor.");
            return false;
        }
        
        if (isNaN(valorRecebidoNum) || valorRecebidoNum <= 0) {
            alert("Por favor, insira um valor recebido válido.");
            return false;
        }

        return true;
    }

    function registrarVenda() {
    if (!validarCampos()) {
        return; 
    }

    const valorCompra = calcularValorCompra(); 
    const valorRecebidoNum = Number(valorRecebido.value);

    if (valorCompra > valorRecebidoNum) {
        alert("Erro, o valor recebido é menor do que o valor da compra.");
        return;
    }

    const troco = valorRecebidoNum - valorCompra;
    const venda = {
        nome: Array.from(document.querySelectorAll('.nome-item'))
        .map(select => select.options[select.selectedIndex].text)
        .filter(Boolean)
        .join(", ") || "Sem Nome",
        valor: valorCompra,
        valorRecebido: valorRecebidoNum,
        troco: troco,
        valoresManuais: totalValorManual 
    };
    
    console.log('Valores Manuais:', totalValorManual);
    console.log(venda);  
    fetch('registrar_venda.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(venda),
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            venda.id = data.id; 
            venda.date = new Date().toLocaleString();
            vendas.push(venda);
            exibirResultado(venda);
            limparCampos();
            alert(`Venda registrada com sucesso! ID: ${data.id}`);
        } else {
            alert(`Erro: ${data.message}`);
        }
    })
    .catch(error => {
        console.error('Erro ao registrar venda:', error);
    });
}


function exibirResultado(venda) {
    result.classList.remove('disabled');
    form.classList.add('disabled');

    result.innerHTML = `
        <div class="header-result">
            <h1>VendaSim</h1>
            <br>
            <span>${venda.date}</span>
            <h2>Venda ${venda.id}</h2>
        </div>
        <div class="info-venda">
            <p>Itens: ${venda.nome}</p>
            <p>Valor da Compra: R$${venda.valor.toFixed(2)}</p>
            <p>Valor Recebido: R$${venda.valorRecebido.toFixed(2)}</p>
            <p>Valores Manuais: R$ ${totalValorManual.toFixed(2)}</p> <!-- Exibe apenas a soma total -->
            <p>Troco: R$${venda.troco.toFixed(2)}</p>
        </div>
        <button class="RegistrarNovamente">Registrar Outra Venda</button>
    `;
}


    const buttonRegister = document.querySelector(".button-register");
    buttonRegister.addEventListener("click", (event) => {
        event.preventDefault();
        registrarVenda();
    });

    result.addEventListener('click', (event) => {
        if (event.target.classList.contains('RegistrarNovamente')) {
            limparCampos();
            result.classList.add('disabled');
            form.classList.remove('disabled');
        }
    });

    function limparCampos() {
        document.querySelectorAll(".nome-item").forEach(select => {
            select.selectedIndex = 0;
        });
        document.getElementById("valorCompraInput").value = ""; 
        valorRecebido.value = ""; 
        atualizarValorDisplay();
    }

    document.querySelector('.add-item').addEventListener('click', () => {
        const campoItens = document.getElementById('campo-itens');
        const newSelect = document.createElement('select');
        newSelect.name = 'nome[]';
        newSelect.classList.add('nome-item');
        newSelect.required = true;

        newSelect.innerHTML = '<option value="">Selecione um item</option>';
        <?php foreach ($itens as $item): ?>
            newSelect.innerHTML += `<option value="<?= htmlspecialchars($item['valor']) ?>"><?= htmlspecialchars($item['nome']) ?></option>`;
        <?php endforeach; ?>

        campoItens.insertBefore(newSelect, document.querySelector('.add-item'));
        newSelect.addEventListener('change', atualizarValorDisplay);
    });

   
    document.querySelectorAll('.nome-item').forEach(select => {
        select.addEventListener('change', atualizarValorDisplay);
    });

    document.getElementById('valorCompraInput').addEventListener('input', atualizarValorDisplay);
    
    document.getElementById('menu-hamburguer').addEventListener('click', () => {
        const nav = document.getElementById('nav');
        nav.classList.toggle('nav-visible'); 
    });


</script>

</body>

</html>
