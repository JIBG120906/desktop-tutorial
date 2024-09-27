<?php
session_start();

if (!isset($_SESSION['ver_codigo'])) {
    echo 'No se ha verificado ningún código. Por favor, verifica tu código primero.';
    exit();
}

require("../conexion.php");
$con = conectar_bd();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nueva_contrasena'])) {
    $nueva_contrasena = $_POST['nueva_contrasena'];
    $email = $_SESSION['mail'];

   
    if (strlen($nueva_contrasena) < 8) {
        echo 'La contraseña debe tener al menos 8 caracteres.';
    } elseif (!preg_match('/[A-Za-z]/', $nueva_contrasena) || !preg_match('/[0-9]/', $nueva_contrasena)) {
        echo 'La contraseña debe contener al menos una letra y un número.';
    } else {
        $hashed_password = password_hash($nueva_contrasena, PASSWORD_BCRYPT);

       
        $stmt = $con->prepare('UPDATE usuario SET contrasena = ? WHERE correo = ?');
        if ($stmt) {
         
            $stmt->bind_param('ss', $hashed_password, $email);
            
            
            if ($stmt->execute()) {
               
                unset($_SESSION['reset_codigo']);
                unset($_SESSION['mail']);

                
                header('Location: lo.php');
                $_SESSION['ver_codigo'] = true;
                exit();
            } else {
                echo 'Error al restablecer la contraseña. Inténtalo de nuevo.';
            }
        } else {
            echo 'Error al preparar la declaración.';
        }
        $stmt->close();
    }
    exit();
}


$csrf_token = bin2hex(random_bytes(32));
$_SESSION['csrf_token'] = $csrf_token;
?>

<!DOCTYPE html>
<html>
<head>

    <title>Restablecer Contraseña</title>
    <link rel="stylesheet" href="registro_login.css">
</head>
<body>

    <div class="contrasenia_recuperar"> 
         <div class="contenedor_encabezado"> 
         <h1>Restablecer Contraseña</h1>
         </div>
    
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
            <p>Ingresa tu nueva contraseña</p>
            <input type="password" class="input_contrasenia" id="nueva_contrasena" name="nueva_contrasena" placeholder="Nueva contraseña" required>
            <button type="submit" class="boton_cambio_contrasenia">Restablecer</button>

        </form>
    </div>
    
</body>
</html>