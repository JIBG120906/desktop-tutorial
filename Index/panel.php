
<body>

    <h1>Gestión de Usuarios</h1>
    <p>Solo los administradores pueden gestionar los usuarios.</p>

    <form id="form-buscar-usuario">
        <input type="text" id="usuario" name="usuario" placeholder="Ingresa el nombre de usuario">
    </form>

    <div id="resultado"></div>

    <table id="tabla-usuarios">
        <thead id="tabla-cabecera">
            <tr>
                <th>ID</th>
                <th>Correo</th>
                <th>Nombre de Usuario</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Admin</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="cuerpo-tabla">
        </tbody>
    </table>

    <p><a href="logout.php">Cerrar Sesión</a></p>

    <script src="panel.js"></script>

</body>

