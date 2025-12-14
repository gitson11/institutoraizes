<?php
require_once __DIR__ . "/lib.php";
require_login();

$alunosFile = __DIR__ . "/data/alunos.json";
$cursosFile = __DIR__ . "/data/cursos.json";
$certFile  = __DIR__ . "/data/certificados.json";

$alunos = read_json($alunosFile);
$cursos = read_json($cursosFile);
$certs  = read_json($certFile);

$cursosMap = [];
foreach ($cursos as $c) $cursosMap[$c["slug"]] = $c;

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Certificados – Painel | Instituto Raízes</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;700&family=Playfair+Display:wght@400;600&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style-admin.css">
</head>
<body>

<div class="admin-layout">
  <aside class="admin-sidebar">
    <div class="brand"><img src="../assets/img/logo_raizes.png" alt=""><span>Raízes</span></div>
    <ul class="admin-menu">
      <li><a href="dashboard.php">Cursos</a></li>
      <li><a href="alunos.php">Alunos</a></li>
      <li><a href="certificados.php" class="active">Certificados</a></li>
      <li><a href="logout.php">Sair</a></li>
    </ul>
  </aside>

  <div class="admin-main-wrapper">
    <header class="admin-topbar">
      <h1>Certificados</h1>
      <div class="admin-user">Logado como: <strong><?= h($_SESSION["usuario"]) ?></strong></div>
    </header>

    <main class="admin-main">

      <div class="card">
        <h2>Emitir certificado (alunos concluídos)</h2>

        <table class="table">
          <thead>
            <tr>
              <th>Aluno</th>
              <th>Curso</th>
              <th>Concluiu</th>
              <th>Ação</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach ($alunos as $a): 
            $cursoNome = $cursosMap[$a["curso_slug"]]["nome"] ?? $a["curso_slug"];
            $ok = !empty($a["concluido"]);
          ?>
            <tr>
              <td><?= h($a["nome"]) ?> <div style="font-size:.8rem;color:#666;"><?= h($a["email"]) ?></div></td>
              <td><?= h($cursoNome) ?></td>
              <td><?= $ok ? '<span class="badge badge-ativo">Sim</span>' : '<span class="badge badge-inativo">Não</span>' ?></td>
              <td>
                <?php if ($ok): ?>
                  <a class="btn-sm" href="emitir_certificado.php?aluno_id=<?= h($a["id"]) ?>">Emitir</a>
                <?php else: ?>
                  <span style="color:#777;font-size:.85rem;">Indisponível</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
          <?php if (empty($alunos)): ?>
            <tr><td colspan="4">Nenhum aluno cadastrado.</td></tr>
          <?php endif; ?>
          </tbody>
        </table>
      </div>

      <div class="card">
        <h2>Certificados emitidos</h2>
        <table class="table">
          <thead>
            <tr>
              <th>Código</th>
              <th>Aluno</th>
              <th>Curso</th>
              <th>Data</th>
              <th>Link</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach ($certs as $c): ?>
            <tr>
              <td><strong><?= h($c["codigo"]) ?></strong></td>
              <td><?= h($c["aluno_nome"]) ?></td>
              <td><?= h($c["curso_nome"]) ?></td>
              <td><?= h($c["data_emissao"]) ?></td>
              <td>
                <a class="btn-sm" target="_blank" href="../certificados/certificado.php?codigo=<?= h($c["codigo"]) ?>">Abrir</a>
                <a class="btn-sm" target="_blank" href="../certificados/validar.php?codigo=<?= h($c["codigo"]) ?>">Validar</a>
              </td>
            </tr>
          <?php endforeach; ?>
          <?php if (empty($certs)): ?>
            <tr><td colspan="5">Nenhum certificado emitido ainda.</td></tr>
          <?php endif; ?>
          </tbody>
        </table>
      </div>

    </main>
  </div>
</div>

</body>
</html>
