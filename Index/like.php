<?php
require_once("../conexion.php");
include 'traerid.php'; 

session_start(); 
$email = $_SESSION["email"];
$idu = idUsuario($email); 

$con = conectar_bd();

if (isset($_GET['id'])) {
    $publicacionId = intval($_GET['id']); 

    // comprovar si ya hay un like
    $query = "SELECT * FROM megusta WHERE IdUsuario = ? AND IdPublicacion = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("ii", $idu, $publicacionId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        // si no hy un like lo crea
        $insertQuery = "INSERT INTO megusta (IdUsuario, IdPublicacion) VALUES (?, ?)";
        $insertStmt = $con->prepare($insertQuery);
        $insertStmt->bind_param("ii", $idu, $publicacionId);
        $insertStmt->execute();
        $insertStmt->close();
    } else {
        // si ya hay un like lo elimina
        $deleteQuery = "DELETE FROM megusta WHERE IdUsuario = ? AND IdPublicacion = ?";
        $deleteStmt = $con->prepare($deleteQuery);
        $deleteStmt->bind_param("ii", $idu, $publicacionId);
        $deleteStmt->execute();
        $deleteStmt->close();
    }

    // contar cantidad de likes en una publicacion
    $countQuery = "SELECT COUNT(*) as total_likes FROM megusta WHERE IdPublicacion = ?";
    $countStmt = $con->prepare($countQuery);
    $countStmt->bind_param("i", $publicacionId);
    $countStmt->execute();
    $countResult = $countStmt->get_result();
    $row = $countResult->fetch_assoc();

    // mandar total de likes en json al js
    echo json_encode(['total_likes' => $row['total_likes']]);
    
  
    $stmt->close();
    $con->close();
} else {
    echo json_encode(['error' => 'ID de publicación no recibido.']);
}
?>
