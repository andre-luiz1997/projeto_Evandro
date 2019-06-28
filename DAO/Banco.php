<?php

class Banco {

//    private $host = 'sql46.main-hosting.eu';
//    private $usuario = 'u547984690_escol';
//    private $senha = 'escola123';
//    private $banco = 'u547984690_escol';
    
    private $host = 'localhost';
    private $usuario = 'root';
    private $senha = '';
    private $banco = 'bd_tec_inf';
    
    
    private $conexao;
    private $mysqli;



    public function __construct() {
        if ($this->conexao == NULL) {
            $this->conectar();
        }
    }

    public function __destruct() {
        if ($this->conexao != NULL) {
            mysqli_close($this->conexao);
        }
    }

    public function conectar() {
        $this->mysqli = new mysqli($this->host, $this->usuario, $this->senha, $this->banco);
        #$this->conexao = mysqli_connect(, , , );
        if(mysqli_connect_errno()){
          printf("Connect failed: %s\n", mysqli_connect_error());
          exit();
        }
        if ($result = $this->mysqli->query("SELECT DATABASE()")) {
            $row = $result->fetch_row();
            //printf("Default database is %s.\n", $row[0]);
            $result->close();
        }
        #mysqli_select_db($this->conexao, $this->banco) or die("Nao foi possível abrir db".mysqli_error());
    }

    public function executar($comandoSql) {
        $resultado = $this->mysqli->query($comandoSql);
        return $resultado;
    }

    public function retornaConsultaString($comandoSql){
        //Retorna uma consulta SQL como um array de strings
        $resultado = $this->mysqli->query($comandoSql);
        return mysqli_fetch_array($resultado);
        
    }
}
?>
