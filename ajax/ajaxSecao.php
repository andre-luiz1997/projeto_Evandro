<?php
    include_once '../ajax/conexaoBancoAjax.php';

    if($_REQUEST['operacao'] == 'cadastrar'){   
        // INSERÇÃO NA TABELA SEÇÃO
        $nome = utf8_decode($_REQUEST['nome']);
        $cdSecao = $_REQUEST['cdSecao'];
        $cdCapitulo = $_REQUEST['cdCapitulo'];
        $sqlcomando = "INSERT INTO `tb_secao` (`cdSecao`,`nome`, `cdCapitulo`)VALUES ('{$cdSecao}', '{$nome}', '{$cdCapitulo}');";
        echo $sqlcomando;       
        $sqlprocesso = $conexao->query($sqlcomando); 

    }else if($_REQUEST['operacao'] == 'pesquisar'){
        //PESQUISAR SEÇÕES NA TABELA, DADO UM cdCapitulo e um cdSecao
        $sqlcomando = "SELECT * FROM `tb_secao` WHERE cdCapitulo={$_REQUEST['cdCapitulo']} AND cdSecao={$_REQUEST['cdSecao']}";
        $sqlprocesso = $conexao->query($sqlcomando); 
        $sqlprocesso = $sqlprocesso->fetch(PDO::FETCH_ASSOC);     
        $nome = utf8_encode($sqlprocesso["nome"]);
        $array = array("cdSecao"=>$sqlprocesso["cdSecao"],"cdCapitulo"=>$sqlprocesso["cdCapitulo"], "nome"=>$nome);
        echo json_encode($array, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); //Retorna o array com o capítulo pesquisado
        
    }else if($_REQUEST['operacao'] == 'editar'){
        //ALTERA O NOME DE UM CAPÍTULO NA TABELA CAPÍTULO, DADO UM cdCapitulo
        $nome = utf8_decode($_REQUEST['nome']);
        $cdSecao = $_REQUEST['cdSecao'];
        $cdCapitulo = $_REQUEST['cdCapitulo'];
        $sqlcomando = "UPDATE `tb_secao` SET `nome` = '{$nome}'
        WHERE `tb_secao`.`cdSecao` = {$cdSecao} AND `tb_secao`.`cdCapitulo` = {$cdCapitulo};";
        $sqlprocesso = $conexao->query($sqlcomando); 
        echo $sqlcomando;
    }else if($_REQUEST['operacao'] == 'excluir'){
        //EXCLUI UMA ENTRADA NA TABELA SEÇÃO, DADO UM cdCapitulo e uma cdSecao
        $cdCapitulo = $_REQUEST['cdCapitulo'];
        $cdSecao = $_REQUEST['cdSecao'];
        $sqlcomando = "DELETE FROM `tb_secao` WHERE `tb_secao`.`cdCapitulo` = {$cdCapitulo} AND `tb_secao`.`cdSecao` = {$cdSecao}";
        $sqlprocesso = $conexao->query($sqlcomando); 
        echo $sqlcomando;
    }else if($_REQUEST['operacao'] == 'listarTodos'){
        //RETORNA TODOS AS AS SEÇÕES DA TABELA DE SEÇÕES
        $sqlcomando = "SELECT * FROM `tb_secao`";
        $sqlprocesso = $conexao->query($sqlcomando);
        $objetos = array();
        while ($linha = $sqlprocesso->fetch(PDO::FETCH_ASSOC)) {
            $objetos[] = array("cdSecao"=>$linha["cdSecao"], "nome"=>utf8_encode($linha["nome"]), 
            "cdCapitulo"=>$linha["cdCapitulo"]);
        }
        echo json_encode($objetos, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }else if($_REQUEST['operacao'] == 'pesquisarPorCapitulo'){
        //PESQUISAR SEÇÕES NA TABELA, DADO UM cdCapitulo e um cdSecao
        $sqlcomando = "SELECT * FROM `tb_secao` WHERE cdCapitulo={$_REQUEST['cdCapitulo']}";
        $sqlprocesso = $conexao->query($sqlcomando);
        $objetos = array();
        while ($linha = $sqlprocesso->fetch(PDO::FETCH_ASSOC)) {
            $objetos[] = array("cdSecao"=>$linha["cdSecao"], "nome"=>utf8_encode($linha["nome"]), 
            "cdCapitulo"=>$linha["cdCapitulo"]);
        }
        echo json_encode($objetos, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
?>