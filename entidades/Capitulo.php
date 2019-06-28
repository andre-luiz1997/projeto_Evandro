<?php

class Capitulo{
    private $cdCapitulo;
    private $nome;
    
    function __construct() {
        
    }
    
    function getCdCapitulo() {
        return $this->cdCapitulo;
    }

    function getNome() {
        return utf8_encode($this->nome);
    }

    function setCdCapitulo($cdCapitulo) {
        $this->cdCapitulo = $cdCapitulo;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }
    
}
