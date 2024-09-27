<?php
require("../conexion.php");
$con = conectar_bd();
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION["email"])) {
    echo"inicie secion"; // Redirige a la página de login si no ha iniciado sesión
    exit();
}

// Verificar si el usuario es administrador
$email = $_SESSION["email"];
$query = "SELECT Admin FROM usuario WHERE Correo = '$email'";
$result = mysqli_query($con, $query);
$fila = mysqli_fetch_assoc($result);

if ($fila["Admin"] != 1) {
    echo "No tienes acceso a esta página.";
    exit();
}

// Función para obtener datos del usuario
function obtenerUsuario($con, $id) {
    $query = "SELECT * FROM usuario WHERE IdUsuario = $id";
    $result = mysqli_query($con, $query);
    return mysqli_fetch_assoc($result);
}

// Función para actualizar el usuario
function actualizarUsuario($con, $id, $nombre, $apellido, $correo) {
    $query = "UPDATE usuario SET Nombre = '$nombre', Apellido = '$apellido', Correo = '$correo' WHERE IdUsuario = $id";
    mysqli_query($con, $query);
}

// Función para eliminar el usuario
function eliminarUsuario($con, $id) {
    // Eliminar comentarios relacionados
    $query_comentarios = "DELETE FROM comentarios WHERE IdUsuario = $id";
    mysqli_query($con, $query_comentarios);

    // Ahora eliminar el usuario
    $query_usuario = "DELETE FROM usuario WHERE IdUsuario = $id";
    mysqli_query($con, $query_usuario);
}

// Función para cambiar el estado de administrador
function cambiarAdmin($con, $id, $admin) {
    $nuevoEstado = $admin == 1 ? 0 : 1;
    $query = "UPDATE usuario SET Admin = $nuevoEstado WHERE IdUsuario = $id";
    mysqli_query($con, $query);
}

// Procesar solicitudes de edición
if (isset($_POST['editar'])) {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $correo = $_POST['correo'];
    actualizarUsuario($con, $id, $nombre, $apellido, $correo);
    header("Location: panel.php");
    exit();
}

// Procesar solicitudes de eliminación
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    eliminarUsuario($con, $id);
    header("Location: panel.php");
    exit();
}

// Procesar solicitudes de cambio de estado de administrador
if (isset($_GET['cambiar_admin'])) {
    $id = $_GET['cambiar_admin'];
    $admin = $_GET['admin'];
    cambiarAdmin($con, $id, $admin);
    header("Location: panel.php");
    exit();
}

// Obtener todos los usuarios
$query_usuarios = "SELECT * FROM usuario";
$result_usuarios = mysqli_query($con, $query_usuarios);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        a {
            text-decoration: none;
            color: blue;
        }
    </style>
</head>
<body>
    <h1>Gestión de Usuarios</h1>
    <p>Solo los administradores pueden gestionar los usuarios.</p>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Correo</th>
                <th>Nombre de Usuario</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Admin</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($usuario = mysqli_fetch_assoc($result_usuarios)) : ?>
            <tr>
                <td><?php echo $usuario['IdUsuario']; ?></td>
                <td><?php echo $usuario['Correo']; ?></td>
                <td><?php echo $usuario['NombreUsuario']; ?></td>
                <td><?php echo $usuario['Nombre']; ?></td>
                <td><?php echo $usuario['Apellido']; ?></td>
                <td><?php echo $usuario['Admin'] == 1 ? 'Sí' : 'No'; ?></td>
                <td>
                    <a href="panel.php?editar=<?php echo $usuario['IdUsuario']; ?>">Editar</a> |
                    <a href="panel.php?eliminar=<?php echo $usuario['IdUsuario']; ?>" onclick="return confirm('¿Estás seguro de que quieres eliminar a este usuario?');">Eliminar</a> |
                    <a href="panel.php?cambiar_admin=<?php echo $usuario['IdUsuario']; ?>&admin=<?php echo $usuario['Admin']; ?>">
                        <?php echo $usuario['Admin'] == 1 ? 'Quitar Admin' : 'Hacer Admin'; ?>
                    </a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <?php
    // Mostrar el formulario de edición si se solicita
    if (isset($_GET['editar'])) {
        $id = $_GET['editar'];
        $usuario = obtenerUsuario($con, $id);
    ?>
    <h2>Editar Usuario</h2>
    <form method="POST">
        <input type="hidden" name="id" value="<?php echo $usuario['IdUsuario']; ?>">
        <label>Nombre:</label>
        <input type="text" name="nombre" value="<?php echo $usuario['Nombre']; ?>">
        <label>Apellido:</label>
        <input type="text" name="apellido" value="<?php echo $usuario['Apellido']; ?>">
        <label>Correo:</label>
        <input type="email" name="correo" value="<?php echo $usuario['Correo']; ?>">
        <input type="submit" name="editar" value="Guardar Cambios">
    </form>
    <?php } ?>

    <p><a href="logout.php">Cerrar Sesión</a></p>
</body>
</html>
