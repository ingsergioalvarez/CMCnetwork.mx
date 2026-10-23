<?php
// Arranque comun: configuracion, BD, sesion y utilidades. Compatible con PHP 7.4.
define('CMC', true);
define('ROOT', dirname(__DIR__));

require __DIR__ . '/iconos.php';

mb_internal_encoding('UTF-8');
date_default_timezone_set('America/Mexico_City');

/** Ruta de un archivo con ?v=fecha de modificacion: al reemplazar una imagen el navegador no muestra la anterior. */
function con_version($ruta)
{
    $t = @filemtime(ROOT . $ruta);
    return $t ? $ruta . '?v=' . $t : $ruta;
}

function config($clave = null)
{
    static $cfg = null;
    if ($cfg === null) {
        $candidatos = [dirname(ROOT) . '/privado/config.php', ROOT . '/config/config.php'];
        foreach ($candidatos as $archivo) {
            if (is_file($archivo)) {
                $cfg = require $archivo;
                break;
            }
        }
        if ($cfg === null) {
            http_response_code(500);
            exit('Falta config.php');
        }
    }
    if ($clave === null) {
        return $cfg;
    }
    $valor = $cfg;
    foreach (explode('.', $clave) as $parte) {
        if (!is_array($valor) || !array_key_exists($parte, $valor)) {
            return null;
        }
        $valor = $valor[$parte];
    }
    return $valor;
}

ini_set('display_errors', config('debug') ? '1' : '0');
error_reporting(E_ALL);

function db()
{
    static $pdo = null;
    if ($pdo === null) {
        $c = config('db');
        $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4', $c['host'], $c['port'], $c['name']);
        $pdo = new PDO($dsn, $c['user'], $c['pass'], [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
        $pdo->exec("SET time_zone = '-06:00'");
    }
    return $pdo;
}

/** Escapa texto para HTML. */
function e($texto)
{
    return htmlspecialchars((string) $texto, ENT_QUOTES, 'UTF-8');
}

function es_https()
{
    return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');
}

function iniciar_sesion()
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }
    session_name('cmc_sid');
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'secure'   => es_https(),
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

function csrf_token()
{
    iniciar_sesion();
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_valido($token)
{
    iniciar_sesion();
    return is_string($token) && !empty($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $token);
}

/**
 * Token para formularios publicos, sin sesion: "timestamp.firma".
 * Tambien sirve de trampa de tiempo (los bots envian en menos de 3 s).
 */
function form_token()
{
    $t = (string) time();
    return $t . '.' . hash_hmac('sha256', $t, config('secret'));
}

function form_token_valido($token)
{
    if (!is_string($token) || strpos($token, '.') === false) {
        return false;
    }
    list($t, $firma) = explode('.', $token, 2);
    if (!ctype_digit($t) || !hash_equals(hash_hmac('sha256', $t, config('secret')), $firma)) {
        return false;
    }
    $edad = time() - (int) $t;
    return $edad >= 3 && $edad <= 86400;
}

function ip_cliente()
{
    return substr(isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '', 0, 45);
}

function ajuste($clave, $valor = null)
{
    if (func_num_args() === 1) {
        $st = db()->prepare('SELECT valor FROM ajustes WHERE clave = ?');
        $st->execute([$clave]);
        $v = $st->fetchColumn();
        return $v === false ? null : $v;
    }
    db()->prepare('REPLACE INTO ajustes (clave, valor) VALUES (?, ?)')->execute([$clave, $valor]);
}

function json_respuesta($datos, $codigo = 200)
{
    http_response_code($codigo);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($datos, JSON_UNESCAPED_UNICODE);
    exit;
}

/** Convierte "Juan Pérez" en "juan-perez". */
function slugify($texto)
{
    $texto = mb_strtolower(trim($texto));
    $texto = strtr($texto, ['á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u', 'ü' => 'u', 'ñ' => 'n']);
    $texto = preg_replace('/[^a-z0-9]+/', '-', $texto);
    return trim($texto, '-');
}

/** Solo digitos, para tel: y wa.me */
function solo_digitos($tel)
{
    return preg_replace('/\D+/', '', (string) $tel);
}
