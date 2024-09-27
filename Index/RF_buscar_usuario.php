<?php
require("../conexion.php");

$con = conectar_bd();

function buscarUsuarios($con, $nombreUsuario) {
    $query = "SELECT * FROM usuario WHERE NombreUsuario LIKE ?";
    $nombreUsuario = "%".$nombreUsuario."%";
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $nombreUsuario);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result;
}

if (isset($_POST['accion']) && $_POST['accion'] === 'buscar') {
    $nombreUsuario = $_POST['usuario'];
    $usuariosResult = buscarUsuarios($con, $nombreUsuario);
    $response = [];
    while ($usuario = mysqli_fetch_assoc($usuariosResult)) {
        $response[] = $usuario;
    }
    echo json_encode($response);
} else {
    echo json_encode(["error" => "Acción no especificada o inválida."]);
}

$con->close();
?>
