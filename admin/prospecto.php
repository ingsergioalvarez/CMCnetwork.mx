<?php
require __DIR__ . '/_inc.php';
require __DIR__ . '/_filtros.php';
requerir_login();
$pdo = db();
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verificar_post();
    if (isset($_POST['eliminar'])) {
        $pdo->prepare('DELETE FROM prospectos WHERE id = ?')->execute([$id]);
        flash('Prospecto eliminado.');
        redirigir('/admin/');
    }
    $estado = isset($ESTADOS[$_POST['estado']]) ? $_POST['estado'] : 'nuevo';
    $notas = mb_substr(trim((string) $_POST['notas']), 0, 5000);
    $pdo->prepare('UPDATE prospectos SET estado = ?, notas = ? WHERE id = ?')->execute([$estado, $notas, $id]);
    flash('Cambios guardados.');
    redirigir('/admin/prospecto.php?id=' . $id);
}

$st = $pdo->prepare(SQL_PROSPECTOS . 'WHERE p.id = ?');
$st->execute([$id]);
$p = $st->fetch();
if (!$p) {
    flash('Ese prospecto no existe.', 'warning');
    redirigir('/admin/');
}

admin_header($p['nombre'], 'prospectos');
$tel = solo_digitos($p['telefono']);
?>
<a href="/admin/" class="small"><i class="bi bi-arrow-left"></i> Prospectos</a>
<div class="row g-4 mt-1">
  <div class="col-lg-7">
    <div class="card"><div class="card-body p-4">
      <h1 class="h4 mb-1"><?= e($p['nombre']) ?></h1>
      <p class="text-muted"><?= e($p['empresa']) ?></p>
      <dl class="row mb-0">
        <dt class="col-sm-3">Teléfono</dt><dd class="col-sm-9"><?= e($p['telefono']) ?>
          <?php if ($tel): ?><a class="btn btn-success btn-sm ms-2" target="_blank" href="https://wa.me/<?= e(strlen($tel) === 10 ? '52' . $tel : $tel) ?>"><i class="bi bi-whatsapp"></i> WhatsApp</a>
          <a class="btn btn-light btn-sm" href="tel:<?= e($tel) ?>"><i class="bi bi-telephone"></i> Llamar</a><?php endif; ?></dd>
        <dt class="col-sm-3">Correo</dt><dd class="col-sm-9"><?php if ($p['email']): ?><a href="mailto:<?= e($p['email']) ?>"><?= e($p['email']) ?></a><?php endif; ?></dd>
        <dt class="col-sm-3">Interés</dt><dd class="col-sm-9"><?= e(interes_texto($p['interes']) ?: '—') ?></dd>
        <dt class="col-sm-3">Mensaje</dt><dd class="col-sm-9"><?= nl2br(e($p['mensaje'])) ?: '—' ?></dd>
        <dt class="col-sm-3">Origen</dt><dd class="col-sm-9"><?= $p['origen'] === 'tarjeta' ? 'Tarjeta QR' : 'Sitio web · ' . e($p['pagina']) ?></dd>
        <?php if ($p['vendedor'] || $p['evento']): ?>
          <dt class="col-sm-3">Vendedor</dt><dd class="col-sm-9"><?= e($p['vendedor'] ?: '—') ?></dd>
          <dt class="col-sm-3">Evento</dt><dd class="col-sm-9"><?= e($p['evento'] ?: '—') ?></dd>
        <?php endif; ?>
        <dt class="col-sm-3">Recibido</dt><dd class="col-sm-9"><?= e(fecha_corta($p['creado'])) ?> <?= $p['notificado'] ? '<span class="badge text-bg-light">aviso enviado</span>' : '<span class="badge text-bg-warning">aviso no enviado</span>' ?></dd>
      </dl>
    </div></div>
  </div>
  <div class="col-lg-5">
    <div class="card"><div class="card-body p-4">
      <form method="post">
        <?= campo_csrf() ?>
        <label class="form-label">Estado</label>
        <select name="estado" class="form-select mb-3"><?php foreach ($ESTADOS as $k => $es): ?><option value="<?= $k ?>"<?= $p['estado'] === $k ? ' selected' : '' ?>><?= $es[0] ?></option><?php endforeach; ?></select>
        <label class="form-label">Notas internas</label>
        <textarea name="notas" class="form-control mb-3" rows="6" placeholder="Seguimiento, cotización enviada, etc."><?= e($p['notas']) ?></textarea>
        <button class="btn btn-cmc">Guardar</button>
      </form>
      <hr>
      <form method="post" onsubmit="return confirm('¿Eliminar este prospecto? No se puede deshacer.')">
        <?= campo_csrf() ?><button name="eliminar" value="1" class="btn btn-link text-danger p-0 small">Eliminar prospecto</button>
      </form>
    </div></div>
  </div>
</div>
<?php admin_footer();
