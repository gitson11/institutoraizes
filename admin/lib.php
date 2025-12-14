<?php
session_start();

function require_login() {
  if (!isset($_SESSION["usuario"])) {
    header("Location: index.php");
    exit;
  }
}

function read_json($file) {
  if (!file_exists($file)) return [];
  return json_decode(file_get_contents($file), true) ?? [];
}

function write_json($file, $data) {
  file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

function next_id($arr) {
  if (!$arr) return 1;
  return max(array_column($arr, "id")) + 1;
}

function gerar_codigo_certificado() {
  return "RAIZES-" . date("Y") . "-" . strtoupper(bin2hex(random_bytes(4)));
}

function h($v) {
  return htmlspecialchars($v, ENT_QUOTES, "UTF-8");
}
