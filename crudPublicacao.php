<?php
    include_once 'entidades/Capitulo.php';
?>

<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.js"></script>

<!-- JQUERY ACTIONS -->
<script> 
    $(document).ready(function() {
        var fileInput; //Variável que irá armazenar o arquivo lido
        var listaArquivos = new Array(); //Lista com os nomes dos arquivos selecionados
        
        function formatBytes(bytes, decimals = 2) {
            if (bytes === 0) return '0 Bytes';

            const k = 1024;
            const dm = decimals < 0 ? 0 : decimals;
            const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

            const i = Math.floor(Math.log(bytes) / Math.log(k));

            return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
        }

        
        function isEmpty(obj) {
            for(var key in obj) {
                if(obj.hasOwnProperty(key))
                    return false;
            }
            return true;
        }

        //Variavel de configuração do editor Quill para a publicação
        var toolbarOptions = [
            ['bold', 'italic', 'underline'],        // toggled buttons
            ['blockquote', 'code-block'],

            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
            [{ 'script': 'sub'}, { 'script': 'super' }],      // superscript/subscript
            [{ 'indent': '-1'}, { 'indent': '+1' }],          // outdent/indent

            [{ 'header': [1, 2, 3, 4, 5, 6, false] }],

            [{ 'font': [] }],
            [{ 'align': [] }],
            ['clean']                                         // remove formatting button
            ];
            var options = {
                debug: 'warn',
                modules: {
                    toolbar: toolbarOptions,
                },
                placeholder: 'Insira o texto...',
                readOnly: false,
                theme: 'snow'
            };
        var editor = new Quill('#editor', options); 

        $("#btn_cadastrar_publicacao").click( function(){
            var cdCapitulo = $("#select-capitulo").val();
            var cdSecao = $("#select-secao").val();
            var texto = editor.container.firstChild.innerHTML; //Pegando o texto digitado em HTML
            var legendas = new Array(); //Lista com as legendas dos arquivos
            //-------------------------------------------------------------------
            //INSERINDO UMA NOVA PUBLICAÇÃO NA TABELA
            $.ajax({
                type: 'post',
                data: {
                    operacao: 'cadastrarTbPublicacao',
                    cdCapitulo: cdCapitulo,
                    cdSecao: cdSecao,
                    texto: texto
                },
                url: 'ajax/ajaxPublicacao.php',
                success: function(retorno){
                    console.debug(retorno);
                    //-------------------------------------------------------------------
                    fileInput = $('#inputArquivo'); //Pega os dados do formulário

                    for (let index = 0; index < fileInput.get(0).files.length; index++) {
                        const id = "#legendaArquivo"+index;
                        const legenda = $(id).val();
                        
                        legendas.push(legenda);
                    }

                    var form_data = new FormData();

                    var ins = document.getElementById("inputArquivo").files.length;

                    for (var x = 0; x < ins; x++) {
                        form_data.append("inputArquivo[]", document.getElementById('inputArquivo').files[x]);
                    }

                    //Enviando os arquivos e legendas para o servidor
                    $.ajax({
                        url: 'ajax/ajaxPublicacao.php?operacao=uploadFigura', // point to server-side PHP script 
                        dataType: 'text', // what to expect back from the PHP script
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: form_data,
                        type: 'post',
                        success: function (retorno) {
                            // alert(retorno);
                            //Cadastrando as figuras no banco de dados
                            $.ajax({
                                url: 'ajax/ajaxPublicacao.php?operacao=cadastrarTbFigura',
                                data: {
                                    legendas: legendas,
                                    cdCapitulo: cdCapitulo,
                                    cdSecao: cdSecao,
                                    listaArquivos: listaArquivos
                                },
                                type: "POST",
                                success: function(retorno){
                                    //Se o código chegou aqui:
                                    //A PUBLICAÇÃO FOI CADASTRADA EM TB_PUBLICACAO
                                    //OS ARQUIVOS FORAM UPADOS PARA O SERVIDOR
                                    //AS FIGURAS FORAM LINKADAS COM A DEVIDA PUBLICACAO
                                    $('#modalSucessoPublicacao').modal({backdrop: 'static', keyboard: false});
                                    //Não é possível fechar o modal clicando do lado de fora, para forçar a seleção por botões
                                }
                            });
                        },
                        error: function (retorno) {
                            // alert(retorno);               
                        }
                    });
                }
            });
            
        });

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
        

        $("#select-capitulo").on('change', function(){
            $("#select-secao").prop('disabled', false);
            var capituloSelecionado = $("#select-capitulo").val();
            $.ajax({
                type: 'post',
                data:{
                    cdCapitulo: capituloSelecionado,
                    operacao: 'pesquisarPorCapitulo'
                },
                url: 'ajax/ajaxSecao.php',
                success: function(retorno){
                    //O retorno é um array com os objetos de Secao Pesquisados
                    var options = "<option disabled selected>Selecione uma seção</option>";
                    
                    if(!isEmpty(retorno)){
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
        
        var texto;
        $("#select-secao").on('change', function(){
            var capituloSelecionado = $("#select-capitulo").val();
            var secaoSelecionada = $("#select-secao").val();
            var nomeCapitulo = $("#select-capitulo option:selected").html();
            var nomeSecao = $("#select-secao option:selected").html();

            $.ajax({
                type: 'post',
                data:{
                    cdCapitulo: capituloSelecionado,
                    cdSecao: secaoSelecionada,
                    operacao: 'pesquisar'
                },
                url: 'ajax/ajaxPublicacao.php',
                success: function(retorno){
                    //O retorno é um array com os objetos de Publicacao pesquisados
                    if(isJson(retorno)){
                        
                        //Já existe uma publicação cadastrada para esta seção/capítulo
                        //Não é possível cadastrar mais publicações para esta escolha!!
                        texto = 'Já existe uma publicação cadastrada para o <br> <b>'+ nomeCapitulo+'</b>, <br> <b>'+ nomeSecao+'</b>!';
                        $('#modalPublicacaoJaExisteBody').html(texto);
                        $('#modalPublicacaoJaExiste').modal('show', 'focus');
                        $('#select-secao option:eq(0)').attr('selected','selected');
                    }
                }
            });
        });

        function isJson(str) {
            try {
                JSON.parse(str);
            } catch (e) {
                return false;
            }

            return true;
        }

        $.fn.removeImagemDaLista = function(posicaoLista){
            fileInput = $('#inputArquivo'); //Pega os dados
            fileInput.splice(posicaoLista,1); //Remove apenas o elemento indicado
            listaArquivos.splice(posicaoLista,1); //Remove o nome da lista de arquivos
            var id = "#card"+posicaoLista;
            $(id).remove(); //Remove o card referente aquele arquivo
        }
        

        $('#inputArquivo').on('change',function(){
            $(function () {
                $('[data-toggle="tooltip"]').tooltip();
            });
            fileInput = $('#inputArquivo'); //Pega os dados
            if (fileInput.get(0).files.length) { //Verifica se há algum arquivo selecionado
                for(var i=0;i<fileInput.get(0).files.length;i++){
                    var filename = fileInput.get(0).files[i].name; // Pega o nome do arquivo atual
                    listaArquivos.push(filename);
                    var fileSize = fileInput.get(0).files[i].size; //TAMANHO DO ARQUIVO
                    var ulItem = 
                    '<div class="row" id="card'+i+'">'+
                        '<div class="col-md-12">'+
                            '<div class="card">'+
                                '<div class="row no-gutters">'+
                                    '<div class="card-header d-flex align-items-center">'+
                                        '<span id="nomeArquivo'+i+'"> <i class="far fa-file-check"></i> <b>Arquivo:</b> '+filename+'</span><br>'+
                                    '</div>'+
                                    '<div class="card-body">'+
                                        '<label for="legendaArquivo'+i+'"><small><b>Legenda</b></small> </label>'+
                                        '<input type="text" class="form-control" id="legendaArquivo'+i+'" placeholder="..." data-toggle="tooltip" data-placement="top" title="Legenda da imagem ou modelo 3D"></input>'+
                                        '<br><span class="card-text" ><b>Tamanho:</b>'+formatBytes(fileSize, 3)+'</span>'+
                                        '<div class="d-flex justify-content-end">'+
                                            '<button id="btn-remover-imagem" type="button" class="btn btn-outline-danger" data-toggle="tooltip" data-placement="top" title="Editar" onclick="$(this).removeImagemDaLista('+i+')"><i class="fa fa-edit"></i></button>'+
                                        '</div>'+
                                    '</div>'+   
                                '</div>'+   
                            '</div>'+  
                        '</div>'+  
                    '</div><br>';
                    
                   
                    $("#listaDeArquivos").append(ulItem);

                    //Os próximos itens vão cuidar da responsividade dos cards. Retirado de "https://www.codeply.com/go/nIB6oSbv6q"
                    if(i%2 == 0){
                        $("#listaDeArquivos").append('<div class="w-100 d-none d-sm-block d-md-none"><!-- wrap every 2 on sm--></div>');
                    }
                    // if(i%3 == 0){
                    //     $("#listaDeArquivos").append('<div class="w-100 d-none d-md-block d-lg-none"><!-- wrap every 3 on md--></div>');
                    // }
                    // if(i%4 == 0){
                    //     $("#listaDeArquivos").append('<div class="w-100 d-none d-lg-block d-xl-none"><!-- wrap every 4 on lg--></div>');
                    // }
                    // if(i%5 == 0){
                    //     $("#listaDeArquivos").append(' <div class="w-100 d-none d-xl-block"><!-- wrap every 5 on xl--></div>');
                    // }
                }
                $("#cardListaDeArquivos").css('visibility','visible');

                $(this).next('.custom-file-label').html(filename);
                // FAZER UMA MENSAGEM DE LOADING...

                // ---------------------------------------------
                //INFORMAÇÕES QUE PODEM SER IMPORTANTES
                // fileInput.get(0).files[0].size + ' bytes' //TAMANHO DO ARQUIVO
                // fileInput.get(0).files[0].type //TIPO DO ARQUIVO
                // filename.split('.').pop() //EXTENSÃO DO ARQUIVO
                // ---------------------------------------------

            } else {
                alertErrorShow('Escolha ao menos um arquivo!');
                return false; // finaliza a função
            }
        });

        $("#btn-sucesso-nova-publicacao").click(function(){
            // $('#publicacaoForm').trigger("reset");

            //Fechar o modal de sucesso da publicação
            $('#modalSucessoPublicacao').modal('hide');
            //Refresh na página
            location.reload();
            //Focar no primeiro elemento do formulário
            $('#select-capitulo').focus();
            //Desativa o select de seção
            $("#select-secao").html('<option disabled selected>Selecione uma seção</option>');
            $("#select-secao").prop('disabled', true);
        });

        
    });
</script>
<?php

    
?>

<br>
<div class="card">
    <h5 class="card-header text-center">Cadastrar nova publicação</h5>
    <div class="card-body">
        <form method="POST" id="publicacaoForm">
            <label for="capitulo-row"><b>Capítulo</b></label>
            <div class="form-row" id="capitulo-row">
                <div class="col-md-7">
                    <select class="custom-select" id="select-capitulo">
                        <option disabled selected>Selecione um capítulo</option>
                    </select>
                </div>
                <div class="col-md-1 text-center">
                    <span>ou</span>
                </div>
                <div class="col-md-4">
                    <button onclick="location.href='?conteudo=novoCapitulo'" class="btn btn-info" type="button" id="btn_novo_capitulo" name="btn_novo_capitulo" value="btn_novo_capitulo"  data-toggle="tooltip" data-placement="top" title="Cadastrar um novo capítulo">Novo Capítulo</button>

                </div>
            </div>
            <br>

            <label for="secao-row"><b>Seção</b></label>
            <div class="form-row" id="secao-row">
                <div class="col-md-7">
                        <select class="custom-select" id="select-secao" disabled>
                            <option disabled selected>Selecione uma seção</option>
                        </select>
                </div>
                <div class="col-md-1 text-center">
                        <span>ou</span>
                </div>
                <div class="col-md-4 ">
                    <button onclick="location.href='?conteudo=novaSecao'" class="btn btn-info" type="button" id="btn_nova_secao" name="btn_nova_secao" value="btn_nova_secao"  data-toggle="tooltip" data-placement="top" title="Cadastrar uma nova seção">Nova seção</button>

                </div>
            </div>
            <br>

            <label for="editor-row"><b>Texto da Publicação</b></label>
            <div class="form-row d-flex justify-content-center responsive" id="editor-row">
            
                <div id="editor" style="height: 300px; width: 723px;">
                </div>
            </div>
        </form>
        <form method="POST" enctype="multipart/form-data" id="arquivosForm">
            <br>
            <label for="inputArquivo"><b>Imagem da Publicação</b></label>
            <div class="form-row d-flex justify-content-start">
                <div class="col-md-10" lang='pt-br'>
                    <div class="custom-file">
                        <input type="file" accept="application/zip" data-buttonText="Pesquisar" class="custom-file-input" name="inputArquivo[]" id="inputArquivo" aria-describedby="inputArquivo" data-toggle="tooltip" data-placement="top" title="Procurar o arquivo em seu computador" multiple/>
                        <label class="custom-file-label" for="inputArquivo" data-browse="Pesquisar">Inserir arquivo...</label>
                    </div>                
                </div>
            </div>
            <br>
            <div id="cardListaDeArquivos" class="card" style="visibility: hidden;">
                <div class="card-header text-center text-white bg-info">
                    Arquivos Selecionados
                </div>
                <div class="card-body">      
                    <div id="listaDeArquivos">
                    </div>
                </div>
                
            </div>

            <div class="form-row d-flex justify-content-end">
                <div class="col-md-2">
                    <button class="btn btn-success btn-block"  type="button" id="btn_cadastrar_publicacao" name="btn_cadastrar_publicacao" value="btn_cadastrar_publicacao"  data-toggle="tooltip" data-placement="top" title="Finalizar o cadastro da publicação">Salvar</button>
                </div>
            </div>
        </form>
            

        
    </div>
</div>
<br>


<div id="modalSucessoPublicacao" class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">MENSAGEM</h5>
        <button id="btnCloseModal" type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Publicação Cadastrada com sucesso!</p>
      </div>
      <div class="modal-footer">
        <button type="button" id="btn-sucesso-dismiss" class="btn btn-secondary" data-dismiss="modal">Finalizar</button>
        <button type="button" id="btn-sucesso-nova-publicacao" class="btn btn-success">Cadastrar outra Publicação</button>
      </div>
    </div>
  </div>
</div>

<div id="modalPublicacaoJaExiste" class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">ERRO</h5>
        <button id="btnCloseModal" type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="modalPublicacaoJaExisteBody">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>
