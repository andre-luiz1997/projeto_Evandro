<?php
    include_once 'entidades/Capitulo.php';
?>

<!-- JQUERY ACTIONS -->
<script> 
    $(document).ready(function() {

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
            alert(texto);
            var image = new Image();
            // image.src = editor.getContents;
        });

        //Buscando os dados para preencher o select de capítulo
        $("#select-capitulo").focus(function(){
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


        //IMAGE UPLOADER
        $(document).on('change', '.btn-file :file', function() {
		    var input = $(this),
			label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
		    input.trigger('fileselect', [label]);
		});

		$('.btn-file :file').on('fileselect', function(event, label) {
		    
		    var input = $(this).parents('.input-group').find(':text'),
		        log = label;
		    
		    if( input.length ) {
		        input.val(log);
		    } else {
		        if( log ) alert(log);
		    }
	    
		});
		function readURL(input) {
		    if (input.files && input.files[0]) {
		        var reader = new FileReader();
		        
		        reader.onload = function (e) {
		            $('#img-upload').attr('src', e.target.result);
		        }
		        
		        reader.readAsDataURL(input.files[0]);
		    }
		}

		$("#imgInp").change(function(){
		    readURL(this);
		}); 	

        
    });
</script>
<?php

    
?>

<br>
<div class="card">
    <h5 class="card-header text-center">Cadastrar nova publicação</h5>
    <div class="card-body">
        <form method="POST">
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
                        <select class="custom-select" id="select-secao" disabled >
                            <option disabled selected>Selecione uma seção</option>
                        </select>
                </div>
                <div class="col-md-1 text-center">
                        <span>ou</span>
                </div>
                <div class="col-md-4">
                    <button onclick="location.href='?conteudo=novaSecao'" class="btn btn-info" type="button" id="btn_nova_secao" name="btn_nova_secao" value="btn_nova_secao"  data-toggle="tooltip" data-placement="top" title="Cadastrar uma nova seção">Nova seção</button>

                </div>
            </div>
            <br>

            <label for="editor-row"><b>Texto da Publicação</b></label>
            <div class="form-row d-flex justify-content-center" id="editor-row">
            
                <div id="editor" style="height: 300px; width: 723px;">
                </div>
            </div>

            <br>
            <div class="form-row d-flex justify-content-end">
                <div class="col-md-2">
                    <button class="btn btn-success btn-block" type="button" id="btn_cadastrar_publicacao" name="btn_cadastrar_publicacao" value="btn_cadastrar_publicacao"  data-toggle="tooltip" data-placement="top" title="Finalizar o cadastro da publicação">Salvar</button>
                </div>
            </div>

            <br>
            

        </form>
    </div>
</div>
<br>

