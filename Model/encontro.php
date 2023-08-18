<?php

include_once '../Database/dbConnect.php';

class Encontro
{

    public $idEncontro;
    public $dataDoEncontro;
    public $inicio;
    public $termino;
    public $idTurma;
    public $idAmbiente;

    public function salvar()
    {
        try {
            $conn = Connect::getConnection();

            if ($conn->connect_error) {
                throw new Exception("Erro na conexão com o banco de dados: " . $conn->connect_error);
            }

            $stringSalvar = "INSERT INTO Encontro(dataDoEncontro, inicio, termino, idTurma, idAmbiente) 
                        VALUES ('" . $this->dataDoEncontro . "', '" . $this->inicio . "', '" . $this->termino . "', 
                                " . $this->idTurma . ", " . $this->idAmbiente . ")";

            $conn->query($stringSalvar);

            //$conn->close();
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function atualizar()
    {
        $stringAtualizar = "UPDATE Encontro SET dataDoEncontro = '" . $this->dataDoEncontro . "', inicio = '" . $this->inicio . "', 
                            termino = '" . $this->termino . "', idTurma = " . $this->idTurma . ", idAmbiente = " . $this->idAmbiente . " 
                            WHERE idEncontro = " . $this->idEncontro;
        Connect::getConnection()->query($stringAtualizar);
    }

    public function deletar()
    {
        $sqlDeletar = "DELETE FROM Encontro WHERE idEncontro = " . $this->idEncontro;
        Connect::getConnection()->query($sqlDeletar);
    }

    public static function buscarTodos()
    {
        $sqlBuscar = "SELECT * FROM Encontro";
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

    public static function buscarEncontrosPorPeriodoEProfessor($mesInicial, $mesFinal, $docenteSelecionado)
    {
        $encontros = array();

        // Query para buscar os encontros para o período entre mes_inicial e mes_final
        $sql = "SELECT * FROM Encontro";

        // If a docente is selected, join the Turma table to filter encontros by the docente's ID
        $sql .= " INNER JOIN Turma ON Encontro.idTurma = Turma.idTurma";
        $sql .= " INNER JOIN Docente ON Turma.idDocenteResponsavel = Docente.nif";

        // Define um array vazio para os tipos de dados dos parâmetros
        $paramTypes = "";

        if ($docenteSelecionado !== "all") {
            $sql .= " WHERE Docente.nif = ?";
            $paramTypes .= "s"; // Adiciona o tipo de dado para string (docenteSelecionado é string)
        }

        $sql .= " AND MONTH(dataDoEncontro) BETWEEN ? AND ?";
        $paramTypes .= "ii"; // Adiciona os tipos de dados para inteiros (mesInicial e mesFinal são inteiros)

        // Get the database connection
        $conn = Connect::getConnection();

        // Prepare the SQL statement
        $stmt = $conn->prepare($sql);

        // Check if the statement preparation was successful
        if ($stmt) {
            // Combina os parâmetros dinamicamente de acordo com a condição
            if ($docenteSelecionado === "all") {
                $stmt->bind_param($paramTypes, $mesInicial, $mesFinal);
            } else {
                $stmt->bind_param($paramTypes, $docenteSelecionado, $mesInicial, $mesFinal);
            }

            // Execute the statement
            $stmt->execute();

            // Get the result of the query
            $result = $stmt->get_result();

            // Check if there are encontros for the selected period and docente
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

            // Close the statement
            $stmt->close();
        } else {
            echo "Error preparing SQL statement: " . $conn->error;
        }

        // Close the connection
        // $conn->close();

        return $encontros;
    }

    public static function verificarConflitos($encontros, $turma)
    {
        // Array para armazenar os encontros com conflitos
        $conflitos = [];

        try {
            // Conectar ao banco de dados
            $conn = Connect::getConnection();

            // Verificar se houve algum erro na conexão
            if ($conn->connect_error) {
                die("Erro na conexão com o banco de dados: " . $conn->connect_error);
            }

            // Consultar o banco de dados para verificar os conflitos
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

            // Preparar a consulta
            $stmt = $conn->prepare($sql);
            foreach ($encontros as $encontro) {
                
                $stmt->bind_param(
                    'ssss',
                    $encontro->dataDoEncontro,
                    $encontro->inicio,
                    $encontro->termino,
                    $turma->idDocenteResponsavel
                );

                // Executar a consulta
                $stmt->execute();

                // Fetch the result as an associative array
                $result = $stmt->get_result();

                // Verificar se há conflitos e adicionar à lista de conflitos, se necessário
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $conflito = new Encontro();
                        $conflito->idEncontro = $row['idEncontro'];
                        $conflito->dataDoEncontro = $row['dataDoEncontro'];
                        $conflito->inicio = $row['hora_inicio'];
                        $conflito->termino = $row['hora_termino'];
                        $conflito->idTurma = $row['nome_turma'];
                        $conflito->idAmbiente = $row['Identificador'];
                        $conflitos[] = $conflito;
                    }
                }
            }

            // Fechar a conexão com o banco de dados
            $stmt->close();
            //$conn->close();

            // Retornar a lista de encontros com conflitos
            return $conflitos;
        } catch (Exception $e) {
            // Tratar o erro de conexão com o banco de dados
            die("Erro na conexão com o banco de dados: " . $e->getMessage());
        }
    }
}
