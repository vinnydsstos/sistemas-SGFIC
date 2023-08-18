<?php

include_once '../Database/dbConnect.php';

class Turma {

    public $idTurma;
    public $nome;
    public $idDocenteResponsavel;
    public $idCurso;
    public $numeroDeVagas;
    public $dataDeInicio;
    public $dataDeFinalizacao;
    public $status;

    public function salvar(){
        $stringSalvar = "INSERT INTO Turma(nome, idDocenteResponsavel, idCurso, numeroDeVagas, dataDeInicio, dataDeFinalizacao, status) 
                        VALUES ('" . $this->nome . "', " . $this->idDocenteResponsavel . ", " . $this->idCurso . ", " . $this->numeroDeVagas . ", 
                                '" . $this->dataDeInicio . "', '" . $this->dataDeFinalizacao . "', '" . $this->status . "')"; 

        Connect::getConnection()->query($stringSalvar);
    }

    public function atualizar(){
        $stringAtualizar = "UPDATE Turma SET nome = '" . $this->nome . "', idDocenteResponsavel = " . $this->idDocenteResponsavel . ", 
                            idCurso = " . $this->idCurso . ", numeroDeVagas = " . $this->numeroDeVagas . ", 
                            dataDeInicio = '" . $this->dataDeInicio . "', dataDeFinalizacao = '" . $this->dataDeFinalizacao . "', 
                            status = '" . $this->status . "' WHERE idTurma = " . $this->idTurma;
        Connect::getConnection()->query($stringAtualizar);
    }

    public function deletar(){
        $sqlDeletar = "DELETE FROM Turma WHERE idTurma = " . $this->idTurma;
        Connect::getConnection()->query($sqlDeletar);
    }

    public static function buscarTodos(){
        $sqlBuscar = "SELECT * FROM Turma";
        $rs = Connect::getConnection()->query($sqlBuscar);
        $turmas = array();
        while ($row = mysqli_fetch_row($rs)){
            $turma = new Turma();
            $turma->idTurma = $row[0];
            $turma->nome = $row[1];
            $turma->idDocenteResponsavel = $row[2];
            $turma->idCurso = $row[3];
            $turma->numeroDeVagas = $row[4];
            $turma->dataDeInicio = $row[5];
            $turma->dataDeFinalizacao = $row[6];
            $turma->status = $row[7];
            array_push($turmas, $turma);
        }
        return $turmas;
    }

    public static function buscarPorId($idTurma){
        $sqlBuscar = "SELECT * FROM Turma WHERE idTurma = " . $idTurma;
        $rs = Connect::getConnection()->query($sqlBuscar);
        $row = mysqli_fetch_row($rs);
        if($row){
            $turma = new Turma();
            $turma->idTurma = $row[0];
            $turma->nome = $row[1];
            $turma->idDocenteResponsavel = $row[2];
            $turma->idCurso = $row[3];
            $turma->numeroDeVagas = $row[4];
            $turma->dataDeInicio = $row[5];
            $turma->dataDeFinalizacao = $row[6];
            $turma->status = $row[7];
            return $turma;
        }
    }
}

?>
