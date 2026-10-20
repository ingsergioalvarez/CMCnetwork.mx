<?php
// Exporta los prospectos filtrados a CSV (Excel lo abre directo gracias al BOM UTF-8).
require __DIR__ . '/_inc.php';
require __DIR__ . '/_filtros.php';
requerir_login();

list($f, $where, $params) = filtros_prospectos();
$st = db()->prepare(SQL_PROSPECTOS . $where . ' ORDER BY p.creado DESC');
$st->execute($params);

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="prospectos-' . date('Y-m-d') . '.csv"');

$out = fopen('php://output', 'w');
fwrite($out, "\xEF\xBB\xBF");
fputcsv($out, ['Fecha', 'Nombre', 'Empresa', 'Teléfono', 'Correo', 'Interés', 'Mensaje', 'Origen', 'Página', 'Vendedor', 'Evento', 'Estado', 'Notas']);
while ($r = $st->fetch()) {
    // Evita que Excel interprete celdas como formulas
    $celda = function ($v) {
        $v = (string) $v;
        return ($v !== '' && strpos('=+-@', $v[0]) !== false) ? "'" . $v : $v;
    };
    fputcsv($out, array_map($celda, [
        $r['creado'], $r['nombre'], $r['empresa'], $r['telefono'], $r['email'],
        isset($INTERESES[$r['interes']]) ? $INTERESES[$r['interes']] : $r['interes'],
        $r['mensaje'], $r['origen'] === 'tarjeta' ? 'QR' : 'Web', $r['pagina'], $r['vendedor'], $r['evento'],
        isset($ESTADOS[$r['estado']]) ? $ESTADOS[$r['estado']][0] : $r['estado'], $r['notas'],
    ]));
}
fclose($out);
