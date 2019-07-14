<?php
    
?>

<script>
    $(document).ready(function(){
        setup("objects/modelo3DLeticia/Modelo26_1.obj", "objects/modelo3DLeticia/Modelo26_1.mtl", './objects/Viga.dae','divModelo3D');
    });
</script>

<div class="container">
 
    <div class="row">
        <div class="col-md">
            <figure class="figure">
                <div id="divModelo3D" style="visibility:visible;" class="img-thumbnail img-fluid"></div>
                <figcaption class="figure-caption">Exemplo de modelo tridimensional.</figcaption>
            </figure>
        </div>     
        <div class="col-md"> 
        <div class="row">      
            <button onclick="location.href='?conteudo=novaPublicacao'"  type="button" name="btn_nova_publicacao" value="btn_nova_publicacao" class="btn btn-success btn-lg btn-block" >Nova Publicação</button>
        </div>
        <br>
        <div class="row">           
            <button onclick="location.href='?conteudo=gerenciarPublicacoes'"  type="button" name="btn_gerenciar_publicacoes" value="btn_gerenciar_publicacoes" class="btn btn-success btn-lg btn-block" >Gerenciar Publicações</button>
        </div>
    </div>   
    </div>
   
</div>