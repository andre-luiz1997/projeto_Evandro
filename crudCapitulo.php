<?php
    include_once 'control\capituloControl.php';
    $controleCapitulo = new CapituloControl();
    $objetosControle = $controleCapitulo->listar();

?>

<script>
    
</script>

<script type="text/javascript">
    $(document).ready(function(){

        $.fn.editarCapitulo = function(numeroCapitulo){
            //Função Jquery chamada quando se clica em editar capítulo
            //numeroCapitulo representa a chave primária do capítulo a ser editado
            $.ajax({
                type: 'post',
                data: {
                    cdCapitulo: numeroCapitulo,
                    operacao: 'pesquisar'
                },
                url: 'ajax/ajaxCapitulo.php',
                success: function(retorno){
                    
                    var retorno = JSON.parse(retorno);
                    $("#nomeCapituloEditarInput").val(retorno.nome);
                    $("#numeroCapituloEditarInput").val(retorno.cdCapitulo);
                    $("#editarCapituloModal").modal('show');
                }
            });
        };

        $.fn.salvarEdicaoCapitulo = function(){
            //Função Jquery chamada quando se clica em para salvar a edição do capítulo
            var numeroCapitulo = $("#numeroCapituloEditarInput").val();
            var nomeCapitulo = $("#nomeCapituloEditarInput").val();
            $.ajax({
                type: 'post',
                data: {
                    numero: numeroCapitulo,
                    nome: nomeCapitulo,
                    operacao: 'editar'
                },
                url: 'ajax/ajaxCapitulo.php',
                success: function(retorno){
                    alert('Alterado com sucesso!');
                    location.reload();
                }
            });
        };

        $.fn.excluirCapitulo = function(numeroCapitulo){
            //Função Jquery chamada quando se clica em para salvar a edição do capítulo
            var x = confirm("Realmente deseja excluir?");
            if(x == true){
                $.ajax({
                    type: 'post',
                    data: {
                        numero: numeroCapitulo,
                        operacao: 'excluir'
                    },
                    url: 'ajax/ajaxCapitulo.php',
                    success: function(retorno){
                        alert('Excluído com sucesso!');
                        location.reload();
                    }
                });
            }
            
        };
        

        $('#btn_inserir_novo_capitulo').click(function(){
            var nomeCapitulo = $('#nomeCapituloInput').val();
            var numeroCapitulo = $('#numeroCapituloInput').val();
            if(nomeCapitulo=='' || numeroCapitulo == ''){
                alert('Preencha alguma coisa!');
            }else{
                $.ajax({
                    type: 'post',
                    data: { 
                        nome: nomeCapitulo, 
                        numero: numeroCapitulo,
                        operacao: 'cadastrar'
                    },
                    url: 'ajax/ajaxCapitulo.php', 
                    success: function(retorno){
                        alert('Capítulo cadastrado com sucesso!');
                        location.reload();
                    }
                });
            }
        });
    });
</script>

<br>
<div class="card">
    <h5 class="card-header text-center">Cadastrar novo Capítulo</h5>
    <div class="card-body">
        
        
        <form id="formCadastrarCapitulo" method='POST'>
            <div class="form-row">
                <div class="form-group col-md-6">                    
                    <label for="nomeCapituloInput"><b>Nome</b></label>
                    <input type="text" class="form-control" id="nomeCapituloInput" name="nomeCapituloInput" placeholder="Insira o nome do capítulo...">
                </div>
                
                <div class="form-group col-md-4">
                    <label for="numeroCapituloInput"><b>Número</b></label>
                    <input type="number" class="form-control" id="numeroCapituloInput" name="numeroCapituloInput" placeholder="Número do capítulo..." min=0>
                </div>

                <div class="form-group col-md-2 pull-right">
                    <label for="btn_inserir_novo_capitulo"><span style="color:white;">Botão </span></label>
                    <button class="btn btn-success btn-block"  type="button" id="btn_inserir_novo_capitulo" name="btn_inserir_novo_capitulo" value="btn_inserir_novo_capitulo"  data-toggle="tooltip" data-placement="top" title="Cadastrar um novo capítulo">Salvar</button>
                </div>
            </div>
            
        </form>
        <div class="card text-center">
            <div class="card-body">
                <div class="row">   
                    <br>
                    <br>
                    <table class="table table-striped table-bordered" style="text-align: center;" id="tabelaCapitulos">
                        <thead class="thead-dark"> 
                            <tr>
                                <th scope="col">Capítulo</th>
                                <th scope="col">Nome</th> 
                                <th style="width:10%" colspan="2" scope="col"></th>  
                            </tr>
                        </thead> 
                        <tbody>
                        <?php

                            if($objetosControle!=null){
                                foreach($objetosControle as $objeto){
                                    echo '<tr>';
                                    echo '<td>'.$objeto->getCdCapitulo().'</td>'; 
                                    echo '<td>'.$objeto->getNome().'</td>';
                                    echo "<td><button id='btn-editar-capitulo' href='#' class='btn btn-outline-warning' data-toggle='tooltip' data-placement='top' title='Editar' onclick='$(this).editarCapitulo(".$objeto->getCdCapitulo().");'><i class='fa fa-edit'></i></button></td>";
                                    echo "<td><button id='btn-excluir-capitulo' href='#' class='btn btn-outline-danger' data-toggle='tooltip' data-placement='top' title='Excluir' onclick='$(this).excluirCapitulo(".$objeto->getCdCapitulo().");'><i class='fa fa-trash'></i></button></td>";
                                    echo '</tr>'; 
                                }
                            }
                            ?>

                        </tbody>
                    </table>
                    <br>
                    <br>
                </div>
            </div>
        </div>  
    </div>
</div>
<br>


<div class="modal fade" id="editarCapituloModal" tabindex="-1" role="dialog" aria-labelledby="editarCapituloModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Editar Capítulo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" action="#" id="formEditarCapitulo">
            <div class="form-row">
                <div class="form-group col-md-8">                    
                    <label for="nomeCapituloEditarInput"><b>Nome</b></label>
                    <input type="text" class="form-control" id="nomeCapituloEditarInput" name="nomeCapituloEditarInput" >
                </div>
                
                <div class="form-group col-md-4">
                    <label for="numeroCapituloInput"><b>Número</b></label>
                    <input type="text" class="form-control text-center" id="numeroCapituloEditarInput" name="numeroCapituloInput" disabled>
                </div>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        <button type="button" class="btn btn-success" onclick="$(this).salvarEdicaoCapitulo();">Salvar alterações</button>
      </div>
    </div>
  </div>
</div>