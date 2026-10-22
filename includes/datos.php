<?php
// Datos del sitio: editar aqui telefonos, horarios, planes y documentos.
defined('CMC') || exit;

$CONTACTO = [
    'telefonos' => [
        ['texto' => '(55) 8967-5040', 'tel' => '+525589675040'],
        ['texto' => '(800) 341-4008', 'tel' => '+528003414008'],
    ],
    'whatsapp'   => '525525727241',
    'whatsapp_msg' => 'Hola, estoy interesado en sus servicios. ¿Podrían proporcionarme más información?',
    'horario'    => ['9:00 a 18:00 hrs'],
    'emails'     => [
        ['titulo' => 'Atención a clientes', 'email' => 'atc_clientes@cmcnetwork.mx'],
        ['titulo' => 'Ventas', 'email' => 'ventas@cmcnetwork.mx'],
        ['titulo' => 'Reporte de fallas (7 x 24)', 'email' => 'noc@cmcnetwork.mx'],
    ],
    'direccion'  => 'Convento de Churubusco #4, Col. Jardines de Santa Mónica, Tlalnepantla, C.P. 54050',
    'mapa_link'  => 'https://www.google.com.mx/maps/place/Convento+de+Churubusco+4,+Hab+Jardines+de+Santa+Monica,+54050+Tlalnepantla,+M%C3%A9x./@19.5288366,-99.2318276,17z/data=!3m1!4b1!4m6!3m5!1s0x85d21d2957b7b681:0x370859abc2c9e3b7!8m2!3d19.5288316!4d-99.2292527!16s%2Fg%2F11c19_g9lx?entry=ttu',
    'mapa_embed' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3762.9627290272194!2d-99.22925269999999!3d19.528831599999998!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x85d21d2957b7b681%3A0x370859abc2c9e3b7!2sConvento%20de%20Churubusco%204%2C%20Hab%20Jardines%20de%20Santa%20Monica%2C%2054050%20Tlalnepantla%2C%20M%C3%A9x.!5e0!3m2!1ses!2smx!4v1624471928130!5m2!1ses!2smx',
];

$PLANES = [
    ['nombre' => 'CMC 20', 'precio' => 600, 'velocidad' => '10/20', 'pdf' => 'FormatoSimplificado_1171390_CMC 20.pdf'],
    ['nombre' => 'CMC 40', 'precio' => 900, 'velocidad' => '10/40', 'pdf' => 'FormatoSimplificado_1171438_CMC 40.pdf', 'destacado' => true],
    ['nombre' => 'CMC 90', 'precio' => 1500, 'velocidad' => '10/90', 'pdf' => 'FormatoSimplificado_1171458_CMC 90.pdf'],
];
$PLAN_INCLUYE = ['Telefonía / Internet', 'Particular / Residencial', 'Prepago', 'Llamadas a Estados Unidos y Canadá'];

$SERVICIOS = [
    ['icono' => 'bi-headset', 'titulo' => 'Soporte y monitoreo', 'texto' => 'NOC 7/24 los 365 días del año, con gestión de fallas y del desempeño.'],
    ['icono' => 'bi-truck', 'titulo' => 'Autotransporte', 'texto' => 'Internet para autotransporte logístico, un medio de comunicación para tus servicios de transporte.'],
    ['icono' => 'bi-building', 'titulo' => 'Conecta mi empresa', 'texto' => 'Acceso a Internet para mantener las operaciones de tu negocio.'],
    ['icono' => 'bi-shop', 'titulo' => 'Tiendas de autoservicio', 'texto' => 'Internet para lugares semifijos como kioscos y plazas comerciales.'],
    ['icono' => 'bi-diagram-3', 'titulo' => 'Redes multisitio', 'texto' => 'Internet 4G para comunicación segura vía VPN.'],
    ['icono' => 'bi-arrow-repeat', 'titulo' => 'Oficinas remotas', 'texto' => 'Redundancia para tu conexión o agregación 4G para mayor ancho de banda.'],
];

$LEGALES = [
    'Términos, procesos y condiciones' => [
        ['Proceso de contratación', 'Diagrama de flujo para contratación de Servicios CMC.30042024.pdf'],
        ['Proceso de pagos', 'DF Pagos.27052024.pdf'],
        ['Cancelación de servicios', 'Politica_de_Cancelación_CMC.25042024.pdf'],
        ['Proceso de escalación', 'Proceso Escalación CMCNetwork Oct24_v2 (003).pdf'],
        ['Política de gestión de red', 'Politica_de_Gestion_Red_abr24_r2[1].pdf'],
        ['Parámetros de calidad', 'Politica_de_Gestion_Red_abr24_r2[1].pdf'],
        ['Derechos ARCO', 'DERECHOS ARCO CMC.pdf'],
        ['Carta de derechos', 'cartaderechostelecom23.pdf'],
        ['Contrato PROFECO', 'CMC Service Network, S.A. de C.V.!!CMC Service Network 196-2024.pdf'],
        ['Lineamientos de administración transparente', 'Definición del Servicio de Internet CMC_a1.pdf'],
        ['Carta de colaboración con la justicia', 'colaboracion con la justicia.pdf'],
    ],
    'Documentación audible' => [
        ['Derechos ARCO', 'Derechos arco-aviso de privacidad.m4a'],
        ['Carta de derechos', 'Carta de derechos.m4a'],
        ['Contrato PROFECO', 'Contrato profeco cmc.m4a'],
        ['Código de prácticas comerciales', 'Código de practica comerciales.m4a'],
    ],
];

$AVISO_PRIVACIDAD = 'DERECHOS ARCO CMC.pdf';

$INTERESES = [
    'residencial' => 'Internet residencial',
    'corporativo' => 'Internet corporativo / dedicado',
    'eventos'     => 'Internet para eventos',
    'respaldo'    => 'Respaldo 4G / 5G',
    'otro'        => 'Otro',
];

/** Ruta a un documento legal codificada para usarse en href. */
function legal_url($archivo)
{
    return 'legales/' . rawurlencode($archivo);
}
