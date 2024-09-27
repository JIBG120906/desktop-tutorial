<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="registro_login.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <title></title>
    
</head>
<body>
    
    
        <div class="login-container">

        <div class="foto_display_left"><p></p></div>

            <main>
                
                <div class="logo_login" >

                <img src="../Logo_Principal.png" class="img_logo" id="logo_no_oculto" alt="">
                <img src="../Logo_Negativo.png" class="img_logo" id="logo_oculto" alt="">


                </div>
                
                    <form action="Funciones_logins.php" class="form_login"  method="POST" >
                    
                        <div class="div_login">
                        <i class="bi bi-person-fill"><svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
            <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
            </svg></i>
                        
                        <input type="email" id="correo" name="correo" placeholder="Correo" required>
                        </div>
                        
                        <div class="div_login"> 
                        <i class="bi bi-lock-fill">
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-lock-fill" viewBox="0 0 16 16">
            <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2m3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2"/>
            </svg>
                        </i>
                        
                        <input type="password" id="con" name="con" placeholder="Contraseña" required>
                        </div>

                        <div class="div_login" id="campo-vacio">
                        <a href="for-con.php" style="text-decoration: none;" id="Forget_Password"><h1>¿Olvidaste tu contraseña?</h1></a>
                        </div>

                        <button type="submit" name="envio_login">Iniciar Sesión</button>

                        <a href="reg.php" class="no_account">No tengo una cuenta</a>
                    </form>
            </main>
        </div>
          

</body>
</html>

