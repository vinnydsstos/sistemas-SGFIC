<?php
// Inclua a classe Curso
include_once '../Model/curso.php';

// Verifica se o ID do curso foi fornecido na URL
if (isset($_GET['id'])) {
    $idCurso = $_GET['id'];

    // Cria um objeto Curso com o ID fornecido
    $curso = new Curso();
    $curso->setIdCurso($idCurso);

    // Chama o método para excluir o curso do banco de dados
    $curso->deletar();
    
    // Redireciona de volta para a página que lista todos os cursos após a exclusão
    header('Location: cursos.php');
    exit();
} else {
    // Caso o ID não tenha sido fornecido, redireciona de volta para a página que lista todos os cursos
    header('Location: cursos.php');
    exit();
}
?>
