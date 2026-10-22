<?php
// Copia este archivo como config.php y llena los valores.
// En el servidor se guarda FUERA de la carpeta publica: /home/cmcnet/privado/config.php
// (la app busca ahi primero y despues en config/config.php).
return [
    'db' => [
        'host' => 'sql.cmcnetwork.mx',
        'port' => 3306,
        'name' => 'cmcnet_web',
        'user' => 'cmcnet',
        'pass' => '',
    ],

    // Quien recibe el aviso de cada prospecto nuevo
    'notificar' => ['ventas@cmcnetwork.mx'],

    'mail' => [
        'from'      => 'noreply@cmcnetwork.mx',
        'from_name' => 'CMC Service Network',
        // Deja 'smtp_host' vacio para usar mail() del servidor
        'smtp_host' => '',
        'smtp_port' => 587,
        'smtp_user' => '',
        'smtp_pass' => '',
        'smtp_secure' => 'tls', // tls | ssl
    ],

    // URL publica del sitio (sin / final); se usa en el QR y en los correos
    'url' => 'https://cmcnetwork.mx',

    // Cadena aleatoria larga para firmar formularios (genera una con: php -r "echo bin2hex(random_bytes(32));")
    'secret' => '',

    'debug' => false,
];
