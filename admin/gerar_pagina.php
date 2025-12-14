<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit;
}

require_once __DIR__ . '/template_curso.php';

$dataFile = __DIR__ . '/data/cursos.json';
$cursos = json_decode(file_get_contents($dataFile), true) ?: [];

$id = $_GET['id'] ?? null;
$curso = null;

foreach ($cursos as $c) {
    if ($c['id'] == $id) {
        $curso = $c;
        break;
    }
}

if (!$curso) {
    die("Curso não encontrado.");
}

$conteudo = gerarTemplateCurso($curso);

// Caminho onde o HTML será salvo
$destino = __DIR__ . '/../cursos/' . $curso['arquivo'];

// Cria a página
file_put_contents($destino, $conteudo);

// Redireciona
header("Location: dashboard.php?pagina_criada=1");
exit;
