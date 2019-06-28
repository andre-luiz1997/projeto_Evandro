<?php
    include_once '../ajax/conexaoBancoAjax.php';

    if($_REQUEST['operacao'] == 'cadastrar'){   
        // INSERÇÃO NA TABELA CAPÍTULO
        $nome = utf8_decode($_REQUEST['nome']);
        $numero = $_REQUEST['numero'];
        $sqlcomando = "INSERT INTO `tb_capitulo` (`cdCapitulo`,`nome`) 
        VALUES ('{$numero}', '{$nome}');";
        echo $sqlcomando;       
        $sqlprocesso = $conexao->query($sqlcomando); 

    }else if($_REQUEST['operacao'] == 'pesquisar'){
        //PESQUISAR UM CAPÍTULO NA TABELA CAPÍTULO, DADO UM cdCapitulo
        $sqlcomando = "SELECT * FROM `tb_capitulo` WHERE cdCapitulo={$_REQUEST['cdCapitulo']}";
        $sqlprocesso = $conexao->query($sqlcomando); 
        $sqlprocesso = $sqlprocesso->fetch(PDO::FETCH_ASSOC);
        $nome = utf8_encode($sqlprocesso["nome"]);
        $array = array("cdCapitulo"=>$sqlprocesso["cdCapitulo"], "nome"=>$nome);
        echo json_encode($array, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); //Retorna o array com o capítulo pesquisado
    
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
    }
?>