<?php
    include_once 'DAO\capituloDAO.php';
class Secao{
    private $cdCapitulo;
    private $nome;
    private $cdSecao;
    
    function __construct() {
        
    }
    
    function getCdCapitulo() {
        return $this->cdCapitulo;
    }

    function getCdSecao() {
        return $this->cdSecao;
    }

    function getNome() {
        return utf8_encode($this->nome);
    }

    function setCdCapitulo($cdCapitulo) {
        $this->cdCapitulo = $cdCapitulo;
    }

    function setCdSecao($cdSecao){
        $this->cdSecao = $cdSecao;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }
    
    function getCapituloNome(){
        $objetoDAO = new capituloDAO();
        return $objetoDAO->buscarRegistro($this->cdCapitulo);
    }
}
