<?php
// Base del panel: sesion, autenticacion, CSRF y plantilla.
require_once __DIR__ . '/../includes/bootstrap.php';
require_once ROOT . '/includes/datos.php';

iniciar_sesion();
header('X-Frame-Options: DENY');
header('X-Robots-Tag: noindex, nofollow');
header('Cache-Control: no-store');

$ESTADOS = [
    'nuevo'      => ['Nuevo', 'primary'],
    'contactado' => ['Contactado', 'info'],
    'cliente'    => ['Cliente', 'success'],
    'descartado' => ['Descartado', 'secondary'],
];

function usuario_actual()
{
    return isset($_SESSION['admin']) ? $_SESSION['admin'] : null;
}

function requerir_login()
{
    $u = usuario_actual();
    // Expira tras 8 h de inactividad
    if (!$u || time() - $_SESSION['admin_visto'] > 8 * 3600) {
        $_SESSION = [];
        header('Location: /admin/login.php');
        exit;
    }
    $_SESSION['admin_visto'] = time();
    return $u;
}

/** Valida CSRF en cualquier POST del panel. */
function verificar_post()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !csrf_valido(isset($_POST['csrf']) ? $_POST['csrf'] : '')) {
        http_response_code(400);
        exit('La sesión expiró. Regresa y recarga la página.');
    }
}

function campo_csrf()
{
    return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">';
}

function flash($mensaje = null, $tipo = 'success')
{
    if ($mensaje !== null) {
        $_SESSION['flash'] = [$mensaje, $tipo];
        return null;
    }
    $f = isset($_SESSION['flash']) ? $_SESSION['flash'] : null;
    unset($_SESSION['flash']);
    return $f;
}

function redirigir($url)
{
    header('Location: ' . $url);
    exit;
}

function fecha_corta($ts)
{
    return $ts ? date('d/m/Y H:i', strtotime($ts)) : '';
}

function admin_header($titulo, $activo = '')
{
    $u = usuario_actual();
    $menu = [
        'prospectos' => ['/admin/', 'bi-people', 'Prospectos'],
        'tarjeta'    => ['/admin/tarjeta.php', 'bi-qr-code', 'Tarjeta QR'],
        'vendedores' => ['/admin/vendedores.php', 'bi-person-badge', 'Vendedores'],
        'eventos'    => ['/admin/eventos.php', 'bi-calendar-event', 'Eventos'],
        'usuarios'   => ['/admin/usuarios.php', 'bi-shield-lock', 'Usuarios'],
    ];
    ?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($titulo) ?> | Panel CMC</title>
  <meta name="robots" content="noindex, nofollow">
  <link rel="icon" href="/imagenes/marca/favicon.ico">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="/css/marca.css">
  <link rel="stylesheet" href="/css/admin.css?v=1">
</head>
<body>
<?php if ($u): ?>
<nav class="navbar navbar-expand-lg navbar-dark admin-nav">
  <div class="container-fluid px-lg-4">
    <a class="navbar-brand" href="/admin/"><img src="/imagenes/marca/logo-cmc-blanco-web.png" alt="CMC" height="34"> <span class="ms-2 small opacity-75">Panel</span></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminMenu" aria-label="Menú"><span class="navbar-toggler-icon"></span></button>
    <div class="collapse navbar-collapse" id="adminMenu">
      <ul class="navbar-nav me-auto">
        <?php foreach ($menu as $k => $m): ?>
          <li class="nav-item"><a class="nav-link<?= $k === $activo ? ' active' : '' ?>" href="<?= $m[0] ?>"><i class="bi <?= $m[1] ?> me-1"></i><?= $m[2] ?></a></li>
        <?php endforeach; ?>
      </ul>
      <span class="navbar-text me-3 small"><i class="bi bi-person-circle me-1"></i><?= e($u['nombre'] ?: $u['usuario']) ?></span>
      <a class="btn btn-outline-light btn-sm" href="/admin/logout.php">Salir</a>
    </div>
  </div>
</nav>
<?php endif; ?>
<main class="container-fluid px-lg-4 py-4">
  <?php if ($f = flash()): ?>
    <div class="alert alert-<?= e($f[1]) ?> alert-dismissible fade show"><?= e($f[0]) ?><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button></div>
  <?php endif; ?>
    <?php
}

function admin_footer($scripts = '')
{
    ?>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?= $scripts ?>
</body>
</html>
    <?php
}
