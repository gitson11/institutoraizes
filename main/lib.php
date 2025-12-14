<?php
function require_login() {
  session_start();
  if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
  }
}

function read_json($path) {
  if (!file_exists($path)) return [];
  $raw = file_get_contents($path);
  $data = json_decode($raw, true);
  return is_array($data) ? $data : [];
}

function write_json($path, $data) {
  file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

function next_id($items) {
  if (empty($items)) return 1;
  $ids = array_map(fn($x) => (int)($x['id'] ?? 0), $items);
  return max($ids) + 1;
}

function h($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

function now_date_br() {
  return date("d/m/Y");
}

function random_code($prefix="CERT") {
  // Ex: CERT-2026-AB12CD34
  $year = date("Y");
  $rand = strtoupper(bin2hex(random_bytes(4)));
  return "{$prefix}-{$year}-{$rand}";
}
