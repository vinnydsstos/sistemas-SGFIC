<?php 

include_once '../Database/dbConnect.php';

function verificarConflitos($encontros)
{
    // Array para armazenar os encontros com conflitos
    $conflitos = [];

    try {
        // Conectar ao banco de dados
        $conn = Connect::getConnection();

        // Consultar o banco de dados para verificar os conflitos
        foreach ($encontros as $encontro) {
            $dataDoEncontro = $encontro->dataDoEncontro;
            $inicio = $encontro->inicio;
            $termino = $encontro->termino;
            $idTurma = $encontro->idTurma;
            $idAmbiente = $encontro->idAmbiente;

            // Consulta SQL para verificar conflitos
            $sql = "SELECT * FROM Encontro WHERE idTurma = :idTurma AND idAmbiente = :idAmbiente AND dataDoEncontro = :dataDoEncontro 
                    AND ((inicio <= :inicio AND termino >= :inicio) OR (inicio <= :termino AND termino >= :termino))";

            // Preparar a consulta
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':idTurma', $idTurma, PDO::PARAM_INT);
            $stmt->bindValue(':idAmbiente', $idAmbiente, PDO::PARAM_INT);
            $stmt->bindValue(':dataDoEncontro', $dataDoEncontro, PDO::PARAM_STR);
            $stmt->bindValue(':inicio', $inicio, PDO::PARAM_STR);
            $stmt->bindValue(':termino', $termino, PDO::PARAM_STR);

            // Executar a consulta
            $stmt->execute();

            // Verificar se há conflitos e adicionar à lista de conflitos, se necessário
            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $conflitos[] = new Encontro($row['idEncontro'], $row['dataDoEncontro'], $row['inicio'], $row['termino'], $row['idTurma'], $row['idAmbiente']);
                }
            }
        }

        // Fechar a conexão com o banco de dados
        $conn = null;

        // Retornar a lista de encontros com conflitos
        return $conflitos;
    } catch (PDOException $e) {
        // Tratar o erro de conexão com o banco de dados
        die("Erro na conexão com o banco de dados: " . $e->getMessage());
    }
}

?>
