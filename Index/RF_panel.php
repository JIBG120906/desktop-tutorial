<?php
require("../conexion.php");

session_start();

$con = conectar_bd();

if (!isset($_SESSION["email"])) {
    header("Location: ../logins/lo.php");
    exit();
}

$email = $_SESSION["email"];
$query = "SELECT Admin FROM usuario WHERE Correo = ?";
$stmt = $con->prepare($query);
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();
$fila = $result->fetch_assoc();

if ($fila["Admin"] != 1) {
    echo "No tienes acceso a esta página.";
    exit();
}

function obtenerTodosUsuarios($con) {
    $query = "SELECT * FROM usuario";
    $result = mysqli_query($con, $query);
    if (!$result) {
        die("Error en la consulta: " . mysqli_error($con));
    }
    return $result;
}

if (isset($_POST['accion'])) {
    $accion = $_POST['accion'];

    if ($accion === 'editar') {
        $id = $_POST['id'];
        $correo = $_POST['correo'];
        $nombreUsuario = $_POST['nombreUsuario'];
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        
        $query = "UPDATE usuario SET Correo = ?, NombreUsuario = ?, Nombre = ?, Apellido = ? WHERE IdUsuario = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param('ssssi', $correo, $nombreUsuario, $nombre, $apellido, $id);
        if ($stmt->execute()) {
            echo "Usuario actualizado exitosamente.";
        } else {
            echo "Error al actualizar el usuario: " . $stmt->error;
        }
    } elseif ($accion === 'eliminar') {
        $id = $_POST['id'];
        $query = "DELETE FROM usuario WHERE IdUsuario = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param('i', $id);
        if ($stmt->execute()) {
            echo "Usuario eliminado exitosamente.";
        } else {
            echo "Error al eliminar el usuario: " . $stmt->error;
        }
    } elseif ($accion === 'cambiar_admin') {
        $id = $_POST['id'];
        $admin = $_POST['admin'] == 1 ? 0 : 1;
        $query = "UPDATE usuario SET Admin = ? WHERE IdUsuario = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param('ii', $admin, $id);
        if ($stmt->execute()) {
            echo $admin == 1 ? "Usuario promovido a administrador." : "Usuario despromovido de administrador.";
        } else {
            echo "Error al cambiar el rol de administrador: " . $stmt->error;
        }
    }
} else {
    $usuariosResult = obtenerTodosUsuarios($con);
    $response = [];
    while ($usuario = mysqli_fetch_assoc($usuariosResult)) {
        $response[] = $usuario;
    }
    echo json_encode($response);
}

$con->close();
?>
