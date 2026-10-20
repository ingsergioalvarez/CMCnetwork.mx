<?php
// Filtros de prospectos compartidos por la lista y la exportacion.
defined('CMC') || exit;

function filtros_prospectos()
{
    $f = [];
    foreach (['q', 'origen', 'estado', 'vendedor', 'evento', 'desde', 'hasta'] as $k) {
        $f[$k] = isset($_GET[$k]) ? trim((string) $_GET[$k]) : '';
    }
    $where = [];
    $params = [];
    if ($f['q'] !== '') {
        $where[] = '(p.nombre LIKE ? OR p.empresa LIKE ? OR p.email LIKE ? OR p.telefono LIKE ?)';
        $like = '%' . $f['q'] . '%';
        array_push($params, $like, $like, $like, $like);
    }
    if (in_array($f['origen'], ['web', 'tarjeta'], true)) {
        $where[] = 'p.origen = ?';
        $params[] = $f['origen'];
    }
    if ($f['estado'] !== '') {
        $where[] = 'p.estado = ?';
        $params[] = $f['estado'];
    }
    if (ctype_digit($f['vendedor'])) {
        $where[] = 'p.vendedor_id = ?';
        $params[] = (int) $f['vendedor'];
    }
    if (ctype_digit($f['evento'])) {
        $where[] = 'p.evento_id = ?';
        $params[] = (int) $f['evento'];
    }
    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $f['desde'])) {
        $where[] = 'p.creado >= ?';
        $params[] = $f['desde'] . ' 00:00:00';
    }
    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $f['hasta'])) {
        $where[] = 'p.creado <= ?';
        $params[] = $f['hasta'] . ' 23:59:59';
    }
    return [$f, $where ? 'WHERE ' . implode(' AND ', $where) : '', $params];
}

const SQL_PROSPECTOS = 'SELECT p.*, v.nombre AS vendedor, ev.nombre AS evento
    FROM prospectos p
    LEFT JOIN vendedores v ON v.id = p.vendedor_id
    LEFT JOIN eventos ev ON ev.id = p.evento_id ';
