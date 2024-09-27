<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="registro_login.css">
    <title>Verificar Código</title>
</head>
<body>
    <div class="contrasenia_recuperar"> 

    <div class="contenedor_encabezado"> 
         <h1>Verificar Código</h1>
         </div>
  
    <form action="funciones_logins.php" method="post">
        <p>Se envío un <span>código de verificación</span> a su correo electrónico.</p>
        <input type="text" class="input_contrasenia" id="codigo" name="codigo" placeholder="Código de verificación" required>
        <button type="submit" class="boton_cambio_contrasenia" name="verificar_codigo">Ingresar código</button>
    </form>

    </div>
   
</body>
</html>
