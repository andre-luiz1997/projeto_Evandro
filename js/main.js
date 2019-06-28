//-------------------------------VARIAVEIS----------------------------------


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
var loadingManager;
// var models = {
//     teste: {
//         obj:"objects/modelo3DLeticia/Modelo26_1.obj",
//         mtl:"objects/modelo3DLeticia/Modelo26_1.mtl",
//         mesh: null
//     }
// };
var models = [];

// Meshes index
var meshes = {};


//-------------------------------FUNCOES----------------------------------

//Chamada quando o body do documento é lido
function setup(caminhoObj, caminhoMtl, divId)
{
    var model = {
        obj: caminhoObj,
        mtl: caminhoMtl,
        mesh: null,
    };
    //Limpa o array de figuras
    models.length = 0;
    //Adiciona o modelo ao array de figuras
    models.push(model);
    WIDTH = $("#"+divId).width();
    HEIGHT = $("#"+divId).height();
    //---------------------------------------------------------------
    //Lê os modelos 3D
    loadModels();
    init_elements(divId);
}

//Funcao que configura todos os elementos basicos para o three.js funcionar
function init_elements(divId){
    //---------------------------------------------------------------
    //Cria um renderizador WEBGL
    renderer = new THREE.WebGLRenderer();

    // começa o renderizador
    renderer.setSize(WIDTH, HEIGHT);

    //linka o renderizador na div que representa nosso canvas
    var c = document.getElementById(divId);
    if(c!=null)    c.appendChild(renderer.domElement);
    console.log(c);
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
    scene.background = new THREE.Color().setHSL( 0.6, 0, 1 );

    var axesHelper = new THREE.AxesHelper( 500 );
    scene.add( axesHelper );

    //Instancia uma fog(fumaça) na cena
    scene.fog = new THREE.Fog( scene.background, 1, 5000 );

    //Instancia uma luz hemisférica na cena
    hemiLight = new THREE.HemisphereLight( 0xffffff, 0xffffff, 0.5);
    hemiLight.color.setHSL( 0.6, 1, 0.6 );
    hemiLight.groundColor.setHSL( 0.095, 1, 0.75 );
    hemiLight.position.set( 0, 50, 0 );
    scene.add( hemiLight );

    //Instancia uma luz direcional na cena
    dirLight = new THREE.DirectionalLight( 0xffffff, 0.5 );
    dirLight.color.setHSL( 0.1, 1, 0.95 );
    dirLight.position.set( - 1, 1.75, 1 );
    dirLight.position.multiplyScalar( 30 );
    scene.add( dirLight );

    dirLight.castShadow = true;

    dirLight.shadow.mapSize.width = 1024;
    dirLight.shadow.mapSize.height = 1024;

    var d = 50;

    dirLight.shadow.camera.left = - d;
    dirLight.shadow.camera.right = d;
    dirLight.shadow.camera.top = d;
    dirLight.shadow.camera.bottom = - d;

    dirLight.shadow.camera.far = 3500;
    dirLight.shadow.bias = - 0.0001;

    //Configurando o solo
    var groundGeo = new THREE.PlaneBufferGeometry( 10000, 10000 );
    var groundMat = new THREE.MeshPhongMaterial( { color: 0xffffff, specular: 0x050505 } );
    groundMat.color.setHSL( 0.170, 0.81, 0.30 );

    ground = new THREE.Mesh( groundGeo, groundMat );
    ground.rotation.x = - Math.PI / 2;
    ground.position.y = - 33;
    scene.add( ground );

    ground.receiveShadow = true;

    //Configurando o domo do céu
    var vertexShader = document.getElementById( 'vertexShader' ).textContent;
    var fragmentShader = document.getElementById( 'fragmentShader' ).textContent;
    var uniforms = {
        topColor: { value: new THREE.Color( 0x0077ff ) },
        bottomColor: { value: new THREE.Color( 0xffffff ) },
        offset: { value: 33 },
        exponent: { value: 0.6 }
    };
    uniforms.topColor.value.copy( hemiLight.color );

    scene.fog.color.copy( uniforms.bottomColor.value );

    var skyGeo = new THREE.SphereBufferGeometry( 4000, 32, 15 );
    var skyMat = new THREE.ShaderMaterial( { vertexShader: vertexShader, fragmentShader: fragmentShader, uniforms: uniforms, side: THREE.BackSide } );

    var sky = new THREE.Mesh( skyGeo, skyMat );
    scene.add( sky );
    //---------------------------------------------------------------
    // Configurando os controles para o usuário movimentar a câmera
    controls = new THREE.MapControls( camera, renderer.domElement );
    controls.enableDamping = true; // an animation loop is required when either damping or auto-rotation are enabled
    controls.dampingFactor = 0.5;

    controls.screenSpacePanning = true;

    controls.minDistance = 5;
    controls.maxDistance = 100;

    controls.maxPolarAngle = Math.PI / 2;
}

//Loop recursivo para renderizar a cena
function draw(){
    if(RESOURCES_LOADED){
        requestAnimationFrame(draw);//Fazendo um loop recursivo
        renderer.render(scene, camera);//Renderizando a cena
    }
}

//Função que lê os modelos 3D indicados pelas chaves do array models
function loadModels(){
    loadingManager = new THREE.LoadingManager();
	loadingManager.onProgress = function(item, loaded, total){
		// console.log(item, loaded, total);
	};
	loadingManager.onLoad = function(){
		// console.log("loaded all resources");
		RESOURCES_LOADED = true;
        onResourcesLoaded();
    };

    //Percorre o array models e para cada elemento lê o modelo 3D correspondente
    for( var _key in models ){
		(function(key){
			
			var mtlLoader = new THREE.MTLLoader(loadingManager);
			mtlLoader.load(models[key].mtl, function(materials){
				materials.preload();
				
				var objLoader = new THREE.OBJLoader(loadingManager);
				
				objLoader.setMaterials(materials);
				objLoader.load(models[key].obj, function(mesh){
					
					mesh.traverse(function(node){
						if( node instanceof THREE.Mesh ){
                            //Define as características do modelo lido
							node.castShadow = true;
                            node.receiveShadow = true;
                            node.scale.set(1,1,1);
                            node.position.set(0,-2,17);
						}
                    });
                    //Armazena o mesh lido no array models, na posição key, indexando por mesh
					models[key].mesh = mesh;
					
				});
			});
			
    })(_key);
  }
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

    for(var i = 0; i<models.length; i++){
        var model = models[i].mesh.clone();
        scene.add(model);
        
        dirLight.target = model;
    }
}

// function addModelsToScene(){
//     var model = models.teste.mesh.clone();
//     scene.add(model);
//     dirLight.target = model;
    
// }
