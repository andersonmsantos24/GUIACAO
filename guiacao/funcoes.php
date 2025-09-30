<?php

require_once 'config.php';
require_once 'traducoes.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function conectarBanco() {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $options = [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::ATTR_EMULATE_PREPARES => false ];
        return new PDO($dsn, DB_USER, DB_PASS, $options);
    } catch (PDOException $e) {
        die('Erro de Conexão com o Banco de Dados: ' . $e->getMessage());
    }
}

function estaLogado() {
    return isset($_SESSION['usuario_id']);
}

function exigirLogin() {
    if (!estaLogado()) {
        header('Location: login.php');
        exit;
    }
}

function chamarApi(string $endpoint): ?array {
    if (!defined('DOG_API_KEY') || DOG_API_KEY === 'COLE_SUA_CHAVE_API_AQUI') {
        die("ERRO: A chave da API não foi definida no arquivo config.php");
    }
    $url = 'https://api.thedogapi.com/v1' . $endpoint;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    $headers = ['x-api-key: ' . DOG_API_KEY];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        error_log('Erro cURL: ' . curl_error($ch));
        return null;
    }
    curl_close($ch);
    return json_decode($result, true);
}

function traduzirString(string $textoOriginal, array $dicionario): string {
    $palavras = explode(', ', $textoOriginal);
    $palavrasTraduzidas = [];
    foreach ($palavras as $palavra) {
        $palavrasTraduzidas[] = $dicionario[trim($palavra)] ?? $palavra;
    }
    return implode(', ', $palavrasTraduzidas);
}

function buscarTodasAsRacas(): array {
    global $traducoesNomes;
    $racas = chamarApi('/breeds');
    if (empty($racas)) return [];
    foreach ($racas as &$raca) {
        if (isset($raca['name']) && isset($traducoesNomes[$raca['name']])) {
            $raca['name'] = $traducoesNomes[$raca['name']];
        }
    }
    usort($racas, fn($a, $b) => strcmp($a['name'], $b['name']));
    return $racas;
}

function buscarRacasParaCarrossel(): array {
    $todasAsRacas = buscarTodasAsRacas();
    if (empty($todasAsRacas)) {
        return [];
    }
    $racasComImagem = array_filter($todasAsRacas, function($raca) {
        return !empty($raca['reference_image_id']);
    });
    shuffle($racasComImagem);
    return array_slice($racasComImagem, 0, 10);
}


function buscarRacaPorId(int $api_id): ?array {
    global $traducoesNomes, $traducoesTermos, $traducoesOrigens;
    $raca = chamarApi("/breeds/{$api_id}");
    if (empty($raca)) return null;
    if (!empty($raca['name']) && isset($traducoesNomes[$raca['name']])) $raca['name'] = $traducoesNomes[$raca['name']];
    if (!empty($raca['temperament'])) $raca['temperament'] = traduzirString($raca['temperament'], $traducoesTermos);
    if (!empty($raca['breed_group'])) $raca['breed_group'] = traduzirString($raca['breed_group'], $traducoesTermos);
    if (!empty($raca['life_span'])) $raca['life_span'] = str_replace('years', 'anos', $raca['life_span']);
    if (!empty($raca['origin'])) $raca['origin'] = traduzirString($raca['origin'], $traducoesOrigens);
    return $raca;
}

function buscarRacasPorNome(string $termoDeBusca): array {
    global $traducoesNomes;
    $termoParaApi = $termoDeBusca;
    $nomeEmIngles = array_search(strtolower($termoDeBusca), array_map('strtolower', $traducoesNomes));
    if ($nomeEmIngles !== false) $termoParaApi = $nomeEmIngles;
    $termoCodificado = urlencode($termoParaApi);
    $racasEncontradas = chamarApi("/breeds/search?q={$termoCodificado}");
    if (empty($racasEncontradas)) return [];
    $racasComDetalhes = [];
    foreach($racasEncontradas as $racaEncontrada) {
        if ($detalhes = buscarRacaPorId($racaEncontrada['id'])) {
            $racasComDetalhes[] = $detalhes;
        }
    }
    return $racasComDetalhes;
}

function registrarUsuario($nome, $email, $telefone, $idade, $possui_cachorro, $qtd_cachorros, $senha) {
    $pdo = conectarBanco();
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) return "Este e-mail já está cadastrado.";
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
    $sql = "INSERT INTO usuarios (nome, email, telefone, idade, possui_cachorro, quantidade_cachorros, senha) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nome, $email, $telefone, $idade, $possui_cachorro, $qtd_cachorros, $senhaHash]);
    return true;
}

function verificarLogin($email, $senha) {
    $pdo = conectarBanco();
    $stmt = $pdo->prepare("SELECT id, senha FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch();
    if ($usuario && password_verify($senha, $usuario['senha'])) {
        return $usuario['id'];
    }
    return false;
}

function buscarUsuarioPorId($id) {
    $pdo = conectarBanco();
    $stmt = $pdo->prepare("SELECT id, nome, email, telefone, idade, possui_cachorro, quantidade_cachorros FROM usuarios WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function atualizarUsuario($id, $nome, $email, $telefone, $idade, $possui_cachorro, $qtd_cachorros) {
    $pdo = conectarBanco();
    $sql = "UPDATE usuarios SET nome = ?, email = ?, telefone = ?, idade = ?, possui_cachorro = ?, quantidade_cachorros = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$nome, $email, $telefone, $idade, $possui_cachorro, $qtd_cachorros, $id]);
}

function adicionarFavorito($usuario_id, $raca_api_id, $raca_nome, $raca_imagem_id) {
    if (verificarFavorito($usuario_id, $raca_api_id)) return;
    $pdo = conectarBanco();
    $sql = "INSERT INTO favoritos (usuario_id, raca_api_id, raca_nome, raca_imagem_id) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$usuario_id, $raca_api_id, $raca_nome, $raca_imagem_id]);
}

function removerFavorito($usuario_id, $raca_api_id) {
    $pdo = conectarBanco();
    $sql = "DELETE FROM favoritos WHERE usuario_id = ? AND raca_api_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$usuario_id, $raca_api_id]);
}

function verificarFavorito($usuario_id, $raca_api_id) {
    $pdo = conectarBanco();
    $stmt = $pdo->prepare("SELECT id FROM favoritos WHERE usuario_id = ? AND raca_api_id = ?");
    $stmt->execute([$usuario_id, $raca_api_id]);
    return $stmt->fetch() !== false;
}

function buscarFavoritosPorUsuario($usuario_id) {
    $pdo = conectarBanco();
    $stmt = $pdo->prepare("SELECT * FROM favoritos WHERE usuario_id = ? ORDER BY raca_nome ASC");
    $stmt->execute([$usuario_id]);
    return $stmt->fetchAll();
}