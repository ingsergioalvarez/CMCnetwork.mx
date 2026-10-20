<?php
// Seccion de planes residenciales (usada en residencial.php y tarifas.php)
defined('CMC') || exit;
?>
<section id="planes" class="seccion seccion-clara">
  <div class="container">
    <div class="titulo-seccion text-center revelar">
      <span class="etiqueta">Tarifas</span>
      <h2>Planes residenciales</h2>
      <p>Internet y telefonía para tu hogar, con llamadas a Estados Unidos y Canadá.</p>
    </div>
    <div class="row g-4 justify-content-center">
      <?php foreach ($PLANES as $p): $dest = !empty($p['destacado']); ?>
        <div class="col-md-6 col-lg-4 revelar">
          <div class="plan<?= $dest ? ' destacado' : '' ?>">
            <?php if ($dest): ?><span class="insignia">Más popular</span><?php endif; ?>
            <h3><?= e($p['nombre']) ?></h3>
            <div class="velocidad">Velocidad <?= e($p['velocidad']) ?></div>
            <div class="precio">$<?= number_format($p['precio']) ?><small>/mes</small></div>
            <ul>
              <?php foreach ($PLAN_INCLUYE as $inc): ?><li><?= e($inc) ?></li><?php endforeach; ?>
            </ul>
            <a href="<?= e('/' . legal_url($p['pdf'])) ?>" target="_blank" rel="noopener" class="btn <?= $dest ? 'btn-cmc' : 'btn-borde' ?>">Más detalles</a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <p class="text-center texto-suave mt-4 mb-0">* Revisa promociones vigentes. Precios en pesos mexicanos.</p>
  </div>
</section>
