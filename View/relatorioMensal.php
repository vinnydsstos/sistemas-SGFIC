<?php
include_once('../Model/encontro.php');
include_once('../Model/turma.php'); // Inclua a classe Turma
include_once('../Model/ambiente.php'); // Inclua a classe Ambiente

$encontro = new Encontro();

// Obtém o número do mês atual (ou o mês selecionado)
if (isset($_GET['mes'])) {
    $mesAtual = intval($_GET['mes']);
} else {
    $mesAtual = intval(date('m'));
}

// Obtém o número do ano atual
$anoAtual = intval(date('Y'));

// Obtém o primeiro dia do mês atual
$primeiroDiaMes = mktime(0, 0, 0, $mesAtual, 1, $anoAtual);

// Obtém o número do dia da semana do primeiro dia do mês
$diaSemanaInicio = date('w', $primeiroDiaMes);

// Obtém o último dia do mês atual
$ultimoDiaMes = mktime(0, 0, 0, $mesAtual + 1, 0, $anoAtual);
$totalDiasMes = date('d', $ultimoDiaMes);

// Busca os encontros para o mês selecionado
$encontros = $encontro->buscarEncontrosPorMes($mesAtual);

// Busca todas as turmas e ambientes para exibir os nomes no calendário

?>

<!DOCTYPE html>
<html lang="en">

<?php include_once '../sharedComponents/head.php';?>

<?php include_once '../sharedComponents/navbar.php'; ?>

<?php $nome_pagina = "Relatórios"; ?>
<?php include_once '../sharedComponents/header.php'; ?>

<body class="container">

    <div class="container container pl-5 pr-5 pb-5 mt-5">


        <!-- Formulário para seleção do mês -->
        <form method="GET" action="relatorioMensal.php">
            <div class="form-group">
                <label for="mes">Selecione o mês:</label>
                <select class="form-control" id="mes" name="mes">
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

                    // Exibir as opções do dropdown
                    foreach ($meses as $mesNumero => $mesNome) {
                        echo "<option value='$mesNumero'" . ($mesAtual === $mesNumero ? ' selected' : '') . ">$mesNome</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Gerar Relatório</button>
        </form>

        <!-- Criação do calendário -->
        <h2 class="mt-4">Calendário - <?php echo "{$meses[$mesAtual]} de $anoAtual"; ?></h2>
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th class="text-center">Domingo</th>
                    <th class="text-center">Segunda</th>
                    <th class="text-center">Terça</th>
                    <th class="text-center">Quarta</th>
                    <th class="text-center">Quinta</th>
                    <th class="text-center">Sexta</th>
                    <th class="text-center">Sábado</th>
                </tr>
            </thead>
            <tbody>

                <?php
                $numeroDia = 1;
                for ($i = 0; $i < 6; $i++) {
                    echo "<tr>";
                    for ($j = 0; $j < 7; $j++) {
                        if (($i === 0 && $j < $diaSemanaInicio) || $numeroDia > $totalDiasMes) {
                            echo "<td></td>";
                        } else {
                            echo "<td>";
                            echo "<strong>$numeroDia</strong><br>";
                            // Verifica se há encontro nesse dia
                            $encontroDia = null;
                            foreach ($encontros as $encontro) {
                                $diaEncontro = date('d', strtotime($encontro->dataDoEncontro));
                                if ($diaEncontro == $numeroDia) {
                                    $encontroDia = $encontro;
                                    break;
                                }
                            }
                            if ($encontroDia) {
                                echo "<div class='card mt-2'>";
                                echo "<div class='card-body'>";
                                echo "<h6 class='card-title'>Horário: {$encontroDia->inicio} - {$encontroDia->termino}</h6>";
                                // Buscar o nome da turma pelo ID
                                $turmaEncontro = Turma::buscarPorId($encontroDia->idTurma);
                                if ($turmaEncontro) {
                                    echo "<p class='card-text'>Turma: {$turmaEncontro->getNome()}</p>";
                                }
                                // Buscar o identificador do ambiente pelo ID
                                $ambienteEncontro = Ambiente::buscarPorId($encontroDia->idAmbiente);
                                if ($ambienteEncontro) {
                                    echo "<p class='card-text'>Ambiente: {$ambienteEncontro->getIdentificador()}</p>";
                                }
                                echo "</div>";
                                echo "</div>";
                            }
                            echo "</td>";
                            $numeroDia++;
                        }
                    }
                    echo "</tr>";
                }
                ?>

            </tbody>
        </table>

    </div>
</body>

</html>
