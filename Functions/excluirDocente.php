<?php
// Verifica se foi enviado o parâmetro 'id' via GET
if (isset($_GET['id'])) {
    // Inclua a classe Docente
    include_once '../Model/Docente.php';

    // Obtém o ID do docente a ser excluído
    $idDocente = $_GET['id'];

    // Cria um objeto Docente com o ID fornecido
    $docente = new Docente();
    $docente->setNif($idDocente);

    // Chama o método para deletar o docente
    $docente->deletar();

    // Redireciona para a página de lista de docentes
    header('Location: ../View/docentes.php');
    exit();
} else {
    // Caso não tenha sido fornecido o parâmetro 'id', redireciona para a página de lista de docentes
    header('Location: ../View/docentes.php');
    exit();
}
?>
