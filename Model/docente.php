<?php

include_once '../Database/dbConnect.php';

class Docente {

    public $nif;
    public $NomeCompleto;
    public $areaDeAtuacao;
    public $tipoDeContratacao;
    public $cargaHoraria;
    public $inicioDaJornada;
    public $fimDaJornada;

    public function salvar(){
        $stringSalvar = "INSERT INTO Docente(nif, NomeCompleto, areaDeAtuacao, tipoDeContratacao, cargaHoraria, inicioDaJornada, fimDaJornada) VALUES ('" . $this->nif . "', '" . $this->NomeCompleto . "', '" . $this->areaDeAtuacao . "', '" . $this->tipoDeContratacao . "', " . $this->cargaHoraria . ", '" . $this->inicioDaJornada . "', '" . $this->fimDaJornada . "')"; 
        Connect::getConnection()->query($stringSalvar);
    }

    public function atualizar(){
        $stringAtualizar = "UPDATE Docente SET NomeCompleto = '" . $this->NomeCompleto . "', areaDeAtuacao = '" . $this->areaDeAtuacao . "', tipoDeContratacao = '" . $this->tipoDeContratacao . "', cargaHoraria = " . $this->cargaHoraria . ", inicioDaJornada = '" . $this->inicioDaJornada . "', fimDaJornada = '" . $this->fimDaJornada . "' WHERE nif = " . $this->nif;
        Connect::getConnection()->query($stringAtualizar);
    }

    public function deletar(){
        $sqlDeletar = "DELETE FROM Docente WHERE nif = " . $this->nif;
        Connect::getConnection()->query($sqlDeletar);
    }

    public static function buscarTodos() {
        $sqlBuscar = "SELECT * FROM Docente";
        $rs = Connect::getConnection()->query($sqlBuscar);
        $docentes = array();
        while ($row = mysqli_fetch_row($rs)){
            $docente = new Docente();
            $docente->nif = $row[0];
            $docente->NomeCompleto = $row[1];
            $docente->areaDeAtuacao = $row[2];
            $docente->tipoDeContratacao = $row[3];
            $docente->cargaHoraria = $row[4];
            $docente->inicioDaJornada = $row[5];
            $docente->fimDaJornada = $row[6];
            array_push($docentes, $docente);
        }
        return $docentes;
    }

    public static function buscarPorNif($nif){
        $sqlBuscar = "SELECT * FROM Docente WHERE nif = " . $nif;
        $rs = Connect::getConnection()->query($sqlBuscar);
        $row = mysqli_fetch_row($rs);
        if($row){
            $docente = new Docente();
            $docente->nif = $row[0];
            $docente->NomeCompleto = $row[1];
            $docente->areaDeAtuacao = $row[2];
            $docente->tipoDeContratacao = $row[3];
            $docente->cargaHoraria = $row[4];
            $docente->inicioDaJornada = $row[5];
            $docente->fimDaJornada = $row[6];
            return $docente;
        }
    }
}

?>
