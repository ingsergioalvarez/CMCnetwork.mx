<?php
// Datos del sitio: editar aqui telefonos, horarios, planes, documentos y el portafolio de soluciones.
defined('CMC') || exit;

$CONTACTO = [
    'telefonos' => [
        ['texto' => '(55) 8967-5040', 'tel' => '+525589675040'],
        ['texto' => '(800) 341-4008', 'tel' => '+528003414008'],
    ],
    'whatsapp'   => '525525727241',
    'whatsapp_msg' => 'Hola, estoy buscando información de CMC Service Network.',
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

$COMUNICALO = ['nombre' => 'Comunícalo', 'url' => 'https://comunicalo.mx'];

// ---------- Portafolio de soluciones ----------
// Pilares = paginas del sitio. Cada solucion pertenece a un pilar y aparece en sus carruseles.
$PILARES = [
    'iot' => [
        'nombre' => 'IoT y Automatización',
        'url'    => '/iot.php',
        'icono'  => 'bi-cpu',
        'img'    => '/img/web/iot-hero.webp',
        'titulo' => ['Tecnología que ve, mide', 'y actúa por ti'],
        'texto'  => 'Videovigilancia con inteligencia artificial, centros de comando, control de acceso, monitoreo sísmico y retail inteligente, integrados en una sola plataforma.',
    ],
    'cadena' => [
        'nombre' => 'Cadena de suministro',
        'url'    => '/cadena-suministro.php',
        'icono'  => 'bi-box-seam',
        'img'    => '/img/soluciones/omniflow.webp',
        'titulo' => ['Trazabilidad', 'de punta a punta'],
        'texto'  => 'Un solo proveedor para toda la cadena de suministro: RFID, código de barras, etiquetado, hardware de uso rudo, WMS y robots autónomos.',
    ],
    'conectividad' => [
        'nombre' => 'Conectividad',
        'url'    => '/conectividad.php',
        'icono'  => 'bi-broadcast',
        'img'    => '/img/soluciones/corenet.webp',
        'titulo' => ['Conectividad', 'de misión crítica'],
        'texto'  => 'Internet dedicado simétrico con SLA, interconexión entre sedes, respaldo satelital y LTE, y telefonía IP en la nube.',
    ],
    'ti' => [
        'nombre' => 'TI y Software',
        'url'    => '/ti-software.php',
        'icono'  => 'bi-shield-lock',
        'img'    => '/img/soluciones/cyberguard.webp',
        'titulo' => ['Ciberseguridad y software', 'a la medida'],
        'texto'  => 'Protección perimetral, cumplimiento normativo y desarrollo de sistemas que se adaptan a tu operación.',
    ],
];

$SOLUCIONES = [
    // --- IoT y Automatizacion ---
    'visionguard' => [
        'pilar' => 'iot', 'nombre' => 'VisionGuard', 'sub' => 'Videovigilancia con inteligencia artificial', 'icono' => 'bi-camera-video',
        'resumen' => 'Cámaras cognitivas que analizan, predicen y protegen tu perímetro de forma automática.',
        'reto' => 'Sustituir la supervisión humana pasiva por un ecosistema de cámaras cognitivas que analizan, predicen y blindan el perímetro de forma automatizada.',
        'incluye' => [
            'Seguridad de alta precisión: filtra falsas alarmas y enfoca la atención solo en amenazas reales de personas y vehículos.',
            'Búsqueda forense instantánea por atributos: ropa, tipo de vehículo o accesorios.',
            'Reconocimiento facial en tiempo real vinculado a listas VIP o de restricción.',
            'Mapas de calor y conteo de personas para optimizar espacios y operación.',
            'Monitoreo térmico que detecta sobrecalentamientos antes de que se genere un incendio.',
        ],
    ],
    'c2' => [
        'pilar' => 'iot', 'nombre' => 'Centros C2', 'sub' => 'Centros de comando y control', 'icono' => 'bi-display',
        'resumen' => 'Toda tu videovigilancia, sensores y alertas en un solo centro de monitoreo.',
        'reto' => 'Cámaras, sensores y alarmas operando por separado, sin una vista unificada para reaccionar a tiempo ante un incidente.',
        'incluye' => [
            'Diseño e implementación de centros de comando y control para empresas y municipios.',
            'Videowall y estaciones de operador con vista unificada de cámaras y sensores.',
            'Integración de videovigilancia, control de acceso, LPR y alertamiento en una sola plataforma.',
            'Enlaces dedicados y respaldo para mantener el centro operando 7 x 24.',
            'Protocolos de alerta y reportes para la toma de decisiones.',
        ],
    ],
    'scardetection' => [
        'pilar' => 'iot', 'nombre' => 'Scardetection', 'sub' => 'Detección de daños estéticos en autos', 'icono' => 'bi-car-front',
        'resumen' => 'Arco con cámaras y aplicación que detecta y documenta daños estéticos en cada vehículo.',
        'reto' => 'Inspecciones manuales lentas y sin evidencia que generan disputas por daños al recibir o entregar un vehículo.',
        'incluye' => [
            'Arco de inspección con cámaras que captura el vehículo completo a su paso.',
            'Aplicación que detecta y señala daños estéticos de forma automática.',
            'Evidencia fotográfica con fecha y hora de cada entrada y salida.',
            'Historial por vehículo para resolver reclamaciones con datos.',
            'Ideal para distribuidores de autos y estacionamientos.',
        ],
        'destacado' => ['Distribuidores de autos', 'Estacionamientos'],
    ],
    'lpr' => [
        'pilar' => 'iot', 'nombre' => 'Cámaras LPR', 'sub' => 'Lectura automática de placas', 'icono' => 'bi-123',
        'resumen' => 'Identifica vehículos por su placa para controlar accesos y generar alertas.',
        'reto' => 'Registro manual de vehículos en accesos, sin forma de saber quién entró, cuándo y si estaba autorizado.',
        'incluye' => [
            'Lectura automática de placas en accesos, estacionamientos y vialidades.',
            'Listas de vehículos autorizados y restringidos con alertas en tiempo real.',
            'Apertura automática de plumas y barreras.',
            'Historial consultable de entradas y salidas.',
            'Integración con centros C2 y control de acceso.',
        ],
    ],
    'analitica' => [
        'pilar' => 'iot', 'nombre' => 'Analítica de video', 'sub' => 'Conteo de personas, caídas y gestos', 'icono' => 'bi-people',
        'resumen' => 'Convierte tus cámaras en sensores que cuentan, detectan y alertan.',
        'reto' => 'Horas de video que nadie revisa y eventos críticos que se descubren demasiado tarde.',
        'incluye' => [
            'Contador de personas para tiendas, plazas y oficinas.',
            'Detección de caídas con alerta inmediata.',
            'Detección de gestos para solicitar ayuda o activar acciones.',
            'Mapas de calor y tiempos de permanencia.',
            'Reportes y tableros para la toma de decisiones.',
        ],
    ],
    'epp' => [
        'pilar' => 'iot', 'nombre' => 'Detección de EPP', 'sub' => 'Seguridad industrial con visión artificial', 'icono' => 'bi-cone-striped',
        'resumen' => 'Verifica que tu personal use casco, chaleco y equipo de protección en planta.',
        'reto' => 'Accidentes y sanciones por personal que no usa su equipo de protección en zonas de riesgo.',
        'incluye' => [
            'Detección automática de casco, chaleco y equipo de protección personal.',
            'Alertas en tiempo real al supervisor de seguridad.',
            'Zonas de riesgo configurables por área de la planta.',
            'Evidencia y reportes para auditorías de seguridad.',
            'Funciona con cámaras IP existentes o nuevas.',
        ],
    ],
    'acceso' => [
        'pilar' => 'iot', 'nombre' => 'Control de acceso', 'sub' => 'Automatización de entradas', 'icono' => 'bi-door-open',
        'resumen' => 'Tarjetas, biometría y reconocimiento facial para saber quién entra y cuándo.',
        'reto' => 'Accesos controlados con libretas o llaves, sin registro confiable de personas y visitantes.',
        'incluye' => [
            'Lectores de tarjeta, huella y reconocimiento facial.',
            'Torniquetes, plumas y cerraduras electromagnéticas.',
            'Registro de visitantes y horarios por zona.',
            'Reportes de asistencia y accesos.',
            'Integración con videovigilancia y LPR.',
        ],
    ],
    'geoalert' => [
        'pilar' => 'iot', 'nombre' => 'GeoAlert', 'sub' => 'Protección y monitoreo sísmico', 'icono' => 'bi-activity',
        'resumen' => 'Alertamiento y monitoreo estructural para corporativos, industria y edificios públicos.',
        'reto' => 'Riesgo sísmico en corporativos y plantas, maquinaria vulnerable a vibraciones y sin diagnóstico después de un sismo.',
        'incluye' => [
            'Alertamiento sísmico y automatización de protocolos.',
            'Automatización de corporativos, industria, minas, edificios públicos y transporte.',
            'Monitoreo 7 x 24 con acelerómetros y sensores de última generación.',
            'Diagnóstico post-sismo inmediato con modelos digitales para recuperarse en horas.',
            'Internet satelital de respaldo ante cortes terrestres o desastres naturales.',
        ],
    ],
    'smarttag' => [
        'pilar' => 'iot', 'nombre' => 'SmartTag', 'sub' => 'Etiquetas electrónicas de anaquel (ESL)', 'icono' => 'bi-tags',
        'resumen' => 'Precios y promociones actualizados en tiempo real en todos tus anaqueles.',
        'reto' => 'Actualizar precios manualmente en cientos de anaqueles consume horas, genera errores y retrasa las promociones.',
        'incluye' => [
            'Pantallas de papel electrónico para precios y promociones en tiempo real.',
            'Actualización centralizada sin intervención manual.',
            'Integración con ERP/POS para sincronizar precios e inventario.',
            'Etiquetas de bajo consumo con batería de hasta 5 años.',
            'Control de miles de etiquetas desde una sola plataforma.',
        ],
    ],

    // --- Cadena de suministro ---
    'omniflow' => [
        'pilar' => 'cadena', 'nombre' => 'OmniFlow', 'sub' => 'Almacenes inteligentes con RFID', 'icono' => 'bi-upc-scan',
        'resumen' => 'Inventarios en minutos con RFID, código de barras y WMS en la nube.',
        'reto' => 'Inventarios que toman días, activos perdidos en el CEDIS y etiquetado que falla en operación.',
        'incluye' => [
            'Conteo masivo e instantáneo por RFID: cientos de etiquetas sin línea de vista.',
            'Tags RFID activos y pasivos para inventario, activos, acceso y transporte.',
            'Beacons para localizar activos, personas y vehículos, con sensores ambientales.',
            'WMS en la nube: visibilidad en tiempo real, alertas y tableros operativos.',
            'Soluciones agro para documentar actividades, personal y producción.',
        ],
    ],
    'impresion' => [
        'pilar' => 'cadena', 'nombre' => 'Impresión y etiquetado', 'sub' => 'Códigos, RFID y tarjetas', 'icono' => 'bi-printer',
        'resumen' => 'Impresoras móviles, de escritorio e industriales con consumibles certificados.',
        'reto' => 'Etiquetas ilegibles o que se despegan, que frenan la operación y generan errores de envío.',
        'incluye' => [
            'Impresoras móviles para etiquetar en cualquier punto de la operación.',
            'Impresoras de escritorio para volúmenes medios.',
            'Impresoras industriales de alta resistencia para entornos exigentes.',
            'Consumibles certificados: etiquetas térmicas, plastificadas, mangas y ribbons.',
            'Impresión y codificación de etiquetas RFID.',
        ],
    ],
    'movilidad' => [
        'pilar' => 'cadena', 'nombre' => 'Movilidad y captura', 'sub' => 'Hardware de uso rudo', 'icono' => 'bi-phone',
        'resumen' => 'Computadoras móviles, escáneres y wearables para trabajar manos libres.',
        'reto' => 'Captura manual de datos, lenta y con errores, en almacenes y piso de venta.',
        'incluye' => [
            'Computadoras móviles y tabletas Android resistentes para entornos extremos.',
            'Escáneres híbridos 1D/2D + RFID en un solo dispositivo.',
            'Wearables para escaneo manos libres en picking.',
            'Lectores RFID portátiles para inventario y rastreo de activos.',
            'Marcas líderes: Zebra, Honeywell, Unitech y Datalogic.',
        ],
    ],
    'amr' => [
        'pilar' => 'cadena', 'nombre' => 'Robots AMR', 'sub' => 'Robots móviles autónomos', 'icono' => 'bi-robot',
        'resumen' => 'Robots que mueven, surten y clasifican mercancía de forma autónoma.',
        'reto' => 'Recorridos largos y tiempos muertos al mover y preparar pedidos dentro del almacén.',
        'incluye' => [
            'AMR de transporte que automatizan el movimiento de materiales.',
            'AMR de picking (goods-to-person) que llevan el inventario al operario.',
            'AMR de clasificación para preparar pedidos con alta velocidad y precisión.',
            'Navegación autónoma con sensores, cámaras y mapas digitales.',
            'Integración con tu WMS.',
        ],
    ],
    'servicio' => [
        'pilar' => 'cadena', 'nombre' => 'Centro de servicio', 'sub' => 'Soporte y mantenimiento certificado', 'icono' => 'bi-tools',
        'resumen' => 'Diagnóstico, mantenimiento y planes de protección para tus equipos.',
        'reto' => 'Equipos detenidos por fallas sin diagnóstico y sin un taller que responda rápido.',
        'incluye' => [
            'Centro certificado en impresoras de código de barras, lectores y cómputo móvil.',
            'Diagnóstico técnico preciso de fallas y bajo rendimiento.',
            'Mantenimiento preventivo y correctivo con personal certificado.',
            'Planes de protección personalizados.',
            'Cambio de piezas cuando es necesario.',
        ],
    ],

    // --- Conectividad ---
    'corenet' => [
        'pilar' => 'conectividad', 'nombre' => 'CoreNet', 'sub' => 'Conectividad continua', 'icono' => 'bi-hdd-network',
        'resumen' => 'Internet dedicado simétrico 7 x 24 con SLA e interconexión entre sedes.',
        'reto' => 'Caídas de Internet que paran la operación, sin interconexión segura entre sedes y sin respaldo ante contingencias.',
        'incluye' => [
            'Internet dedicado simétrico 7 x 24 con SLA y concesión oficial.',
            'Interconexión LAN to LAN por fibra óptica y microondas entre sedes y plantas.',
            'VPN corporativas cifradas para home office y acceso remoto seguro.',
            'WiFi 6 de alta densidad para convenciones y campus industriales.',
            'Respaldo satelital y LTE/4G automático.',
        ],
    ],
    'voice365' => [
        'pilar' => 'conectividad', 'nombre' => 'Voice365', 'sub' => 'Comunicación unificada', 'icono' => 'bi-headset',
        'resumen' => 'Telefonía IP en la nube: voz, datos y video con un solo proveedor.',
        'reto' => 'Telefonía obsoleta y costosa, empresa incomunicada ante contingencias y sedes desconectadas entre sí.',
        'incluye' => [
            'Telefonía IP en la nube: IVR, números nacionales e internacionales y extensiones ilimitadas.',
            'VPN de voz cifrada entre sedes.',
            'Internet satelital de respaldo ante cortes terrestres o desastres naturales.',
            'LTE/4G como respaldo automático para operaciones críticas.',
            'Voz, datos y video: un contrato, un proveedor, un soporte.',
        ],
    ],

    // --- TI y Software ---
    'cyberguard' => [
        'pilar' => 'ti', 'nombre' => 'CyberGuard', 'sub' => 'Ciberseguridad 360°', 'icono' => 'bi-shield-check',
        'resumen' => 'Firewalls de nueva generación, VPN cifradas y cumplimiento normativo.',
        'reto' => 'Miedo al ransomware, personal en home office sin control perimetral y necesidad de cumplir normas corporativas.',
        'incluye' => [
            'Firewalls NGFW: inspección profunda y bloqueo proactivo de intrusiones 7 x 24.',
            'VPN AES-256 para trabajo remoto seguro.',
            'Auditorías alineadas a ISO 27001, COBIT 2019 e ITIL.',
            'Planes de recuperación ante desastres (DRP) y pruebas de resiliencia.',
            'Diseño de redes seguras con ingenieros certificados.',
        ],
    ],
    'devsoft' => [
        'pilar' => 'ti', 'nombre' => 'DevSoft', 'sub' => 'Software a la medida', 'icono' => 'bi-code-slash',
        'resumen' => 'Sistemas, apps e integraciones que se adaptan a tu operación.',
        'reto' => 'Software comercial que no encaja, operaciones manuales sin automatizar y falta de dirección tecnológica.',
        'incluye' => [
            'Desarrollo de sistemas, apps y plataformas de comercio electrónico a la medida.',
            'Automatización de flujos e integración con ERP, CRM y terceros vía API.',
            'Integración IoT + software: sensores y dispositivos en un sistema unificado.',
            'Hosting y servidores de alta disponibilidad para sistemas críticos.',
            'vCTO: dirección de tecnología externa bajo demanda.',
        ],
    ],
];

$INDUSTRIAS = [
    ['icono' => 'bi-shop', 'nombre' => 'Retail y autoservicio', 'texto' => 'Etiquetas electrónicas, conteo de personas, prevención de pérdidas e inventario RFID.'],
    ['icono' => 'bi-truck', 'nombre' => 'Logística y CEDIS', 'texto' => 'Trazabilidad RFID, WMS, robots AMR y gestión de andenes con video.'],
    ['icono' => 'bi-gear-wide-connected', 'nombre' => 'Manufactura', 'texto' => 'Detección de EPP, control de calidad con RFID y monitoreo de maquinaria.'],
    ['icono' => 'bi-car-front', 'nombre' => 'Automotriz y estacionamientos', 'texto' => 'Scardetection, cámaras LPR y control de accesos vehiculares.'],
    ['icono' => 'bi-bank', 'nombre' => 'Gobierno y municipios', 'texto' => 'Centros C2, videovigilancia urbana y monitoreo sísmico.'],
    ['icono' => 'bi-cart-check', 'nombre' => 'Comercio electrónico', 'texto' => 'Almacenes inteligentes, etiquetado y conectividad de misión crítica.'],
    ['icono' => 'bi-buildings', 'nombre' => 'Corporativos', 'texto' => 'Internet dedicado, ciberseguridad, telefonía IP y control de acceso.'],
    ['icono' => 'bi-flower1', 'nombre' => 'Agro', 'texto' => 'Aplicaciones para documentar actividades agrícolas, personal y producción.'],
];

// Certificaciones y alianzas (logos en img/certificaciones/)
$CERTIFICACIONES = [
    ['archivo' => 'zebra.png', 'nombre' => 'Zebra Premier Business Partner · Advanced Printer Repair Specialist'],
    ['archivo' => 'honeywell.png', 'nombre' => 'Honeywell Platinum Performance Partner'],
    ['archivo' => 'unitech.png', 'nombre' => 'Unitech VASP Certified Partner'],
    ['archivo' => 'tsc.png', 'nombre' => 'TSC Power Partner Platinum'],
    ['archivo' => 'sato.png', 'nombre' => 'SATO Prestige Partner Program'],
    ['archivo' => 'opticon.png', 'nombre' => 'Opticon Official Partner'],
    ['archivo' => 'impinj.png', 'nombre' => 'Impinj Partner Network Silver'],
    ['archivo' => 'hikvision.png', 'nombre' => 'Hikvision'],
    ['archivo' => 'syscom.svg', 'nombre' => 'Syscom'],
    ['archivo' => 'mikrotik.png', 'nombre' => 'MikroTik'],
    ['archivo' => 'teltonika.png', 'nombre' => 'Teltonika'],
];

// Opciones de "Me interesa": soluciones del portafolio + residencial
$INTERESES = [];
foreach ($SOLUCIONES as $slug => $s) {
    $INTERESES[$slug] = $s['nombre'] . ' · ' . $s['sub'];
}
$INTERESES['residencial'] = 'Internet residencial';
$INTERESES['otro'] = 'Otro';

/** Soluciones de un pilar. */
function soluciones($pilar)
{
    global $SOLUCIONES;
    return array_filter($SOLUCIONES, function ($s) use ($pilar) { return $s['pilar'] === $pilar; });
}

/** URL del detalle de una solucion dentro de la pagina de su pilar. */
function solucion_url($slug)
{
    global $SOLUCIONES, $PILARES;
    return $PILARES[$SOLUCIONES[$slug]['pilar']]['url'] . '#' . $slug;
}

/** Texto de un interes guardado, incluidas opciones de la version anterior del sitio. */
function interes_texto($clave)
{
    global $INTERESES;
    $anteriores = ['corporativo' => 'Internet corporativo / dedicado', 'eventos' => 'Internet para eventos', 'respaldo' => 'Respaldo 4G / 5G'];
    if (isset($INTERESES[$clave])) return $INTERESES[$clave];
    return isset($anteriores[$clave]) ? $anteriores[$clave] : (string) $clave;
}

/** Ruta a un documento legal codificada para usarse en href. */
function legal_url($archivo)
{
    return 'legales/' . rawurlencode($archivo);
}
