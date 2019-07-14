<?php 
    
    ini_set('default_charset','UTF-8');
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

<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js "></script>

<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">


    

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

    <!-- ----------------------------------------------------------------------- -->

</head>
<body onload="">
    <?php
        if(ISSET($_REQUEST["btn_login"])){
            header("Location: paginaAdministrador.php");
            die();
            // require("login.php");
        }else{
        
    ?>
    
    <?php
        require("navbar.php");
    ?>
                
    <?php
        
        if(ISSET($_REQUEST['acao']) && $_REQUEST['acao'] == 'conteudos'){
            ?>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#" onclick="location.href='index.php'">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Conteúdos</li>
                </ol>
            </nav>
            <div class="flex-wrapper">
            <div class="container">
            <?php
            require("publicacao.php");
        }else{
            ?>
            <br>
            <div class="flex-wrapper">
            <div class="container">
            <?php
            require("paginaInicial.php");
        }       
    ?>
            </div>
    <?php
            require_once("footer.php");
        
    ?>
        </div>
    <?php
        }
    ?>
        
    
    <script src="js/MTLLoader.js"></script>
    <script src="js/ColladaLoader.js"></script>
    <script src="js/OBJLoader.js"></script>
    <script src="js/MapControls.js"></script>
    <!--O ARQUIVO main.js CONTÉM TODA A CODIFICAÇÃO EM THREE.JS PARA O FUNCIONAMENTO DA VISUALIZAÇÃO 3D-->
    <script src="js/main.js"></script>
    
</body>
</html>