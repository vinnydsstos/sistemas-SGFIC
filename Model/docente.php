<?php include_once __DIR__ . '/../Database/dbConnect.php';


class Docente
{

    private $nif;
    private $NomeCompleto;
    private $areaDeAtuacao;
    private $tipoDeContratacao;
    private $cargaHoraria;
    private $inicioDaJornada;
    private $fimDaJornada;

    public function salvar()
    {
        $conn = Connect::getConnection();

        $sqlSalvar = "INSERT INTO Docente(nif, NomeCompleto, areaDeAtuacao, tipoDeContratacao, cargaHoraria, inicioDaJornada, fimDaJornada) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sqlSalvar);
        $stmt->bind_param("ssssiss", $this->nif, $this->NomeCompleto, $this->areaDeAtuacao, $this->tipoDeContratacao, $this->cargaHoraria, $this->inicioDaJornada, $this->fimDaJornada);
        $stmt->execute();
        $stmt->close();
    }

    public function atualizar()
    {
        $conn = Connect::getConnection();

        $sqlAtualizar = "UPDATE Docente SET NomeCompleto = ?, areaDeAtuacao = ?, tipoDeContratacao = ?, cargaHoraria = ?, inicioDaJornada = ?, fimDaJornada = ? WHERE nif = ?";

        $stmt = $conn->prepare($sqlAtualizar);
        $stmt->bind_param("ssssiss", $this->NomeCompleto, $this->areaDeAtuacao, $this->tipoDeContratacao, $this->cargaHoraria, $this->inicioDaJornada, $this->fimDaJornada, $this->nif);
        $stmt->execute();
        $stmt->close();
    }

    public function deletar()
    {
        $conn = Connect::getConnection();

        $sqlDeletar = "DELETE FROM Docente WHERE nif = ?";
        $stmt = $conn->prepare($sqlDeletar);
        $stmt->bind_param("s", $this->nif);
        $stmt->execute();
        $stmt->close();
    }

    public static function buscarTodos()
    {
        $conn = Connect::getConnection();

        $sqlBuscar = "SELECT * FROM Docente ORDER BY nomecompleto ASC";
        $rs = $conn->query($sqlBuscar);
        $docentes = array();
        while ($row = mysqli_fetch_assoc($rs)) {
            $docente = new Docente();
            $docente->setNif($row['nif']);
            $docente->setNomeCompleto($row['NomeCompleto']);
            $docente->setAreaDeAtuacao($row['areaDeAtuacao']);
            $docente->setTipoDeContratacao($row['tipoDeContratacao']);
            $docente->setCargaHoraria($row['cargaHoraria']);
            $docente->setInicioDaJornada($row['inicioDaJornada']);
            $docente->setFimDaJornada($row['fimDaJornada']);
            array_push($docentes, $docente);
        }
        return $docentes;
    }

    public static function buscarPorNif($nif)
    {
        $conn = Connect::getConnection();

        $sqlBuscar = "SELECT * FROM Docente WHERE nif = ?";
        $stmt = $conn->prepare($sqlBuscar);
        $stmt->bind_param("s", $nif);
        $stmt->execute();
        $result = $stmt->get_result();
        $docente = null;
        if ($row = $result->fetch_assoc()) {
            $docente = new Docente();
            $docente->setNif($row['nif']);
            $docente->setNomeCompleto($row['NomeCompleto']);
            $docente->setAreaDeAtuacao($row['areaDeAtuacao']);
            $docente->setTipoDeContratacao($row['tipoDeContratacao']);
            $docente->setCargaHoraria($row['cargaHoraria']);
            $docente->setInicioDaJornada($row['inicioDaJornada']);
            $docente->setFimDaJornada($row['fimDaJornada']);
        }
        return $docente;
    }

    public static function buscarDocentesComPoucasAulas($mes, $indiceBaixoDeAulas)
    {
        $conn = Connect::getConnection();


        $sql = "SELECT D.nif, COUNT(E.idEncontro) AS totalEncontros
        FROM Docente D
        LEFT JOIN Turma T ON D.nif = T.idDocenteResponsavel
        LEFT JOIN Encontro E ON T.idTurma = E.idTurma
        WHERE MONTH(E.dataDoEncontro) = ?
        GROUP BY D.nif;";


        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $mes);
        $stmt->execute();
        $result = $stmt->get_result();


        $docentesComPoucasAulas = [];

        while ($row = $result->fetch_assoc()) {
            if ($row['totalEncontros'] < $indiceBaixoDeAulas) {
                $docente = self::buscarPorNif($row['nif']);
                $docentesComPoucasAulas[] = [
                    'docente' => $docente,
                    'totalEncontros' => $row['totalEncontros']
                ];
            }
        }
    
        

        return $docentesComPoucasAulas;
    }



    public function setNif($nif)
    {
        $this->nif = $nif;
    }

    public function getNif()
    {
        return $this->nif;
    }

    public function setNomeCompleto($NomeCompleto)
    {
        $this->NomeCompleto = $NomeCompleto;
    }

    public function getNomeCompleto()
    {
        return $this->NomeCompleto;
    }

    public function setAreaDeAtuacao($areaDeAtuacao)
    {
        $this->areaDeAtuacao = $areaDeAtuacao;
    }

    public function getAreaDeAtuacao()
    {
        return $this->areaDeAtuacao;
    }

    public function setTipoDeContratacao($tipoDeContratacao)
    {
        $this->tipoDeContratacao = $tipoDeContratacao;
    }

    public function getTipoDeContratacao()
    {
        return $this->tipoDeContratacao;
    }

    public function setCargaHoraria($cargaHoraria)
    {
        $this->cargaHoraria = $cargaHoraria;
    }

    public function getCargaHoraria()
    {
        return $this->cargaHoraria;
    }

    public function setInicioDaJornada($inicioDaJornada)
    {
        $this->inicioDaJornada = $inicioDaJornada;
    }

    public function getInicioDaJornada()
    {
        return $this->inicioDaJornada;
    }

    public function setFimDaJornada($fimDaJornada)
    {
        $this->fimDaJornada = $fimDaJornada;
    }

    public function getFimDaJornada()
    {
        return $this->fimDaJornada;
    }
}
