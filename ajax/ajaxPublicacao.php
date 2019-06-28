<?php
    include_once '../ajax/conexaoBancoAjax.php';

    if($_REQUEST['operacao'] == 'cadastrarTbPublicacao'){   
        // INSERÇÃO DE UMA PUBLICAÇÃO
        $texto = utf8_decode($_REQUEST['texto']);

        //NA TABELA PUBLICAÇÃO
        $sqlcomando = "INSERT INTO `tb_publicacao`(`cdPublicacao`,`cdSecao`, `cdCapitulo`, `texto`) VALUES (NULL,{$_REQUEST['cdSecao']},{$_REQUEST['cdCapitulo']},'{$texto}');";       
        $sqlprocesso = $conexao->query($sqlcomando); 
        
    }else if($_REQUEST['operacao'] == 'uploadFigura'){
        
        //FAZENDO UPLOAD DOS ARQUIVOS PARA O SERVIDOR
        $no_files = count($_FILES["inputArquivo"]['name']);
        for ($i = 0; $i < $no_files; $i++) {
            if ($_FILES["inputArquivo"]["error"][$i] > 0) {
                echo "Error: " . $_FILES["inputArquivo"]["error"][$i] . "<br>";
            } else {
                if (file_exists('../uploads/' . $_FILES["inputArquivo"]["name"][$i])) {
                    // echo 'O arquivo já existe no banco de dados : uploads/' . $_FILES["inputArquivo"]["name"][$i];
                    echo 'O arquivo já existe no banco de dados!';
                } else {
                    move_uploaded_file($_FILES["inputArquivo"]["tmp_name"][$i], '../uploads/' . $_FILES["inputArquivo"]["name"][$i]);
                    // echo 'Arquivos salvos com sucesso!: ../uploads/' . $_FILES["inputArquivo"]["name"][$i] . ' ';
                    echo 'Arquivos salvos com sucesso!';
                }
            }
        }
               
    }else if($_REQUEST['operacao'] == 'cadastrarTbFigura'){
        //FAZENDO O CADASTRO DOS DADOS DOS ARQUIVOS NAS TABELAS DE FIGURA E FIGURA_PUBLICACAO
        if($_REQUEST['legendas']){
            for ($i=0; $i < count($_REQUEST['legendas']); $i++) { 
                // INSERÇÃO NA TABELA DE FIGURA
                $sqlcomando = "INSERT INTO `tb_figura`(`cdFigura`, `legenda`, `caminho`)VALUES (NULL,".utf8_encode($_REQUEST['legendas'][$i]).",'{$_REQUEST['listaArquivos'][$i]}');";
                $sqlprocesso = $conexao->query($sqlcomando);

                // //SELEÇÃO DO ÚLTIMO REGISTRO INSERIDO(EM BUSCA DO CÓDIGO GERADO PARA ESTA ÚLTIMA FIGURA)
                $sqlcomando = "SELECT `cdFigura` FROM `tb_figura` ORDER BY `cdFigura` DESC LIMIT 1;"; 
                $sqlprocesso = $conexao->query($sqlcomando);
                $sqlprocesso = $sqlprocesso->fetch(PDO::FETCH_ASSOC);
                $cdFigura = $sqlprocesso["cdFigura"];
                
                // //SELEÇÃO DA PUBLICACAO
                $cdCapitulo = $_REQUEST['cdCapitulo'];
                $cdSecao = $_REQUEST['cdSecao'];
                $sqlcomando = "SELECT `cdPublicacao` FROM `tb_publicacao` WHERE `cdCapitulo`={$cdCapitulo} AND `cdSecao`={$cdSecao};"; 
                $sqlprocesso = $conexao->query($sqlcomando);
                $sqlprocesso = $sqlprocesso->fetch(PDO::FETCH_ASSOC);
                $cdPublicacao = $sqlprocesso["cdPublicacao"];
                
                // // INSERÇÃO NA TABELA DE FIGURA_PUBLICACAO
                $sqlcomando = "INSERT INTO `tb_figura_publicacao`(`cdFigura`, `cdPublicacao`) VALUES ({$cdFigura},{$cdPublicacao})";
                $sqlprocesso = $conexao->query($sqlcomando);
                
                
            }
        }

    }else if($_REQUEST['operacao'] == 'pesquisar'){
        //PESQUISAR UMA PUBLICAÇÃO DA TABELA, DADO O CÓDIGO DO CAPÍTULO E DA SEÇÃO
        $sqlcomando = "SELECT * FROM `tb_publicacao` WHERE cdCapitulo={$_REQUEST['cdCapitulo']} AND cdSecao={$_REQUEST['cdSecao']};";
        $sqlprocesso = $conexao->query($sqlcomando); 
        $sqlprocesso = $sqlprocesso->fetch(PDO::FETCH_ASSOC);
        if (empty($sqlprocesso)) { 
            echo "vazio";
        }else{
            $array = array("cdCapitulo"=>$sqlprocesso["cdCapitulo"], "cdSecao"=>$sqlprocesso["cdSecao"], "texto"=>$sqlprocesso["texto"]);
            echo json_encode($array, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); //Retorna o array com o capítulo pesquisado
        }
    }else if($_REQUEST['operacao'] == 'editar'){
        //ALTERA O NOME DE UM CAPÍTULO NA TABELA CAPÍTULO, DADO UM cdCapitulo
        $nome = utf8_decode($_REQUEST['nome']);
        $numero = $_REQUEST['numero'];
        $sqlcomando = "UPDATE `tb_capitulo` SET `nome` = '{$nome}' WHERE `tb_capitulo`.`cdCapitulo` = {$numero};";
        $sqlprocesso = $conexao->query($sqlcomando); 
        echo $sqlcomando;
    }else if($_REQUEST['operacao'] == 'excluir'){
        //EXCLUI UM CAPÍTULO NA TABELA CAPÍTULO, DADO UM cdCapitulo
        $numero = $_REQUEST['numero'];
        $sqlcomando = "DELETE FROM `tb_capitulo` WHERE `tb_capitulo`.`cdCapitulo` = {$numero}";
        $sqlprocesso = $conexao->query($sqlcomando); 
        echo $sqlprocesso;
    }else if($_REQUEST['operacao'] == 'listarTodos'){
        //RETORNA TODOS OS CAPÍTULOS DA TABELA DE CAPÍTULOS
        $sqlcomando = "SELECT * FROM `tb_capitulo`";
        $sqlprocesso = $conexao->query($sqlcomando);
        $objetos = array();
        while ($linha = $sqlprocesso->fetch(PDO::FETCH_ASSOC)) {
            $objetos[] = array("cdCapitulo"=>$linha["cdCapitulo"], "nome"=>utf8_encode($linha["nome"]));
        }
        echo json_encode($objetos, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

    }else if($_REQUEST['operacao'] == 'pesquisarTbFigura_Publicacao'){
        $cdPublicacao = $_REQUEST['cdPublicacao'];
        //RETORNA TODAS AS INFORMAÇÕES DE FIGURAS DA TABELA DE TB_FIGURA DAQUELA PUBLICACAO
        $sqlcomando = "SELECT * FROM `tb_figura` WHERE `tb_figura`.`cdFigura` IN (SELECT cdFigura FROM `tb_figura_publicacao` WHERE `tb_figura_publicacao`.`cdPublicacao` = {$cdPublicacao});";
        $sqlprocesso = $conexao->query($sqlcomando);
        $objetos = array();
        while ($linha = $sqlprocesso->fetch(PDO::FETCH_ASSOC)) {
            $objetos[] = array("cdFigura"=>$linha["cdFigura"], "legenda"=>utf8_encode($linha["legenda"]), 
            "caminho"=>$linha["caminho"]);
        }
        echo json_encode($objetos, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }else if($_REQUEST['operacao'] == 'getCdPublicacao'){
        $cdCapitulo = $_REQUEST["cdCapitulo"];
        $cdSecao = $_REQUEST["cdSecao"];
        $sqlcomando = "SELECT cdPublicacao FROM `tb_publicacao` WHERE `cdSecao`={$cdSecao} AND `cdCapitulo`={$cdCapitulo};";
        $sqlprocesso = $conexao->query($sqlcomando);
        $sqlprocesso = $sqlprocesso->fetch(PDO::FETCH_ASSOC);
        $array = array("cdPublicacao"=>$sqlprocesso["cdPublicacao"]);
        echo json_encode($array, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }else if($_REQUEST['operacao'] == 'getFiguraArquivos'){
        $cdFigura = $_REQUEST["cdFigura"];
        $sqlcomando = "SELECT * FROM `tb_figura` WHERE `cdFigura`={$cdFigura};";
        $sqlprocesso = $conexao->query($sqlcomando);
        $sqlprocesso = $sqlprocesso->fetch(PDO::FETCH_ASSOC);
        $array = array("cdFigura"=>$sqlprocesso["cdFigura"],"legenda"=>utf8_encode($sqlprocesso["legenda"]),"caminho"=>$sqlprocesso["caminho"]);
        $zip = new ZipArchive();
        $arrayNomesArquivos = array();
        $caminho = '../uploads/'.$sqlprocesso["caminho"];
        
        $zip = zip_open($caminho);
        if($zip){
            while($zip_entry = zip_read($zip)){
                $file = basename(zip_entry_name($zip_entry));
                array_push($arrayNomesArquivos, $file);
            }
            
        }
        // echo var_dump($arrayNomesArquivos);
        echo json_encode($arrayNomesArquivos, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); 

    }else if($_REQUEST['operacao'] == 'pesquisarFigura'){
        $cdFigura = $_REQUEST["cdFigura"];
        $sqlcomando = "SELECT * FROM `tb_figura` WHERE `tb_figura`.`cdFigura`={$cdFigura};";
        $sqlprocesso = $conexao->query($sqlcomando);
        $sqlprocesso = $sqlprocesso->fetch(PDO::FETCH_ASSOC);
        $array = array("legenda"=>utf8_encode($sqlprocesso["legenda"]), "caminho"=>$sqlprocesso["caminho"]);
        echo json_encode($array, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
?>