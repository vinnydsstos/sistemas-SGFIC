<?php include_once '../env.php'; ?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="<?php echo $path; ?>index.php">SGFIC</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item dropdown <?php if ($_SERVER['PHP_SELF'] === $path . 'View/turmas.php' || $_SERVER['PHP_SELF'] === $path . 'View/adicionarTurma.php') echo 'active'; ?>">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownTurmas" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Turmas
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownTurmas">
                    <a class="dropdown-item" href="<?php echo $path; ?>View/turmas.php">Listar Turmas</a>
                    <a class="dropdown-item" href="<?php echo $path; ?>View/adicionarTurma.php">Nova Turma</a>
                </div>
            </li>
            <li class="nav-item dropdown <?php if ($_SERVER['PHP_SELF'] === $path . 'View/encontros.php' || $_SERVER['PHP_SELF'] === $path . 'View/adicionarEncontro.php') echo 'active'; ?>">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownEncontros" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Encontros
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownEncontros">
                    <a class="dropdown-item" href="<?php echo $path; ?>View/encontros.php">Listar encontros</a>
                    <a class="dropdown-item" href="<?php echo $path; ?>View/adicionarEncontro.php">Agendar encontros</a>
                </div>
            </li>
            <li class="nav-item dropdown <?php if ($_SERVER['PHP_SELF'] === $path . 'View/docentes.php' || $_SERVER['PHP_SELF'] === $path . 'View/adicionarDocente.php') echo 'active'; ?>">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownDocentes" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Docentes
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownDocentes">
                    <a class="dropdown-item" href="<?php echo $path; ?>View/docentes.php">Listar Docentes</a>
                    <a class="dropdown-item" href="<?php echo $path; ?>View/adicionarDocente.php">Novo Docente</a>
                </div>
            </li>
            <li class="nav-item dropdown <?php if ($_SERVER['PHP_SELF'] === $path . 'View/cursos.php' || $_SERVER['PHP_SELF'] === $path . 'View/adicionarCurso.php') echo 'active'; ?>">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownCursos" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Cursos
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownCursos">
                    <a class="dropdown-item" href="<?php echo $path; ?>View/cursos.php">Listar Cursos</a>
                    <a class="dropdown-item" href="<?php echo $path; ?>View/adicionarCurso.php">Novo Curso</a>
                </div>
            </li>

            <li class="nav-item dropdown <?php if ($_SERVER['PHP_SELF'] === $path . 'View/relatorioMensal.php' || $_SERVER['PHP_SELF'] === $path . 'View/adicionarCurso.php') echo 'active'; ?>">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownCursos" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Relatório                
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownCursos">
                    <a class="dropdown-item" href="<?php echo $path; ?>View/relatorioDocente.php">Relatório Docente</a>
                </div>
            </li>

        </ul>
    </div>
</nav>