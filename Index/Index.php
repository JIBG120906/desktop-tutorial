<?php include 'header.php'; ?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

<body>
<div class="container">
    <div id="publicaciones" class="row">
        <template id="tarjeta-template">
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title"></h5>
                        <h6 class="card-subtitle mb-2 text-muted"></h6>
                        <p class="card-text"></p>
                        <img class="card-img-top d-none" alt="Imagen de la publicación">
                        <p class="card-licencia"><strong>Licencia:</strong></p>
                        <a class="btn btn-primary d-none">Descargar archivo</a>
                        <p class="card-text"><small class="text-muted"></small></p>
                        
                        <input type="hidden" class="publicacion-id" />
                        <button class="me-gusta btn btn-success">Me Gusta</button>
                        <span class="like-count">0</span> 
                    </div>
                </div>
            </div>
        </template>
    </div>
</div>

<script src="publicaciones.js"></script>
</body>
