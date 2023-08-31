<?php
include_once('../Model/encontro.php');
include_once('../Model/turma.php'); // Inclua a classe Turma
include_once('../Model/ambiente.php'); // Inclua a classe Ambiente
include_once('../Model/docente.php'); // Inclua a classe Docente

$encontro = new Encontro();
$docente = new Docente();

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

$anos = [
    1 => 2023,
    2 => 2024,
];

$anoCorrente = 2023;

$turmas = Turma::buscarTodos();
$ambientes = Ambiente::buscarTodos();

$anoCorrente = isset($_GET['ano']) ? intval($_GET['ano']) : date('Y');
$mesInicial = isset($_GET['mes_inicial']) ? intval($_GET['mes_inicial']) : date('m');
$mesFinal = isset($_GET['mes_final']) ? intval($_GET['mes_final']) : date('m');
$anoAtual = intval(date('Y'));

$docentes = $docente->buscarTodos();
$docenteSelecionado = (isset($_GET['docente']) && $_GET['docente'] !== 'all') ? $_GET['docente'] : 'all';

$encontros = $encontro->buscarEncontrosPorAnoMesEProfessor($anoCorrente, $mesInicial, $mesFinal, $docenteSelecionado);

$turmasData = [];
$ambientesData = [];

foreach ($turmas as $turma) {
    $turmasData[$turma->getIdTurma()] = $turma;
}

foreach ($ambientes as $ambiente) {
    $ambientesData[$ambiente->getIdSala()] = $ambiente;
}

$encontrosPorTurma = [];
foreach ($encontros as $encontro) {
    $turmaId = $encontro->getIdTurma();
    if (!isset($encontrosPorTurma[$turmaId])) {
        $encontrosPorTurma[$turmaId] = [];
    }
    $encontrosPorTurma[$turmaId][] = $encontro;
}

$totalEncontrosPorTurma = [];
$totalHorasAgendadasPorTurma = [];

$encontrosPorTurma = [];
foreach ($encontros as $encontro) {
    $turmaId = $encontro->getIdTurma();
    if (!isset($encontrosPorTurma[$turmaId])) {
        $encontrosPorTurma[$turmaId] = [];
    }
    $encontrosPorTurma[$turmaId][] = $encontro;

    $totalEncontrosPorTurma[$turmaId] = isset($totalEncontrosPorTurma[$turmaId]) ? $totalEncontrosPorTurma[$turmaId] + 1 : 1;

    $inicio = new DateTime($encontro->getInicio());
    $termino = new DateTime($encontro->getTermino());
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
            <h1>Relatório do Docente</h1>
        </div>

        <form class="mb-5 card p-5" style="background-color:beige" method="GET" action="relatorioDocente.php">
            <div class="form-row">
                <div class="form-group col-md-3 mb-0">
                    <label for="docente">Docente:</label>
                    <select class="form-control" id="docente" name="docente">
                        <option value="all">Todos os Docentes</option>
                        <?php
                        foreach ($docentes as $doc) {
                            echo "<option value='{$doc->getNif()}'" . ($docenteSelecionado === $doc->getNif() ? ' selected' : '') . ">{$doc->getNomeCompleto()}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group col-md-3 mb-0">
                    <label for="mes_inicial">Mês inicial:</label>
                    <select class="form-control" id="mes_inicial" name="mes_inicial">
                        <option>Selecione o mês inicial</option>
                        <?php
                        foreach ($meses as $mesNumero => $mesNome) {
                            echo "<option value='$mesNumero'" . ($mesInicial === $mesNumero ? ' selected' : '') . ">$mesNome</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group col-md-3 mb-0">
                    <label for="mes_final">Mês final:</label>
                    <select class="form-control" id="mes_final" name="mes_final">
                        <option>Selecione o mês final</option>
                        <?php
                        foreach ($meses as $mesNumero => $mesNome) {
                            echo "<option value='$mesNumero'" . ($mesFinal === $mesNumero ? ' selected' : '') . ">$mesNome</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group col-md-3 mb-0">
                    <label for="mes_ano">Ano:</label>
                    <select class="form-control" id="ano" name="ano">
                        <option>Selecione o ano</option>
                        <?php
                        foreach ($anos as $ano) {
                            echo "<option value='$ano'>$ano</option>";
                        }
                        ?>
                    </select>
                </div>

            </div>
            <br>
            <div class="form-row">
                <div class="col-md-12 text-center">
                    <button type="submit" class="btn btn-primary btn-block">Gerar Relatório</button>
                </div>
            </div>
        </form>

        <div id="accordion">
            <?php foreach ($encontrosPorTurma as $turmaId => $encontrosTurma) : ?>
                <?php $turmaEncontro = $turmasData[$turmaId]; ?>
                <div class="card mb-5">
                    <a class="card-header card-color card-relatorio" href="#collapse<?php echo $turmaId; ?>" data-toggle="collapse" aria-expanded="true" aria-controls="collapse<?php echo $turmaId; ?>">
                        <h2 class="mb-0">
                            <button class="btn" data-toggle="collapse" data-target="#collapse<?php echo $turmaId; ?>" aria-expanded="true" aria-controls="collapse<?php echo $turmaId; ?>">
                                <strong class="nome-turma">Turma - <?php echo $turmaEncontro->getNome(); ?> - </strong>
                                <span class="badge badge-primary">Vagas: <?php echo $turmaEncontro->getNumeroDeVagas(); ?></span>
                                <span class="badge badge-warning">Data de Início: <?php echo $turmaEncontro->getDataDeInicio(); ?></span>
                                <span class="badge badge-warning">Data de Término: <?php echo $turmaEncontro->getDataDeFinalizacao(); ?></span>
                                <span class="badge badge-info">Status: <?php echo $turmaEncontro->getStatus(); ?></span>
                            </button>
                        </h2>
                    </a>

                    <div id="collapse<?php echo $turmaId; ?>" class="collapse show" aria-labelledby="heading<?php echo $turmaId; ?>" data-parent="#accordion">
                        <div class="card-body">
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
                                            <td><?php echo date('d/m/Y', strtotime($encontro->getDataDoEncontro())); ?></td>
                                            <td><?php echo "{$encontro->getInicio()} - {$encontro->getTermino()}"; ?></td>
                                            <?php $ambienteEncontro = $ambientesData[$encontro->getIdAmbiente()]->getIdentificador(); ?>
                                            <td><?php echo $ambienteEncontro; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer footer-color">
                        Total de Encontros: <strong><?php echo $totalEncontrosPorTurma[$turmaId]; ?></strong> |
                        Total de Horas Agendadas: <strong> <?php echo number_format($totalHorasAgendadasPorTurma[$turmaId] / 60, 2); ?> hs </strong>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>


</body>

<?php require_once '../sharedComponents/footer.php' ?>;

</html>