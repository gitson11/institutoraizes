<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit;
}

$dataFile = __DIR__ . '/data/cursos.json';

// Lê os cursos
$cursos = json_decode(file_get_contents($dataFile), true) ?: [];

$id = $_GET['id'] ?? null;
$curso = null;

// Localiza o curso pelo ID
foreach ($cursos as $c) {
    if ($c['id'] == $id) {
        $curso = $c;
        break;
    }
}

if (!$curso) {
    die("Curso não encontrado.");
}

// Salvar alterações
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($cursos as &$item) {
        if ($item['id'] == $id) {

            $item['nome']     = $_POST['nome'];
            $item['slug']     = $_POST['slug'];
            $item['arquivo']  = $_POST['arquivo'];
            $item['descricao']= $_POST['descricao'];
            $item['ativo']    = isset($_POST['ativo']);

            break;
        }
    }

    file_put_contents($dataFile, json_encode($cursos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    header("Location: dashboard.php?edit_ok=1");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Editar Curso – Painel | Instituto Raízes</title>

  <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;700&family=Playfair+Display:wght@400;600&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style-admin.css">

  <style>
    .form-group {
        margin-bottom: 1rem;
    }
    .form-group label {
        font-size: .85rem;
        display: block;
        margin-bottom: .25rem;
    }
    .form-group input,
    .form-group textarea,
    .form-group select {
        width: 100%;
        padding: .45rem .6rem;
        border: 1px solid #ccc;
        border-radius: .4rem;
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
        text-decoration: underline;
        color: var(--azul-profundo);
        font-size: .9rem;
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
      <li><a href="dashboard.php">Cursos</a></li>
      <li><a href="logout.php">Sair</a></li>
    </ul>
  </aside>

  <div class="admin-main-wrapper">

    <header class="admin-topbar">
      <h1>Editar Curso</h1>
      <div class="admin-user">
        Logado como: <strong><?= htmlspecialchars($_SESSION['usuario']) ?></strong>
      </div>
    </header>

    <main class="admin-main">

      <div class="card">
        <h2>Informações do Curso</h2>

        <form method="POST">
          
          <div class="form-group">
            <label>Nome do curso</label>
            <input type="text" name="nome" value="<?= htmlspecialchars($curso['nome']) ?>" required>
          </div>

          <div class="form-group">
            <label>Slug (sem espaços, usado como URL)</label>
            <input type="text" name="slug" value="<?= htmlspecialchars($curso['slug']) ?>" required>
          </div>

          <div class="form-group">
            <label>Arquivo HTML do curso</label>
            <input type="text" name="arquivo" value="<?= htmlspecialchars($curso['arquivo']) ?>" required>
          </div>

          <div class="form-group">
            <label>Descrição curta</label>
            <textarea name="descricao" rows="3"><?= htmlspecialchars($curso['descricao'] ?? "") ?></textarea>
          </div>

          <div class="form-group">
            <label>Status</label>
            <input type="checkbox" name="ativo" <?= $curso['ativo'] ? "checked" : "" ?>>
            <span>Curso ativo</span>
          </div>

          <br>

          <a href="dashboard.php" class="btn-back">← Voltar</a>
          <button type="submit" class="btn-save">Salvar alterações</button>

        </form>
      </div>

    </main>
  </div>
</div>

</body>
</html>
