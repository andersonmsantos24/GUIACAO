<?php
require_once 'funcoes.php';

if (estaLogado()) {
    header('Location: dashboard.php');
    exit;
}

$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';
    $usuario_id = verificarLogin($email, $senha);
    if ($usuario_id) {
        $_SESSION['usuario_id'] = $usuario_id;
        header('Location: dashboard.php');
        exit;
    } else {
        $erro = "E-mail ou senha inválidos.";
    }
}
$tituloDaPagina = "Login";
include 'header.php';
?>
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-4">
        <h2 class="text-center mb-4">Acessar Conta</h2>
        <?php if ($erro): ?><div class="alert alert-danger"><?= $erro ?></div><?php endif; ?>
        <form method="POST">
            <div class="form-floating mb-3">
                <input type="email" class="form-control" id="email" name="email" placeholder="nome@exemplo.com" required>
                <label for="email">E-mail</label>
            </div>
            <div class="form-floating mb-3">
                <input type="password" class="form-control" id="senha" name="senha" placeholder="Senha" required>
                <label for="senha">Senha</label>
            </div>
            <button type="submit" class="btn btn-primary w-100 py-2">Entrar</button>
        </form>
        <p class="text-center mt-3">Não tem uma conta? <a href="cadastro.php">Cadastre-se aqui</a>.</p>
    </div>
</div>
<?php include 'footer.php'; ?>