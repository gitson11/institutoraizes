<?php
$certFile = __DIR__ . "/../admin/data/certificados.json";
$certs = [];
if (file_exists($certFile)) {
  $certs = json_decode(file_get_contents($certFile), true) ?: [];
}

$codigo = $_GET["codigo"] ?? "";
$cert = null;
foreach ($certs as $c) {
  if (($c["codigo"] ?? "") === $codigo) { $cert = $c; break; }
}
if (!$cert) { http_response_code(404); die("Certificado não encontrado."); }

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>CERTIFICADO DE <?= mb_strtoupper(htmlspecialchars($cert["aluno_nome"])) ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link
  href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;700&family=Playfair+Display:wght@400;600&family=Lato:wght@300;400;700&display=swap"
  rel="stylesheet"
  />
  <style>
    :root {
      --azul-profundo: #0e1a40;
      --dourado: #cfaf58;
      --vinho: #4a1f2a;
      --branco: #ffffff;
      --cinza: #d1d1d1;
      --azul-neutro: #465a7e;
      --texto: #222222;
    }

    body {
      margin:0;
      background: var(--cinza);
      font-family: "Lato", system-ui, -apple-system, BlinkMacSystemFont,
      "Segoe UI", sans-serif;
      color: var(--texto);
    }

    span{
      text-align: center;

    }

    .wrap {
      max-width:980px;
      margin:2rem auto;
      padding:1rem;
    }

    .cert {
      background: var(--branco);
      border:10px solid var(--dourado);
      padding:2.2rem 2rem;
      border-radius:14px;
      box-shadow:0 18px 40px rgba(0,0,0,.12);

      /* Tamanho exato A4 paisagem */
      width: 297mm;
      height: 210mm;

      box-sizing: border-box;
    }

    .top {
      display:flex;
      justify-content:space-between;
      align-items:center;
      gap:1rem;
    }

    .top img {
      height:120px;
      object-fit:contain;
    }

    .title {
      margin-top:1.4rem;
      text-align:center;
    }

    .title h1 {
      margin:0;
      font-size:4rem;
      color: var(--azul-profundo);
      letter-spacing:.06em;
      text-transform:uppercase;
      font-weight: 700 ;
    }

    .title p {
      margin:.4rem 0 0;
      color: var(--azul-neutro);
    }

    .main {
      margin-top:1.6rem;
      font-size:1.05rem;
      line-height:1.8;
      color: var(--texto);
    }

    .name {
      font-size:2.5rem;
      font-weight:800;
      color: var(--dourado);
      text-align:center;
      margin:1.2rem 0;
    }

    .meta {
      display:flex;
      flex-wrap:wrap;
      gap:1rem;
      justify-content:space-between;
      margin-top:100px;
      font-size:.95rem;
      color: var(--azul-neutro);
    }

    .code {
      margin-top:1rem;
      font-size:.9rem;
      color: var(--azul-neutro);
      text-align:center;
    }

    .actions {
      margin-top:1rem;
      display:flex;
      gap:.6rem;
      justify-content:center;
    }

    .btn {
      border:none;
      border-radius:999px;
      padding:.7rem 1.2rem;
      cursor:pointer;
      font-weight:800;
      letter-spacing:.08em;
      text-transform:uppercase;
    }

    .btn-print {
      background: var(--dourado);
      color:#1a1306;
    }

    .btn-validate {
      background: var(--branco);
      border:1px solid var(--cinza);
      color: var(--texto);
    }

    .sign {
      margin-top:2rem;
      display:flex;
      justify-content: space-evenly;
      gap:1rem;
      align-content: stretch;
    }

    .line {
      display: start;
      width:310px;
      border-top:1px solid var(--azul-neutro);
      padding-top:.4rem;
      font-size:.9rem;
      color: var(--azul-neutro);
      text-align:center;
    }

    .direita{
      margin-right: 20%;
    }

    .esquerda{
    }

    @media print {
      body { background: var(--branco); }
      .wrap { margin:0; max-width:none; padding:0; }
      .actions { display:none; }
      .cert { box-shadow:none; }
    }
</style>
</head>
<body>
  <div class="wrap">
    <div class="cert">
      <div class="top">
        <img src="../assets/img/logo_raizes.png" alt="Instituto Raízes">
        <div style="text-align:right;">
          <div style="font-weight:800;color:var(--azul); font-size: 1.5rem;">Instituto Raízes</div>
          <div style="font-size:.9rem;color:#444;">Formação Teológica</div>
        </div>
      </div>

      <div class="title">
        <h1 style="font-weight: 700 !important;"><strong>Certificado</strong></h1>
        <p>Certificamos para os devidos fins que </p>
      </div>

      <div class="main">
        <div class="name"><?= mb_strtoupper(htmlspecialchars($cert["aluno_nome"])) ?></div> 
        <p>concluiu com êxito o curso <strong><?= htmlspecialchars($cert["curso_nome"]) ?></strong>,
        com carga horária de <strong><?= htmlspecialchars($cert["carga_horaria"]) ?></strong></p>.
      </div>

      <div class="meta">
        <div><strong>Data de emissão:</strong> <?= htmlspecialchars($cert["data_emissao"]) ?></div>
        <div><strong>Conclusão:</strong> <?= htmlspecialchars($cert["data_conclusao"] ?: "—") ?></div>
      </div>
<br>
      <div class="sign">

        <div class="line direita">
          <?= htmlspecialchars($cert["coordenador"]) ?><br>
          Coordenação Geral
        </div>
        <div class="line esquerda">
          Instituto Raízes
        </div>
      </div>

      <div class="code">
        <strong>Código de validação:</strong> <?= htmlspecialchars($cert["codigo"]) ?>
      </div>

      <div class="actions">
        <button class="btn btn-print" onclick="window.print()">Imprimir / Salvar PDF</button>
        <a class="btn btn-validate" href="validar.php?codigo=<?= urlencode($cert["codigo"]) ?>">Validar</a>
      </div>
    </div>
  </div>
</body>
</html>
