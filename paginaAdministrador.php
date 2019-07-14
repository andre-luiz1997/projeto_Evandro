<?php 
    ini_set('default_charset','UTF-8');
    $conteudo = 'dashboard';
    if(ISSET($_GET['conteudo'])){
        $conteudo = $_GET['conteudo'];
    }
?>


<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Meu site</title>
    <link rel="stylesheet" type="text/css" media="screen" href="css/meuEstilo.css" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"> <!-- ÍCONES DO GOOGLE MATERIAL DESIGN -->
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <!--SCRIPTS PARA EXECUÇÃO DOS PLUGINS NECESSÁRIOS -->
    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.4.1.min.js"
integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

<link href="//cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="//cdn.quilljs.com/1.3.6/quill.min.js"></script>

<script src="js/three.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js "></script>

<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

<script src="https://cdn.jsdelivr.net/npm/quill-image-upload@0.1.3/index.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bs-custom-file-input/dist/bs-custom-file-input.min.js"></script>
    



    <!-- ----------------------------------------------------------------------- -->
    <!-- Shaders que serão usados na iluminação ambiente pelo three.js -->
    <script type="x-shader/x-vertex" id="vertexShader">

        varying vec3 vWorldPosition;

        void main() {

            vec4 worldPosition = modelMatrix * vec4( position, 1.0 );
            vWorldPosition = worldPosition.xyz;

            gl_Position = projectionMatrix * modelViewMatrix * vec4( position, 1.0 );

        }

    </script>

    <script type="x-shader/x-fragment" id="fragmentShader">

        uniform vec3 topColor;
        uniform vec3 bottomColor;
        uniform float offset;
        uniform float exponent;

        varying vec3 vWorldPosition;

        void main() {

            float h = normalize( vWorldPosition + offset ).y;
            gl_FragColor = vec4( mix( bottomColor, topColor, max( pow( max( h , 0.0), exponent ), 0.0 ) ), 1.0 );

        }

    </script>

    <!-- JQUERY ACTIONS -->
    <script> 
        $(document).ready(function() {
            
            $(function () {
                $('[data-toggle="tooltip"]').tooltip();
            });           

            setup('objects/MuroContraforte/MuroContraforte.dae','divModelo3D');   
        });
         

        
    </script>
    <!-- ----------------------------------------------------------------------- -->

    

</head>
<body>
    <div class="row">
        <div class="col s12">
            <?php
                require_once("navbar.php");
                if($conteudo == 'dashboard'){
                    ?>
                    <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" onclick="location.href='index.php'">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Área Restrita</li>
                    </ol>
                    </nav>
                    <?php
                    require_once('dashboardAdmin.php');
                }else if($conteudo == 'novaPublicacao')  {
                    ?>
                    <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" onclick="location.href='index.php'">Home</a></li>
                        <li class="breadcrumb-item"><a href="#" onclick="location.href='?conteudo=dashboard'">Área Restrita</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Nova Publicação</li>
                    </ol>
                    </nav>
                    <?php
                    echo "
                        <section id='crudPublicacao'>
                            <div class='container'>
                    ";
                    require_once("crudPublicacao.php");
                    echo "
                            </div>
                        </section>";
                }else if($conteudo == 'novoCapitulo'){
                    ?>
                    <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" onclick="location.href='index.php'">Home</a></li>
                        <li class="breadcrumb-item"><a href="#" onclick="location.href='?conteudo=dashboard'">Área Restrita</a></li>
                        <li class="breadcrumb-item"><a href="#" onclick="location.href='?conteudo=novaPublicacao'">Nova Publicação</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Novo Capítulo</li>
                    </ol>
                    </nav>
                    <?php
                    echo "
                        <section id='crudCapitulo'>
                            <div class='container'>
                    ";
                    require_once("crudCapitulo.php");
                    echo "
                            </div>
                        </section>";
                }else if($conteudo == 'novaSecao'){
                    ?>
                    <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" onclick="location.href='index.php'">Home</a></li>
                        <li class="breadcrumb-item"><a href="#" onclick="location.href='?conteudo=dashboard'">Área Restrita</a></li>
                        <li class="breadcrumb-item"><a href="#" onclick="location.href='?conteudo=novaPublicacao'">Nova Publicação</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Nova Seção</li>
                    </ol>
                    </nav>
                    <?php
                    echo "
                        <section id='crudSecao'>
                            <div class='container'>
                    ";
                    require_once("crudSecao.php");
                    echo "
                            </div>
                        </section>";
            ?>
        </div>
    </div>
    

    <?php
        }//Fechamento do Else da verificação do btn_nova_publicacao
        require_once("footer.php");
    ?>

    
    
    <script src="js/MTLLoader.js"></script>
    <script src="js/OBJLoader.js"></script>
    <script src="js/ColladaLoader.js"></script>
    <script src="js/MapControls.js"></script>
    <!--O ARQUIVO main.js CONTÉM TODA A CODIFICAÇÃO EM THREE.JS PARA O FUNCIONAMENTO DA VISUALIZAÇÃO 3D-->
    <script src="js/main.js"></script>
    
</body>
</html>