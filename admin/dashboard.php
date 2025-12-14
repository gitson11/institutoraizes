<?php
session_start();
if (!isset($_SESSION['usuario'])) {
  header("Location: index.php");
  exit;
}

$dataFile = __DIR__ . '/data/cursos.json';

// Lê os cursos
$cursos = [];
if (file_exists($dataFile)) {
  $json = file_get_contents($dataFile);
  $cursos = json_decode($json, true) ?: [];
}

// Toggle ativo/inativo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_id'])) {
  $id = $_POST['toggle_id'];

  foreach ($cursos as &$curso) {
    if ($curso['id'] == $id) {
      $curso['ativo'] = !$curso['ativo'];
      break;
    }
  }
  file_put_contents($dataFile, json_encode($cursos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
  header("Location: dashboard.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Painel – Cursos | Instituto Raízes</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;700&family=Playfair+Display:wght@400;600&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="style-admin.css">
</head>
<body>

  <div class="admin-layout">
    <aside class="admin-sidebar">
      <div class="brand">
        <img src="../assets/img/logo_raizes.png" alt="Instituto Raízes">
        <span>Raízes</span>
      </div>

      <ul class="admin-menu">
        <li><a href="dashboard.php">Cursos</a></li>
        <li><a href="alunos.php">Alunos</a></li>
        <li><a href="certificados.php">Certificados</a></li>
        <li><a href="logout.php">Sair</a></li>
      </ul>
    </aside>

    <div class="admin-main-wrapper">
      <header class="admin-topbar">
        <h1>Cursos</h1>
        <div class="admin-user">
          Logado como: <strong><?= htmlspecialchars($_SESSION['usuario']) ?></strong>
        </div>
      </header>

      <main class="admin-main">

        <div class="card">
          <h2>Lista de Cursos</h2>
          <p style="font-size:0.85rem; margin-bottom:0.6rem;">
            Aqui você pode ativar/desativar cursos que aparecem no site público.  
            (Edição mais avançada pode ser feita em <code>cursos.json</code> ou em uma próxima versão do painel)
          </p>

          <a href="novo_curso.php" class="btn-sm btn-primary" style="margin-bottom:0.6rem; display:inline-block;">
            + Novo Curso
          </a>
          <table class="table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Curso</th>
                <th>Slug</th>
                <th>Status</th>
                <th>Ações</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($cursos as $curso): ?>
                <tr>
                  <td><?= htmlspecialchars($curso['id']) ?></td>
                  <td><?= htmlspecialchars($curso['nome']) ?></td>
                  <td><?= htmlspecialchars($curso['slug']) ?></td>
                  <td>
                    <?php if ($curso['ativo']): ?>
                      <span class="badge badge-ativo">Ativo</span>
                    <?php else: ?>
                      <span class="badge badge-inativo">Inativo</span>
                    <?php endif; ?>
                  </td>
                  <td>
                    <form method="POST" style="display:inline;">
                      <input type="hidden" name="toggle_id" value="<?= htmlspecialchars($curso['id']) ?>">
                      <button type="submit" class="btn-sm">
                        <?= $curso['ativo'] ? 'Desativar' : 'Ativar' ?>
                      </button>
                    </form>
                    <a href="editar_curso.php?id=<?= $curso['id'] ?>" class="btn-sm">Editar</a>
                    <a href="gerar_pagina.php?id=<?= $curso['id'] ?>" class="btn-sm">
                      Gerar Página
                    </a>

                    <!-- Espaço reservado para edição completa futuramente -->
                    <!-- <button class="btn-sm">Editar</button> -->
                  </td>
                </tr>
              <?php endforeach; ?>
              <?php if (empty($cursos)): ?>
                <tr>
                  <td colspan="5">Nenhum curso cadastrado.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

      </main>
    </div>
  </div>

</body>
</html>
