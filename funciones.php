<?php

require_once("conexion.php");
$con = conectar_bd();

function comprobar_sesion(){
    // Verificar si una sesión ya está activa antes de iniciarla
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['email'])) {
        header("location: ../logins/lo.php");
        exit();
    } else {
        // Define email and nickname variables for global usage
        global $email, $nickname;
        $nickname = $_SESSION["nombre_usuario"];
        $email = $_SESSION["email"];
    }
}

function traer_categorias() {
    global $con;
    $sql = "SELECT NombreCategoria FROM categoriapublicacion";
    $resultado = $con->query($sql);

    if ($resultado->num_rows > 0) {
        while ($fila = $resultado->fetch_assoc()) {
            echo "<option value='" . htmlspecialchars($fila['NombreCategoria']) . "'>" . htmlspecialchars($fila['NombreCategoria']) . "</option>";
        }
    } else {
        echo "<option value=''>No hay categorías disponibles</option>";
    }
}

function traer_licencias() {
    global $con;
    $sql = "SELECT Tipos FROM licencias";
    $resultado = $con->query($sql);
    if ($resultado->num_rows > 0) {
        while ($fila = $resultado->fetch_assoc()) {
            echo "<option value='" . $fila['Tipos'] . "'>" . $fila['Tipos'] . "</option>";
        }
    } else {
        echo "<option value=''>No hay licencias disponibles</option>";
    }
}
?>
