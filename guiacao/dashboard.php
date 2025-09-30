<?php
require_once 'funcoes.php';
exigirLogin();

$termoDeBusca = filter_input(INPUT_GET, 'q', FILTER_SANITIZE_SPECIAL_CHARS);
$racas = [];

if (!empty($termoDeBusca)) {
    $tituloDaPagina = "Resultados para: " . htmlspecialchars($termoDeBusca);
    $racas = buscarRacasPorNome($termoDeBusca);
} else {
    $tituloDaPagina = 'Dashboard';
    $racas = buscarTodasAsRacas();
}


?>
<!DOCTYPE html>
<html lang="pt-br" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $tituloDaPagina ?? 'Guia de Raças de Cães' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-secondary">
  <div class="container">
    <a class="navbar-brand" href="dashboard.php">🐶 Guia de Raças</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
            <?php if (estaLogado()): ?>
                
                <li class="nav-item"><a class="nav-link" href="favoritos.php">Meus Favoritos</a></li>
                <li class="nav-item"><a class="nav-link" href="perfil.php">Editar Perfil</a></li>
                <li class="nav-item"><a class="nav-link" href="logout.php">Sair</a></li>
            <?php else: ?>
                <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                <li class="nav-item"><a class="nav-link" href="cadastro.php">Cadastre-se</a></li>
            <?php endif; ?>
        </ul>
    </div>
  </div>
</nav>
<div class="content-wrap"> <main class="container py-4">
<div class="text-center mb-5">
    <h1 class="display-4">Navegue pelas Raças</h1>
    <p class="lead text-muted">Clique em uma raça para ver os detalhes ou use a busca abaixo.</p>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <form class="d-flex" action="dashboard.php" method="GET">
                <input class="form-control me-2" type="search" name="q"
                    value="<?= htmlspecialchars($termoDeBusca ?? '') ?>" placeholder="Pesquisar por nome...">
                <button class="btn btn-outline-success" type="submit">Buscar</button>
            </form>
        </div>
    </div>
</div>

<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
    <?php if (!empty($racas)): ?>
    <?php foreach ($racas as $raca): ?>
    <div class="col">
        <a href="raca.php?id=<?= $raca['id'] ?>" class="card h-100 text-decoration-none text-light">
            <img src="https://cdn2.thedogapi.com/images/<?= $raca['reference_image_id'] ?>.jpg" class="card-img-top"
                alt="<?= htmlspecialchars($raca['name']) ?>">
            <div class="card-body">
                <h5 class="card-title text-center">
                    <?= htmlspecialchars($raca['name']) ?>
                </h5>
            </div>
        </a>
    </div>
    <?php endforeach; ?>
    <?php else: ?>
    <div class="col-12">
        <div class="alert alert-warning text-center" >Nenhuma raça encontrada.</div>
    </div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>