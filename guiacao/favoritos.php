<?php
require_once 'funcoes.php';
exigirLogin();

$favoritos = buscarFavoritosPorUsuario($_SESSION['usuario_id']);
$tituloDaPagina = "Meus Favoritos";
include 'header.php';
?>
<div class="text-center mb-5">
    <h1 class="display-4">Meus Favoritos</h1>
    <p class="lead text-muted">Aqui estão as raças que você marcou como favoritas.</p>
</div>

<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
    <?php if (!empty($favoritos)): ?>
        <?php foreach ($favoritos as $fav): ?>
            <div class="col">
                <a href="raca.php?id=<?= htmlspecialchars($fav['raca_api_id']) ?>" class="card h-100 text-decoration-none text-light">
                    <img src="https://cdn2.thedogapi.com/images/<?= htmlspecialchars($fav['raca_imagem_id']) ?>.jpg" class="card-img-top" alt="<?= htmlspecialchars($fav['raca_nome']) ?>">
                    <div class="card-body">
                        <h5 class="card-title text-center"><?= htmlspecialchars($fav['raca_nome']) ?></h5>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12"><p class="text-center">Você ainda não adicionou nenhuma raça aos seus favoritos.</p></div>
    <?php endif; ?>
</div>
<?php include 'footer.php'; ?>