<?php

?>

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

<script type="text/javascript">
    $(document).ready(function(){
    var cdPublicacao, cdCapitulo, cdSecao, cdFigura;
        var caminhoMtl, caminhoObj, caminhoCollada;
        var titulo = "";
        var conteudo = "";
        var legenda = "";

        //Buscando os dados para preencher o select de capítulo
        $(function(){
            $.ajax({
                type: 'post',
                data: {
                    operacao: 'listarTodos'
                },
                url: 'ajax/ajaxCapitulo.php',
                success: function(retorno){
                    //O retorno é um array com os objetos de Capítulo
                    var retorno = JSON.parse(retorno);
                    var options = "<option disabled selected>Selecione um capítulo</option>";
                    if(retorno!=null){
                        for (var i = 0; i < retorno.length; i++) {
                            options += '<option value="' + retorno[i].cdCapitulo + '">' + 'Capítulo '+retorno[i].cdCapitulo +': '+retorno[i].nome + '</option>';
                        }
                        $('#select-capitulo').html(options).show();
                    }
                }
            })
        });
        
        function isEmpty(obj) {
            for(var key in obj) {
                if(obj.hasOwnProperty(key))
                    return false;
            }
            return true;
        }

        //Preenchendo o select de Seção quando o Capítulo for escolhido
        $("#select-capitulo").on('change', function(){
            $("#select-secao").prop('disabled', false);
            cdCapitulo = $("#select-capitulo").val();
            $.ajax({
                type: 'post',
                data:{
                    cdCapitulo: cdCapitulo,
                    operacao: 'pesquisarPorCapitulo'
                },
                url: 'ajax/ajaxSecao.php',
                success: function(retorno){
                    //O retorno é um array com os objetos de Secao Pesquisados
                    var options = "<option disabled selected>Selecione uma seção</option>";
                    if(!isEmpty(retorno) || retorno.length > 0){
                        var retorno = JSON.parse(retorno);
                        for (var i = 0; i < retorno.length; i++) {
                            options += '<option value="' + retorno[i].cdSecao + '">' + 'Seção '+retorno[i].cdSecao +': '+retorno[i].nome + '</option>';
                            
                        }
                        
                    }else{
                        options = '<option disabled selected>Não há seção cadastrada neste capítulo!</option>';
                    }
                    
                    $('#select-secao').html(options).show();
                }
            });
        });


        //Ao selecionar um capítulo & uma seção, então montar a publicação
        $("#select-secao").on('change', function(){
            var sucess = false;
            titulo = "";
            conteudo = "";
            legenda = "";
            document.getElementById('divConteudo').innerHTML = "";
            cdCapitulo = $("#select-capitulo").val();
            cdSecao = $("#select-secao").val();
            
            $.ajax({
                type: 'post',
                data:{
                    cdCapitulo: cdCapitulo,
                    cdSecao: cdSecao,
                    operacao: 'getCdPublicacao' 
                },
                url: 'ajax/ajaxPublicacao.php',
                success: function(retorno){
                    
                    //O retorno esperado é o código da publicação selecionada
                    var retorno = JSON.parse(retorno);
                    cdPublicacao = retorno.cdPublicacao;
                },
                async: false
            });
            //--------------------------------------------------
            //Montar a publicação na página para o usuário
            //--------------------------------------------------
            //Pegando o nome do Capítulo Selecionado
            $.ajax({
                type: 'post',
                data: {
                    cdCapitulo: cdCapitulo,
                    operacao: 'pesquisar'
                },
                url: 'ajax/ajaxCapitulo.php',
                success: function(retorno){
                    var retorno = JSON.parse(retorno);
                    titulo = "<h1> Capítulo "+ cdCapitulo+": "+retorno.nome+"</h1></br>";
                }, 
                async: false
            });
            var div = document.createElement('div');
            div.className = "row";
            div.innerHTML = titulo;
            document.getElementById('divConteudo').appendChild(div);
            //-----------------------------------------------------------------------------

            //-----------------------------------------------------------------------------
            //Pegando o nome da Seção Selecionada
            $.ajax({
                type: 'post',
                data: {
                    cdCapitulo: cdCapitulo,
                    cdSecao: cdSecao,
                    operacao: 'pesquisar'
                },
                url: 'ajax/ajaxSecao.php',
                success: function(retorno){
                    var retorno = JSON.parse(retorno);
                    titulo = "<h3> Seção "+ cdCapitulo+"."+cdSecao+": "+retorno.nome+"</h3>";
                },
                async: false
            });
            var div = document.createElement('div');
            div.className = "row";
            div.innerHTML = titulo;
            document.getElementById('divConteudo').appendChild(div);
            //-----------------------------------------------------------------------------

            //-----------------------------------------------------------------------------
            //Pegando o texto cadastrado da publicação e adicionando ao conteúdo do site
            $.ajax({
                type: 'post',
                dataType: 'html',
                data: {
                    cdCapitulo: cdCapitulo,
                    cdSecao: cdSecao,
                    operacao: 'pesquisar'
                },
                url: 'ajax/ajaxPublicacao.php',
                success: function(retorno){
                    var retorno = JSON.parse(retorno);
                    conteudo = retorno.texto;
                    
                },
                async: false
            });
            
            div = document.createElement('div');
            div.className = "row";
            div.innerHTML = conteudo;
            document.getElementById('divConteudo').appendChild(div);
            //-----------------------------------------------------------------------------
            show('conteudoRow');
            //-----------------------------------------------------------------------------
            //Mostra as figuras disponíveis para o usuário selecioná-las
            $('#select-figura').html("<option disabled selected>Selecione uma figura</option>");
            var cdFigura;
            $.ajax({
                type: 'post',
                data:{
                    cdPublicacao: cdPublicacao,
                    operacao: 'pesquisarTbFigura_Publicacao'
                },
                url: 'ajax/ajaxPublicacao.php',
                success: function(retorno){
                    //O retorno é um array com os objetos de Secao Pesquisados
                    var options = "<option disabled selected>Selecione uma figura</option>";
                    
                    var retorno = JSON.parse(retorno);
                    if(!isEmpty(retorno)){  
                        cdFigura = retorno[0].cdFigura;
                        for (var i = 0; i < retorno.length; i++) {
                            var legenda = retorno[i].legenda;
                            if(legenda == "") legenda = "Sem legenda";
                            options += '<option value="' + retorno[i].cdFigura + '">' + 'Figura '+(i+1)+': '+ legenda + '</option>';
                            
                        }
                        $('#select-figura').html(options);
                        $('#select-figura').prop('disabled', false);
                        show('linhaSelectFigura');
                        showBlock('figura3D');
                        //-----------------------------------------------------------------------------

                        //-----------------------------------------------------------------------------
                        $.ajax({
                            type: 'post',
                            data:{
                                cdFigura: cdFigura,
                                operacao: 'pesquisarFigura'
                            },
                            url: 'ajax/ajaxPublicacao.php',
                            success: function(retorno){
                                var retorno = JSON.parse(retorno);
                                
                                if(!isEmpty(retorno)){
                                    legenda = retorno.legenda;     
                                }
                            },
                            async: false
                        });
                        
                        document.getElementById("legendaFigura").innerHTML = legenda;
                        loadFigura(cdFigura);

                    }else{
                        hide('figura3D');
                        hide('linhaSelectFigura');
                    }                 
                    
                }
            });
           
               
        });

        function loadFigura(cdFigura){
            $.ajax({
                type: 'post',
                data:{
                    cdFigura: cdFigura,
                    operacao: 'getFiguraArquivos'
                },
                url: 'ajax/ajaxPublicacao.php',
                success: function(retorno){
                    //O retorno desta consulta é um array com os nomes de todos os arquivos necessários para a figura 3D
                    console.debug(retorno);
                    var retorno = JSON.parse(retorno);
                    
                    var nomePasta = retorno.shift();
                    
                    for (let index = 0; index < retorno.length; index++) {
                        
                        const element = retorno[index];
                        //Pegando a extensão do arquivo
                        const extensaoArquivo = element.replace(/^.*\./, '');
                        
                        // VERIFICA SE POSSUI UM MTL NO ARQUIVO
                        if(extensaoArquivo == 'mtl') caminhoMtl = 'objects/'+nomePasta+'/'+element;
                        //VERIFICA SE POSSUI UM OBJ NO ARQUIVO
                        if(extensaoArquivo == 'obj') caminhoObj = 'objects/'+nomePasta+'/'+element;
                        // VERIFICA SE POSSUI UM .DAE NO ARQUIVO(COLLADA)
                        if(extensaoArquivo == 'dae') caminhoCollada = 'objects/'+nomePasta+'/'+element;
                        success =  true;
                    }
                    console.debug(caminhoCollada);
                },
                async: false
            });
            
            setup(caminhoCollada, 'divModelo3D');
        }


        $("#select-figura").on('change', function(){
            //Código da figura selecionada
            cdFigura = $("#select-figura").val();
            loadFigura(cdFigura);

            $.ajax({
                type: 'post',
                data:{
                    cdFigura: cdFigura,
                    operacao: 'pesquisarFigura'
                },
                url: 'ajax/ajaxPublicacao.php',
                success: function(retorno){
                    var retorno = JSON.parse(retorno);
                    legenda = retorno.legenda;                    
                },
                async: false
            });
            document.getElementById("legendaFigura").innerHTML = legenda;


        });


        function hide(id){
          var elem = document.getElementById(id);
          elem.style.display = 'none';
        }

        function show(id){
          var elem = document.getElementById(id);
          elem.style.display = 'flex';
        }

        function showBlock(id){
          var elem = document.getElementById(id);
          elem.style.display = 'block';
        }
    });
</script>

<div class="row">
    <div class="col-sm">
        <div class="input-group input-group-sm">
            <div class="input-group-prepend">
                <label class="input-group-text" for="selectCapitulo">Capítulos</label>
            </div>
            <select class="custom-select" id="select-capitulo" action="">
                
            </select>
            
        </div>
    </div>
    </br>
    <div class="col-sm">
        <div class="input-group input-group-sm">
            <div class="input-group-prepend">
                <label class="input-group-text" for="select-secao">Seção</label>
            </div>
            <select class="custom-select" id="select-secao" action="" disabled>
                <option disabled selected>Selecione uma seção</option>
            </select>
            
        </div>
    </div>
    </br>
    <div class="col-sm">
        <div class="input-group input-group-sm" style='display:none;' id="linhaSelectFigura"> 
            <div class="input-group-prepend">
                <label class="input-group-text" for="select-figura" >Figura</label>
            </div>
            <select class="custom-select" id="select-figura" action="">
                <option disabled selected>Selecione uma figura</option>
            </select>
            
        </div>
    </div>    
</div>
</br>
<div class="row" style="display: none;" id="conteudoRow">
    <div class="col-lg  d-flex justify-content-center">
        <figure class="figure" id="figura3D">
            <div id="divModelo3D" style="visibility:visible; width: 500px; height: 500px; margin: auto; text-align: center;" class="img-thumbnail img-fluid"></div>
            <figcaption class="figure-caption" id="legendaFigura"></figcaption>
        </figure>
    </div>
    <div class="col-lg order-first text-justify" id="divConteudo">
        <!-- CONTEÚDO -->
    </div>
    </div>
</div>

<br>