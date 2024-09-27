<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="registro_login.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <title>Registro de Usuario</title>
</head>
<body>
<div class="registro-container">
    <center><img src="../Logo_Negativo.png" id="logo_reg" alt=""></center>
    
    <form action="funciones_logins.php?accion=registrarse" method="POST" onsubmit="return validarFormulario()">
        <div id="form_reg">
            <div class="div_reg">
                <i class="bi bi-file-person-fill">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-file-person-fill" viewBox="0 0 16 16">
                        <path d="M12 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2m-1 7a3 3 0 1 1-6 0 3 3 0 0 1 6 0m-3 4c2.623 0 4.146.826 5 1.755V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1v-1.245C3.854 11.825 5.377 11 8 11"/>
                    </svg>
                </i>
                <input type="text" id="nombre" name="nombre" placeholder="Nombre" required>
            </div>
            
            <div class="div_reg">
                <i class="bi bi-file-person-fill">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-file-person-fill" viewBox="0 0 16 16">
                        <path d="M12 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2m-1 7a3 3 0 1 1-6 0 3 3 0 0 1 6 0m-3 4c2.623 0 4.146.826 5 1.755V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1v-1.245C3.854 11.825 5.377 11 8 11"/>
                    </svg>
                </i>
                <input type="text" id="apellido" name="apellido" placeholder="Apellido" required>
            </div>
            
            <div class="div_reg">
                <i class="bi bi-envelope-fill">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-envelope-fill" viewBox="0 0 16 16">
                        <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414zM0 4.697v7.104l5.803-3.558zM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586zm3.436-.586L16 11.801V4.697z"/>
                    </svg>
                </i>
                <input type="email" id="correo" name="correo" placeholder="Correo" required>
            </div>

            <div class="div_reg">
                <i class="bi bi-person-fill">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                        <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                    </svg>
                </i>
                <input type="text" id="nombreUsuario" name="nombreUsuario" placeholder="Nombre de Usuario" required>
            </div>
           
            <div class="div_reg">
                <i class="bi bi-lock-fill">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-lock-fill" viewBox="0 0 16 16">
                        <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2m3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2"/>
                    </svg>
                </i>
                <input type="password" id="contraseña" name="contraseña" placeholder="Contraseña" required>
            </div>

            <div class="div_reg">
                <i class="bi bi-lock-fill" id="color_cambio">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-lock-fill" viewBox="0 0 16 16">
                        <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2m3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2"/>
                    </svg>
                </i>
                <input type="password" id="contraseña1" name="contraseña1" placeholder="Repita la contraseña" required>
            </div>
        </div>
            
        <div id="boton_reg">
        <button type="submit" name="envio_registro">Registrarse</button>
        </div>
    </form>
    <a href="lo.php" class="yes_account">Tengo una cuenta</a>
</div>
<script>
    function validarFormulario() {
        var contraseña = document.getElementById('contraseña');
        var contraseña1 = document.getElementById('contraseña1');
        var passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
        var isValid = true;

        contraseña.classList.remove('input-error');
        contraseña1.classList.remove('input-error');

        var icons = document.querySelectorAll('.div_reg i');
        icons.forEach(icon => icon.style.color = '#3043C'); 

        var iconContraseña = contraseña.previousElementSibling;
        var iconContraseña1 = contraseña1.previousElementSibling;

        if (!passwordPattern.test(contraseña.value)) {
            contraseña.classList.add('input-error');
            contraseña.placeholder = 'Debe tener 8 caracteres, una mayúscula, un número y un carácter especial';
            contraseña.value = '';
            iconContraseña.style.color = 'red'; 
            isValid = false;
        }

        if (contraseña.value !== contraseña1.value) {
            contraseña1.classList.add('input-error');
            contraseña1.placeholder = 'Las contraseñas no coinciden';
            contraseña1.value = '';
            iconContraseña1.style.color = 'red'; 
            isValid = false;
        }

        return isValid;
    }
</script>
</body>
</html>
