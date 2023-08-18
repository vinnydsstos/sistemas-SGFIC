<?php
include_once('../Model/encontro.php');
include_once('../Model/turma.php'); // Inclua a classe Turma
include_once('../Model/ambiente.php'); // Inclua a classe Ambiente
include_once('../Model/docente.php'); // Inclua a classe Docente

$encontro = new Encontro();
$docente = new Docente();

$turmas = Turma::buscarTodos();
$ambientes = Ambiente::buscarTodos();

// Obtém o número do mês inicial (ou o mês selecionado)
if (isset($_GET['mes_inicial'])) {
    $mesInicial = intval($_GET['mes_inicial']);
} else {
    $mesInicial = intval(date('m'));
}

// Obtém o número do mês final (ou o mês selecionado)
if (isset($_GET['mes_final'])) {
    $mesFinal = intval($_GET['mes_final']);
} else {
    $mesFinal = intval(date('m'));
}

// Obtém o número do ano atual
$anoAtual = intval(date('Y'));

// Busca todos os docentes
$docentes = $docente->buscarTodos();

// Obtém o NIF do docente selecionado (ou nenhum se não houver seleção)
if (isset($_GET['docente']) && $_GET['docente'] !== 'all') {
    $docenteSelecionado = $_GET['docente'];
} else {
    $docenteSelecionado = 'all';
}

// Busca os encontros para o período entre mes_inicial e mes_final e o docente selecionado (ou todos os docentes)
$encontros = $encontro->buscarEncontrosPorPeriodoEProfessor($mesInicial, $mesFinal, $docenteSelecionado);

// Create associative arrays to store the Turma and Ambiente data
$turmasData = [];
$ambientesData = [];

// Fetch Turma data and store it in the associative array
foreach ($turmas as $turma) {
    $turmasData[$turma->idTurma] = $turma;
}

// Fetch Ambiente data and store it in the associative array
foreach ($ambientes as $ambiente) {
    $ambientesData[$ambiente->idSala] = $ambiente;
}

// Obtém os encontros agrupados por turma
$encontrosPorTurma = [];
foreach ($encontros as $encontro) {
    $turmaId = $encontro->idTurma;
    if (!isset($encontrosPorTurma[$turmaId])) {
        $encontrosPorTurma[$turmaId] = [];
    }
    $encontrosPorTurma[$turmaId][] = $encontro;
}

// Variáveis para armazenar as somatórias
$totalEncontrosPorTurma = [];
$totalHorasAgendadasPorTurma = [];

// Obtém os encontros agrupados por turma e realiza as somatórias
$encontrosPorTurma = [];
foreach ($encontros as $encontro) {
    $turmaId = $encontro->idTurma;
    if (!isset($encontrosPorTurma[$turmaId])) {
        $encontrosPorTurma[$turmaId] = [];
    }
    $encontrosPorTurma[$turmaId][] = $encontro;

    $totalEncontrosPorTurma[$turmaId] = isset($totalEncontrosPorTurma[$turmaId]) ? $totalEncontrosPorTurma[$turmaId] + 1 : 1;

    $inicio = new DateTime($encontro->inicio);
    $termino = new DateTime($encontro->termino);
    $interval = $inicio->diff($termino);
    $totalHorasAgendadasPorTurma[$turmaId] = isset($totalHorasAgendadasPorTurma[$turmaId]) ? $totalHorasAgendadasPorTurma[$turmaId] + ($interval->h * 60) + $interval->i : ($interval->h * 60) + $interval->i;
}


?>

<!DOCTYPE html>
<html lang="en">

<?php include_once '../sharedComponents/head.php'; ?>

<?php include_once '../sharedComponents/navbar.php'; ?>

<?php $nome_pagina = "Relatórios"; ?>
<?php include_once '../sharedComponents/header.php'; ?>

<body class="container">
    <div class="container container pl-5 pr-5 pb-5 mt-5">
        <div class="mb-5">
            <h1>Encontros planejados</h1>
        </div>

        <!-- Formulário para seleção do mês e docente -->
        <form class="mb-5" method="GET" action="relatorioDocente.php">
            <div class="form-row">

                <!-- Campo para selecionar o docente -->
                <div class="form-group col-md-3 mb-0">
                    <select class="form-control" id="docente" name="docente">
                        <option value="all">Todos os Docentes</option>
                        <?php
                        // Exibir as opções do dropdown para selecionar o docente
                        foreach ($docentes as $doc) {
                            echo "<option value='{$doc->nif}'" . ($docenteSelecionado === $doc->nif ? ' selected' : '') . ">{$doc->NomeCompleto}</option>";
                        }
                        ?>
                    </select>
                </div>

                <!-- Campo para selecionar o mês inicial -->
                <div class="form-group col-md-3 mb-0">
                    <select class="form-control" id="mes_inicial" name="mes_inicial">
                        <option>Selecione o mês inicial</option>
                        <?php
                        // Obter os nomes dos meses em Português
                        $meses = [
                            1 => 'Janeiro',
                            2 => 'Fevereiro',
                            3 => 'Março',
                            4 => 'Abril',
                            5 => 'Maio',
                            6 => 'Junho',
                            7 => 'Julho',
                            8 => 'Agosto',
                            9 => 'Setembro',
                            10 => 'Outubro',
                            11 => 'Novembro',
                            12 => 'Dezembro'
                        ];

                        // Exibir as opções do dropdown para selecionar o mês inicial
                        foreach ($meses as $mesNumero => $mesNome) {
                            echo "<option value='$mesNumero'" . ($mesInicial === $mesNumero ? ' selected' : '') . ">$mesNome</option>";
                        }
                        ?>
                    </select>
                </div>

                <!-- Campo para selecionar o mês final -->
                <div class="form-group col-md-3 mb-0">
                    <select class="form-control" id="mes_final" name="mes_final">
                        <option>Selecione o mês final</option>
                        <?php
                        // Exibir as opções do dropdown para selecionar o mês final
                        foreach ($meses as $mesNumero => $mesNome) {
                            echo "<option value='$mesNumero'" . ($mesFinal === $mesNumero ? ' selected' : '') . ">$mesNome</option>";
                        }
                        ?>
                    </select>
                </div>


                <div class="form-group col-md-3">
                    <label></label>
                    <button type="submit" class="btn btn-primary ">Gerar Relatório</button>
                </div>
            </div>
        </form>

        <!-- Início do acordeão -->
        <div id="accordion">
            <?php foreach ($encontrosPorTurma as $turmaId => $encontrosTurma) : ?>
                <?php $turmaEncontro = $turmasData[$turmaId]; ?>
                <div class="card mb-5">
                    <a class="card-header card-color"  href="#collapse<?php echo $turmaId; ?>" data-toggle="collapse" aria-expanded="true" aria-controls="collapse<?php echo $turmaId; ?>">
                        <h2 class="mb-0">
                            <button class="btn" data-toggle="collapse" data-target="#collapse<?php echo $turmaId; ?>" aria-expanded="true" aria-controls="collapse<?php echo $turmaId; ?>">
                                <strong class="nome-turma">Turma - <?php echo $turmaEncontro->nome; ?> - </strong> 
                                <span class="badge badge-primary">Vagas: <?php echo $turmaEncontro->numeroDeVagas; ?></span>
                                <span class="badge badge-warning">Data de Início: <?php echo $turmaEncontro->dataDeInicio; ?></span>
                                <span class="badge badge-warning">Data de Término: <?php echo $turmaEncontro->dataDeFinalizacao; ?></span>
                                <span class="badge badge-info">Status: <?php echo $turmaEncontro->status; ?></span>
                            </button>
                        </h2>
                    </a>

                    <!-- Use a classe 'show' para manter os itens expandidos -->
                    <div id="collapse<?php echo $turmaId; ?>" class="collapse show" aria-labelledby="heading<?php echo $turmaId; ?>" data-parent="#accordion">
                        <div class="card-body">
                            <!-- Tabela para mostrar os encontros da turma -->
                            <table class="table table-bordered mt-3">
                                <thead>
                                    <tr>
                                        <th class="text-center">Dia</th>
                                        <th class="text-center">Horário</th>
                                        <th class="text-center">Ambiente</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($encontrosTurma as $encontro) : ?>
                                        <tr>
                                            <td><?php echo date('d/m/Y', strtotime($encontro->dataDoEncontro)); ?></td>
                                            <td><?php echo "{$encontro->inicio} - {$encontro->termino}"; ?></td>
                                            <?php $ambienteEncontro = $ambientesData[$encontro->idAmbiente]->identificador; ?>
                                            <td><?php echo $ambienteEncontro; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer footer-color">
                        Total de Encontros: <strong><?php echo $totalEncontrosPorTurma[$turmaId]; ?></strong> |
                        Total de Horas Agendadas: <strong> <?php echo $totalHorasAgendadasPorTurma[$turmaId]/60 ; ?> hs </strong>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <!-- Fim do acordeão -->
    </div>

    <script>
        $(document).ready(function() {
            $('#encontrosTable').DataTable();
        });
    </script>
</body>

</html>