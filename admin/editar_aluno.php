<?php
require_once __DIR__ . "/lib.php";
require_login();

$alunosFile = __DIR__ . "/data/alunos.json";
$cursosFile = __DIR__ . "/data/cursos.json";

$alunos = read_json($alunosFile);
$cursos = read_json($cursosFile);

$id = $_GET["id"] ?? null;
$edit = null;

// Busca aluno para edição
foreach ($alunos as $a) {
  if ((string)$a["id"] === (string)$id) {
    $edit = $a;
    break;
  }
}

// Salvar
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $nome = trim($_POST["nome"] ?? "");
  $email = trim($_POST["email"] ?? "");
  $curso_slug = trim($_POST["curso_slug"] ?? "");
  $concluido = isset($_POST["concluido"]);
  $data_conclusao = trim($_POST["data_conclusao"] ?? "");

  if ($id) {
    foreach ($alunos as &$a) {
      if ((string)$a["id"] === (string)$id) {
        $a["nome"] = $nome;
        $a["email"] = $email;
        $a["curso_slug"] = $curso_slug;
        $a["concluido"] = $concluido;
        $a["data_conclusao"] = $data_conclusao;
        break;
      }
    }
  } else {
    $alunos[] = [
      "id" => next_id($alunos),
      "nome" => $nome,
      "email" => $email,
      "curso_slug" => $curso_slug,
      "concluido" => $concluido,
      "data_conclusao" => $data_conclusao
    ];
  }

  write_json($alunosFile, $alunos);
  header("Location: alunos.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title><?= $id ? "Editar Aluno" : "Novo Aluno" ?> – Painel | Instituto Raízes</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;700&family=Playfair+Display:wght@400;600&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">

  <!-- CSS do painel -->
  <link rel="stylesheet" href="style-admin.css">

  <style>
    .form-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1.2rem;
      margin-top: 0.8rem;
    }

    @media (max-width: 900px) {
      .form-grid {
        grid-template-columns: 1fr;
      }
    }

    .form-group label {
      display: block;
      font-size: 0.85rem;
      margin-bottom: 0.25rem;
      color: #333;
    }

    .form-group input,
    .form-group select {
      width: 100%;
      padding: 0.45rem 0.55rem;
      font-size: 0.9rem;
      border-radius: 0.45rem;
      border: 1px solid #ccc;
    }

    .form-check {
      margin-top: 0.8rem;
      font-size: 0.9rem;
    }

    .form-actions {
      margin-top: 1.4rem;
      display: flex;
      gap: 0.8rem;
      align-items: center;
    }

    .btn-primary {
      background: var(--dourado);
      color: #1a1306;
      border: none;
      padding: 0.6rem 1.6rem;
      border-radius: 999px;
      font-weight: 800;
      text-transform: uppercase;
      font-size: 0.85rem;
      letter-spacing: 0.1em;
      cursor: pointer;
    }

    .btn-link {
      font-size: 0.85rem;
      color: var(--azul-profundo);
      text-decoration: underline;
    }
  </style>
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
      <h1><?= $id ? "Editar Aluno" : "Novo Aluno" ?></h1>
      <div class="admin-user">
        Logado como: <strong><?= h($_SESSION["usuario"]) ?></strong>
      </div>
    </header>

    <!-- MAIN -->
    <main class="admin-main">

      <div class="card">
        <h2>Dados do Aluno</h2>

        <form method="POST">

          <div class="form-grid">
            <div class="form-group">
              <label>Nome completo</label>
              <input name="nome" required value="<?= h($edit["nome"] ?? "") ?>">
            </div>

            <div class="form-group">
              <label>E-mail</label>
              <input type="email" name="email" required value="<?= h($edit["email"] ?? "") ?>">
            </div>
          </div>

          <div class="form-grid">
            <div class="form-group">
              <label>Curso</label>
              <select name="curso_slug" required>
                <option value="">Selecione...</option>
                <?php foreach ($cursos as $c): ?>
                  <option value="<?= h($c["slug"]) ?>"
                    <?= (($edit["curso_slug"] ?? "") === $c["slug"]) ? "selected" : "" ?>>
                    <?= h($c["nome"]) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="form-group">
              <label>Data de conclusão</label>
              <input name="data_conclusao" placeholder="dd/mm/aaaa"
                     value="<?= h($edit["data_conclusao"] ?? "") ?>">
            </div>
          </div>

          <div class="form-check">
            <label>
              <input type="checkbox" name="concluido"
                <?= !empty($edit["concluido"]) ? "checked" : "" ?>>
              Curso concluído
            </label>
          </div>

          <div class="form-actions">
            <a href="alunos.php" class="btn-link">← Voltar</a>
            <button type="submit" class="btn-primary">Salvar</button>
          </div>

        </form>
      </div>

    </main>
  </div>
</div>

</body>
</html>
