<?php

include_once '../Database/dbConnect.php';

class Encontro
{

    private $idEncontro;
    private $dataDoEncontro;
    private $inicio;
    private $termino;
    private $idTurma;
    private $idAmbiente;

    public function salvar()
    {
        try {
            $conn = Connect::getConnection();

            if ($conn->connect_error) {
                throw new Exception("Erro na conexão com o banco de dados: " . $conn->connect_error);
            }

            $stmt = $conn->prepare("INSERT INTO Encontro(dataDoEncontro, inicio, termino, idTurma, idAmbiente) 
                                    VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssi", $this->dataDoEncontro, $this->inicio, $this->termino, $this->idTurma, $this->idAmbiente);
            $stmt->execute();
            $stmt->close();
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function atualizar()
    {
        $conexao = Connect::getConnection();
        $stmt = $conexao->prepare("UPDATE Encontro SET dataDoEncontro = ?, inicio = ?, termino = ?, idTurma = ?, idAmbiente = ? 
                                   WHERE idEncontro = ?");
        $stmt->bind_param("ssssii", $this->dataDoEncontro, $this->inicio, $this->termino, $this->idTurma, $this->idAmbiente, $this->idEncontro);
        $stmt->execute();
        $stmt->close();
    }

    public function deletar()
    {
        $conexao = Connect::getConnection();
        $stmt = $conexao->prepare("DELETE FROM Encontro WHERE idEncontro = ?");
        $stmt->bind_param("i", $this->idEncontro);
        $stmt->execute();
        $stmt->close();
    }

    public static function buscarTodos($limit = null)
    {
        $sqlBuscar = "SELECT * FROM Encontro";

        if ($limit != null) {
            $sqlBuscar = $sqlBuscar . " LIMIT " . $limit;
        }

        $rs = Connect::getConnection()->query($sqlBuscar);
        $encontros = array();
        while ($row = mysqli_fetch_row($rs)) {
            $encontro = new Encontro();
            $encontro->idEncontro = $row[0];
            $encontro->dataDoEncontro = $row[1];
            $encontro->inicio = $row[2];
            $encontro->termino = $row[3];
            $encontro->idTurma = $row[4];
            $encontro->idAmbiente = $row[5];
            array_push($encontros, $encontro);
        }
        return $encontros;
    }

    public static function buscarEncontros($idDocente = null, $idCurso = null, $idTurma = null, $status = null)
    {
        $conexao = Connect::getConnection();
        $query = "SELECT * FROM Encontro e, Turma t WHERE e.idTurma = t.idTurma";
        
        
        if ($idCurso !== null) {
            $query .= " AND t.idCurso = $idCurso";
        }


        if ($idTurma !== null) {
            $query .= " AND e.idTurma = $idTurma";
        }

        if ($idDocente !== null &&  $idDocente != 'all') {
            $query .= " AND t.idDocenteResponsavel = $idDocente";
        }


        if ($status !== null) {
            $query .= " AND t.status = '$status'";
        }

       //echo $query;
        $rs = $conexao->query($query);
        $encontros = array();

        while ($row = mysqli_fetch_assoc($rs)) {
            $encontro = new Encontro();
            $encontro->idEncontro = $row['idEncontro'];
            $encontro->dataDoEncontro = $row['dataDoEncontro'];
            $encontro->inicio = $row['inicio'];
            $encontro->termino = $row['termino'];
            $encontro->idTurma = $row['idTurma'];
            $encontro->idAmbiente = $row['idAmbiente'];
            array_push($encontros, $encontro);
        }

        return $encontros;
    }

    public static function buscarPorId($idEncontro)
    {
        $sqlBuscar = "SELECT * FROM Encontro WHERE idEncontro = " . $idEncontro;
        $rs = Connect::getConnection()->query($sqlBuscar);
        $row = mysqli_fetch_row($rs);
        if ($row) {
            $encontro = new Encontro();
            $encontro->idEncontro = $row[0];
            $encontro->dataDoEncontro = $row[1];
            $encontro->inicio = $row[2];
            $encontro->termino = $row[3];
            $encontro->idTurma = $row[4];
            $encontro->idAmbiente = $row[5];
            return $encontro;
        }
    }

    public static function buscarEncontrosPorMes($mesSelecionado)
    {
        $encontros = array();

        // Query para buscar os encontros para o mês selecionado
        $sql = "SELECT * FROM Encontro WHERE MONTH(dataDoEncontro) = ?";

        // Obter a conexão com o banco de dados
        $conn = Connect::getConnection();

        // Prepara a declaração SQL
        $stmt = $conn->prepare($sql);

        // Verifica se a preparação da declaração foi bem-sucedida
        if ($stmt) {
            // Vincula o parâmetro à declaração
            $stmt->bind_param("i", $mesSelecionado);

            // Executa a declaração
            $stmt->execute();

            // Obtém o resultado da consulta
            $result = $stmt->get_result();

            // Verifica se há encontros para o mês selecionado
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $encontro = new Encontro();
                    $encontro->idEncontro = $row['idEncontro'];
                    $encontro->dataDoEncontro = $row['dataDoEncontro'];
                    $encontro->inicio = $row['inicio'];
                    $encontro->termino = $row['termino'];
                    $encontro->idTurma = $row['idTurma'];
                    $encontro->idAmbiente = $row['idAmbiente'];
                    $encontros[] = $encontro;
                }
            }

            // Fecha a declaração
            $stmt->close();
        } else {
            echo "Erro na preparação da declaração SQL: " . $conn->error;
        }

        // Fecha a conexão
        //$conn->close();

        return $encontros;
    }

    public static function buscarEncontrosPorAnoMesEProfessor($ano, $mesInicial, $mesFinal, $docenteSelecionado)
    {
        $encontros = array();

        $sql = "SELECT * FROM Encontro";
        $sql .= " INNER JOIN Turma ON Encontro.idTurma = Turma.idTurma";
        $sql .= " INNER JOIN Docente ON Turma.idDocenteResponsavel = Docente.nif";

        $paramTypes = "";

        if ($docenteSelecionado !== "all") {
            $sql .= " WHERE Docente.nif = ?";
            $paramTypes .= "s";
        }

        $sql .= " AND YEAR(dataDoEncontro) = ?";
        $sql .= " AND MONTH(dataDoEncontro) BETWEEN ? AND ?";
        $paramTypes .= "iii";

        $conn = Connect::getConnection();

        $stmt = $conn->prepare($sql);

        if ($stmt) {
            if ($docenteSelecionado === "all") {
                $stmt->bind_param($paramTypes, $ano, $mesInicial, $mesFinal);
            } else {
                $stmt->bind_param($paramTypes, $docenteSelecionado, $ano, $mesInicial, $mesFinal);
            }

            $stmt->execute();

            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $encontro = new Encontro();
                    $encontro->idEncontro = $row['idEncontro'];
                    $encontro->dataDoEncontro = $row['dataDoEncontro'];
                    $encontro->inicio = $row['inicio'];
                    $encontro->termino = $row['termino'];
                    $encontro->idTurma = $row['idTurma'];
                    $encontro->idAmbiente = $row['idAmbiente'];
                    $encontros[] = $encontro;
                }
            }

            $stmt->close();
        } else {
            echo "Error preparing SQL statement: " . $conn->error;
        }

        return $encontros;
    }


    public static function verificarConflitos($encontros, $turma)
    {
        $conflitos = [];

        try {
            $conn = Connect::getConnection();

            if ($conn->connect_error) {
                die("Erro na conexão com o banco de dados: " . $conn->connect_error);
            }

            $sql = 'SELECT
                        Encontro.idEncontro,
                        Encontro.dataDoEncontro,
                        Encontro.inicio AS hora_inicio,
                        Encontro.termino AS hora_termino,
                        Turma.nome AS nome_turma,
                        Ambiente.Identificador,
                        Turma.idDocenteResponsavel
                    FROM
                        Encontro
                    INNER JOIN
                        Turma ON Encontro.idTurma = Turma.idTurma
                    INNER JOIN
                        Ambiente ON Encontro.idAmbiente = Ambiente.idSala 
                    WHERE 
                        dataDoEncontro = ? 
                    AND (inicio <= ? AND termino >= ?)
                    AND idDocenteResponsavel = ?';

            $stmt = $conn->prepare($sql);
            foreach ($encontros as $encontro) {

                $stmt->bind_param(
                    'ssss',
                    $encontro->dataDoEncontro,
                    $encontro->inicio,
                    $encontro->termino,
                    $encontro->idDocenteResponsavel
                );

                $stmt->execute();

                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $conflito = new Encontro();
                        $conflito->setIdEncontro($row['idEncontro']);
                        $conflito->setDataDoEncontro($row['dataDoEncontro']);
                        $conflito->setInicio($row['hora_inicio']);
                        $conflito->setTermino($row['hora_termino']);
                        $conflito->setIdTurma($row['nome_turma']);
                        $conflito->setIdAmbiente($row['Identificador']);
                        $conflitos[] = $conflito;
                    }
                }
            }

            $stmt->close();
            //$conn->close();

            return $conflitos;
        } catch (Exception $e) {
            die("Erro na conexão com o banco de dados: " . $e->getMessage());
        }
    }


    public function setIdEncontro($idEncontro)
    {
        $this->idEncontro = $idEncontro;
    }

    public function getIdEncontro()
    {
        return $this->idEncontro;
    }

    public function setDataDoEncontro($dataDoEncontro)
    {
        $this->dataDoEncontro = $dataDoEncontro;
    }

    public function getDataDoEncontro()
    {
        return $this->dataDoEncontro;
    }

    public function setInicio($inicio)
    {
        $this->inicio = $inicio;
    }

    public function getInicio()
    {
        return $this->inicio;
    }

    public function setTermino($termino)
    {
        $this->termino = $termino;
    }

    public function getTermino()
    {
        return $this->termino;
    }

    public function setIdTurma($idTurma)
    {
        $this->idTurma = $idTurma;
    }

    public function getIdTurma()
    {
        return $this->idTurma;
    }

    public function setIdAmbiente($idAmbiente)
    {
        $this->idAmbiente = $idAmbiente;
    }

    public function getIdAmbiente()
    {
        return $this->idAmbiente;
    }
}
