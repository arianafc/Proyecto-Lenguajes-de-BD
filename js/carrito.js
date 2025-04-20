



document.addEventListener("DOMContentLoaded", function () {

    function actualizarContadorCarrito() {
        fetch('./data/addArticuloCarrito.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'action=contarCarrito'
        })
        .then(res => res.json())
        .then(data => {
            const badge = document.getElementById('contadorCarrito');
            if (data.success && badge) {
                badge.textContent = data.total;
                badge.style.display = data.total > 0 ? 'inline-block' : 'none';
            }
        })
        .catch(err => console.error('Error al contar artículos del carrito:', err));
    }
    
   
    actualizarContadorCarrito();

    // Opcional: actualiza cada 30 segundos automáticamente
    setInterval(actualizarContadorCarrito, 30000);

});