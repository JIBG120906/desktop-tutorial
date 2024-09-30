<?php
require('upload.php'); 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Style.css">
    <title>Perfil</title>
</head>
<body>
    <div class="registro_container">

        <div class="contenedor_banner">
            
         <?php if ($banner_mostrar): ?>
                <div class="mostrar_banner">  
                    <div> <?php echo $banner_mostrar; ?> </div>
                 </div>
            <?php else: ?>
                <p>No tienes un banner de perfil cargado.</p>
            <?php endif; ?>
        </div>

        <div class="perfil_container">
                <?php if ($foto_mostrar): ?>
                    <div class="mostrar_perfil">
                        <div> <?php echo $foto_mostrar; ?> </div>
                    </div>
                <?php else: ?>
                    <p>No tienes una foto de perfil cargada.</p>
                <?php endif; ?>
        </div>

        <div class="info_container">
            <h1><?php echo htmlspecialchars($nickname); ?></h1>
            <p><?php echo htmlspecialchars($email); ?></p>

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

                <br>
              <a href="../logout.php">Cerrar sesión</a>
              <a href="crear_publicacion.php">Crear publicaciones</a>   
            </form>
            </div>
   
        </div>








    </div>
</body>
</html>


