<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Swann L VR</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <style>
      body {
        margin: 0px;
        overflow: hidden;
      }
      #example {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
      }
    </style>
  </head>
  <body>
    <div id="example"></div>

  <script src="js/third-party/threejs/three.min.js"></script>
  <script src="js/third-party/threejs/StereoEffect.js"></script>
  <script src="js/third-party/threejs/DeviceOrientationControls.js"></script>
  <script src="js/third-party/threejs/OrbitControls.js"></script>
  <script src="fonts/optimer_bold.typeface.js"></script>

  <script>
    var camera, scene, renderer;
    var effect, controls;
    var element, container;

    var clock = new THREE.Clock();

    init();
    animate();

    function init() {
      renderer = new THREE.WebGLRenderer();
      element = renderer.domElement;
      container = document.getElementById('example');
      container.appendChild(element);

      effect = new THREE.StereoEffect(renderer);

      scene = new THREE.Scene();

      camera = new THREE.PerspectiveCamera(90, 1, 0.001, 700);
      camera.position.set(0, 10, 0);
      scene.add(camera);

      controls = new THREE.OrbitControls(camera, element);
      controls.rotateUp(Math.PI / 4);
      controls.target.set(
        camera.position.x + 0.1,
        camera.position.y,
        camera.position.z
      );
      controls.noZoom = true;
      controls.noPan = true;

      function setOrientationControls(e) {
        if (!e.alpha) {
          return;
        }

        controls = new THREE.DeviceOrientationControls(camera, true);
        controls.connect();
        controls.update();

        element.addEventListener('click', fullscreen, false);

        window.removeEventListener('deviceorientation', setOrientationControls, true);
      }
      window.addEventListener('deviceorientation', setOrientationControls, true);


      var light = new THREE.HemisphereLight(0x777777, 0x000000, 0.6);
          light.position.y = 50;
          scene.add(light);
      
      var light2 = new THREE.PointLight( 0xffffff, 1.5, 4500 );
					//light2.color.setHSL( h, s, l );
					light2.position.set( 0, 50, 500 );
					scene.add( light2 );

      var texture = THREE.ImageUtils.loadTexture(
            'textures/patterns/checker.png'
          );
          texture.wrapS = THREE.RepeatWrapping;
          texture.wrapT = THREE.RepeatWrapping;
          texture.repeat = new THREE.Vector2(50, 50);
          texture.anisotropy = renderer.getMaxAnisotropy();

      var material = new THREE.MeshPhongMaterial({
            color: 0xFF5555,
            specular: 0xffffff,
            shininess: 1,
            shading: THREE.FlatShading,
            map: texture
          });

      var geometry = new THREE.PlaneGeometry(1000, 1000);

      var mesh = new THREE.Mesh(geometry, material);
          mesh.rotation.x = -Math.PI / 2;
          scene.add(mesh);
      
      var text = "UNION",
    			height = 20,
    			size = 70,
    			hover = 30,
    			curveSegments = 4,
    			bevelThickness = 2,
    			bevelSize = 1.5,
    			bevelSegments = 3,
    			bevelEnabled = true,
    			font = "optimer", // helvetiker, optimer, gentilis, droid sans, droid serif
    			weight = "bold", // normal bold
    			style = "normal"; // normal italic
      var textGeo = new THREE.TextGeometry( text, {
    				size: size,
    				height: height,
    				curveSegments: curveSegments,
    				font: font,
    				weight: weight,
    				style: style,
    				bevelThickness: bevelThickness,
    				bevelSize: bevelSize,
    				bevelEnabled: bevelEnabled,
    				material: 0,
    				extrudeMaterial: 1
    			});
    			textGeo.computeBoundingBox();
    			textGeo.computeVertexNormals();
      var textMesh1 = new THREE.Mesh( textGeo, material );
    			textMesh1.position.x = 0;
    			textMesh1.position.y = 50;
    			textMesh1.position.z = - 200;
    			textMesh1.rotation.x = 0;
    			textMesh1.rotation.y = 100;
    			scene.add(textMesh1);

      window.addEventListener('resize', resize, false);
      setTimeout(resize, 1);
    }

    function resize() {
      var width = container.offsetWidth;
      var height = container.offsetHeight;

      camera.aspect = width / height;
      camera.updateProjectionMatrix();

      renderer.setSize(width, height);
      effect.setSize(width, height);
    }

    function update(dt) {
      resize();

      camera.updateProjectionMatrix();

      controls.update(dt);
    }

    function render(dt) {
      effect.render(scene, camera);
    }

    function animate(t) {
      requestAnimationFrame(animate);

      update(clock.getDelta());
      render(clock.getDelta());
    }

    function fullscreen() {
      if (container.requestFullscreen) {
        container.requestFullscreen();
      } else if (container.msRequestFullscreen) {
        container.msRequestFullscreen();
      } else if (container.mozRequestFullScreen) {
        container.mozRequestFullScreen();
      } else if (container.webkitRequestFullscreen) {
        container.webkitRequestFullscreen();
      }
    }
  </script>
  </body>
</html>
