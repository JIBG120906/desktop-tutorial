function cambiarRestriccionesArchivo() {
    var visibilidad = document.getElementById('PrivadoPublico').value;
    var inputArchivo = document.getElementById('archivo');

    if (visibilidad === 'Publico') {
        // Solo se permiten imágenes, audio y PDF
        inputArchivo.accept = 'image/*,audio/*,application/pdf';
    } else {
        // Se permiten todos los archivos
        inputArchivo.accept = '*/*';
    }
}

// Ejecutar la función en la carga inicial de la página
cambiarRestriccionesArchivo();