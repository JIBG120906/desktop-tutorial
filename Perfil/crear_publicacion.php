<?php

require_once("../conexion.php");
$con = conectar_bd();
?>

<form action="procesar_publicacion.php" method="POST" enctype="multipart/form-data">
    <label for="titulo">Título:</label>
    <input type="text" name="titulo" required><br>

    <label for="categoria">Categoría:</label>
    <select name="categoria" required>
        <?php
        $sql = "SELECT NombreCategoria FROM categoriapublicacion";
        $resultado = $con->query($sql);
        if ($resultado->num_rows > 0) {
            while ($fila = $resultado->fetch_assoc()) {
                echo "<option value='" . $fila['NombreCategoria'] . "'>" . $fila['NombreCategoria'] . "</option>";
            }
        } else {
            echo "<option value=''>No hay categorías disponibles</option>";
        }
        ?>
    </select><br>

    <label for="contenido">Contenido:</label>
    <textarea name="contenido" required></textarea><br>

    <label for="PrivadoPublico">Visibilidad:</label>
    <select name="PrivadoPublico" required>
        <option value="Publico">Público</option>
        <option value="Privado">Privado</option>
    </select><br>

    <label for="archivo">Archivos:</label>
    <input type="file" name="archivo" required><br>

    <label for="Licencia">Licencia:</label>
    <select name="Licencia" required>
        <?php
        $sql = "SELECT Tipos FROM licencias";
        $resultado = $con->query($sql);
        if ($resultado->num_rows > 0) {
            while ($fila = $resultado->fetch_assoc()) {
                echo "<option value='" . $fila['Tipos'] . "'>" . $fila['Tipos'] . "</option>";
            }
        } else {
            echo "<option value=''>No hay licencias disponibles</option>";
        }
        ?>
    </select><br>

    <button type="submit">Enviar</button>
</form>

<?php
$con->close();
?>
