<?php
require __DIR__ . '/_inc.php';
$yo = requerir_login();
$pdo = db();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verificar_post();
    $accion = isset($_POST['accion']) ? $_POST['accion'] : '';

    if ($accion === 'password') {
        $st = $pdo->prepare('SELECT password_hash FROM usuarios_admin WHERE id = ?');
        $st->execute([$yo['id']]);
        $nueva = (string) $_POST['nueva'];
        if (!password_verify((string) $_POST['actual'], $st->fetchColumn())) {
            flash('La contraseña actual no es correcta.', 'danger');
        } elseif (strlen($nueva) < 10) {
            flash('La nueva contraseña debe tener al menos 10 caracteres.', 'danger');
        } elseif ($nueva !== $_POST['confirmar']) {
            flash('Las contraseñas nuevas no coinciden.', 'danger');
        } else {
            $pdo->prepare('UPDATE usuarios_admin SET password_hash = ? WHERE id = ?')->execute([password_hash($nueva, PASSWORD_DEFAULT), $yo['id']]);
            flash('Contraseña actualizada.');
        }
    } elseif ($accion === 'crear') {
        $usuario = strtolower(trim((string) $_POST['usuario']));
        $nombre = mb_substr(trim((string) $_POST['nombre']), 0, 120);
        $pass = (string) $_POST['password'];
        $st = $pdo->prepare('SELECT id FROM usuarios_admin WHERE usuario = ?');
        $st->execute([$usuario]);
        if (!preg_match('/^[a-z0-9._-]{3,60}$/', $usuario)) {
            flash('El usuario solo puede tener letras, números, punto, guion (3 a 60).', 'danger');
        } elseif ($st->fetch()) {
            flash('Ese usuario ya existe.', 'danger');
        } elseif (strlen($pass) < 10) {
            flash('La contraseña debe tener al menos 10 caracteres.', 'danger');
        } else {
            $pdo->prepare('INSERT INTO usuarios_admin (usuario, nombre, password_hash) VALUES (?, ?, ?)')->execute([$usuario, $nombre, password_hash($pass, PASSWORD_DEFAULT)]);
            flash('Usuario creado.');
        }
    } elseif ($accion === 'eliminar') {
        $id = (int) $_POST['id'];
        if ($id === $yo['id']) {
            flash('No puedes eliminar tu propio usuario.', 'warning');
        } else {
            $pdo->prepare('DELETE FROM usuarios_admin WHERE id = ?')->execute([$id]);
            flash('Usuario eliminado.');
        }
    }
    redirigir('/admin/usuarios.php');
}

$lista = $pdo->query('SELECT id, usuario, nombre, ultimo_acceso FROM usuarios_admin ORDER BY usuario')->fetchAll();
admin_header('Usuarios', 'usuarios');
?>
<h1 class="h4 mb-3">Usuarios del panel</h1>
<div class="row g-4">
  <div class="col-lg-7">
    <div class="card"><div class="table-responsive"><table class="table align-middle mb-0">
      <thead><tr><th>Usuario</th><th>Nombre</th><th>Último acceso</th><th></th></tr></thead>
      <tbody>
      <?php foreach ($lista as $u): ?>
        <tr>
          <td><strong><?= e($u['usuario']) ?></strong><?= $u['id'] == $yo['id'] ? ' <span class="badge text-bg-light">tú</span>' : '' ?></td>
          <td><?= e($u['nombre']) ?></td>
          <td class="small"><?= e(fecha_corta($u['ultimo_acceso'])) ?: '—' ?></td>
          <td class="text-end"><?php if ($u['id'] != $yo['id']): ?>
            <form method="post" class="m-0" onsubmit="return confirm('¿Eliminar este usuario?')"><?= campo_csrf() ?><input type="hidden" name="id" value="<?= $u['id'] ?>"><button name="accion" value="eliminar" class="btn btn-light btn-sm text-danger"><i class="bi bi-trash"></i></button></form>
          <?php endif; ?></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table></div></div>

    <div class="card mt-4"><div class="card-body p-4">
      <h2 class="h6 text-uppercase text-muted mb-3">Agregar usuario</h2>
      <form method="post" class="row g-3">
        <?= campo_csrf() ?><input type="hidden" name="accion" value="crear">
        <div class="col-md-4"><input class="form-control" name="usuario" placeholder="usuario" required autocomplete="off"></div>
        <div class="col-md-4"><input class="form-control" name="nombre" placeholder="Nombre"></div>
        <div class="col-md-4"><input class="form-control" type="password" name="password" placeholder="Contraseña (mín. 10)" required autocomplete="new-password"></div>
        <div class="col-12"><button class="btn btn-cmc btn-sm">Crear usuario</button></div>
      </form>
    </div></div>
  </div>

  <div class="col-lg-5">
    <div class="card"><div class="card-body p-4">
      <h2 class="h6 text-uppercase text-muted mb-3">Cambiar mi contraseña</h2>
      <form method="post">
        <?= campo_csrf() ?><input type="hidden" name="accion" value="password">
        <input class="form-control mb-2" type="password" name="actual" placeholder="Contraseña actual" required autocomplete="current-password">
        <input class="form-control mb-2" type="password" name="nueva" placeholder="Nueva contraseña (mín. 10)" required autocomplete="new-password">
        <input class="form-control mb-3" type="password" name="confirmar" placeholder="Repite la nueva" required autocomplete="new-password">
        <button class="btn btn-cmc btn-sm">Actualizar</button>
      </form>
    </div></div>
  </div>
</div>
<?php admin_footer();
