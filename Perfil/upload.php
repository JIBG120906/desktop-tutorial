<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("location: ../Logins/lo.php");
    exit();
} else {
    $email = $_SESSION["email"];
}


require("../conexion.php");
$con = conectar_bd();


$consulta_usuario = "SELECT IdUsuario, NombreUsuario FROM usuario WHERE Correo = '$email'";
$resultado_usuario = mysqli_query($con, $consulta_usuario);
$usuario = mysqli_fetch_assoc($resultado_usuario);

$id_usuario = $usuario['IdUsuario'];
$nickname = $usuario['NombreUsuario'];

// Sacar perfil de usuario
$consulta_perfil = "SELECT * FROM perfil WHERE IdUsuario = $id_usuario";
$resultado_perfil = mysqli_query($con, $consulta_perfil);
$perfil = mysqli_fetch_assoc($resultado_perfil);


$mensaje = "";

// verificar si el perifl esta creado
$perfil_existe = !is_null($perfil);

//consulta paises
$consulta_paises = "SELECT Pais FROM paises";
$resultado_paises = mysqli_query($con, $consulta_paises);
$paises = [];
while ($pais = mysqli_fetch_assoc($resultado_paises)) {
    $paises[] = $pais['Pais'];
}

// formulario
if (isset($_POST["guardar"])) {
    $pais = $_POST["pais"];
    $telefono = $_POST["telefono"];
    $fecha_nacimiento = $_POST["fecha_nacimiento"];
    $sexo = $_POST["sexo"];

    if ($perfil_existe) {
        // Actualizar datos del perfil
        $consulta_actualizar = "UPDATE perfil SET Pais = '$pais', Telefono = '$telefono', FechaNacimiento = '$fecha_nacimiento', Sexo = '$sexo' WHERE IdUsuario = $id_usuario";
        
        if (mysqli_query($con, $consulta_actualizar)) {
            $mensaje = "Perfil actualizado correctamente.";
        } else {
            $mensaje = "Error al actualizar el perfil.";
        }
    } else {
        // Crear nuevo perfil
        $consulta_crear = "INSERT INTO perfil (IdUsuario, Pais, Telefono, FechaNacimiento, Sexo) VALUES (?, ?, ?, ?, ?)";
        $stmt = $con->prepare($consulta_crear);
        $stmt->bind_param("issss", $id_usuario, $pais, $telefono, $fecha_nacimiento, $sexo);

        if ($stmt->execute()) {
            $mensaje = "Perfil creado correctamente.";
        } else {
            $mensaje = "Error al crear el perfil.";
        }

        $stmt->close();
    }

   // subir foto
if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
    $archivoImagen = $_FILES['foto']['tmp_name'];

    if (file_exists($archivoImagen)) {
        $contenidoBinario = file_get_contents($archivoImagen);

        if ($perfil_existe) {
            $stmt = $con->prepare("UPDATE perfil SET Foto = ? WHERE IdUsuario = ?");
            $stmt->bind_param("bi", $dummy, $id_usuario);
        } else {
            $stmt = $con->prepare("INSERT INTO perfil (IdUsuario, Foto) VALUES (?, ?)");
            $stmt->bind_param("ib", $id_usuario, $dummy);
        }

        // Asigna la longitud de datos binarios
        $stmt->send_long_data(0, $contenidoBinario);

        if ($stmt->execute()) {
            $mensaje = "Imagen actualizada correctamente.";
        } else {
            $mensaje = "Error al actualizar la imagen: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $mensaje = "El archivo no existe o no se puede leer.";
    }
}

// subir banner
if (isset($_FILES['banner']) && $_FILES['banner']['error'] === 0) {
    $archivoImagen = $_FILES['banner']['tmp_name'];

    if (file_exists($archivoImagen)) {
        $contenidoBinario = file_get_contents($archivoImagen);

        if ($perfil_existe) {
            $stmt = $con->prepare("UPDATE perfil SET Banner = ? WHERE IdUsuario = ?");
            $stmt->bind_param("bi", $dummy, $id_usuario);
        } else {
            $stmt = $con->prepare("INSERT INTO perfil (IdUsuario, Banner) VALUES (?, ?)");
            $stmt->bind_param("ib", $id_usuario, $dummy);
        }

        // Asigna la longitud de datos binarios
        $stmt->send_long_data(0, $contenidoBinario);

        if ($stmt->execute()) {
            $mensaje = "Banner actualizado correctamente.";
        } else {
            $mensaje = "Error al actualizar el banner: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $mensaje = "El archivo no existe o no se puede leer.";
    }
}

   
    header("Location: perfil.php");
    exit();
}


// foto
$foto_mostrar = '';
if ($perfil_existe && !empty($perfil['Foto'])) {
    $foto_base64 = base64_encode($perfil['Foto']);
    $foto_mostrar = '<div class="foto-perfil" style="background-image: url(\'data:image/jpeg;base64,' . $foto_base64 . '\');"></div>';
}

// banner
$banner_mostrar = '';
if ($perfil_existe && !empty($perfil['Banner'])) {
    $banner_base64 = base64_encode($perfil['Banner']);
    $banner_mostrar = '<div class="banner-perfil" style="background-image: url(\'data:image/jpeg;base64,' . $banner_base64 . '\');"></div>';
}
?>
