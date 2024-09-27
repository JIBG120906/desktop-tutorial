document.addEventListener('DOMContentLoaded', () => {
    const template = document.getElementById('tarjeta-template').content;
    const publicacionesContainer = document.getElementById('publicaciones');

    // Limpiar el contenedor antes de cargar nuevas publicaciones
    publicacionesContainer.innerHTML = '';

    fetch('traer_publicaciones.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta de la red');
            }
            return response.json();
        })
        .then(publicaciones => {
            if (publicaciones.length === 0) {
                const noPublicacionesMsg = document.createElement('p');
                noPublicacionesMsg.textContent = 'No hay publicaciones disponibles.';
                publicacionesContainer.appendChild(noPublicacionesMsg);
                return;
            }

            publicaciones.forEach(publicacion => {
                const tarjeta = document.importNode(template, true);

                // Rellenar los datos de la tarjeta
                tarjeta.querySelector('.card-title').textContent = publicacion.Titulo;
                tarjeta.querySelector('.card-subtitle').textContent = 'Categoría: ' + publicacion.Categoria;
                tarjeta.querySelector('.card-text').textContent = publicacion.Contenido;
                tarjeta.querySelector('small').textContent = 'Fecha: ' + publicacion.FechaPublicacion;
                tarjeta.querySelector('.card-licencia').innerHTML += publicacion.licencia;

                const imgElement = tarjeta.querySelector('.card-img-top');
                const hiddenInput = tarjeta.querySelector('.publicacion-id');

                // Asignar el ID de la publicación al campo oculto
                hiddenInput.value = publicacion.IdPublicacion;

                // Asignar el ID al botón "Me Gusta"
                const meGustaButton = tarjeta.querySelector('.me-gusta');
                meGustaButton.setAttribute('data-id', publicacion.IdPublicacion);

                // Mostrar la imagen si es un archivo de imagen
                if (/\.(jpg|jpeg|png|gif)$/i.test(publicacion.Archivos)) {
                    imgElement.src = publicacion.Archivos;
                    imgElement.classList.remove('d-none');
                } else {
                    const linkElement = tarjeta.querySelector('a');
                    linkElement.href = publicacion.Archivos;
                    linkElement.classList.remove('d-none');
                }

                // Mostrar la cantidad de likes
                const likeCountElement = tarjeta.querySelector('.like-count');
                likeCountElement.textContent = publicacion.total_likes || 0; // Asignar total de likes

                publicacionesContainer.appendChild(tarjeta);
            });

            // Agregar el evento de clic después de agregar las tarjetas
            const meGustaButtons = document.querySelectorAll('.me-gusta');
            meGustaButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const publicacionId = this.getAttribute('data-id');

                    // Redirigir a like.php pasando el ID en la URL
                    fetch('like.php?id=' + encodeURIComponent(publicacionId), { method: 'POST' })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Error al dar Me Gusta');
                            }
                            return response.json();
                        })
                        .then(data => {
                            // Actualiza el contador de "likes"
                            actualizarContadorLikes(publicacionId);
                        })
                        .catch(error => console.error('Error al procesar Me Gusta:', error));
                });
            });
        })
        .catch(error => {
            console.error('Error al cargar las publicaciones:', error);
            const errorMsg = document.createElement('p');
            errorMsg.textContent = 'Error al cargar las publicaciones. Intenta más tarde.';
            publicacionesContainer.appendChild(errorMsg);
        });
});

// Función para actualizar el contador de likes
function actualizarContadorLikes(publicacionId) {
    fetch('contador_likes.php?id=' + encodeURIComponent(publicacionId))
        .then(response => response.json())
        .then(data => {
            if (data.total_likes !== undefined) {
                
                const tarjeta = document.querySelector(`.publicacion-id[value="${publicacionId}"]`).closest('.card-body');
                const likeCountElement = tarjeta.querySelector('.like-count');
                likeCountElement.textContent = data.total_likes;
            }
        })
        .catch(error => {
            console.error('Error al actualizar los likes:', error);
        });
}
