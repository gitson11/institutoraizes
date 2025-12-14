<?php
$certFile = __DIR__ . "/../admin/data/certificados.json";
$certs = file_exists($certFile) ? (json_decode(file_get_contents($certFile), true) ?: []) : [];

$codigo = $_GET["codigo"] ?? "";
$cert = null;
foreach ($certs as $c) {
  if (($c["codigo"] ?? "") === $codigo) { $cert = $c; break; }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Validar Certificado – Instituto Raízes</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body{font-family:Arial,sans-serif;background:#f5f5f5;margin:0;padding:2rem;}
    .box{max-width:680px;margin:auto;background:#fff;border-radius:12px;padding:1.4rem 1.2rem;box-shadow:0 10px 24px rgba(0,0,0,.08);}
    h1{margin:0 0 .6rem;}
    .ok{background:#e7f5e8;border:1px solid #7cc486;padding:.8rem;border-radius:10px;}
    .bad{background:#ffe3e3;border:1px solid #f3b4b4;padding:.8rem;border-radius:10px;}
    .meta{margin-top:.6rem;color:#333;line-height:1.7;}
    .code{font-family:monospace;}
  </style>
</head>
<body>
  <div class="box">
    <h1>Validação de Certificado</h1>
    <?php if ($cert): ?>
      <div class="ok">
        ✅ Certificado válido
        <div class="meta">
          <div><strong>Aluno:</strong> <?= htmlspecialchars($cert["aluno_nome"]) ?></div>
          <div><strong>Curso:</strong> <?= htmlspecialchars($cert["curso_nome"]) ?></div>
          <div><strong>Emissão:</strong> <?= htmlspecialchars($cert["data_emissao"]) ?></div>
          <div><strong>Código:</strong> <span class="code"><?= htmlspecialchars($cert["codigo"]) ?></span></div>
        </div>
      </div>
    <?php else: ?>
      <div class="bad">
        ❌ Certificado não encontrado ou código inválido.
        <div class="meta"><strong>Código:</strong> <span class="code"><?= htmlspecialchars($codigo) ?></span></div>
      </div>
    <?php endif; ?>
  </div>
</body>
</html>
