<?php
require_once __DIR__ . "/lib.php";
require_login();

$alunosFile = __DIR__ . "/data/alunos.json";
$cursosFile = __DIR__ . "/data/cursos.json";

$alunos = read_json($alunosFile);
$cursos = read_json($cursosFile);

// Mapa slug → nome do curso
$cursosMap = [];
foreach ($cursos as $c) {
  $cursosMap[$c["slug"]] = $c["nome"];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Alunos – Painel | Instituto Raízes</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;700&family=Playfair+Display:wght@400;600&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">

  <!-- CSS do painel -->
  <link rel="stylesheet" href="style-admin.css">
</head>
<body>

<div class="admin-layout">

  <!-- SIDEBAR -->
  <aside class="admin-sidebar">
    <div class="brand">
      <img src="../assets/img/logo_raizes.png" alt="Instituto Raízes">
      <span>Raízes</span>
    </div>

    <ul class="admin-menu">
      <li><a href="dashboard.php">Cursos</a></li>
      <li><a href="alunos.php" class="active">Alunos</a></li>
      <li><a href="certificados.php">Certificados</a></li>
      <li><a href="logout.php">Sair</a></li>
    </ul>
  </aside>

  <!-- CONTEÚDO -->
  <div class="admin-main-wrapper">

    <!-- TOPBAR -->
    <header class="admin-topbar">
      <h1>Alunos Matriculados</h1>
      <div class="admin-user">
        Logado como: <strong><?= h($_SESSION["usuario"]) ?></strong>
      </div>
    </header>

    <!-- MAIN -->
    <main class="admin-main">

      <div class="card">

        <div style="display:flex; justify-content:space-between; align-items:center; gap:1rem; flex-wrap:wrap;">
          <h2>Lista de Alunos</h2>
          <a href="editar_aluno.php" class="btn-sm">+ Novo Aluno</a>
        </div>

        <table class="table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nome</th>
              <th>E-mail</th>
              <th>Curso</th>
              <th>Status</th>
              <th>Ações</th>
            </tr>
          </thead>

          <tbody>
          <?php foreach ($alunos as $a): ?>
            <tr>
              <td><?= h($a["id"]) ?></td>

              <td>
                <?= h($a["nome"]) ?>
              </td>

              <td style="font-size:0.85rem;color:#555;">
                <?= h($a["email"]) ?>
              </td>

              <td>
                <?= h($cursosMap[$a["curso_slug"]] ?? $a["curso_slug"]) ?>
              </td>

              <td>
                <?php if (!empty($a["concluido"])): ?>
                  <span class="badge badge-ativo">Concluído</span>
                <?php else: ?>
                  <span class="badge badge-inativo">Em andamento</span>
                <?php endif; ?>
              </td>

              <td>
                <a class="btn-sm" href="editar_aluno.php?id=<?= h($a["id"]) ?>">
                  Editar
                </a>
              </td>
            </tr>
          <?php endforeach; ?>

          <?php if (empty($alunos)): ?>
            <tr>
              <td colspan="6" style="text-align:center;color:#666;">
                Nenhum aluno cadastrado.
              </td>
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
