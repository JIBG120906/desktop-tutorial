<?php
require_once("../conexion.php");

if (isset($_GET['id'])) {
    $publicacionId = intval($_GET['id']); 

    // Conectar a la base de datos
    $con = conectar_bd();

    // Consultar la cantidad total de "likes" para la publicación
    $countQuery = "SELECT COUNT(*) as total_likes FROM megusta WHERE IdPublicacion = ?";
    $countStmt = $con->prepare($countQuery);
    $countStmt->bind_param("i", $publicacionId);
    $countStmt->execute();
    $countResult = $countStmt->get_result();

    if ($countRow = $countResult->fetch_assoc()) {
        $totalLikes = $countRow['total_likes'];
        // Devolver el total de likes en formato JSON
        echo json_encode(['total_likes' => $totalLikes]);
    } else {
        echo json_encode(['total_likes' => 0]); // En caso de que no haya likes
    }

    // Cerrar conexiones
    $countStmt->close();
    $con->close(); 
} else {
    echo json_encode(['error' => 'ID de publicación no recibido']);
}
?>
