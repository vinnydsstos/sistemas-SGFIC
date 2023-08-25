<?php 

include_once '../Database/dbConnect.php';

function verificarConflitos($encontros)
{
    $conflitos = [];

    try {
        $conn = Connect::getConnection();

        foreach ($encontros as $encontro) {
            $dataDoEncontro = $encontro->getDataDoEncontro();
            $inicio = $encontro->getInicio();
            $termino = $encontro->getTermino();
            $idTurma = $encontro->getIdTurma();
            $idAmbiente = $encontro->getIdAmbiente();

            $sql = "SELECT * FROM Encontro WHERE idTurma = :idTurma AND idAmbiente = :idAmbiente AND dataDoEncontro = :dataDoEncontro 
                    AND ((inicio <= :inicio AND termino >= :inicio) OR (inicio <= :termino AND termino >= :termino))";

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':idTurma', $idTurma, PDO::PARAM_INT);
            $stmt->bindValue(':idAmbiente', $idAmbiente, PDO::PARAM_INT);
            $stmt->bindValue(':dataDoEncontro', $dataDoEncontro, PDO::PARAM_STR);
            $stmt->bindValue(':inicio', $inicio, PDO::PARAM_STR);
            $stmt->bindValue(':termino', $termino, PDO::PARAM_STR);

            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $conflitos[] = new Encontro($row['idEncontro'], $row['dataDoEncontro'], $row['inicio'], $row['termino'], $row['idTurma'], $row['idAmbiente']);
                }
            }
        }

        $conn = null;

        return $conflitos;
    } catch (PDOException $e) {
        die("Erro na conexão com o banco de dados: " . $e->getMessage());
    }
}

?>
