<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="registro_login.css">
    <title></title>
</head>
<body>
 
    <div class="contrasenia_recuperar">
     
    
    <div class="contenedor_encabezado">
    <h1>Recuperar Contraseña</h1>
    </div>
    <form action="Funciones_logins.php" method="post">

        <p>Ingrese su correo para solicitar el <span>código de restablecimiento.</span></p>
        <input type="email" class="input_contrasenia" id="email" name="correo" placeholder="Correo electrónico" required>
        <button type="submit" name="envio_codigo" class="boton_cambio_contrasenia">Solicitar código</button>
        
    </form>
    </div>
</body>
</html>
