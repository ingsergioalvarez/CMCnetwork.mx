<?php
// Mapa de cobertura de conectividad. ?estado=N abre el panel de ese estado (lo usa el recuadro del inicio).
require __DIR__ . '/includes/bootstrap.php';
$pagina = 'cobertura';
$titulo = 'Cobertura de conectividad | CMC Service Network';
$descripcion = 'Red propia de fibra óptica y microondas en el centro del país, cobertura extendida, alianzas y LTE y satelital en los 32 estados.';
require ROOT . '/includes/header.php';
?>

<?php require ROOT . '/includes/cobertura.php'; ?>
<?php $interes = 'corenet'; require ROOT . '/includes/cta-contacto.php'; ?>
<?php require ROOT . '/includes/footer.php'; ?>
