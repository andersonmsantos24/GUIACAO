<?php
require_once 'funcoes.php';

$racasCarrossel = buscarRacasParaCarrossel();
$tituloDaPagina = "Bem-vindo ao Guia de Raças";
include 'header.php';
?>
<?php if (!empty($racasCarrossel)): ?>
    <div class="container my-5">
    <div class="row align-items-center">

        <div class="col-lg-6 mb-4 mb-lg-0">
            <div id="heroCarousel" class="carousel slide mb-5" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    <?php foreach ($racasCarrossel as $index => $raca): ?>
                    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="<?= $index ?>"
                        class="<?= $index === 0 ? 'active' : '' ?>" aria-current="<?= $index === 0 ? 'true' : 'false' ?>"
                        aria-label="Slide <?= $index + 1 ?>"></button>
                    <?php endforeach; ?>
                </div>
                <div class="carousel-inner rounded shadow-lg">
                    <?php foreach ($racasCarrossel as $index => $raca): ?>
                    <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                        <img src="https://cdn2.thedogapi.com/images/<?= htmlspecialchars($raca['reference_image_id']) ?>.jpg"
                            class="d-block w-100" alt="<?= htmlspecialchars($raca['name']) ?>">
                        <div class="carousel-caption d-none d-md-block">
                            <h5>
                                <?= htmlspecialchars($raca['name']) ?>
                            </h5>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Anterior</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Próximo</span>
                </button>
            </div>
            <?php endif; ?>
        </div>
        <div class="col-lg-6 text-center text-lg-start">
            <div class="text-center">
                <h1 class="display-4">Bem-vindo ao Guia de Raças!</h1>
                <p class="fs-5 text-muted">Seu recurso completo para informações sobre cães.</p>
                <hr class="my-4">
                <p>Para explorar o conteúdo, adicionar raças aos favoritos e muito mais, por favor, acesse sua conta ou crie um
                    novo
                    cadastro.</p>
                <a class="btn btn-primary btn-lg" href="login.php">Fazer Login</a>
                <a class="btn btn-success btn-lg" href="cadastro.php">Cadastre-se</a>
            </div>
        </div>
    </div>
<?php include 'footer.php'; ?>