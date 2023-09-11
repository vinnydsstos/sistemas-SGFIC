<?php
// Inclua a classe Turma
include_once '../Model/Turma.php';

// Verifica se o ID da turma foi fornecido na URL
if (isset($_GET['id'])) {
    $idTurma = $_GET['id'];

    // Cria um objeto Turma com o ID fornecido
    $turma = new Turma();
    $turma->setIdTurma($idTurma);

    // Chama o método para excluir a turma do banco de dados
    $turma->deletar();
    
    // Redireciona de volta para a página que lista todas as turmas após a exclusão
    header('Location: ../View/turmas.php');
    exit();
} else {
    // Caso o ID não tenha sido fornecido, redireciona de volta para a página que lista todas as turmas
    header('Location: ../View/turmas.php');
    exit();
}
?>
