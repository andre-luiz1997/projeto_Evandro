<?php
    ini_set('default_charset','UTF-8');
    include_once 'control\secaoControl.php';
    $controleSecao = new SecaoControl();
    $objetosControle = $controleSecao->listar();

?>

<script>
    
</script>

<script type="text/javascript">
    $(document).ready(function(){

        //Buscando os dados para preencher o select de capítulo
        $("#select-capitulo-secao").focus(function(){
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
                        $('#select-capitulo-secao').html(options).show();
                    }
                }
            })
        });
        $.fn.editarSecao = function(numeroSecao, numeroCapitulo){
            //Função Jquery chamada quando se clica em editar seção
            //numeroSecao representa a chave primária do seção a ser editado
            $.ajax({
                type: 'post',
                data: {
                    cdSecao: numeroSecao,
                    cdCapitulo: numeroCapitulo,
                    operacao: 'pesquisar'
                },
                url: 'ajax/ajaxSecao.php',
                success: function(retorno){
                    var retorno = JSON.parse(retorno);
                    $("#nomeSecaoEditarInput").val(retorno.nome);
                    $("#numeroSecaoEditarInput").val(retorno.cdSecao);
                    
                    $.ajax({
                        type: 'post',
                        data: {
                            cdCapitulo: retorno.cdCapitulo,
                            operacao: 'pesquisar'
                        },
                        url: 'ajax/ajaxCapitulo.php',
                        success: function(retorno){
                            //O retorno é um array com os objetos de Capítulo
                            var retorno = JSON.parse(retorno);
                            $("#nomeCapituloSecaoEditarInput").html("Capítulo "+retorno.cdCapitulo+": "+retorno.nome);
                            $("#nomeCapituloSecaoEditarInput").val(retorno.cdCapitulo);
                        }
                    });

                    $("#editarSecaoModal").modal('show');
                }
            });
        };

        $.fn.salvarEdicaoSecao = function(){
            //Função Jquery chamada quando se clica em para salvar a edição do seção
            var numeroSecao = $("#numeroSecaoEditarInput").val();
            var nomeSecao = $("#nomeSecaoEditarInput").val();
            var numeroCapitulo = $("#nomeCapituloSecaoEditarInput").val();
            $.ajax({
                type: 'post',
                data: {
                    cdSecao: numeroSecao,
                    nome: nomeSecao,
                    cdCapitulo: numeroCapitulo,
                    operacao: 'editar'
                },
                url: 'ajax/ajaxSecao.php',
                success: function(retorno){
                    alert('Alterado com sucesso!');
                    location.reload();
                }
            });
        };

        $.fn.excluirSecao = function(numeroSecao, numeroCapitulo){
            //Função Jquery chamada quando se clica em para salvar a edição do seção
            var x = confirm("Realmente deseja excluir?");
            if(x == true){
                $.ajax({
                    type: 'post',
                    data: {
                        cdSecao: numeroSecao,
                        cdCapitulo: numeroCapitulo,
                        operacao: 'excluir'
                    },
                    url: 'ajax/ajaxSecao.php',
                    success: function(retorno){
                        alert('Excluído com sucesso!');
                        location.reload();
                    }
                });
            }
            
        };
        

        $('#btn_inserir_novo_Secao').click(function(){
            var nomeSecao = $('#nomeSecaoInput').val();
            var numeroSecao = $('#numeroSecaoInput').val();
            var numeroCapitulo = $('#select-capitulo-secao').val();
            if(nomeSecao=='' || numeroSecao == ''){
                alert('Preencha alguma coisa!');
            }else{
                $.ajax({
                    type: 'post',
                    data: { 
                        nome: nomeSecao, 
                        cdSecao: numeroSecao,
                        cdCapitulo: numeroCapitulo,
                        operacao: 'cadastrar'
                    },
                    url: 'ajax/ajaxSecao.php', 
                    success: function(retorno){
                        alert('Seção cadastrada com sucesso!');
                        location.reload();
                    }
                });
            }
        });
    });
</script>

<br>
<div class="card">
    <h5 class="card-header text-center">Cadastrar nova seção</h5>
    <div class="card-body">
        
        
        <form id="formCadastrarSecao" method='POST'>
            <label for="capitulo-row-secao"><b>Capítulo</b></label>
            <div class="form-row" id="capitulo-row-secao">
                <div class="col-md-6">
                    <select class="custom-select" id="select-capitulo-secao">
                        <option disabled selected>Selecione um capítulo</option>
                    </select>
                </div>
            </div>
            <br>
            <div class="form-row">
                
                <div class="form-group col-md-6">                    
                    <label for="nomeSecaoInput"><b>Nome</b></label>
                    <input type="text" class="form-control" id="nomeSecaoInput" name="nomeSecaoInput" placeholder="Insira o nome da seção...">
                </div>
                
                <div class="form-group col-md-4">
                    <label for="numeroSecaoInput"><b>Número</b></label>
                    <input type="number" class="form-control" id="numeroSecaoInput" name="numeroSecaoInput" placeholder="Número da seção..." min=0>
                </div>

                <div class="form-group col-md-2 pull-right">
                    <label for="btn_inserir_novo_Secao"><span style="color:white;">Botão </span></label>
                    <button class="btn btn-success btn-block"  type="button" id="btn_inserir_novo_Secao" name="btn_inserir_novo_Secao" value="btn_inserir_novo_Secao"  data-toggle="tooltip" data-placement="top" title="Cadastrar uma nova seção">Salvar</button>
                </div>
            </div>
            
        </form>
        <div class="card text-center">
            <div class="card-body">
                <div class="row">   
                    <br>
                    <br>
                    <table class="table table-striped table-bordered" style="text-align: center;" id="tabelaSecoes">
                        <thead class="thead-dark"> 
                            <tr>
                                <th scope="col">Capítulo</th>
                                <th scope="col">Seção</th> 
                                <th style="width:10%" colspan="2" scope="col"></th>  
                            </tr>
                        </thead> 
                        <tbody id="tbody-tabelaSecoes" style="text-align: left;">
                        <?php

                            if($objetosControle!=null){
                                foreach($objetosControle as $objeto){
                                    echo '<tr>';
                                    echo '<td>'.$objeto->getCapituloNome().'</td>';
                                    echo '<td> Seção '.$objeto->getCdCapitulo().'.'.$objeto->getCdSecao().': '.$objeto->getNome().'</td>'; 
                                    echo "<td><button id='btn-editar-Secao' href='#' class='btn btn-outline-warning' data-toggle='tooltip' data-placement='top' title='Editar' onclick='$(this).editarSecao(".$objeto->getCdSecao().','.$objeto->getCdCapitulo().");'><i class='fa fa-edit'></i></button></td>";
                                    echo "<td><button id='btn-excluir-Secao' href='#' class='btn btn-outline-danger' data-toggle='tooltip' data-placement='top' title='Excluir' onclick='$(this).excluirSecao(".$objeto->getCdSecao().','.$objeto->getCdCapitulo().");'><i class='fa fa-trash'></i></button></td>";
                                    echo '</tr>'; 
                                }
                            }
                            ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>  
    </div>
</div>
<br>


<div class="modal fade" id="editarSecaoModal" tabindex="-1" role="dialog" aria-labelledby="editarSecaoModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Editar seção</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" action="#" id="formEditarSecao">
            <div class="form-row">
                <div class="form-group col-md-12">                    
                    <label for="nomeCapituloSecaoEditarInput"><b>Capítulo</b></label>
                    <input type="text" class="form-control" id="nomeCapituloSecaoEditarInput" name="nomeCapituloSecaoEditarInput" disabled>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-8">                    
                    <label for="nomeSecaoEditarInput"><b>Nome</b></label>
                    <input type="text" class="form-control" id="nomeSecaoEditarInput" name="nomeSecaoEditarInput" >
                </div>
                
                <div class="form-group col-md-4">
                    <label for="numeroSecaoEditarInput"><b>Número</b></label>
                    <input type="text" class="form-control text-center" id="numeroSecaoEditarInput" name="numeroSecaoInput" disabled>
                </div>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        <button type="button" class="btn btn-success" onclick="$(this).salvarEdicaoSecao();">Salvar alterações</button>
      </div>
    </div>
  </div>
</div>