<?php
// Control de la tarjeta del QR: quien aparece, en que evento, y el QR para imprimir.
require __DIR__ . '/_inc.php';
requerir_login();
$pdo = db();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verificar_post();
    ajuste('tarjeta_vendedor_id', (string) (int) $_POST['vendedor']);
    ajuste('tarjeta_evento_id', (string) (int) $_POST['evento']);
    flash('Tarjeta actualizada. El QR de la lona ya muestra los nuevos datos.');
    redirigir('/admin/tarjeta.php');
}

$vid = (int) ajuste('tarjeta_vendedor_id');
$eid = (int) ajuste('tarjeta_evento_id');
$vendedores = $pdo->query('SELECT id, slug, nombre, puesto, foto_tipo FROM vendedores WHERE activo = 1 ORDER BY nombre')->fetchAll();
$eventos = $pdo->query('SELECT id, nombre, fecha_inicio FROM eventos ORDER BY COALESCE(fecha_inicio, DATE(creado)) DESC LIMIT 50')->fetchAll();
$urlTarjeta = rtrim(config('url'), '/') . '/tarjeta/';

admin_header('Tarjeta QR', 'tarjeta');
?>
<h1 class="h4 mb-3">Tarjeta del QR</h1>
<div class="row g-4">
  <div class="col-lg-6">
    <div class="card"><div class="card-body p-4">
      <h2 class="h6 text-uppercase text-muted mb-3">¿Quién atiende hoy la lona?</h2>
      <form method="post">
        <?= campo_csrf() ?>
        <label class="form-label">Vendedor que aparece en la tarjeta</label>
        <select name="vendedor" class="form-select mb-3">
          <option value="0">— Datos generales de Ventas CMC —</option>
          <?php foreach ($vendedores as $v): ?><option value="<?= $v['id'] ?>"<?= $v['id'] == $vid ? ' selected' : '' ?>><?= e($v['nombre']) ?> · <?= e($v['puesto']) ?></option><?php endforeach; ?>
        </select>
        <label class="form-label">Evento donde se registran los prospectos</label>
        <select name="evento" class="form-select mb-4">
          <option value="0">— Sin evento —</option>
          <?php foreach ($eventos as $ev): ?><option value="<?= $ev['id'] ?>"<?= $ev['id'] == $eid ? ' selected' : '' ?>><?= e($ev['nombre']) ?><?= $ev['fecha_inicio'] ? ' · ' . e(date('d/m/Y', strtotime($ev['fecha_inicio']))) : '' ?></option><?php endforeach; ?>
        </select>
        <div class="d-flex flex-wrap gap-2">
          <button class="btn btn-cmc">Guardar cambios</button>
          <a href="/tarjeta/" target="_blank" class="btn btn-light"><i class="bi bi-phone me-1"></i>Ver la tarjeta</a>
        </div>
      </form>
      <p class="small text-muted mt-4 mb-0">
        <a href="/admin/vendedores.php?nuevo=1">+ Nuevo vendedor</a> &nbsp;·&nbsp; <a href="/admin/eventos.php?nuevo=1">+ Nuevo evento</a>
      </p>
    </div></div>
  </div>

  <div class="col-lg-6">
    <div class="card"><div class="card-body p-4 text-center">
      <h2 class="h6 text-uppercase text-muted mb-3">QR de la lona (no cambia nunca)</h2>
      <div class="qr-caja" id="qr" data-url="<?= e($urlTarjeta) ?>"></div>
      <p class="mt-3 mb-2"><code><?= e($urlTarjeta) ?></code></p>
      <div class="d-flex justify-content-center gap-2">
        <button class="btn btn-outline-primary btn-sm js-descargar" data-url="<?= e($urlTarjeta) ?>" data-nombre="qr-cmc-lona" data-formato="svg"><i class="bi bi-download me-1"></i>SVG (imprenta)</button>
        <button class="btn btn-outline-primary btn-sm js-descargar" data-url="<?= e($urlTarjeta) ?>" data-nombre="qr-cmc-lona" data-formato="png"><i class="bi bi-download me-1"></i>PNG</button>
      </div>
      <p class="small text-muted mt-3 mb-0">Para la lona usa el SVG: se puede ampliar a cualquier tamaño sin perder calidad.</p>
    </div></div>
  </div>

  <?php if ($vendedores): ?>
  <div class="col-12">
    <div class="card"><div class="card-body p-4">
      <h2 class="h6 text-uppercase text-muted mb-1">QR personal por vendedor</h2>
      <p class="small text-muted">Para tarjetas de presentación: siempre muestran a ese vendedor, sin importar quién esté en la lona.</p>
      <div class="table-responsive"><table class="table align-middle mb-0">
        <?php foreach ($vendedores as $v): $u = $urlTarjeta . '?v=' . rawurlencode($v['slug']); ?>
          <tr>
            <td><strong><?= e($v['nombre']) ?></strong></td>
            <td class="small"><a href="<?= e($u) ?>" target="_blank"><?= e($u) ?></a></td>
            <td class="text-end text-nowrap">
              <button class="btn btn-light btn-sm js-descargar" data-url="<?= e($u) ?>" data-nombre="qr-<?= e($v['slug']) ?>" data-formato="svg">SVG</button>
              <button class="btn btn-light btn-sm js-descargar" data-url="<?= e($u) ?>" data-nombre="qr-<?= e($v['slug']) ?>" data-formato="png">PNG</button>
            </td>
          </tr>
        <?php endforeach; ?>
      </table></div>
    </div></div>
  </div>
  <?php endif; ?>
</div>
<?php
admin_footer(<<<'HTML'
<script src="https://cdn.jsdelivr.net/npm/qrcode-generator@1.4.4/qrcode.js"></script>
<script>
(function () {
  function qr(url) { var q = qrcode(0, 'H'); q.addData(url); q.make(); return q; }
  function svg(url) { return qr(url).createSvgTag({ cellSize: 10, margin: 4, scalable: true }); }
  function bajar(blob, nombre) {
    var a = document.createElement('a');
    a.href = URL.createObjectURL(blob); a.download = nombre; document.body.appendChild(a); a.click();
    setTimeout(function () { URL.revokeObjectURL(a.href); a.remove(); }, 500);
  }
  var caja = document.getElementById('qr');
  caja.innerHTML = svg(caja.dataset.url);

  document.querySelectorAll('.js-descargar').forEach(function (b) {
    b.addEventListener('click', function () {
      var s = svg(b.dataset.url);
      if (b.dataset.formato === 'svg') return bajar(new Blob([s], { type: 'image/svg+xml' }), b.dataset.nombre + '.svg');
      // PNG de 2000 px para buena calidad de impresion
      var img = new Image(), c = document.createElement('canvas');
      c.width = c.height = 2000;
      img.onload = function () {
        var ctx = c.getContext('2d'); ctx.fillStyle = '#fff'; ctx.fillRect(0, 0, 2000, 2000); ctx.drawImage(img, 0, 0, 2000, 2000);
        c.toBlob(function (bl) { bajar(bl, b.dataset.nombre + '.png'); });
      };
      img.src = 'data:image/svg+xml;charset=utf-8,' + encodeURIComponent(s);
    });
  });
})();
</script>
HTML
);
