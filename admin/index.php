<?php
require __DIR__ . '/_inc.php';
require __DIR__ . '/_filtros.php';
requerir_login();
$pdo = db();

// Cambio rapido de estado desde la lista
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verificar_post();
    $id = (int) $_POST['id'];
    $estado = isset($ESTADOS[$_POST['estado']]) ? $_POST['estado'] : 'nuevo';
    $pdo->prepare('UPDATE prospectos SET estado = ? WHERE id = ?')->execute([$estado, $id]);
    redirigir('/admin/?' . $_SERVER['QUERY_STRING']);
}

list($f, $where, $params) = filtros_prospectos();
$porPagina = 50;
$pag = max(1, isset($_GET['p']) ? (int) $_GET['p'] : 1);

$st = $pdo->prepare('SELECT COUNT(*) FROM prospectos p ' . $where);
$st->execute($params);
$total = (int) $st->fetchColumn();
$paginas = max(1, (int) ceil($total / $porPagina));

$st = $pdo->prepare(SQL_PROSPECTOS . $where . ' ORDER BY p.creado DESC LIMIT ' . $porPagina . ' OFFSET ' . (($pag - 1) * $porPagina));
$st->execute($params);
$filas = $st->fetchAll();

$stats = $pdo->query("SELECT
    SUM(creado >= CURDATE()) hoy,
    SUM(creado >= CURDATE() - INTERVAL 7 DAY) semana,
    SUM(estado = 'nuevo') nuevos,
    COUNT(*) total FROM prospectos")->fetch();
$vendedores = $pdo->query('SELECT id, nombre FROM vendedores ORDER BY nombre')->fetchAll();
$eventos = $pdo->query('SELECT id, nombre FROM eventos ORDER BY creado DESC')->fetchAll();

$qs = function ($cambios) use ($f) {
    return '?' . http_build_query(array_filter(array_merge($f, $cambios), 'strlen'));
};

admin_header('Prospectos', 'prospectos');
?>
<div class="row g-3 mb-4">
  <div class="col-6 col-md-3"><div class="stat"><strong><?= (int) $stats['hoy'] ?></strong><span>Hoy</span></div></div>
  <div class="col-6 col-md-3"><div class="stat"><strong><?= (int) $stats['semana'] ?></strong><span>Últimos 7 días</span></div></div>
  <div class="col-6 col-md-3"><div class="stat"><strong><?= (int) $stats['nuevos'] ?></strong><span>Sin atender</span></div></div>
  <div class="col-6 col-md-3"><div class="stat"><strong><?= (int) $stats['total'] ?></strong><span>Total</span></div></div>
</div>

<div class="card mb-3">
  <div class="card-body">
    <form class="row g-2 align-items-end" method="get">
      <div class="col-md-3"><label class="form-label small">Buscar</label><input class="form-control form-control-sm" name="q" value="<?= e($f['q']) ?>" placeholder="Nombre, empresa, correo, teléfono"></div>
      <div class="col-6 col-md-1"><label class="form-label small">Origen</label>
        <select class="form-select form-select-sm" name="origen"><option value="">Todos</option><option value="web"<?= $f['origen'] === 'web' ? ' selected' : '' ?>>Web</option><option value="tarjeta"<?= $f['origen'] === 'tarjeta' ? ' selected' : '' ?>>QR</option></select></div>
      <div class="col-6 col-md-1"><label class="form-label small">Estado</label>
        <select class="form-select form-select-sm" name="estado"><option value="">Todos</option><?php foreach ($ESTADOS as $k => $es): ?><option value="<?= $k ?>"<?= $f['estado'] === $k ? ' selected' : '' ?>><?= $es[0] ?></option><?php endforeach; ?></select></div>
      <div class="col-6 col-md-2"><label class="form-label small">Vendedor</label>
        <select class="form-select form-select-sm" name="vendedor"><option value="">Todos</option><?php foreach ($vendedores as $v): ?><option value="<?= $v['id'] ?>"<?= $f['vendedor'] === (string) $v['id'] ? ' selected' : '' ?>><?= e($v['nombre']) ?></option><?php endforeach; ?></select></div>
      <div class="col-6 col-md-2"><label class="form-label small">Evento</label>
        <select class="form-select form-select-sm" name="evento"><option value="">Todos</option><?php foreach ($eventos as $ev): ?><option value="<?= $ev['id'] ?>"<?= $f['evento'] === (string) $ev['id'] ? ' selected' : '' ?>><?= e($ev['nombre']) ?></option><?php endforeach; ?></select></div>
      <div class="col-6 col-md-1"><label class="form-label small">Desde</label><input type="date" class="form-control form-control-sm" name="desde" value="<?= e($f['desde']) ?>"></div>
      <div class="col-6 col-md-1"><label class="form-label small">Hasta</label><input type="date" class="form-control form-control-sm" name="hasta" value="<?= e($f['hasta']) ?>"></div>
      <div class="col-md-1 d-flex gap-1"><button class="btn btn-cmc btn-sm flex-fill">Filtrar</button><a class="btn btn-light btn-sm" href="/admin/" title="Limpiar"><i class="bi bi-x-lg"></i></a></div>
    </form>
  </div>
</div>

<div class="d-flex justify-content-between align-items-center mb-2">
  <span class="small text-muted"><?= $total ?> prospecto<?= $total === 1 ? '' : 's' ?></span>
  <a class="btn btn-outline-success btn-sm" href="/admin/exportar.php<?= e($qs([])) ?>"><i class="bi bi-file-earmark-spreadsheet me-1"></i>Exportar a Excel</a>
</div>

<div class="card">
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead><tr><th>Fecha</th><th>Nombre</th><th>Contacto</th><th>Interés</th><th>Origen</th><th>Estado</th><th></th></tr></thead>
      <tbody>
      <?php foreach ($filas as $r): ?>
        <tr>
          <td class="text-nowrap small"><?= e(fecha_corta($r['creado'])) ?></td>
          <td><strong><?= e($r['nombre']) ?></strong><?php if ($r['empresa'] !== ''): ?><br><span class="small text-muted"><?= e($r['empresa']) ?></span><?php endif; ?></td>
          <td class="small">
            <?php if ($r['telefono'] !== ''): ?><a href="https://wa.me/<?= e(strlen(solo_digitos($r['telefono'])) === 10 ? '52' . solo_digitos($r['telefono']) : solo_digitos($r['telefono'])) ?>" target="_blank" title="WhatsApp"><i class="bi bi-whatsapp text-success"></i></a> <a href="tel:<?= e(solo_digitos($r['telefono'])) ?>"><?= e($r['telefono']) ?></a><br><?php endif; ?>
            <?php if ($r['email'] !== ''): ?><a href="mailto:<?= e($r['email']) ?>"><?= e($r['email']) ?></a><?php endif; ?>
          </td>
          <td class="small"><?= e(isset($INTERESES[$r['interes']]) ? $INTERESES[$r['interes']] : '') ?></td>
          <td class="small">
            <?php if ($r['origen'] === 'tarjeta'): ?>
              <span class="badge text-bg-dark"><i class="bi bi-qr-code"></i> QR</span>
              <div class="text-muted"><?= e($r['vendedor']) ?><?= $r['evento'] ? ' · ' . e($r['evento']) : '' ?></div>
            <?php else: ?>
              <span class="badge text-bg-light">Web</span> <span class="text-muted"><?= e($r['pagina']) ?></span>
            <?php endif; ?>
          </td>
          <td>
            <form method="post" class="m-0">
              <?= campo_csrf() ?><input type="hidden" name="id" value="<?= $r['id'] ?>">
              <select name="estado" class="form-select form-select-sm border-<?= $ESTADOS[$r['estado']][1] ?>" onchange="this.form.submit()">
                <?php foreach ($ESTADOS as $k => $es): ?><option value="<?= $k ?>"<?= $r['estado'] === $k ? ' selected' : '' ?>><?= $es[0] ?></option><?php endforeach; ?>
              </select>
            </form>
          </td>
          <td><a class="btn btn-light btn-sm" href="/admin/prospecto.php?id=<?= $r['id'] ?>" title="Ver detalle"><i class="bi bi-chevron-right"></i></a></td>
        </tr>
      <?php endforeach; ?>
      <?php if (!$filas): ?><tr><td colspan="7" class="text-center text-muted py-5">No hay prospectos con estos filtros.</td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php if ($paginas > 1): ?>
<nav class="mt-3"><ul class="pagination pagination-sm justify-content-center">
  <?php for ($i = 1; $i <= $paginas; $i++): ?>
    <li class="page-item<?= $i === $pag ? ' active' : '' ?>"><a class="page-link" href="<?= e($qs(['p' => $i])) ?>"><?= $i ?></a></li>
  <?php endfor; ?>
</ul></nav>
<?php endif; ?>
<?php admin_footer();
