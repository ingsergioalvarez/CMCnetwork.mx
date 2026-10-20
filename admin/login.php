<?php
require __DIR__ . '/_inc.php';

if (usuario_actual()) {
    redirigir('/admin/');
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verificar_post();
    $ip = ip_cliente();
    $pdo = db();
    $st = $pdo->prepare('SELECT COUNT(*) FROM login_intentos WHERE ip = ? AND creado > NOW() - INTERVAL 15 MINUTE');
    $st->execute([$ip]);
    if ((int) $st->fetchColumn() >= 5) {
        $error = 'Demasiados intentos. Espera 15 minutos.';
    } else {
        $usuario = trim(isset($_POST['usuario']) ? $_POST['usuario'] : '');
        $pass = isset($_POST['password']) ? $_POST['password'] : '';
        $st = $pdo->prepare('SELECT * FROM usuarios_admin WHERE usuario = ?');
        $st->execute([$usuario]);
        $u = $st->fetch();
        if ($u && password_verify($pass, $u['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['admin'] = ['id' => (int) $u['id'], 'usuario' => $u['usuario'], 'nombre' => $u['nombre']];
            $_SESSION['admin_visto'] = time();
            $pdo->prepare('UPDATE usuarios_admin SET ultimo_acceso = NOW() WHERE id = ?')->execute([$u['id']]);
            $pdo->prepare('DELETE FROM login_intentos WHERE ip = ?')->execute([$ip]);
            if (password_needs_rehash($u['password_hash'], PASSWORD_DEFAULT)) {
                $pdo->prepare('UPDATE usuarios_admin SET password_hash = ? WHERE id = ?')->execute([password_hash($pass, PASSWORD_DEFAULT), $u['id']]);
            }
            redirigir('/admin/');
        }
        $pdo->prepare('INSERT INTO login_intentos (ip) VALUES (?)')->execute([$ip]);
        $error = 'Usuario o contraseña incorrectos.';
    }
}

admin_header('Entrar');
?>
<div class="login-caja">
  <img src="/imagenes/marca/logo-cmc-web.png" alt="CMC Service Network" class="d-block mx-auto mb-4" height="56">
  <div class="card shadow-sm">
    <div class="card-body p-4">
      <h1 class="h5 mb-3 text-center">Panel de administración</h1>
      <?php if ($error): ?><div class="alert alert-danger py-2"><?= e($error) ?></div><?php endif; ?>
      <form method="post">
        <?= campo_csrf() ?>
        <div class="mb-3">
          <label class="form-label" for="usuario">Usuario</label>
          <input class="form-control" id="usuario" name="usuario" required autofocus autocomplete="username" value="<?= e(isset($_POST['usuario']) ? $_POST['usuario'] : '') ?>">
        </div>
        <div class="mb-4">
          <label class="form-label" for="password">Contraseña</label>
          <input class="form-control" id="password" name="password" type="password" required autocomplete="current-password">
        </div>
        <button class="btn btn-cmc w-100" type="submit">Entrar</button>
      </form>
    </div>
  </div>
</div>
<?php admin_footer();
