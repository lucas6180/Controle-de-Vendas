const vendas = [];

const nomeProduto = document.querySelector(".nome-item");
const valorDaCompra = document.querySelector(".valor-compra");
const valorRecebido = document.querySelector(".valor-recebido");
const result = document.querySelector(".resultado");

function registrarVenda() {
    return new Promise((resolve, reject) => {
        const valorCompra = Number(valorDaCompra.value);
        const valorRecebidoNum = Number(valorRecebido.value);

        if (isNaN(valorCompra) || isNaN(valorRecebidoNum)) {
            alert("Por favor, insira valores válidos.");
            reject();
            return;
        } else if (valorCompra > valorRecebidoNum) {
            alert("Erro, o valor recebido é menor do que o valor da compra.");
            reject();
            return;
        }

        const troco = valorRecebidoNum - valorCompra;

        const venda = {
            nome: nomeProduto.value || "Sem Nome",
            valor: valorCompra,
            valorRecebido: valorRecebidoNum,
            troco: troco,
        };

        // Envia a venda para o servidor
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
                venda.date = new Date().toLocaleString(); // Adiciona a data
                vendas.push(venda);
                exibirResultado(venda); // Exibe o resultado após registrar a venda
                limparCampos(); // Limpa os campos após o sucesso
                alert(`Venda registrada com sucesso! ID: ${data.id}`);
                resolve(); // Resolve a promise
            } else {
                alert(`Erro: ${data.message}`);
                reject();
            }
        })
        .catch(error => {
            console.error('Erro ao registrar venda:', error);
            reject();
        });
    });
}

function exibirResultado(venda) {
    result.classList.remove('disabled');
    result.innerHTML = `
        <div class="header-result">
            <h1>VendaSim</h1>
            <br>
            <span>${venda.date}</span>
            <h2>Venda ${venda.id}</h2>
        </div>
        <div class="info-venda">
            <p>Nome: ${venda.nome}</p>
            <p>Valor da Compra: R$${venda.valor.toFixed(2)}</p>
            <p>Valor Recebido: R$${venda.valorRecebido.toFixed(2)}</p>
            <p>Troco: R$${venda.troco.toFixed(2)}</p>
        </div>
        <button class="RegistrarNovamente">Registrar Outra Venda</button>
    `;
}

// Previne o comportamento padrão do formulário
const buttonRegister = document.querySelector(".button-register");
buttonRegister.addEventListener("click", (event) => {
    event.preventDefault();
    registrarVenda(); // Chama a função e não limpa os campos imediatamente
});

// Lógica para registrar outra venda
result.addEventListener('click', (event) => {
    if (event.target.classList.contains('RegistrarNovamente')) {
        limparCampos();
        result.classList.add('disabled'); // Adiciona a classe 'disabled' se necessário
    }
});

function limparCampos() {
    nomeProduto.value = "";
    valorDaCompra.value = "";
    valorRecebido.value = "";
}
