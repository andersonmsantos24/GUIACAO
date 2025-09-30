<?php require_once 'funcoes.php'; ?>
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
    <a class="navbar-brand" href="index.php">🐶 Guia de Raças</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
            <?php if (estaLogado()): ?>
                <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
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