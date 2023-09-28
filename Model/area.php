<?php

include_once '../Database/dbConnect.php';

class Area
{
    private $idArea;
    private $nome;

    public function salvar()
    {
        try {
            $conn = Connect::getConnection();

            if ($conn->connect_error) {
                throw new Exception("Erro na conexão com o banco de dados: " . $conn->connect_error);
            }

            $stmt = $conn->prepare("INSERT INTO Area(nome) VALUES (?)");
            $stmt->bind_param("s", $this->nome);
            $stmt->execute();
            $stmt->close();
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function atualizar()
    {
        $conexao = Connect::getConnection();
        $stmt = $conexao->prepare("UPDATE Area SET nome = ? WHERE idArea = ?");
        $stmt->bind_param("si", $this->nome, $this->idArea);
        $stmt->execute();
        $stmt->close();
    }

    public function deletar()
    {
        $conexao = Connect::getConnection();
        $stmt = $conexao->prepare("DELETE FROM Area WHERE idArea = ?");
        $stmt->bind_param("i", $this->idArea);
        $stmt->execute();
        $stmt->close();
    }

    public static function buscarTodos($limit = null)
    {
        $sqlBuscar = "SELECT * FROM Area";

        if ($limit != null) {
            $sqlBuscar = $sqlBuscar . " LIMIT " . $limit;
        }

        $rs = Connect::getConnection()->query($sqlBuscar);
        $areas = array();
        while ($row = mysqli_fetch_row($rs)) {
            $area = new Area();
            $area->idArea = $row[0];
            $area->nome = $row[1];
            array_push($areas, $area);
        }
        return $areas;
    }

    public static function buscarPorId($idArea)
    {
        $sqlBuscar = "SELECT * FROM Area WHERE idArea = " . $idArea;
        $rs = Connect::getConnection()->query($sqlBuscar);
        $row = mysqli_fetch_row($rs);
        if ($row) {
            $area = new Area();
            $area->idArea = $row[0];
            $area->nome = $row[1];
            return $area;
        }
    }

    public function setIdArea($idArea)
    {
        $this->idArea = $idArea;
    }

    public function getIdArea()
    {
        return $this->idArea;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    public function getNome()
    {
        return $this->nome;
    }
}
?>
