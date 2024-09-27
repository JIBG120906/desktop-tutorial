<?php
require("../conexion.php");
$con = conectar_bd();

// Modificar el query para traer solo publicaciones públicas y la cantidad de likes
$sql = "SELECT 
            p.IdPublicacion, 
            p.Titulo, 
            p.Categoria, 
            p.Contenido, 
            p.PrivadoPublico, 
            p.Archivos, 
            DATE(p.FechaPublicacion) AS FechaPublicacion, 
            p.IdUsuario, 
            p.licencia, 
            (SELECT COUNT(*) FROM megusta WHERE IdPublicacion = p.IdPublicacion) AS total_likes 
        FROM publicaciones p 
        WHERE p.PrivadoPublico = 'publico'";

$result = $con->query($sql);

$publicaciones = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $publicaciones[] = $row;
    }
}

// Devolver el JSON con las publicaciones y el conteo de likes
header('Content-Type: application/json');
echo json_encode($publicaciones);

$con->close();
?>
