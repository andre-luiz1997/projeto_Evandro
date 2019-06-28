<?php
    include_once 'DAO\Banco.php';
    include_once 'entidades\Capitulo.php';

    class capituloDAO extends Banco{
        public function excluir($cdCapitulo) {
        
        }
    
        public function listar() {
            $comandoSql = "SELECT * FROM tb_capitulo";
            $resultado = parent::executar($comandoSql);
            $objetos = array();
    
            while ($linha = mysqli_fetch_array($resultado)) {
    
                $objeto = new Capitulo();
                $objeto->setCdCapitulo($linha[0]);
                $objeto->setNome($linha[1]);
                $objetos[] = $objeto;
            }
            return $objetos;
        }
    
        public function buscarRegistro($cdCapitulo) {
            $comandoSql = "SELECT * FROM tb_capitulo WHERE cdCapitulo = {$cdCapitulo}";
    
            try {
                $resultado = parent::executar($comandoSql);
                $linha = mysqli_fetch_assoc($resultado);
                $nomeCapitulo = "Capítulo ".$linha['cdCapitulo'] .": ".utf8_encode($linha["nome"]);
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }
            return $nomeCapitulo;
        }   
    }

?>