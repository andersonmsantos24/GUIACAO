<?php
require_once 'funcoes.php';
exigirLogin();

$usuario_id = $_SESSION['usuario_id'];
$sucesso = '';
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $telefone = $_POST['telefone'] ?? '';
    $idade = $_POST['idade'] ?? null;
    $possui_cachorro = isset($_POST['possui_cachorro']) ? 1 : 0;
    $qtd_cachorros = $_POST['quantidade_cachorros'] ?? 0;

    if (atualizarUsuario($usuario_id, $nome, $email, $telefone, $idade, $possui_cachorro, $qtd_cachorros)) {
        $sucesso = "Perfil atualizado com sucesso!";
    } else {
        $erro = "Ocorreu um erro ao atualizar o perfil.";
    }
}

$usuario = buscarUsuarioPorId($usuario_id);
$tituloDaPagina = "Editar Perfil";
include 'header.php';
?>
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <h2 class="text-center mb-4">Editar Perfil</h2>
        <?php if ($erro): ?><div class="alert alert-danger"><?= $erro ?></div><?php endif; ?>
        <?php if ($sucesso): ?><div class="alert alert-success"><?= $sucesso ?></div><?php endif; ?>

        <form method="POST">
            <div class="mb-3"><label class="form-label">Nome</label><input type="text" class="form-control" name="nome" value="<?= htmlspecialchars($usuario['nome']) ?>" required></div>
            <div class="mb-3"><label class="form-label">Email</label><input type="email" class="form-control" name="email" value="<?= htmlspecialchars($usuario['email']) ?>" required></div>
            <div class="mb-3"><label class="form-label">Telefone</label><input type="tel" class="form-control" name="telefone" value="<?= htmlspecialchars($usuario['telefone']) ?>"></div>
            <div class="mb-3"><label class="form-label">Idade</label><input type="number" class="form-control" name="idade" value="<?= htmlspecialchars($usuario['idade']) ?>"></div>
            <div class="mb-3 form-check"><input type="checkbox" class="form-check-input" name="possui_cachorro" value="1" <?= $usuario['possui_cachorro'] ? 'checked' : '' ?>><label class="form-check-label">Já possui cachorro?</label></div>
            <div class="mb-3"><label class="form-label">Quantidade</label><input type="number" class="form-control" name="quantidade_cachorros" value="<?= htmlspecialchars($usuario['quantidade_cachorros']) ?>"></div>
            <button type="submit" class="btn btn-primary w-100">Salvar Alterações</button>
        </form>
    </div>
</div>
<?php include 'footer.php'; ?>