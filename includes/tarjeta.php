<?php
// Resolver que vendedor/evento muestra la tarjeta: ?v=slug / ?e=slug o los activos en el panel.
defined('CMC') || exit;

function tarjeta_vendedor($slug = '')
{
    if ($slug !== '') {
        $st = db()->prepare('SELECT * FROM vendedores WHERE slug = ? AND activo = 1');
        $st->execute([$slug]);
        if ($v = $st->fetch()) {
            return $v;
        }
    }
    $id = (int) ajuste('tarjeta_vendedor_id');
    if ($id) {
        $st = db()->prepare('SELECT * FROM vendedores WHERE id = ? AND activo = 1');
        $st->execute([$id]);
        if ($v = $st->fetch()) {
            return $v;
        }
    }
    return null;
}

function tarjeta_evento($slug = '')
{
    if ($slug !== '') {
        $st = db()->prepare('SELECT * FROM eventos WHERE slug = ?');
        $st->execute([$slug]);
        if ($ev = $st->fetch()) {
            return $ev;
        }
    }
    $id = (int) ajuste('tarjeta_evento_id');
    if ($id) {
        $st = db()->prepare('SELECT * FROM eventos WHERE id = ?');
        $st->execute([$id]);
        return $st->fetch() ?: null;
    }
    return null;
}

/** Datos a mostrar: el vendedor o, si no hay, los datos generales de ventas. */
function tarjeta_datos($vendedor, array $CONTACTO)
{
    if ($vendedor) {
        return [
            'id'       => (int) $vendedor['id'],
            'slug'     => $vendedor['slug'],
            'nombre'   => 'CMC Service Network Bajío', // nombre de marca, no el del vendedor
            'puesto'   => 'Ventas Bajío', // fijo para la tarjeta, aunque el vendedor tenga otro puesto en el panel
            'telefono' => $vendedor['telefono'],
            'whatsapp' => $vendedor['whatsapp'] !== '' ? $vendedor['whatsapp'] : $vendedor['telefono'],
            'email'    => $vendedor['email'],
            'foto'     => false, // en lugar de la foto del vendedor se muestra el simbolo de CMC
        ];
    }
    return [
        'id'       => 0,
        'slug'     => '',
        'nombre'   => 'CMC Service Network Bajío',
        'puesto'   => 'Ventas Bajío',
        'telefono' => $CONTACTO['telefonos'][0]['tel'],
        'whatsapp' => $CONTACTO['whatsapp'],
        'email'    => 'ventas@cmcnetwork.mx',
        'foto'     => false,
    ];
}

/** Numero para wa.me: agrega 52 a numeros mexicanos de 10 digitos. */
function wa_numero($tel)
{
    $d = solo_digitos($tel);
    return strlen($d) === 10 ? '52' . $d : $d;
}
