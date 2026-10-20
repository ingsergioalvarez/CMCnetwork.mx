<?php
require __DIR__ . '/_inc.php';
requerir_login();
$pdo = db();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verificar_post();
    $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
    $accion = isset($_POST['accion']) ? $_POST['accion'] : 'guardar';

    if ($accion === 'activar_tarjeta') {
        ajuste('tarjeta_evento_id', (string) $id);
        flash($id ? 'Los prospectos del QR se registrarán en este evento.' : 'La tarjeta ya no está ligada a ningún evento.');
        redirigir('/admin/eventos.php');
    }
    if ($accion === 'eliminar') {
        $pdo->prepare('DELETE FROM eventos WHERE id = ?')->execute([$id]);
        if ((int) ajuste('tarjeta_evento_id') === $id) {
            ajuste('tarjeta_evento_id', '0');
        }
        flash('Evento eliminado. Sus prospectos se conservan sin evento.');
        redirigir('/admin/eventos.php');
    }

    $nombre = mb_substr(trim((string) $_POST['nombre']), 0, 150);
    $lugar = mb_substr(trim((string) $_POST['lugar']), 0, 150);
    $fecha = function ($v) {
        return preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) $v) ? $v : null;
    };
    $ini = $fecha($_POST['fecha_inicio']);
    $fin = $fecha($_POST['fecha_fin']);
    if (mb_strlen($nombre) < 2) {
        flash('Escribe el nombre del evento.', 'danger');
        redirigir('/admin/eventos.php?' . ($id ? 'id=' . $id : 'nuevo=1'));
    }
    $slug = slugify($nombre . ($ini ? '-' . substr($ini, 0, 4) : ''));
    $st = $pdo->prepare('SELECT id FROM eventos WHERE slug = ? AND id <> ?');
    $st->execute([$slug, $id]);
    if ($st->fetch()) {
        $slug .= '-' . substr(bin2hex(random_bytes(2)), 0, 3);
    }
    if ($id) {
        $pdo->prepare('UPDATE eventos SET slug = ?, nombre = ?, lugar = ?, fecha_inicio = ?, fecha_fin = ? WHERE id = ?')->execute([$slug, $nombre, $lugar, $ini, $fin, $id]);
    } else {
        $pdo->prepare('INSERT INTO eventos (slug, nombre, lugar, fecha_inicio, fecha_fin) VALUES (?, ?, ?, ?, ?)')->execute([$slug, $nombre, $lugar, $ini, $fin]);
        $id = (int) $pdo->lastInsertId();
        if (!empty($_POST['activar'])) {
            ajuste('tarjeta_evento_id', (string) $id);
        }
    }
    flash('Evento guardado.');
    redirigir('/admin/eventos.php');
}

$activoTarjeta = (int) ajuste('tarjeta_evento_id');
$editar = null;
if (isset($_GET['id'])) {
    $st = $pdo->prepare('SELECT * FROM eventos WHERE id = ?');
    $st->execute([(int) $_GET['id']]);
    $editar = $st->fetch();
}
$mostrarForm = $editar || isset($_GET['nuevo']);
$lista = $pdo->query('SELECT e.*, (SELECT COUNT(*) FROM prospectos p WHERE p.evento_id = e.id) prospectos
    FROM eventos e ORDER BY COALESCE(e.fecha_inicio, DATE(e.creado)) DESC')->fetchAll();

admin_header('Eventos', 'eventos');
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h4 m-0">Eventos</h1>
  <?php if (!$mostrarForm): ?><a href="?nuevo=1" class="btn btn-cmc btn-sm"><i class="bi bi-plus-lg me-1"></i>Nuevo evento</a><?php endif; ?>
</div>

<?php if ($mostrarForm): $x = $editar ?: ['id' => 0, 'nombre' => '', 'lugar' => '', 'fecha_inicio' => '', 'fecha_fin' => '']; ?>
<div class="card mb-4"><div class="card-body p-4">
  <h2 class="h5 mb-3"><?= $x['id'] ? 'Editar evento' : 'Nuevo evento' ?></h2>
  <form method="post" class="row g-3">
    <?= campo_csrf() ?><input type="hidden" name="id" value="<?= (int) $x['id'] ?>">
    <div class="col-md-6"><label class="form-label">Nombre del evento *</label><input class="form-control" name="nombre" required value="<?= e($x['nombre']) ?>" placeholder="Expo Telecom 2026"></div>
    <div class="col-md-6"><label class="form-label">Lugar</label><input class="form-control" name="lugar" value="<?= e($x['lugar']) ?>" placeholder="Centro Citibanamex, CDMX"></div>
    <div class="col-md-3"><label class="form-label">Inicio</label><input class="form-control" type="date" name="fecha_inicio" value="<?= e($x['fecha_inicio']) ?>"></div>
    <div class="col-md-3"><label class="form-label">Fin</label><input class="form-control" type="date" name="fecha_fin" value="<?= e($x['fecha_fin']) ?>"></div>
    <?php if (!$x['id']): ?><div class="col-md-6 d-flex align-items-end"><label class="form-check"><input type="checkbox" class="form-check-input" name="activar" value="1" checked> <span class="form-check-label">Usarlo ya en la tarjeta del QR</span></label></div><?php endif; ?>
    <div class="col-12 d-flex gap-2"><button class="btn btn-cmc">Guardar</button><a href="/admin/eventos.php" class="btn btn-light">Cancelar</a></div>
  </form>
</div></div>
<?php endif; ?>

<div class="card"><div class="table-responsive">
  <table class="table align-middle mb-0">
    <thead><tr><th>Evento</th><th>Fechas</th><th>Prospectos</th><th>Tarjeta QR</th><th class="text-end">Acciones</th></tr></thead>
    <tbody>
    <?php foreach ($lista as $ev): $esActivo = (int) $ev['id'] === $activoTarjeta; ?>
      <tr>
        <td><strong><?= e($ev['nombre']) ?></strong><br><span class="small text-muted"><?= e($ev['lugar']) ?></span></td>
        <td class="small"><?= $ev['fecha_inicio'] ? e(date('d/m/Y', strtotime($ev['fecha_inicio']))) : '' ?><?= $ev['fecha_fin'] && $ev['fecha_fin'] !== $ev['fecha_inicio'] ? ' – ' . e(date('d/m/Y', strtotime($ev['fecha_fin']))) : '' ?></td>
        <td><a href="/admin/?evento=<?= $ev['id'] ?>"><?= (int) $ev['prospectos'] ?></a></td>
        <td>
          <form method="post" class="m-0"><?= campo_csrf() ?>
            <?php if ($esActivo): ?>
              <span class="badge text-bg-info me-1"><i class="bi bi-check-circle"></i> Activo</span>
              <input type="hidden" name="id" value="0"><button name="accion" value="activar_tarjeta" class="btn btn-link btn-sm p-0">quitar</button>
            <?php else: ?>
              <input type="hidden" name="id" value="<?= $ev['id'] ?>"><button name="accion" value="activar_tarjeta" class="btn btn-outline-primary btn-sm">Activar</button>
            <?php endif; ?>
          </form>
        </td>
        <td class="text-end text-nowrap">
          <a href="?id=<?= $ev['id'] ?>" class="btn btn-light btn-sm" title="Editar"><i class="bi bi-pencil"></i></a>
          <form method="post" class="d-inline" onsubmit="return confirm('¿Eliminar este evento?')"><?= campo_csrf() ?><input type="hidden" name="id" value="<?= $ev['id'] ?>"><button name="accion" value="eliminar" class="btn btn-light btn-sm text-danger" title="Eliminar"><i class="bi bi-trash"></i></button></form>
        </td>
      </tr>
    <?php endforeach; ?>
    <?php if (!$lista): ?><tr><td colspan="5" class="text-center text-muted py-5">Aún no hay eventos.</td></tr><?php endif; ?>
    </tbody>
  </table>
</div></div>
<?php admin_footer();
