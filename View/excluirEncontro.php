<?php
// Inclua a classe Encontro
include_once '../Model/Encontro.php';

// Verifica se o ID do encontro foi fornecido na URL
if (isset($_GET['id'])) {
    $idEncontro = $_GET['id'];

    // Cria um objeto Encontro com o ID fornecido
    $encontro = new Encontro();
    $encontro->idEncontro = $idEncontro;

    // Chama o método para excluir o encontro do banco de dados
    $encontro->deletar();
    
    // Redireciona de volta para a página que lista todos os encontros após a exclusão
    header('Location: encontros.php');
    exit();
} else {
    // Caso o ID não tenha sido fornecido, redireciona de volta para a página que lista todos os encontros
    header('Location: encontros.php');
    exit();
}
?>
