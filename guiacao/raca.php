<?php
require_once 'funcoes.php';
exigirLogin();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) { header('Location: dashboard.php'); exit; }
$usuario_id = $_SESSION['usuario_id'];
$raca = buscarRacaPorId($id);
if (!$raca) { header('Location: dashboard.php'); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['favoritar'])) {
        adicionarFavorito($usuario_id, $id, $raca['name'], $raca['reference_image_id']);
    } elseif (isset($_POST['desfavoritar'])) {
        removerFavorito($usuario_id, $id);
    }
    header("Location: raca.php?id=$id");
    exit;
}

$eFavorito = verificarFavorito($usuario_id, $id);
$tituloDaPagina = $raca['name'];
include 'header.php';
?>
<div class="card shadow-sm">
    <div class="card-body">
        <h1 class="card-title display-5 border-bottom pb-3 mb-4"><?= htmlspecialchars($raca['name']) ?></h1>
        <div class="row">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <img src="https://cdn2.thedogapi.com/images/<?= htmlspecialchars($raca['reference_image_id']) ?>.jpg" class="img-fluid raca-detalhe-img" alt="<?= htmlspecialchars($raca['name']) ?>">
            </div>
            <div class="col-lg-6">
                <ul class="list-group list-group-flush fs-5">
                    <li class="list-group-item"><strong>Temperamento:</strong> <?= htmlspecialchars($raca['temperament'] ?? 'Não informado') ?></li>
                    <li class="list-group-item"><strong>Grupo:</strong> <?= htmlspecialchars($raca['breed_group'] ?? 'Não informado') ?></li>
                    <li class="list-group-item"><strong>Expectativa de Vida:</strong> <?= htmlspecialchars($raca['life_span'] ?? 'Não informado') ?></li>
                    <li class="list-group-item"><strong>Origem:</strong> <?= htmlspecialchars($raca['origin'] ?? 'Desconhecida') ?></li>
                    <li class="list-group-item"><strong>Peso:</strong> <?= htmlspecialchars($raca['weight']['metric'] ?? 'Não informado') ?> kg</li>
                    <li class="list-group-item"><strong>Altura:</strong> <?= htmlspecialchars($raca['height']['metric'] ?? 'Não informada') ?> cm</li>
                </ul>
                <div class="mt-4 d-flex align-items-center gap-2">
                    <form method="POST" class="d-inline">
                        <?php if ($eFavorito): ?>
                            <button type="submit" name="desfavoritar" class="btn btn-danger">Remover dos Favoritos ★</button>
                        <?php else: ?>
                            <button type="submit" name="favoritar" class="btn btn-warning">Adicionar aos Favoritos ☆</button>
                        <?php endif; ?>
                    </form>
                    <a href="dashboard.php" class="btn btn-dark">← Voltar ao Dashboard</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>