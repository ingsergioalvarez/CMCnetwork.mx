<?php
defined('CMC') || exit;

require_once ROOT . '/lib/PHPMailer/Exception.php';
require_once ROOT . '/lib/PHPMailer/PHPMailer.php';
require_once ROOT . '/lib/PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;

/**
 * Envia un correo HTML. Usa SMTP si esta configurado, si no mail() del servidor.
 * Devuelve true/false; nunca lanza excepcion para no romper el formulario.
 */
function enviar_correo(array $para, $asunto, $html, $responder_a = null)
{
    $c = config('mail');
    $m = new PHPMailer(true);
    try {
        $m->CharSet = 'UTF-8';
        if (!empty($c['smtp_host'])) {
            $m->isSMTP();
            $m->Host = $c['smtp_host'];
            $m->Port = (int) $c['smtp_port'];
            $m->SMTPAuth = true;
            $m->Username = $c['smtp_user'];
            $m->Password = $c['smtp_pass'];
            $m->SMTPSecure = $c['smtp_secure'] === 'ssl' ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
            $m->Timeout = 10;
        }
        // Sender = remitente tambien en el sobre (-f de mail()); sin esto el servidor usa
        // apache@host y muchos proveedores rechazan el correo o lo mandan a spam
        $m->setFrom($c['from'], $c['from_name'], true);
        foreach ($para as $correo) {
            $m->addAddress($correo);
        }
        if ($responder_a) {
            $m->addReplyTo($responder_a);
        }
        $m->isHTML(true);
        $m->Subject = $asunto;
        $m->Body = $html;
        $m->AltBody = trim(html_entity_decode(strip_tags(str_replace(['<br>', '</tr>', '</p>'], "\n", $html)), ENT_QUOTES, 'UTF-8'));
        return $m->send();
    } catch (Exception $ex) {
        error_log('Correo no enviado: ' . $m->ErrorInfo);
        return false;
    }
}

/** Envia el aviso de un prospecto (fila de la BD con vendedor y evento) a config('notificar'). */
function avisar_prospecto(array $p)
{
    $asunto = 'Nuevo interesado' . ($p['origen'] === 'tarjeta' ? ' (tarjeta QR)' : ' (sitio web)') . ': ' . $p['nombre']
        . ($p['empresa'] !== '' ? ' - ' . $p['empresa'] : '');
    $html = correo_prospecto([
        'Nombre'   => $p['nombre'],
        'Empresa'  => $p['empresa'],
        'Teléfono' => $p['telefono'],
        'Correo'   => $p['email'],
        'Interés'  => interes_texto($p['interes']),
        'Mensaje'  => $p['mensaje'],
        'Origen'   => $p['origen'] === 'tarjeta' ? ($p['pagina'] === 'pasaporte' ? 'Pasaporte Networking (QR)' : 'Tarjeta QR') : 'Sitio web (' . $p['pagina'] . ')',
        'Vendedor' => $p['vendedor'],
        'Evento'   => $p['evento'],
        'Fecha'    => $p['creado'],
    ]);
    return enviar_correo((array) config('notificar'), $asunto, $html, $p['email'] !== '' ? $p['email'] : null);
}

/** Tabla HTML simple con los datos del prospecto para el aviso. */
function correo_prospecto(array $datos)
{
    $filas = '';
    foreach ($datos as $etiqueta => $valor) {
        if ($valor === '' || $valor === null) {
            continue;
        }
        $filas .= '<tr><td style="padding:8px 12px;color:#545454;font-weight:600;vertical-align:top">' . e($etiqueta)
            . '</td><td style="padding:8px 12px;color:#06123D">' . nl2br(e($valor)) . '</td></tr>';
    }
    return '<div style="font-family:Arial,sans-serif;max-width:560px">'
        . '<div style="background:linear-gradient(90deg,#0510C4,#0A7CFC,#00F0FF);height:6px"></div>'
        . '<h2 style="color:#06123D;margin:20px 0 8px">Nuevo prospecto</h2>'
        . '<table style="border-collapse:collapse;width:100%;background:#F4F7FD">' . $filas . '</table>'
        . '<p style="color:#848484;font-size:12px;margin-top:16px">Ver todos en ' . e(config('url')) . '/admin/</p></div>';
}
