<?php

include_once '../Database/dbConnect.php';

class Curso
{
    private $idCurso;
    private $nome;
    private $metaDeTI;
    private $carga_horaria;
    private $vigencia;
    private $descricao;
    private $requisitos;
    private $Sigla;

    public function salvar()
    {
        $conexao = Connect::getConnection();
        $stmt = $conexao->prepare("INSERT INTO Curso (nome, metaDeTI, carga_horaria, vigencia, descricao, requisitos, Sigla) 
                                   VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $this->nome, $this->metaDeTI, $this->carga_horaria, $this->vigencia, $this->descricao, $this->requisitos, $this->Sigla);
        $stmt->execute();
        $stmt->close();
    }

    public function atualizar()
    {
        $conexao = Connect::getConnection();
        $stmt = $conexao->prepare("UPDATE Curso SET nome=?, metaDeTI=?, carga_horaria=?, vigencia=?, descricao=?, requisitos=?, Sigla=? WHERE idCurso=?");
        $stmt->bind_param("sssssssi", $this->nome, $this->metaDeTI, $this->carga_horaria, $this->vigencia, $this->descricao, $this->requisitos, $this->Sigla, $this->idCurso);
        $stmt->execute();
        $stmt->close();
    }

    public function deletar()
    {
        $conexao = Connect::getConnection();
        $stmt = $conexao->prepare("DELETE FROM Curso WHERE idCurso=?");
        $stmt->bind_param("i", $this->idCurso);
        $stmt->execute();
        $stmt->close();
    }

    public static function buscarTodos()
    {
        $conexao = Connect::getConnection();
        $sql = "SELECT * FROM Curso ORDER BY nome";
        $rs = $conexao->query($sql);
        $cursos = array();
        while ($row = $rs->fetch_assoc()) {
            $curso = new Curso();
            $curso->setIdCurso($row['idCurso']);
            $curso->setNome($row['nome']);
            $curso->setMetaDeTI($row['metaDeTI']);
            $curso->setCargaHoraria($row['carga_horaria']);
            $curso->setVigencia($row['vigencia']);
            $curso->setDescricao($row['descricao']);
            $curso->setRequisitos($row['requisitos']);
            $curso->setSigla($row['Sigla']);
            $cursos[] = $curso;
        }
        return $cursos;
    }

    public static function buscarPorId($idCurso)
    {
        $conexao = Connect::getConnection();
        $stmt = $conexao->prepare("SELECT * FROM Curso WHERE idCurso=?");
        $stmt->bind_param("i", $idCurso);
        $stmt->execute();
        $rs = $stmt->get_result();
        $row = $rs->fetch_assoc();
        $stmt->close();
        if ($row) {
            $curso = new Curso();
            $curso->setIdCurso($row['idCurso']);
            $curso->setNome($row['nome']);
            $curso->setMetaDeTI($row['metaDeTI']);
            $curso->setCargaHoraria($row['carga_horaria']);
            $curso->setVigencia($row['vigencia']);
            $curso->setDescricao($row['descricao']);
            $curso->setRequisitos($row['requisitos']);
            $curso->setSigla($row['Sigla']);
            return $curso;
        }
        return null;
    }

    public function setIdCurso($idCurso) {
        $this->idCurso = $idCurso;
    }

    public function getIdCurso() {
        return $this->idCurso;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function getNome() {
        return $this->nome;
    }

    public function setMetaDeTI($metaDeTI) {
        $this->metaDeTI = $metaDeTI;
    }

    public function getMetaDeTI() {
        return $this->metaDeTI;
    }

    public function setCargaHoraria($carga_horaria) {
        $this->carga_horaria = $carga_horaria;
    }

    public function getCargaHoraria() {
        return $this->carga_horaria;
    }

    public function setVigencia($vigencia) {
        $this->vigencia = $vigencia;
    }

    public function getVigencia() {
        return $this->vigencia;
    }

    public function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    public function getDescricao() {
        return $this->descricao;
    }

    public function setRequisitos($requisitos) {
        $this->requisitos = $requisitos;
    }

    public function getRequisitos() {
        return $this->requisitos;
    }

    public function setSigla($Sigla) {
        $this->Sigla = $Sigla;
    }

    public function getSigla() {
        return $this->Sigla;
    }
}
?>
