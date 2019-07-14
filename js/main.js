//-------------------------------VARIAVEIS----------------------------------
var box,center;
//Capturando altura e largura da div onde serao lidos os modelos 3D
var WIDTH = $('#divModelo3D').width();
var HEIGHT = $('#divModelo3D').height();

//Variaveis globais importantes
var renderer; //O renderizador three.js
var camera; //A camera para visualizacao
var scene; //A cena ao qual os objetos serao lidos
var hemiLight, dirLight; //Uma luz atmosférica esférica e outra direcional
var ground, sky; //Respectivamente o chão e o céu do "cenário onde será lido o modelo"
var VIEW_ANGLE = 60, ASPECT = WIDTH / HEIGHT, NEAR = 0.1, FAR = 10000; //Variaveis que controlam a camera
var controls;


var RESOURCES_LOADED = false;
var loadingManager, loader;
var models = [];

var model;

// Meshes index
var meshes = {};
var id = "";

//-------------------------------FUNCOES----------------------------------

//Chamada quando o body do documento é lido
function setup(caminhoCollada, divId)
{
    id = divId;
    WIDTH = $("#"+divId).width();
    HEIGHT = $("#"+divId).height();
    //---------------------------------------------------------------
    //Lê os modelos 3D
    loadModels(caminhoCollada);
    init_elements(divId);
}

//Funcao que configura todos os elementos basicos para o three.js funcionar
function init_elements(divId){
    //---------------------------------------------------------------
    //Cria um renderizador WEBGL
    renderer = new THREE.WebGLRenderer();
    
    // começa o renderizador
    renderer.setSize(WIDTH, HEIGHT);
    renderer.gammaInput = true;
    renderer.gammaOutput = true;
    renderer.shadowMap.enabled = true;

    //linka o renderizador na div que representa nosso canvas
    var c = document.getElementById(divId);
    
    while (c.firstChild) {
        c.removeChild(c.firstChild);
    }
    if(c!=null)    c.appendChild(renderer.domElement);
    
    //---------------------------------------------------------------
    //Configura a camera
    camera = new THREE.PerspectiveCamera(VIEW_ANGLE, ASPECT, NEAR, FAR);
    //Instancia uma nova cena
    scene = new THREE.Scene();
    camera.position.set( 15, 10, 20 );
    //Adiciona a camera à cena
    scene.add(camera);
    //---------------------------------------------------------------
    //Preparando o ambiente para receber o modelo 3D
    //Background da cena
    scene.background = new THREE.Color( 0x8cb1ed );

    //Instancia uma fog(fumaça) na cena
    
    // scene.fog = new THREE.Fog( 0xa0a0a0, 200, 1000 );

    //Instancia uma luz hemisférica na cena
    // hemiLight = new THREE.HemisphereLight( 0xffffff, 0x444444, 0.5);
    // hemiLight.position.set( 0, 200, 0 );
    // scene.add( hemiLight );
    //Instancia uma luz hemisférica na cena
    hemiLight = new THREE.HemisphereLight( 0xffffff, 0xffffe3, 0.5);
    // hemiLight.color.setHSL( 0.6, 1, 0.6 );
    // hemiLight.groundColor.setHSL( 0.095, 1, 0.75 );
    hemiLight.position.set( 0, 200, 0 );
    scene.add( hemiLight );

    //Instancia uma luz direcional na cena
    dirLight = new THREE.DirectionalLight( 0xffffff, 0.5 );
    // dirLight.position.set( 0, 200, 200 );
    dirLight.position.set( - 1, 1.75, 1 );
    dirLight.position.multiplyScalar( 30 );
    dirLight.castShadow = true;

    dirLight.shadow.mapSize.width = 2048;
    dirLight.shadow.mapSize.height = 2048;

    dirLight.shadow.camera.far = 3500;
    dirLight.shadow.bias = - 0.0001;
   
    dirLight.shadow.camera.top = 180;
    dirLight.shadow.camera.bottom = - 100;
    dirLight.shadow.camera.left = - 120;
    dirLight.shadow.camera.right = 120;
    scene.add( dirLight ); 

    var mesh = new THREE.Mesh( new THREE.PlaneBufferGeometry( 2000, 2000 ), new THREE.MeshBasicMaterial( { color: 0x6d8c51, depthWrite: false } ) );
    mesh.rotation.x = - Math.PI / 2;
    mesh.receiveShadow = false;
    scene.add( mesh );

    var grid = new THREE.GridHelper( 2000, 20, 0x000000, 0x000000 );
    grid.material.opacity = 0.2;
    grid.material.transparent = true;
    scene.add( grid );

    //---------------------------------------------------------------
    // Configurando os controles para o usuário movimentar a câmera
    controls = new THREE.MapControls( camera, renderer.domElement );
    controls.enableDamping = true; // an animation loop is required when either damping or auto-rotation are enabled
    controls.dampingFactor = 0.5;

    controls.screenSpacePanning = true;

    controls.minDistance = 5;
    controls.maxDistance = 1000;

    controls.maxPolarAngle = Math.PI / 2;

    window.addEventListener( 'resize', onWindowResize, false );
}

function onWindowResize() {
    WIDTH = $("#"+id).width();
    HEIGHT = $("#"+id).height();
    camera.aspect = WIDTH / HEIGHT;
    camera.updateProjectionMatrix();

    renderer.setSize( WIDTH, HEIGHT );

}

//Loop recursivo para renderizar a cena
function draw(){
    controls.update();
    if(RESOURCES_LOADED){
        requestAnimationFrame(draw);//Fazendo um loop recursivo
        renderer.render(scene, camera);//Renderizando a cena
        
    }
}


//Função que lê os modelos 3D indicados pelas chaves do array models
function loadModels(caminhoCollada){
    loadingManager = new THREE.LoadingManager(function(){
        scene.add(model);
    });
	loadingManager.onProgress = function(item, loaded, total){
		console.log(item, loaded, total);
	};
	loadingManager.onLoad = function(){
		// console.log("loaded all resources");
		RESOURCES_LOADED = true;
        onResourcesLoaded();
    };

    loader = new THREE.ColladaLoader(loadingManager);
    loader.load(caminhoCollada, function(collada){
        model = collada.scene;
        model.scale.set(1,1,1);
        // model.position.set(0,0,0);
        model.castShadow = true;
        model.receiveShadow = true;

        
        box = new THREE.Box3().setFromObject( model );
        center = new THREE.Vector3();
        box.getCenter( center );
        model.position.sub( center ); // center the model
        model.position.y=0;
    });
}

//Função que é chamada quando todos os objetos 3D são lidos
function onResourcesLoaded(){
    //Renderiza a cena
    draw();
    addModelsToScene();
}

function addModelsToScene(){
    //Limpa a cena antes de inserir o novo modelo
    for(let i = 0; i< scene.children.length ; i++){ 
        if(scene.children[i].type == "Group"){
            scene.remove(scene.children[i]); 
        }
    }
    scene.add(model);

    var maxDim = Math.min(box.getSize().x,box.getSize().y,box.getSize().z);
    var aspectRatio = model.width / model.height;    
    // Convert camera fov degrees to radians
    var fov = camera.fov * ( Math.PI / 180 );     
    var distance = maxDim / 2 / Math.tan(Math.PI * fov / 360);
    var cameraPosition = new THREE.Vector3(
        0,
        model.position.y + Math.abs( distance / Math.sin( fov / 2 ) ),
        100000
      );
    camera.position.copy(cameraPosition);
    camera.updateProjectionMatrix();
    camera.lookAt(model.position);
    
    dirLight.position.copy(camera.position);
    dirLight.target = model;
    console.debug(box.max);
    
    
}


