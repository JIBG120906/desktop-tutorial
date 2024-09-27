<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("location: ../Logins/lo.php");
    exit();
} else {
    $email = $_SESSION["email"];
}

function idUsuario($email) {
    require_once("../conexion.php");
    $con = conectar_bd();

    $sql = "SELECT IdUsuario FROM usuario WHERE correo = ?";

    if ($stmt = $con->prepare($sql)) {
        // Enlaza parámetros 
        $stmt->bind_param("s", $email);

        // Ejecuta la consulta
        $stmt->execute();

        // Obtiene el resultado
        $stmt->bind_result($IdUsuario);

        // Muestra el resultado
        if ($stmt->fetch()) {
            return $IdUsuario; 
        } else {
            return null; 
        }

        $stmt->close();
    }

    $con->close();
}

// Llamada a la función
$idUsuario = idUsuario($email);

?>
