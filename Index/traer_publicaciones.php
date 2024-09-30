<?php
require("../conexion.php");
$con = conectar_bd();


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
            pf.Foto,  
            (SELECT COUNT(*) FROM megusta WHERE IdPublicacion = p.IdPublicacion) AS total_likes 
        FROM publicaciones p 
        JOIN perfil pf ON p.IdUsuario = pf.IdUsuario  
        WHERE p.PrivadoPublico = 'publico'";


if ($result = $con->query($sql)) {
    $publicaciones = array();
    //ver si existe la imagen y pasarla abase64
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            
            if (!empty($row['Foto'])) {
                $row['Foto'] = base64_encode($row['Foto']);
            }
            $publicaciones[] = $row;
        }
    }
    
    // devolver el json con los dato
    header('Content-Type: application/json');
    echo json_encode($publicaciones);
} else {
    
    http_response_code(500); 
    echo json_encode(["error" => "Error en la consulta SQL: " . $con->error]);
}

$con->close();

?>
