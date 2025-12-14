<?php
require_once "lib.php";
require_login();

$alunos = read_json("data/alunos.json");
$cursos = read_json("data/cursos.json");
$certs  = read_json("data/certificados.json");

$id = $_GET["aluno_id"];
$aluno = array_values(array_filter($alunos, fn($a)=>$a["id"]==$id))[0];

if (!$aluno["concluido"]) die("Aluno não concluiu.");

$curso = array_values(array_filter($cursos, fn($c)=>$c["slug"]==$aluno["curso_slug"]))[0];

$cert = [
  "codigo" => gerar_codigo_certificado(),
  "aluno_id" => $aluno["id"],
  "aluno_nome" => $aluno["nome"],
  "aluno_email" => $aluno["email"],
  "curso_slug" => $curso["slug"],
  "curso_nome" => $curso["nome"],
  "carga_horaria" => $curso["carga_horaria"],
  "data_conclusao" => $aluno["data_conclusao"],
  "data_emissao" => date("d/m/Y"),
  "coordenador" => "Rubem Gitison Rodrigues Alves"
];

$certs[] = $cert;
write_json("data/certificados.json", $certs);

header("Location: ../certificados/certificado.php?codigo=".$cert["codigo"]);
