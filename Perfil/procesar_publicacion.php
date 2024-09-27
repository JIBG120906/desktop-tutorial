<?php
require_once("../conexion.php");
$con = conectar_bd();

require("funciones.php");
comprobar_sesion();

$consulta_usuario = "SELECT IdUsuario FROM usuario WHERE Correo = ?";
if ($stmt = $con->prepare($consulta_usuario)) {
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($id_usuario);
    if ($stmt->fetch()) {
        // Obtiene el ID del usuario
    } else {
        echo "Error: No se pudo obtener el ID del usuario.";
        exit;
    }
    $stmt->close();
} else {
    echo "Error en la preparación de la consulta: " . $con->error;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['titulo']) && !empty($_POST['titulo'])) {
        $categoria = $_POST['categoria'];
        $titulo = $_POST['titulo'];
        $contenido = $_POST['contenido'];
        $PrivadoPublico = $_POST['PrivadoPublico'];
        $Licencia = $_POST['Licencia'];

        // Ruta de la carpeta de publicaciones del usuario según visibilidad
        $carpetaBase = '../Index/publicaciones/';
        $carpetaVisibilidad = $PrivadoPublico === 'Publico' ? 'publico' : 'privado';
        $carpetaUsuario = $carpetaBase . "$carpetaVisibilidad/$id_usuario/";

        // Verifica si la carpeta del usuario existe, si no, la crea
        if (!file_exists($carpetaUsuario)) {
            mkdir($carpetaUsuario, 0777, true); // Crea la carpeta con permisos 0777
        }

        // Manejo de archivos
        $archivoNombre = $_FILES['archivo']['name'];
        $archivoTmp = $_FILES['archivo']['tmp_name'];
        $archivoDestino = $carpetaUsuario . basename($archivoNombre);

        // Valida el archivo
        $uploadOk = 1;
        $fileType = strtolower(pathinfo($archivoDestino, PATHINFO_EXTENSION));

        // Definir extensiones permitidas según la visibilidad
        if ($PrivadoPublico === 'Publico') {
            // Público: solo imágenes, audio y PDF
            $extensionesPermitidas = array('jpg', 'jpeg', 'png', 'pdf', 'mp3', 'wav');
        } else {
            // Privado: permitir todos los archivos
            $extensionesPermitidas = array('jpg', 'jpeg', 'png', 'mp4', 'avi', 'mov', 'wmv', 'mkv', 'pdf', 'mp3', 'wav', 'zip', 'rar', 'doc', 'docx', 'sql');
        }

        // Verifica si el archivo ya existe
        if (file_exists($archivoDestino)) {
            echo "Lo siento, el archivo ya existe.";
            $uploadOk = 0;
        }

        // Verifica el tipo de archivo según las restricciones
        if (!in_array($fileType, $extensionesPermitidas)) {
            echo "Lo siento, el tipo de archivo no es permitido.";
            $uploadOk = 0;
        }

        // Verifica el tamaño del archivo (ajustar a 100MB para videos, por ejemplo)
        if ($_FILES['archivo']['size'] > 100000000) { // 100MB
            echo "Lo siento, el archivo es demasiado grande.";
            $uploadOk = 0;
        }

        // Intenta subir el archivo
        if ($uploadOk == 1) {
            if (move_uploaded_file($archivoTmp, $archivoDestino)) {
                // Inserta los datos en la base de datos
                $sql = "INSERT INTO publicaciones (categoria, titulo, contenido, PrivadoPublico, Archivos, FechaPublicacion, IdUsuario, Licencia)
                        VALUES (?, ?, ?, ?, ?, NOW(), ?, ?)";

                if ($stmt = $con->prepare($sql)) {
                    // Enlaza parámetros
                    $stmt->bind_param("sssssss", $categoria, $titulo, $contenido, $PrivadoPublico, $archivoDestino, $id_usuario, $Licencia);

                    // Ejecuta la consulta
                    if ($stmt->execute()) {
                        echo "Publicación agregada exitosamente.";
                    } else {
                        echo "Error al insertar datos: " . $stmt->error;
                    }
                    $stmt->close();
                } else {
                    echo "Error en la preparación de la consulta: " . $con->error;
                }
            } else {
                echo "Lo siento, hubo un error al subir tu archivo.";
            }
        }
    } else {
        echo "Error: El título no puede estar vacío.";
    }
}

$con->close();
?>
