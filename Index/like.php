<?php
require_once("../conexion.php");
include 'traerid.php'; 

$_SESSION["email"] = $email;
$idu = idUsuario($email); 

$con = conectar_bd();
$mesage = ""; // Variable para almacenar mensajes

if (isset($_GET['id'])) {
    $publicacionId = intval($_GET['id']); 

    
    $query = "SELECT * FROM megusta WHERE IdUsuario = ? AND IdPublicacion = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("ii", $idu, $publicacionId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        // Si no existe un "like", insertar uno nuevo
        $insertQuery = "INSERT INTO megusta (IdUsuario, IdPublicacion) VALUES (?, ?)";
        $insertStmt = $con->prepare($insertQuery);
        $insertStmt->bind_param("ii", $idu, $publicacionId);

        if ($insertStmt->execute()) {
            $mesage = "Like agregado exitosamente.";
        } else {
            $mesage = "Error al agregar el like: " . $con->error;
        }
        $insertStmt->close(); // Cerrar el statement
    } else {
        $mesage = "Ya has dado like a esta publicación.";
    }
} else {
    $mesage = "Error: ID de publicación no recibido.";
}

// Cerrar conexiones
$stmt->close();
$con->close(); 

// Guardar el mensaje en la sesión para mostrar en index.php
$_SESSION['message'] = $message;

// Redireccionar a index.php
header("Location: index.php");
exit(); // Asegurarse de que el script se detenga después de la redirección
?>
