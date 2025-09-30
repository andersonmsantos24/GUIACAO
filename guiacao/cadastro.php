<?php
require_once 'funcoes.php';

$erro = '';
$sucesso = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';
    $telefone = $_POST['telefone'] ?? null;
    $idade = $_POST['idade'] ?? null;
    $possui_cachorro = isset($_POST['possui_cachorro']) ? 1 : 0;
    $qtd_cachorros = $_POST['quantidade_cachorros'] ?? 0;

    $resultado = registrarUsuario($nome, $email, $telefone, $idade, $possui_cachorro, $qtd_cachorros, $senha);

    if ($resultado === true) {
        $sucesso = "Cadastro realizado com sucesso! Você já pode fazer o <a href='login.php' class='alert-link'>login</a>.";
    } else {
        $erro = $resultado;
    }
}
$tituloDaPagina = "Cadastro";
include 'header.php';
?>
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <h2 class="text-center mb-4">Crie sua Conta</h2>
        <?php if ($erro): ?><div class="alert alert-danger"><?= $erro ?></div><?php endif; ?>
        <?php if ($sucesso): ?><div class="alert alert-success"><?= $sucesso ?></div><?php endif; ?>
        
        <?php if (!$sucesso): ?>
        <form method="POST">
            <div class="mb-3"><label for="nome" class="form-label">Nome Completo</label><input type="text" class="form-control" id="nome" name="nome" required></div>
            <div class="mb-3"><label for="email" class="form-label">E-mail</label><input type="email" class="form-control" id="email" name="email" required></div>
            <div class="mb-3"><label for="senha" class="form-label">Senha</label><input type="password" class="form-control" id="senha" name="senha" required></div>
            <div class="mb-3"><label for="telefone" class="form-label">Telefone</label><input type="tel" class="form-control" id="telefone" name="telefone"></div>
            <div class="mb-3"><label for="idade" class="form-label">Idade</label><input type="number" class="form-control" id="idade" name="idade"></div>
            <div class="mb-3 form-check"><input type="checkbox" class="form-check-input" id="possui_cachorro" name="possui_cachorro" value="1"><label class="form-check-label" for="possui_cachorro">Já possui cachorro?</label></div>
            <div class="mb-3"><label for="quantidade_cachorros" class="form-label">Se sim, quantos?</label><input type="number" class="form-control" id="quantidade_cachorros" name="quantidade_cachorros" value="0"></div>
            <button type="submit" class="btn btn-success w-100">Criar Conta</button>
        </form>
        <?php endif; ?>
    </div>
</div>
<?php include 'footer.php'; ?>