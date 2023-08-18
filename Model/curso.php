<?php
// Inclua a classe de conexão com o banco de dados
include_once '../Database/dbConnect.php';

class Curso
{
    public $idCurso;
    public $nome;
    public $metaDeTI;
    public $carga_horaria;
    public $vigencia;
    public $descricao;
    public $requisitos;
    public $Sigla;

    // Método para salvar um novo curso no banco de dados
    public function salvar()
    {
        // Cria a instrução SQL para inserir um novo curso
        $sql = "INSERT INTO Curso (nome, metaDeTI, carga_horaria, vigencia, descricao, requisitos, Sigla) 
                VALUES ('$this->nome', '$this->metaDeTI', '$this->carga_horaria', '$this->vigencia', '$this->descricao', 
                        '$this->requisitos', '$this->Sigla')";

        // Executa a instrução SQL
        Connect::getConnection()->query($sql);
    }

    // Método para atualizar os dados de um curso existente no banco de dados
    public function atualizar()
    {
        // Cria a instrução SQL para atualizar os dados do curso
        $sql = "UPDATE Curso SET nome='$this->nome', metaDeTI='$this->metaDeTI', carga_horaria='$this->carga_horaria', 
                vigencia='$this->vigencia', descricao='$this->descricao', requisitos='$this->requisitos', 
                Sigla='$this->Sigla' WHERE idCurso=$this->idCurso";

        // Executa a instrução SQL
        Connect::getConnection()->query($sql);
    }

    // Método para deletar um curso do banco de dados
    public function deletar()
    {
        // Cria a instrução SQL para deletar o curso
        $sql = "DELETE FROM Curso WHERE idCurso=$this->idCurso";

        // Executa a instrução SQL
        Connect::getConnection()->query($sql);
    }

    // Método para buscar todos os cursos do banco de dados e retorná-los como um array de objetos Curso
    public static function buscarTodos()
    {
        // Cria a instrução SQL para buscar todos os cursos
        $sql = "SELECT * FROM Curso ORDER BY nome";

        // Executa a instrução SQL e armazena o resultado em uma variável
        $rs = Connect::getConnection()->query($sql);

        // Cria um array para armazenar os cursos encontrados
        $cursos = array();

        // Loop para criar os objetos Curso e adicioná-los ao array
        while ($row = mysqli_fetch_assoc($rs)) {
            $curso = new Curso();
            $curso->idCurso = $row['idCurso'];
            $curso->nome = $row['nome'];
            $curso->metaDeTI = $row['metaDeTI'];
            $curso->carga_horaria = $row['carga_horaria'];
            $curso->vigencia = $row['vigencia'];
            $curso->descricao = $row['descricao'];
            $curso->requisitos = $row['requisitos'];
            $curso->Sigla = $row['Sigla'];
            $cursos[] = $curso;
        }

        // Retorna o array de cursos
        return $cursos;
    }

    // Método para buscar um curso pelo ID e retorná-lo como um objeto Curso
    public static function buscarPorId($idCurso)
    {
        // Cria a instrução SQL para buscar o curso pelo ID
        $sql = "SELECT * FROM Curso WHERE idCurso=$idCurso ";

        // Executa a instrução SQL e armazena o resultado em uma variável
        $rs = Connect::getConnection()->query($sql);

        // Verifica se o curso foi encontrado
        if ($row = mysqli_fetch_assoc($rs)) {
            $curso = new Curso();
            $curso->idCurso = $row['idCurso'];
            $curso->nome = $row['nome'];
            $curso->metaDeTI = $row['metaDeTI'];
            $curso->carga_horaria = $row['carga_horaria'];
            $curso->vigencia = $row['vigencia'];
            $curso->descricao = $row['descricao'];
            $curso->requisitos = $row['requisitos'];
            $curso->Sigla = $row['Sigla'];
            return $curso;
        }

        // Se o curso não for encontrado, retorna null
        return null;
    }
}
?>
