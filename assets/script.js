document.querySelectorAll('[data-confirm]').forEach((elemento) => {
    elemento.addEventListener('click', (evento) => {
        const mensagem = elemento.getAttribute('data-confirm');

        if (!confirm(mensagem)) {
            evento.preventDefault();
        }
    });
});
