<?php
//	INFO BANCO EXTERNO
//  $par_bd_servidor    = "sql46.main-hosting.eu";    
//	$par_bd_usuario     = "u547984690_escol";    
//	$par_bd_senha       = "escola123";    
//	$par_bd_banco       = "u547984690_escol";
	
//	INFO BANCO LOCAL	    
    $par_bd_servidor    = "localhost";    
	$par_bd_usuario     = "root";    
	$par_bd_senha       = "";    
	$par_bd_banco       = "bd_tec_inf";  
        
        
	$par_bd_erros       = array("mysql" => array( "2002"=>"Servidor não encontrado"
	, "1049"=>"Banco de dados não encontrado" 
	, "1044"=>"Usuário inválido" 
	, "1045"=>"Senha incorreta" ));
	try
	{
		$conexao = new PDO("mysql:host=".$par_bd_servidor.";dbname=".$par_bd_banco,$par_bd_usuario,$par_bd_senha);
	}
	catch(PDOException $e)
	{
		echo $e->getCode()." - ".$par_bd_erros[$par_bd_tipo][$e->getCode()] ;
	}



?>

