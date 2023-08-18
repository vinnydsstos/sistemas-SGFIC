<?php

include_once '../Database/dbConnect.php';

class Ambiente {

    public $idSala;
    public $identificador;
    public $descricao;

    public function salvar(){
        $stringSalvar = "INSERT INTO Ambiente(Identificador, descricao) 
                        VALUES ('" . $this->identificador . "', '" . $this->descricao . "')"; 
        Connect::getConnection()->query($stringSalvar);
    }

    public function atualizar(){
        $stringAtualizar = "UPDATE Ambiente SET Identificador = '" . $this->identificador . "', descricao = '" . $this->descricao . "' 
                            WHERE idSala = " . $this->idSala;
        Connect::getConnection()->query($stringAtualizar);
    }

    public function deletar(){
        $sqlDeletar = "DELETE FROM Ambiente WHERE idSala = " . $this->idSala;
        Connect::getConnection()->query($sqlDeletar);
    }

    public static function buscarTodos(){
        $sqlBuscar = "SELECT * FROM Ambiente";
        $rs = Connect::getConnection()->query($sqlBuscar);
        $ambientes = array();
        while ($row = mysqli_fetch_assoc($rs)){
            $ambiente = new Ambiente();
            $ambiente->idSala = $row['idSala'];
            $ambiente->identificador = $row['Identificador'];
            $ambiente->descricao = $row['descricao'];
            array_push($ambientes, $ambiente);
        }
        return $ambientes;
    }

    public static function buscarPorId($idSala){
        $sqlBuscar = "SELECT * FROM Ambiente WHERE idSala = " . $idSala;
        $rs = Connect::getConnection()->query($sqlBuscar);
        $row = mysqli_fetch_assoc($rs);
        if($row){
            $ambiente = new Ambiente();
            $ambiente->idSala = $row['idSala'];
            $ambiente->identificador = $row['Identificador'];
            $ambiente->descricao = $row['descricao'];
            return $ambiente;
        }
    }
}

?>
