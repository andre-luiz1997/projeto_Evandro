<?php

include_once 'DAO\capituloDAO.php';

class CapituloControl {

    function __construct() {
        
    }

    public function salvar(Capitulo $objeto) {
        if ($objeto != null) {
            $objetoDAO = new capituloDAO();
            return $objetoDAO->salvar($objeto);
        }
        return null;
    }

    public function excluir($cdCapitulo) {
        $objetoDAO = new capituloDAO();
        return $objetoDAO->excluir($cdCapitulo);
    }

    public function listar() {
        $objetoDAO = new capituloDAO();
        return $objetoDAO->listar();
    }

    public function buscarRegistro($cdCapitulo) {
        $objetoDAO = new capituloDAO();
        return $objetoDAO->buscarRegistro($cdCapitulo);
    }

}
