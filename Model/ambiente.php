<?php

include_once '../Database/dbConnect.php';

class Ambiente
{

    private $idSala;
    private $identificador;
    private $descricao;

    public function salvar()
    {
        $conexao = Connect::getConnection();
        $stmt = $conexao->prepare("INSERT INTO Ambiente (Identificador, descricao) VALUES (?, ?)");
        $stmt->bind_param("ss", $this->identificador, $this->descricao);
        $stmt->execute();
        $stmt->close();
    }

    public function atualizar()
    {
        $conexao = Connect::getConnection();
        $stmt = $conexao->prepare("UPDATE Ambiente SET Identificador = ?, descricao = ? WHERE idSala = ?");
        $stmt->bind_param("ssi", $this->identificador, $this->descricao, $this->idSala);
        $stmt->execute();
        $stmt->close();
    }

    public function deletar()
    {
        $conexao = Connect::getConnection();
        $stmt = $conexao->prepare("DELETE FROM Ambiente WHERE idSala = ?");
        $stmt->bind_param("i", $this->idSala);
        $stmt->execute();
        $stmt->close();
    }

    public static function buscarTodos()
    {
        $conexao = Connect::getConnection();
        $sqlBuscar = "SELECT * FROM Ambiente";
        $rs = $conexao->query($sqlBuscar);
        $ambientes = array();
        while ($row = $rs->fetch_assoc()) {
            $ambiente = new Ambiente();
            $ambiente->setIdSala($row['idSala']);
            $ambiente->setIdentificador($row['Identificador']);
            $ambiente->setDescricao($row['descricao']);
            array_push($ambientes, $ambiente);
        }
        return $ambientes;
    }

    public static function buscarPorId($idSala)
    {
        $conexao = Connect::getConnection();
        $stmt = $conexao->prepare("SELECT * FROM Ambiente WHERE idSala = ?");
        $stmt->bind_param("i", $idSala);
        $stmt->execute();
        $rs = $stmt->get_result();
        $row = $rs->fetch_assoc();
        $stmt->close();
        if ($row) {
            $ambiente = new Ambiente();
            $ambiente->setIdSala($row['idSala']);
            $ambiente->setIdentificador($row['Identificador']);
            $ambiente->setDescricao($row['descricao']);
            return $ambiente;
        }
    }

    public function setIdSala($idSala)
    {
        $this->idSala = $idSala;
    }

    public function getIdSala()
    {
        return $this->idSala;
    }

    public function setIdentificador($identificador)
    {
        $this->identificador = $identificador;
    }

    public function getIdentificador()
    {
        return $this->identificador;
    }

    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }

    public function getDescricao()
    {
        return $this->descricao;
    }
}
