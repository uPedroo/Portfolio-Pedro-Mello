<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Portfolio Admin | Dashboard</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<?php if(isset($_SESSION['user_id'])): ?>

    <!-- MENU DASHBOARD -->

    <div class="container-fluid p-0">

        <div class="d-flex cabecalho justify-content-between align-items-center px-4 py-2">

        <span>
            <a href="index.php" class="text-decoration-none text-light fs-4">
                Portfolio
            </a>
        </span> 
        
            <div class="d-flex align-items-center gap-4">

                <a href="dashboard.php#sobre" class="nav-link text-light">
                    Sobre
                </a>

                <a href="dashboard.php#habilidades" class="nav-link text-light">
                    Habilidades
                </a>

                <a href="dashboard.php#projetos" class="nav-link text-light">
                    Projetos
                </a>

                <a href="dashboard.php#rodape" class="nav-link text-light">
                    Rodapé
                </a>

                <!-- LOGOUT -->

                <a href="dashboard.php?logout=true" class="btn btn-danger">
                    Sair
                </a>
            </div>

        </div>
    </div>

<?php endif; ?>