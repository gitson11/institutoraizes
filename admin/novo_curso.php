<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit;
}

$dataFile = __DIR__ . '/data/cursos.json';

// Carrega cursos existentes
$cursos = [];
if (file_exists($dataFile)) {
    $cursos = json_decode(file_get_contents($dataFile), true) ?: [];
}

// Gera novo ID automaticamente
$novoId = 1;
if (!empty($cursos)) {
    $ids = array_column($cursos, 'id');
    $novoId = max($ids) + 1;
}

// Salvar novo curso
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $novoCurso = [
        "id"        => $novoId,
        "nome"      => $_POST['nome'],
        "slug"      => $_POST['slug'],
        "arquivo"   => $_POST['arquivo'],
        "descricao" => $_POST['descricao'],
        "ativo"     => isset($_POST['ativo'])
    ];

    $cursos[] = $novoCurso;

    file_put_contents(
        $dataFile,
        json_encode($cursos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
    );

    header("Location: dashboard.php?novo=1");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Novo Curso – Painel | Instituto Raízes</title>

  <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;700&family=Playfair+Display:wght@400;600&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style-admin.css">

  <style>
    .form-group { margin-bottom: 1rem; }
    .form-group label {
      font-size: .85rem;
      display: block;
      margin-bottom: .25rem;
    }
    .form-group input,
    .form-group textarea {
      width: 100%;
      padding: .45rem .6rem;
      border-radius: .4rem;
      border: 1px solid #ccc;
      font-size: .9rem;
    }
    .btn-save {
      background: var(--dourado);
      border: none;
      padding: .6rem 1.4rem;
      border-radius: 999px;
      font-weight: bold;
      text-transform: uppercase;
      cursor: pointer;
    }
    .btn-back {
      margin-right: 1rem;
      font-size: .9rem;
      text-decoration: underline;
      color: var(--azul-profundo);
    }
  </style>
</head>

<body>

<div class="admin-layout">

  <aside class="admin-sidebar">
    <div class="brand">
      <img src="../assets/img/logo_raizes.png" alt="">
      <span>Raízes</span>
    </div>

    <ul class="admin-menu">
      <li><a href="dashboard.php" class="active">Cursos</a></li>
      <li><a href="logout.php">Sair</a></li>
    </ul>
  </aside>

  <div class="admin-main-wrapper">

    <header class="admin-topbar">
      <h1>Novo Curso</h1>
      <div class="admin-user">
        Logado como: <strong><?= htmlspecialchars($_SESSION['usuario']) ?></strong>
      </div>
    </header>

    <main class="admin-main">

      <div class="card">
        <h2>Cadastrar Novo Curso</h2>

        <form method="POST">

          <div class="form-group">
            <label>Nome do curso</label>
            <input type="text" name="nome" required>
          </div>

          <div class="form-group">
            <label>Slug (ex: escatologia-cristocentrica)</label>
            <input type="text" name="slug" required>
          </div>

          <div class="form-group">
            <label>Arquivo HTML (ex: escatologia.html)</label>
            <input type="text" name="arquivo" required>
          </div>

          <div class="form-group">
            <label>Descrição curta</label>
            <textarea name="descricao" rows="3"></textarea>
          </div>

          <div class="form-group">
            <input type="checkbox" name="ativo" checked>
            <span>Curso ativo</span>
          </div>

          <br>

          <a href="dashboard.php" class="btn-back">← Voltar</a>
          <button type="submit" class="btn-save">Criar Curso</button>

        </form>
      </div>

    </main>
  </div>
</div>

</body>
</html>
