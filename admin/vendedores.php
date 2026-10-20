<?php
require __DIR__ . '/_inc.php';
requerir_login();
$pdo = db();

/** Recorta al centro y reduce a 400x400 JPEG. Devuelve [bytes, tipo] o null. */
function procesar_foto(array $archivo)
{
    if ($archivo['error'] !== UPLOAD_ERR_OK || $archivo['size'] > 8 * 1024 * 1024) {
        return null;
    }
    $info = @getimagesize($archivo['tmp_name']);
    if (!$info || !in_array($info[2], [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_WEBP], true)) {
        return null;
    }
    if (!function_exists('imagecreatefromstring')) {
        return [file_get_contents($archivo['tmp_name']), $info['mime']];
    }
    $src = imagecreatefromstring(file_get_contents($archivo['tmp_name']));
    if (!$src) {
        return null;
    }
    // Respetar la orientacion de fotos de celular
    if ($info[2] === IMAGETYPE_JPEG && function_exists('exif_read_data')) {
        $exif = @exif_read_data($archivo['tmp_name']);
        $giro = ['3' => 180, '6' => -90, '8' => 90];
        if (!empty($exif['Orientation']) && isset($giro[$exif['Orientation']])) {
            $src = imagerotate($src, $giro[$exif['Orientation']], 0);
        }
    }
    $w = imagesx($src);
    $h = imagesy($src);
    $lado = min($w, $h);
    $dst = imagecreatetruecolor(400, 400);
    imagefill($dst, 0, 0, imagecolorallocate($dst, 255, 255, 255));
    imagecopyresampled($dst, $src, 0, 0, (int) (($w - $lado) / 2), (int) (($h - $lado) / 2), 400, 400, $lado, $lado);
    ob_start();
    imagejpeg($dst, null, 85);
    $bytes = ob_get_clean();
    imagedestroy($src);
    imagedestroy($dst);
    return [$bytes, 'image/jpeg'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verificar_post();
    $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
    $accion = isset($_POST['accion']) ? $_POST['accion'] : 'guardar';

    if ($accion === 'activar_tarjeta') {
        ajuste('tarjeta_vendedor_id', (string) $id);
        flash('Listo: la tarjeta del QR ahora muestra a este vendedor.');
        redirigir('/admin/vendedores.php');
    }
    if ($accion === 'alternar') {
        $pdo->prepare('UPDATE vendedores SET activo = 1 - activo WHERE id = ?')->execute([$id]);
        redirigir('/admin/vendedores.php');
    }
    if ($accion === 'eliminar') {
        $st = $pdo->prepare('SELECT COUNT(*) FROM prospectos WHERE vendedor_id = ?');
        $st->execute([$id]);
        if ((int) $st->fetchColumn() > 0) {
            flash('Tiene prospectos asignados; mejor desactívalo para conservar el historial.', 'warning');
        } else {
            $pdo->prepare('DELETE FROM vendedores WHERE id = ?')->execute([$id]);
            flash('Vendedor eliminado.');
        }
        redirigir('/admin/vendedores.php');
    }

    $d = [];
    foreach (['nombre' => 120, 'puesto' => 120, 'telefono' => 30, 'whatsapp' => 30, 'email' => 150] as $k => $max) {
        $d[$k] = mb_substr(trim((string) (isset($_POST[$k]) ? $_POST[$k] : '')), 0, $max);
    }
    $slug = slugify(isset($_POST['slug']) && trim($_POST['slug']) !== '' ? $_POST['slug'] : $d['nombre']);
    $errores = [];
    if (mb_strlen($d['nombre']) < 2) {
        $errores[] = 'Escribe el nombre.';
    }
    if ($d['email'] !== '' && !filter_var($d['email'], FILTER_VALIDATE_EMAIL)) {
        $errores[] = 'El correo no es válido.';
    }
    $st = $pdo->prepare('SELECT id FROM vendedores WHERE slug = ? AND id <> ?');
    $st->execute([$slug, $id]);
    if ($st->fetch()) {
        $errores[] = 'Ya existe un vendedor con el identificador "' . $slug . '".';
    }
    $foto = null;
    if (!empty($_FILES['foto']['name'])) {
        $foto = procesar_foto($_FILES['foto']);
        if (!$foto) {
            $errores[] = 'La foto debe ser JPG, PNG o WebP de máximo 8 MB.';
        }
    }

    if ($errores) {
        flash(implode(' ', $errores), 'danger');
        redirigir('/admin/vendedores.php?' . ($id ? 'id=' . $id : 'nuevo=1'));
    }

    if ($id) {
        $pdo->prepare('UPDATE vendedores SET slug = ?, nombre = ?, puesto = ?, telefono = ?, whatsapp = ?, email = ? WHERE id = ?')
            ->execute([$slug, $d['nombre'], $d['puesto'], $d['telefono'], $d['whatsapp'], $d['email'], $id]);
    } else {
        $pdo->prepare('INSERT INTO vendedores (slug, nombre, puesto, telefono, whatsapp, email) VALUES (?, ?, ?, ?, ?, ?)')
            ->execute([$slug, $d['nombre'], $d['puesto'], $d['telefono'], $d['whatsapp'], $d['email']]);
        $id = (int) $pdo->lastInsertId();
    }
    if ($foto) {
        $st = $pdo->prepare('UPDATE vendedores SET foto = ?, foto_tipo = ? WHERE id = ?');
        $st->bindValue(1, $foto[0], PDO::PARAM_LOB);
        $st->bindValue(2, $foto[1]);
        $st->bindValue(3, $id, PDO::PARAM_INT);
        $st->execute();
    } elseif (!empty($_POST['quitar_foto'])) {
        $pdo->prepare('UPDATE vendedores SET foto = NULL, foto_tipo = NULL WHERE id = ?')->execute([$id]);
    }
    flash('Vendedor guardado.');
    redirigir('/admin/vendedores.php');
}

$activoTarjeta = (int) ajuste('tarjeta_vendedor_id');
$editar = null;
if (isset($_GET['id'])) {
    $st = $pdo->prepare('SELECT id, slug, nombre, puesto, telefono, whatsapp, email, foto_tipo FROM vendedores WHERE id = ?');
    $st->execute([(int) $_GET['id']]);
    $editar = $st->fetch();
}
$mostrarForm = $editar || isset($_GET['nuevo']);
$lista = $pdo->query('SELECT v.id, v.slug, v.nombre, v.puesto, v.telefono, v.email, v.activo, v.foto_tipo,
    (SELECT COUNT(*) FROM prospectos p WHERE p.vendedor_id = v.id) prospectos
    FROM vendedores v ORDER BY v.activo DESC, v.nombre')->fetchAll();

admin_header('Vendedores', 'vendedores');
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h4 m-0">Vendedores</h1>
  <?php if (!$mostrarForm): ?><a href="?nuevo=1" class="btn btn-cmc btn-sm"><i class="bi bi-plus-lg me-1"></i>Nuevo vendedor</a><?php endif; ?>
</div>

<?php if ($mostrarForm): $x = $editar ?: ['id' => 0, 'slug' => '', 'nombre' => '', 'puesto' => 'Asesor comercial', 'telefono' => '', 'whatsapp' => '', 'email' => '', 'foto_tipo' => null]; ?>
<div class="card mb-4"><div class="card-body p-4">
  <h2 class="h5 mb-3"><?= $x['id'] ? 'Editar vendedor' : 'Nuevo vendedor' ?></h2>
  <form method="post" enctype="multipart/form-data" class="row g-3">
    <?= campo_csrf() ?><input type="hidden" name="id" value="<?= (int) $x['id'] ?>">
    <div class="col-md-6"><label class="form-label">Nombre completo *</label><input class="form-control" name="nombre" required value="<?= e($x['nombre']) ?>"></div>
    <div class="col-md-6"><label class="form-label">Puesto</label><input class="form-control" name="puesto" value="<?= e($x['puesto']) ?>"></div>
    <div class="col-md-4"><label class="form-label">Teléfono celular</label><input class="form-control" name="telefono" value="<?= e($x['telefono']) ?>" placeholder="55 1234 5678"></div>
    <div class="col-md-4"><label class="form-label">WhatsApp <span class="text-muted small">(si es distinto)</span></label><input class="form-control" name="whatsapp" value="<?= e($x['whatsapp']) ?>"></div>
    <div class="col-md-4"><label class="form-label">Correo</label><input class="form-control" type="email" name="email" value="<?= e($x['email']) ?>"></div>
    <div class="col-md-6">
      <label class="form-label">Foto <span class="text-muted small">(se recorta cuadrada)</span></label>
      <input class="form-control" type="file" name="foto" accept="image/jpeg,image/png,image/webp">
      <?php if ($x['foto_tipo']): ?>
        <div class="d-flex align-items-center gap-2 mt-2"><img src="/tarjeta/foto.php?id=<?= (int) $x['id'] ?>&t=<?= time() ?>" class="avatar" alt="">
        <label class="small"><input type="checkbox" name="quitar_foto" value="1"> Quitar foto</label></div>
      <?php endif; ?>
    </div>
    <div class="col-md-6"><label class="form-label">Identificador para su enlace</label>
      <div class="input-group"><span class="input-group-text small">/tarjeta/?v=</span><input class="form-control" name="slug" value="<?= e($x['slug']) ?>" placeholder="se genera del nombre"></div></div>
    <div class="col-12 d-flex gap-2"><button class="btn btn-cmc">Guardar</button><a href="/admin/vendedores.php" class="btn btn-light">Cancelar</a></div>
  </form>
</div></div>
<?php endif; ?>

<div class="card"><div class="table-responsive">
  <table class="table align-middle mb-0">
    <thead><tr><th></th><th>Nombre</th><th>Contacto</th><th>Prospectos</th><th>Tarjeta QR</th><th class="text-end">Acciones</th></tr></thead>
    <tbody>
    <?php foreach ($lista as $v): $esActivo = (int) $v['id'] === $activoTarjeta; ?>
      <tr class="<?= $v['activo'] ? '' : 'opacity-50' ?>">
        <td style="width:56px"><?php if ($v['foto_tipo']): ?><img src="/tarjeta/foto.php?id=<?= $v['id'] ?>" class="avatar" alt=""><?php else: ?><span class="avatar"><?= e(mb_strtoupper(mb_substr($v['nombre'], 0, 1))) ?></span><?php endif; ?></td>
        <td><strong><?= e($v['nombre']) ?></strong><br><span class="small text-muted"><?= e($v['puesto']) ?><?= $v['activo'] ? '' : ' · inactivo' ?></span></td>
        <td class="small"><?= e($v['telefono']) ?><br><?= e($v['email']) ?></td>
        <td><a href="/admin/?vendedor=<?= $v['id'] ?>"><?= (int) $v['prospectos'] ?></a></td>
        <td>
          <?php if ($esActivo): ?>
            <span class="badge text-bg-info"><i class="bi bi-check-circle"></i> En la lona</span>
          <?php elseif ($v['activo']): ?>
            <form method="post" class="m-0"><?= campo_csrf() ?><input type="hidden" name="id" value="<?= $v['id'] ?>"><button name="accion" value="activar_tarjeta" class="btn btn-outline-primary btn-sm">Poner en la lona</button></form>
          <?php endif; ?>
          <div class="small mt-1"><a href="/tarjeta/?v=<?= e($v['slug']) ?>" target="_blank">/tarjeta/?v=<?= e($v['slug']) ?></a></div>
        </td>
        <td class="text-end text-nowrap">
          <a href="?id=<?= $v['id'] ?>" class="btn btn-light btn-sm" title="Editar"><i class="bi bi-pencil"></i></a>
          <form method="post" class="d-inline"><?= campo_csrf() ?><input type="hidden" name="id" value="<?= $v['id'] ?>"><button name="accion" value="alternar" class="btn btn-light btn-sm" title="<?= $v['activo'] ? 'Desactivar' : 'Activar' ?>"><i class="bi <?= $v['activo'] ? 'bi-pause' : 'bi-play' ?>"></i></button></form>
          <form method="post" class="d-inline" onsubmit="return confirm(<?= e(json_encode('¿Eliminar a ' . $v['nombre'] . '?', JSON_UNESCAPED_UNICODE)) ?>)"><?= campo_csrf() ?><input type="hidden" name="id" value="<?= $v['id'] ?>"><button name="accion" value="eliminar" class="btn btn-light btn-sm text-danger" title="Eliminar"><i class="bi bi-trash"></i></button></form>
        </td>
      </tr>
    <?php endforeach; ?>
    <?php if (!$lista): ?><tr><td colspan="6" class="text-center text-muted py-5">Aún no hay vendedores. Da de alta el primero.</td></tr><?php endif; ?>
    </tbody>
  </table>
</div></div>
<?php admin_footer();
