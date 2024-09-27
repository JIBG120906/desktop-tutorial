<?php
require('upload.php'); 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css.css">
    <style>
      .registro-container {
            position: relative;
            width: 100%;
            text-align: center;
        }
        .banner-perfil {
            width: 100%;
            height: 200px;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        .perfil-container {
            position: relative;
            display: inline-block;
            margin-top: -50px; 
        }
        .foto-perfil {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background-size: cover;
            background-position: center;
            border: 2px solid #fff; /* Opcional: añade un borde blanco para que la foto se destaque */
        }
    </style>
    <title>Perfil</title>
</head>
<body>
<div class="registro-container">
        <center>
            <h1>Bienvenido <?php echo htmlspecialchars($nickname); ?></h1>
            <p>Correo: <?php echo htmlspecialchars($email); ?></p>

            <?php if ($banner_mostrar): ?>
                <div class="banner-perfil">
                    <?php echo $banner_mostrar; ?>
                </div>
            <?php else: ?>
                <p>No tienes un banner de perfil cargado.</p>
            <?php endif; ?>

            <div class="perfil-container">
                <?php if ($foto_mostrar): ?>
                    <?php echo $foto_mostrar; ?>
                <?php else: ?>
                    <p>No tienes una foto de perfil cargada.</p>
                <?php endif; ?>
            </div>

            <form method="POST" enctype="multipart/form-data" action="upload.php">
                <label for="pais">País:</label>
                <select name="pais">
                    <?php foreach ($paises as $pais): ?>
                        <option value="<?php echo htmlspecialchars($pais); ?>" <?php if ($perfil_existe && $perfil['Pais'] == $pais) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($pais); ?>
                        </option>
                    <?php endforeach; ?>
                </select><br>

                <label for="telefono">Teléfono:</label>
                <input type="text" name="telefono" value="<?php echo htmlspecialchars($perfil_existe ? $perfil['Telefono'] : ''); ?>"><br>

                <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
                <input type="date" name="fecha_nacimiento" value="<?php echo htmlspecialchars($perfil_existe ? $perfil['FechaNacimiento'] : ''); ?>"><br>

                <label for="sexo">Sexo:</label>
                <select name="sexo">
                    <option value="Masculino" <?php if ($perfil_existe && $perfil['Sexo'] == 'Masculino') echo 'selected'; ?>>Masculino</option>
                    <option value="Femenino" <?php if ($perfil_existe && $perfil['Sexo'] == 'Femenino') echo 'selected'; ?>>Femenino</option>
                </select><br><br>

                <label for="foto">Foto de perfil:</label>
                <input type="file" name="foto" accept="image/jpeg"><br><br>

                <label for="banner">Banner de perfil:</label>
                <input type="file" name="banner" accept="image/jpeg"><br><br>

                <input type="submit" name="guardar" value="Guardar Cambios">
            </form>
            <br>
            <a href="../logout.php">Cerrar sesión</a>
            <a href="crear_publicacion.php">Crear publicaciones</a>
        </center>
    </div>
</body>
</html>
