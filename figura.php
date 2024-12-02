<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Figura 3D Interactiva</title>
    <style>
        body { margin: 0; display: flex; justify-content: center; align-items: center; height: 100vh; background-color: #eaeaea; }
        #cubo3D { width: 100%; height: 100vh; }
    </style>
</head>
<body>

<div id="cubo3D"></div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/OrbitControls.js"></script>
<script>
    // Crear la escena
    const escena = new THREE.Scene();
    escena.background = new THREE.Color(0xdddddd);

    // Crear la cámara
    const camara = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
    camara.position.z = 5;

    // Crear el renderizador
    const renderizador = new THREE.WebGLRenderer();
    renderizador.setSize(window.innerWidth, window.innerHeight);
    document.getElementById('cubo3D').appendChild(renderizador.domElement);

    // Crear el cubo
    const geometria = new THREE.BoxGeometry(1, 1, 1);
    const material = new THREE.MeshBasicMaterial({ color: 0x0077ff, wireframe: false });
    const cubo = new THREE.Mesh(geometria, material);
    escena.add(cubo);

    // Añadir controles de órbita
    const controles = new THREE.OrbitControls(camara, renderizador.domElement);

    // Animación del cubo
    function animar() {
        requestAnimationFrame(animar);
        controles.update(); // Permitir la rotación
        renderizador.render(escena, camara);
    }
    animar();

    // Ajustar el tamaño de la ventana
    window.addEventListener('resize', () => {
        renderizador.setSize(window.innerWidth, window.innerHeight);
        camara.aspect = window.innerWidth / window.innerHeight;
        camara.updateProjectionMatrix();
    });
</script>

</body>
</html>
