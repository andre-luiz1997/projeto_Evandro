<?php
    include_once 'DAO/Banco.php';
    include_once 'entidades/Secao.php';

    class secaoDAO extends Banco{
        public function excluir($cdSecao) {
        
        }
    
        public function listar() {
            $comandoSql = "SELECT * FROM tb_secao";
            $resultado = parent::executar($comandoSql);
            $objetos = array();
    
            while ($linha = mysqli_fetch_array($resultado)) {
    
                $objeto = new Secao();
                $objeto->setCdSecao($linha[0]);
                $objeto->setNome($linha[1]);
                $objeto->setcdCapitulo($linha[2]);
                $objetos[] = $objeto;
            }
            return $objetos;
        }
    
        public function buscarRegistro($cdSecao) {
            
        }
    }

?>