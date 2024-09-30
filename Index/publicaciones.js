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

                // rellenar los datos
                tarjeta.querySelector('.card-title').textContent = publicacion.Titulo;
                tarjeta.querySelector('.card-subtitle').textContent = 'Categoría: ' + publicacion.Categoria;
                tarjeta.querySelector('.card-text').textContent = publicacion.Contenido;
                tarjeta.querySelector('small').textContent = 'Fecha: ' + publicacion.FechaPublicacion;
                tarjeta.querySelector('.card-licencia').innerHTML += publicacion.licencia;

                const imgElement = tarjeta.querySelector('.card-img-top');
                const hiddenInput = tarjeta.querySelector('.publicacion-id');

                hiddenInput.value = publicacion.IdPublicacion;

                
                const meGustaButton = tarjeta.querySelector('.me-gusta');
                meGustaButton.setAttribute('data-id', publicacion.IdPublicacion);

                // mostrar la imagen 
                if (/\.(jpg|jpeg|png|gif)$/i.test(publicacion.Archivos)) {
                    imgElement.src = publicacion.Archivos;
                    imgElement.classList.remove('d-none');
                } else {
                    const linkElement = tarjeta.querySelector('a');
                    linkElement.href = publicacion.Archivos;
                    linkElement.classList.remove('d-none');
                }

                //  contador de likes 
                const likeCountElement = tarjeta.querySelector('.like-count');
                likeCountElement.textContent = publicacion.total_likes || 0;

                // mostrar foto de el que creo la publicacion
                if (publicacion.Foto) {
                    const fotoPerfil = tarjeta.querySelector('.foto-perfil-publicacion');
                    fotoPerfil.src = 'data:image/jpeg;base64,' + publicacion.Foto;
                    fotoPerfil.alt = 'Foto de perfil';
                    fotoPerfil.classList.remove('d-none');
                }

                publicacionesContainer.appendChild(tarjeta);
            });

            //evento a botones like
            const meGustaButtons = document.querySelectorAll('.me-gusta');
            meGustaButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const publicacionId = this.getAttribute('data-id');

                   
                    fetch('like.php?id=' + encodeURIComponent(publicacionId), { method: 'POST' })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Error al dar Me Gusta');
                            }
                            return response.json();
                        })
                        .then(data => {
                           
                            window.location.reload();
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
