<?php

include_once 'DAO\secaoDAO.php';

class secaoControl {

    function __construct() {
        
    }

    public function salvar(secao $objeto) {
        if ($objeto != null) {
            $objetoDAO = new secaoDAO();
            return $objetoDAO->salvar($objeto);
        }
        return null;
    }

    public function excluir($cdSecao) {
        $objetoDAO = new secaoDAO();
        return $objetoDAO->excluir($cdSecao);
    }

    public function listar() {
        $objetoDAO = new secaoDAO();
        return $objetoDAO->listar();
    }

    public function buscarRegistro($cdSecao) {
        $objetoDAO = new secaoDAO();
        return $objetoDAO->buscarRegistro($cdSecao);
    }

}
