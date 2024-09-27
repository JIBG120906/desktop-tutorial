document.addEventListener('DOMContentLoaded', function() { 
    // Cargar todos los usuarios al inicio
    cargarUsuarios();

    // Buscar usuario
    document.getElementById('usuario').addEventListener('input', function() {
        const nombreUsuario = this.value;
        buscarUsuario(nombreUsuario);
    });

    // Editar usuario
    document.getElementById('cuerpo-tabla').addEventListener('click', function(e) {
        if (e.target.classList.contains('editar-btn')) {
            const fila = e.target.closest('tr');
            const camposEditable = fila.querySelectorAll('.editable');

            // Cambiar los campos a modo de edición
            camposEditable.forEach(campo => {
                const texto = campo.textContent;
                campo.innerHTML = `<input type="text" value="${texto}">`;
            });

            e.target.textContent = 'Guardar';
            e.target.classList.remove('editar-btn');
            e.target.classList.add('guardar-btn');
        } else if (e.target.classList.contains('guardar-btn')) {
            const fila = e.target.closest('tr');
            const camposEditable = fila.querySelectorAll('.editable input');
            const datos = {
                id: fila.querySelector('.id-usuario').textContent,
                correo: camposEditable[0].value,
                nombreUsuario: camposEditable[1].value,
                nombre: camposEditable[2].value,
                apellido: camposEditable[3].value
            };

            actualizarUsuario(datos);
        }
    });

    // Eliminar usuario
    document.getElementById('cuerpo-tabla').addEventListener('click', function(e) {
        if (e.target.classList.contains('eliminar-btn')) {
            const id = e.target.getAttribute('data-id');
            if (confirm('¿Estás seguro de que quieres eliminar a este usuario?')) {
                eliminarUsuario(id);
            }
        }
    });

    // Cambiar estado de admin
    document.getElementById('cuerpo-tabla').addEventListener('click', function(e) {
        if (e.target.classList.contains('cambiar-admin-btn')) {
            const id = e.target.getAttribute('data-id');
            const admin = e.target.getAttribute('data-admin');
            cambiarAdmin(id, admin);
        }
    });

    // Funciones AJAX
    function actualizarUsuario(datos) {
        fetch('RF_panel.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `accion=editar&id=${datos.id}&correo=${datos.correo}&nombreUsuario=${datos.nombreUsuario}&nombre=${datos.nombre}&apellido=${datos.apellido}`
        })
        .then(response => response.text())
        .then(data => {
            alert(data);  // Muestra el mensaje de éxito o error
            cargarUsuarios();  // Vuelve a cargar los usuarios después de la actualización
        })
        .catch(error => console.error('Error al actualizar usuario:', error));
    }

    function eliminarUsuario(id) {
        fetch('RF_panel.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `accion=eliminar&id=${id}`
        })
        .then(response => response.text())
        .then(data => {
            alert(data);
            cargarUsuarios();  // Vuelve a cargar los usuarios después de eliminar
        })
        .catch(error => console.error('Error al eliminar usuario:', error));
    }

    function cambiarAdmin(id, admin) {
        fetch('RF_panel.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `accion=cambiar_admin&id=${id}&admin=${admin}`
        })
        .then(response => response.text())
        .then(data => {
            alert(data);
            cargarUsuarios();  // Vuelve a cargar los usuarios después del cambio de rol
        })
        .catch(error => console.error('Error al cambiar rol de administrador:', error));
    }

    function cargarUsuarios() {
        fetch('RF_panel.php')
        .then(response => response.json())
        .then(data => {
            let cuerpoTabla = document.getElementById('cuerpo-tabla');
            cuerpoTabla.innerHTML = '';

            if (data.length === 0) {
                document.getElementById('tabla-cabecera').style.display = 'none';
                cuerpoTabla.innerHTML = `<tr><td colspan="7">No se encontraron usuarios.</td></tr>`;
            } else {
                document.getElementById('tabla-cabecera').style.display = '';
                data.forEach(usuario => {
                    cuerpoTabla.innerHTML += `
                        <tr>
                            <td class="id-usuario">${usuario.IdUsuario}</td>
                            <td class="editable">${usuario.Correo}</td>
                            <td class="editable">${usuario.NombreUsuario}</td>
                            <td class="editable">${usuario.Nombre}</td>
                            <td class="editable">${usuario.Apellido}</td>
                            <td>${usuario.Admin == 1 ? 'Sí' : 'No'}</td>
                            <td>
                                <button class="editar-btn" data-id="${usuario.IdUsuario}">Editar</button> |
                                <button class="eliminar-btn" data-id="${usuario.IdUsuario}">Eliminar</button> |
                                <button class="cambiar-admin-btn" data-id="${usuario.IdUsuario}" data-admin="${usuario.Admin}">
                                    ${usuario.Admin == 1 ? 'Quitar Admin' : 'Hacer Admin'}
                                </button>
                            </td>
                        </tr>
                    `;
                });
            }
        })
        .catch(error => {
            console.error('Error al cargar los usuarios:', error);
        });
    }

    function buscarUsuario(nombreUsuario) {
        fetch('RF_buscar_usuario.php', {  // Cambiado a RF_buscar_usuario.php
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `accion=buscar&usuario=${nombreUsuario}`
        })
        .then(response => response.json())
        .then(data => {
            let cuerpoTabla = document.getElementById('cuerpo-tabla');
            cuerpoTabla.innerHTML = '';

            if (data.length === 0) {
                document.getElementById('tabla-cabecera').style.display = 'none';
                cuerpoTabla.innerHTML = `<tr><td colspan="7">No se encontraron usuarios.</td></tr>`;
            } else {
                document.getElementById('tabla-cabecera').style.display = '';
                data.forEach(usuario => {
                    cuerpoTabla.innerHTML += `
                        <tr>
                            <td class="id-usuario">${usuario.IdUsuario}</td>
                            <td class="editable">${usuario.Correo}</td>
                            <td class="editable">${usuario.NombreUsuario}</td>
                            <td class="editable">${usuario.Nombre}</td>
                            <td class="editable">${usuario.Apellido}</td>
                            <td>${usuario.Admin == 1 ? 'Sí' : 'No'}</td>
                            <td>
                                <button class="editar-btn" data-id="${usuario.IdUsuario}">Editar</button> |
                                <button class="eliminar-btn" data-id="${usuario.IdUsuario}">Eliminar</button> |
                                <button class="cambiar-admin-btn" data-id="${usuario.IdUsuario}" data-admin="${usuario.Admin}">
                                    ${usuario.Admin == 1 ? 'Quitar Admin' : 'Hacer Admin'}
                                </button>
                            </td>
                        </tr>
                    `;
                });
            }
        })
        .catch(error => {
            console.error('Error al buscar usuario:', error);
        });
    }
});
