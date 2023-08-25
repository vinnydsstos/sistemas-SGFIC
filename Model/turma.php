<?php

include_once '../Database/dbConnect.php';

class Turma
{

    private $idTurma;
    private $nome;
    private $idDocenteResponsavel;
    private $idCurso;
    private $nomeCurso;
    private $numeroDeVagas;
    private $dataDeInicio;
    private $dataDeFinalizacao;
    private $status;


    public function salvar()
    {
        $conexao = Connect::getConnection();
        $stmt = $conexao->prepare("INSERT INTO Turma(nome, idDocenteResponsavel, idCurso, numeroDeVagas, dataDeInicio, dataDeFinalizacao, status) 
                                   VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param(
            "siiisss",
            $this->nome,
            $this->idDocenteResponsavel,
            $this->idCurso,
            $this->numeroDeVagas,
            $this->dataDeInicio,
            $this->dataDeFinalizacao,
            $this->status
        );
        $stmt->execute();
        $stmt->close();
    }

    public function atualizar()
    {
        $conexao = Connect::getConnection();
        $stmt = $conexao->prepare("UPDATE Turma SET nome = ?, idDocenteResponsavel = ?, idCurso = ?, numeroDeVagas = ?, 
                                   dataDeInicio = ?, dataDeFinalizacao = ?, status = ? WHERE idTurma = ?");

        $stmt->bind_param(
            "siiisssi",
            $this->nome,
            $this->idDocenteResponsavel,
            $this->idCurso,
            $this->numeroDeVagas,
            $this->dataDeInicio,
            $this->dataDeFinalizacao,
            $this->status,
            $this->idTurma
        );
        $stmt->execute();
        $stmt->close();
    }

    public function deletar()
    {
        $conexao = Connect::getConnection();
        $stmt = $conexao->prepare("DELETE FROM Turma WHERE idTurma = ?");
        $stmt->bind_param("i", $this->idTurma);
        $stmt->execute();
        $stmt->close();
    }

    public static function buscarTodos()
    {
        $conexao = Connect::getConnection();
        $rs = $conexao->query("SELECT 
        t.idTurma,
        t.nome AS nomeTurma,
        t.idDocenteResponsavel,
        t.idCurso,
        c.nome AS nomeCurso,
        t.numeroDeVagas,
        t.dataDeInicio,
        t.dataDeFinalizacao,
        t.status 
    FROM 
        Turma t
    JOIN 
        Curso c ON t.idCurso = c.idCurso;
    ");
        $turmas = array();
        while ($row = mysqli_fetch_assoc($rs)) {
            $turma = new Turma();
            $turma->idTurma = $row['idTurma'];
            $turma->nome = $row['nomeTurma'];
            $turma->idDocenteResponsavel = $row['idDocenteResponsavel'];
            $turma->idCurso = $row['idCurso'];
            $turma->nomeCurso = $row['nomeCurso'];
            $turma->numeroDeVagas = $row['numeroDeVagas'];
            $turma->dataDeInicio = $row['dataDeInicio'];
            $turma->dataDeFinalizacao = $row['dataDeFinalizacao'];
            $turma->status = $row['status'];
            array_push($turmas,$turma);
        }

        return $turmas;
    }


    public static function buscarPorId($idTurma)
    {
        $conexao = Connect::getConnection();
        $rs = $conexao->query("SELECT * FROM Turma WHERE idTurma = " . $idTurma);
        $row = mysqli_fetch_row($rs);
        if ($row) {
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

    public static function buscarPorNome($nomeTurma)
    {
        $conexao = Connect::getConnection();
        $rs = $conexao->query("SELECT * FROM Turma WHERE nome = '" . $nomeTurma . "'");
        $row = mysqli_fetch_row($rs);
        if ($row) {
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

    public function setIdTurma($idTurma)
    {
        $this->idTurma = $idTurma;
    }

    public function getIdTurma()
    {
        return $this->idTurma;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function setIdDocenteResponsavel($idDocenteResponsavel)
    {
        $this->idDocenteResponsavel = $idDocenteResponsavel;
    }

    public function getIdDocenteResponsavel()
    {
        return $this->idDocenteResponsavel;
    }

    public function setIdCurso($idCurso)
    {
        $this->idCurso = $idCurso;
    }

    public function getIdCurso()
    {
        return $this->idCurso;
    }

    public function getNomeCurso()
    {
        return $this->nomeCurso;
    }

    public function setNumeroDeVagas($numeroDeVagas)
    {
        $this->numeroDeVagas = $numeroDeVagas;
    }

    public function getNumeroDeVagas()
    {
        return $this->numeroDeVagas;
    }

    public function setDataDeInicio($dataDeInicio)
    {
        $this->dataDeInicio = $dataDeInicio;
    }

    public function getDataDeInicio()
    {
        return $this->dataDeInicio;
    }

    public function setDataDeFinalizacao($dataDeFinalizacao)
    {
        $this->dataDeFinalizacao = $dataDeFinalizacao;
    }

    public function getDataDeFinalizacao()
    {
        return $this->dataDeFinalizacao;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
    }
}
