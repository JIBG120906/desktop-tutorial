<?php
require("../conexion.php");
require __DIR__ . '/vendor/autoload.php'; 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$con = conectar_bd();
session_start();

// código para ver desde qué página viene 
if (isset($_POST["envio_registro"])) {
    registrarse($con);
}
if (isset($_POST["envio_login"])) {
    $email = $_POST["correo"];
    $contrasenia = $_POST["con"];
    logear($con, $email, $contrasenia);
}
if (isset($_POST["envio_codigo"])) {
    enviarCodigo($con);
}
if (isset($_POST["verificar_codigo"])) {
    verificarCodigo();
}

// para crear un nuevo usuario
function registrarse($con) {
    $nombre = trim($_POST["nombre"]);
    $apellido = trim($_POST["apellido"]);
    $nombreUsuario = trim($_POST['nombreUsuario']);
    $email = trim($_POST["correo"]);
    $contrasenia = trim($_POST["contraseña"]);

    if (!empty($nombre) && !empty($apellido) && !empty($nombreUsuario) && !empty($email) && !empty($contrasenia)) {
        if (strlen($contrasenia) < 6) {
            echo "La contraseña debe tener al menos 6 caracteres.";
            return;
        }

        $existe_usr = consultar_existe_usr($con, $email);
        insertar_datos($con, $nombre, $apellido, $email, $contrasenia, $existe_usr, $nombreUsuario);
    } else {
        echo "Todos los campos son requeridos.";
    }
}

// ver si el usuario existe en la bd
function consultar_existe_usr($con, $email) {
    $consulta_existe_usr = "SELECT Correo FROM Usuario WHERE Correo = ?";
    $stmt = mysqli_prepare($con, $consulta_existe_usr);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $existe = mysqli_stmt_num_rows($stmt) > 0;
    mysqli_stmt_close($stmt);
    return $existe;
}

// insertar datos en la bd
function insertar_datos($con, $nombre, $apellido, $email, $contrasenia, $existe_usr, $nombreUsuario) {
    if (!$existe_usr) {
        $consulta_insertar = "INSERT INTO Usuario (Correo, NombreUsuario, contrasena, Nombre, Apellido) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($con, $consulta_insertar);
        $contrasenia_hash = password_hash($contrasenia, PASSWORD_DEFAULT);
        mysqli_stmt_bind_param($stmt, "sssss", $email, $nombreUsuario, $contrasenia_hash, $nombre, $apellido);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            mysqli_close($con);
            header("Location: lo.php");
            exit();
        } else {
            echo "Error: " . mysqli_error($con);
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "El usuario ya existe.";
    }
}

// loguear un usuario
function logear($con, $email, $pass) {
    $consulta_login = "SELECT * FROM Usuario WHERE Correo = ?";
    $stmt = mysqli_prepare($con, $consulta_login);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $resultado_login = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($resultado_login) > 0) {
        $fila = mysqli_fetch_assoc($resultado_login);
        $password_bd = $fila["contrasena"];
        $Nickname = $fila["NombreUsuario"];
        $admin = $fila["Admin"];

        if (password_verify($pass, $password_bd)) {
            $_SESSION["email"] = $email;
            $_SESSION["nombre_usuario"] = $Nickname;

            if ($admin == 1) {
                header("Location: ../index/panel.php");
            } else {
                header("Location: ../index/index.php");
            }
            
            exit();
        } else {
            echo "Contraseña incorrecta";
        }
    } else {
        echo "El usuario con ese correo no existe";
        echo "<br>";
        echo '<a href="lo.php">Login</a>';
    }
    mysqli_stmt_close($stmt);
}

// enviar código de verificación por correo
function enviarCodigo($con) {
    
    $email = trim($_POST['correo']);
    $_SESSION['mail'] = $email;
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "El formato de correo no es válido.";
        return;
    }

    $consulta = "SELECT Correo FROM Usuario WHERE Correo = ?";
    $stmt = mysqli_prepare($con, $consulta);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($resultado) > 0) {
        $codigo = bin2hex(random_bytes(4)); 
        $_SESSION['codigo_verificacion'] = $codigo;
        $_SESSION['email_recuperacion'] = $email;

        enviarCorreo($email, $codigo);
        
        
        header("Location: verificacion.php");
        exit();
    } else {
        echo "El correo no está registrado.";
    }

    mysqli_stmt_close($stmt);
}

// verifica el código de recuperación
function verificarCodigo() {
    $codigo_ingresado = $_POST['codigo'];
    if (isset($_SESSION['codigo_verificacion']) && $codigo_ingresado === $_SESSION['codigo_verificacion']) {
        $_SESSION['ver_codigo'] = true;
        header("Location: restablecer.php");
        exit();
    } else {
        echo "El código ingresado es incorrecto.";
    }
}

// función para enviar mails
function enviarCorreo($email, $codigo) {
    $mail = new PHPMailer(true);

    try {
       
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'codyment4@gmail.com'; 
        $mail->Password = 'escrwtjrwhynwcjs'; 
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        // Creación del mail
        $mail->setFrom('codyment4@gmail.com', 'Codyment');
        $mail->addAddress($email); 
        $mail->isHTML(true);
        $mail->Subject = 'Código de recuperación de contraseña';
        $mail->Body    = "<strong>Tu código de recuperación es: <b>$codigo</b></strong>";
        $mail->AltBody = "Tu código de recuperación es: $codigo";

        $mail->send();
        header("Location: verificacion.php");
    } catch (Exception $e) {
        echo 'Error al enviar el correo: ' . $mail->ErrorInfo;
    }
}
?>
