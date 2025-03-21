document.addEventListener('DOMContentLoaded', function() {
    // Manejador para ver elementos
    document.querySelectorAll('.ver-elementos').forEach(button => {
        button.addEventListener('click', function() {
            const usuarioId = this.dataset.usuarioId;
            const modal = document.getElementById('elementosModal');
            
            fetch(`/admin/usuarios/${usuarioId}/elementos`)
                .then(response => response.json())
                .then(data => {
                    const elementosLista = document.getElementById('elementos-lista');
                    if (data.elementos.length === 0) {
                        elementosLista.innerHTML = '<p class="has-text-centered">Este usuario no tiene elementos registrados.</p>';
                    } else {
                        elementosLista.innerHTML = `
                            <div class="elementos-grid">
                                ${data.elementos.map(elemento => `
                                    <div class="elemento-card">
                                        <p><strong>Categoría:</strong> ${elemento.categoria.nombre}</p>
                                        <p><strong>Descripción:</strong> ${elemento.descripcion}</p>
                                        <p><strong>Marca:</strong> ${elemento.marca}</p>
                                        <p><strong>Modelo:</strong> ${elemento.modelo}</p>
                                        <img src="{{ asset('storage/' . $elemento->foto) }}" alt="Foto del elemento" style="max-width: 100px; max-height: 100px; border-radius: 5px;">
                                        ${elemento.serie ? `<p><strong>Serie:</strong> ${elemento.serie}</p>` : ''}
                                    </div>
                                `).join('')}
                            </div>
                        `;
                    }
                    modal.classList.add('is-active');
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al cargar los elementos del usuario');
                });
        });
    });

    // Cerrar modal
    document.querySelectorAll('.modal .delete, .modal-background').forEach(element => {
        element.addEventListener('click', () => {
            document.querySelectorAll('.modal').forEach(modal => {
                modal.classList.remove('is-active');
            });
        });
    });
});
