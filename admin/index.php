<?php
session_start();

// Se já estiver logado, manda pro dashboard
if (isset($_SESSION['usuario'])) {
    header("Location: dashboard.php");
    exit;
}

$erro = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'] ?? '';
    $senha   = $_POST['senha'] ?? '';

    // CREDENCIAIS SIMPLES (PODE ALTERAR AQUI)
    $USUARIO_CORRETO = "admin";
    $SENHA_CORRETA   = "raizes123";

    if ($usuario === $USUARIO_CORRETO && $senha === $SENHA_CORRETA) {
        $_SESSION['usuario'] = $usuario;
        header("Location: dashboard.php");
        exit;
    } else {
        $erro = "Usuário ou senha inválidos.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Login – Painel Administrativo | Instituto Raízes</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;700&family=Playfair+Display:wght@400;600&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="style-admin.css">
</head>
<body class="login-body">

  <div class="login-container">
    <div class="login-card">
      <div class="login-logo">
        <img src="../assets/img/logo_raizes.png" alt="Instituto Raízes">
        <span>Instituto Raízes</span>
      </div>

      <h1>Painel Administrativo</h1>
      <p class="login-subtitle">Acesse com suas credenciais para gerenciar os cursos.</p>

      <?php if ($erro): ?>
        <div class="alert alert-error"><?= htmlspecialchars($erro) ?></div>
      <?php endif; ?>

      <form method="POST" class="login-form">
        <label for="usuario">Usuário</label>
        <input type="text" name="usuario" id="usuario" required>

        <label for="senha">Senha</label>
        <input type="password" name="senha" id="senha" required>

        <button type="submit" class="btn-primary-full">Entrar</button>
      </form>
    </div>
  </div>

</body>
</html>
