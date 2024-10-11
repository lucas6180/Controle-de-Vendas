
document.getElementById('valorCompraInput').addEventListener('input', () => {
    valorDaCompraDisplay.textContent = 'Valor: R$ ' + calcularValorCompra().toFixed(2);
});

document.getElementById('menu-hamburguer').addEventListener('click', () => {
    const nav = document.getElementById('nav');
    nav.classList.toggle('nav-visible');
});
