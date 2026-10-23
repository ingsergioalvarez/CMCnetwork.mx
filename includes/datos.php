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

// ---------- Tecnologias de conectividad (carrusel de la portada y de Conectividad) ----------
$TECNOLOGIAS = [
    'fibra' => [
        'titulo' => 'Internet dedicado por fibra óptica', 'insignia' => 'Simétrico 1:1', 'icono' => 'bi-lightning-charge',
        'texto' => 'La misma velocidad de subida y bajada, sin compartir el ancho de banda, sobre nuestra red propia.',
        'puntos' => ['Velocidad simétrica garantizada por contrato', 'Direcciones IP fijas públicas', 'SLA de 99.95% y monitoreo NOC 7 x 24',
                     'Red propia en 6 estados y cobertura extendida en 19', 'Escalable conforme crece tu operación'],
        'ideal' => 'Corporativos, plantas, hospitales, call centers y escuelas.',
    ],
    'microondas' => [
        'titulo' => 'Enlaces por microondas', 'insignia' => 'Despliegue rápido', 'icono' => 'bi-broadcast-pin',
        'texto' => 'Enlaces punto a punto dedicados donde la fibra no llega, o como segunda ruta para que tu operación no se detenga.',
        'puntos' => ['Instalación sin obra civil ni canalización', 'Ruta de respaldo con conmutación automática', 'Alta capacidad entre sitios',
                     'Capacidad escalable', 'Monitoreo proactivo del enlace'],
        'ideal' => 'Parques industriales, naves, obras y sitios sin fibra.',
    ],
    'interconexion' => [
        'titulo' => 'Redes privadas entre sedes', 'insignia' => 'Acceso seguro', 'icono' => 'bi-diagram-3',
        'texto' => 'Une sucursales, plantas y oficinas en una sola red privada, cifrada y administrada por nosotros.',
        'puntos' => ['Interconexión LAN to LAN por fibra y microondas', 'VPN cifradas para home office y acceso remoto', 'Priorización del tráfico crítico',
                     'Configuración y administración incluidas', 'Integración con tu firewall'],
        'ideal' => 'Empresas con sucursales, cadenas comerciales y plantas.',
    ],
    'lte' => [
        'titulo' => 'Internet LTE empresarial', 'insignia' => '32 estados', 'icono' => 'bi-reception-4',
        'texto' => 'Conectividad en todo el país para zonas sin red terrestre (offnet), sitios temporales o como respaldo automático de tu enlace principal.',
        'puntos' => ['Respaldo automático (failover) sin intervención', 'Instalación en días, sin obra civil', 'Gestión y monitoreo remoto desde la nube',
                     'Agregación de enlaces para más capacidad', 'Ideal para kioscos, sucursales y obras'],
        'ideal' => 'Retail, sucursales, sitios temporales y respaldo de enlaces.',
    ],
    'satelital' => [
        'titulo' => 'Internet satelital', 'insignia' => 'Donde nada más llega', 'icono' => 'bi-globe-americas',
        'texto' => 'Conectividad para sitios remotos y respaldo ante cortes terrestres o desastres naturales, en cualquier punto del país.',
        'puntos' => ['Cobertura en los 32 estados', 'Respaldo ante contingencias y desastres naturales', 'Operación en campo, minas, ranchos y obras',
                     'Integración con tu red corporativa', 'Soporte 7 x 24'],
        'ideal' => 'Minería, agro, energía, construcción y protección civil.',
    ],
];

// ---------- Cobertura de red ----------
// Nivel de cada estado. Las capas del mapa (img/cobertura) ya vienen pintadas: si un estado
// cambia de nivel tambien hay que rehacer la imagen de esa capa.
$COBERTURA = [
    'cifras' => [
        ['32 / 32', 'Estados con cobertura nacional activa'],
        ['4 tecnologías', 'Fibra óptica · Microondas · Satelital · LTE'],
        ['6 estados', 'Red propia directa (CDMX, Edomex, Hgo, Pue, Mor, Tlax)'],
        ['99.95%', 'SLA de disponibilidad garantizado, NOC 7 x 24'],
    ],
    'niveles' => [
        'propia' => [
            'nombre' => 'Red propia', 'color' => '#00F0FF', 'img' => 'propia',
            'tecnologia' => 'Fibra óptica + microondas',
            'texto' => 'Infraestructura operada directamente por CMC.',
        ],
        'extendida' => [
            'nombre' => 'Cobertura extendida', 'color' => '#3D6BFF', 'img' => 'extendida',
            'tecnologia' => 'Fibra óptica / microondas',
            'texto' => 'Enlaces de fibra y microondas a través de nuestra cobertura extendida.',
        ],
        'interconexion' => [
            'nombre' => 'Alianzas de conectividad', 'color' => '#FFFFFF', 'img' => 'interconexion',
            'tecnologia' => 'Según el aliado disponible en la zona',
            'texto' => 'Validamos tu enlace mediante nuestras alianzas de conectividad.',
        ],
    ],
    // Capa nacional: no es un nivel de estado, cubre los 32
    'lte' => [
        'nombre' => 'LTE y satelital', 'color' => '#0A7CFC', 'img' => 'lte-satelital',
        'tecnologia' => 'LTE y enlace satelital',
        'texto' => 'Cobertura en los 32 estados mediante LTE y satelital: para zonas sin red terrestre (offnet) y como respaldo automático.',
    ],
    'estados' => [
        ['Aguascalientes', 'extendida'], ['Baja California', 'extendida'], ['Baja California Sur', 'interconexion'],
        ['Campeche', 'extendida'], ['Chiapas', 'extendida'], ['Chihuahua', 'extendida'], ['Ciudad de México', 'propia'],
        ['Coahuila', 'extendida', ['Torreón', 'Saltillo', 'Piedras Negras']], ['Colima', 'interconexion'],
        ['Durango', 'extendida'], ['Estado de México', 'propia'], ['Guanajuato', 'extendida'], ['Guerrero', 'interconexion'],
        ['Hidalgo', 'propia'], ['Jalisco', 'extendida', ['Guadalajara']], ['Michoacán', 'interconexion'], ['Morelos', 'propia'],
        ['Nayarit', 'interconexion'], ['Nuevo León', 'extendida'], ['Oaxaca', 'interconexion'], ['Puebla', 'propia'],
        ['Querétaro', 'extendida'], ['Quintana Roo', 'extendida'], ['San Luis Potosí', 'extendida'], ['Sinaloa', 'extendida'],
        ['Sonora', 'extendida', ['San Luis Río Colorado', 'Hermosillo', 'Nogales']], ['Tabasco', 'interconexion'],
        ['Tamaulipas', 'extendida'], ['Tlaxcala', 'propia'], ['Veracruz', 'extendida'], ['Yucatán', 'extendida', ['Mérida']],
        ['Zacatecas', 'extendida'],
    ],
];

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
        'titulo' => ['Enlaces dedicados', 'por fibra óptica y microondas'],
        'texto'  => 'Red propia en el centro del país, cobertura en los 32 estados con aliados, LTE y satelital, SLA de 99.95% y telefonía IP en la nube.',
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
        'alcance' => [
            ['Diagnóstico de perímetro', 'Recorrido del sitio para definir ubicación y tipo de cámara, puntos ciegos y la analítica que realmente necesitas.'],
            ['Cámaras cognitivas con IA', 'Detección de personas y vehículos que filtra falsas alarmas y enfoca la atención solo en amenazas reales.'],
            ['Búsqueda forense por atributos', 'Encuentra un evento en segundos por ropa, tipo de vehículo o accesorios, sin revisar horas de video.'],
            ['Reconocimiento facial', 'Alertas en tiempo real vinculadas a listas VIP o de restricción, donde el caso y la normativa lo permiten.'],
            ['Mapas de calor y conteo', 'Flujo de personas y zonas de mayor actividad para optimizar espacios y operación.'],
            ['Monitoreo térmico', 'Detección temprana de sobrecalentamientos en equipos o áreas críticas, antes de que se genere un incendio.'],
        ],
    ],
    'c2' => [
        'pilar' => 'iot', 'nombre' => 'Centros C2', 'sub' => 'Centros de comando y control', 'icono' => 'bi-display',
        'resumen' => 'Toda tu videovigilancia, sensores y alertas en un solo centro de monitoreo.',
        'reto' => 'Cámaras, sensores y alarmas operando por separado, sin una vista unificada para reaccionar a tiempo ante un incidente.',
        'alcance' => [
            ['Diseño del centro', 'Dimensionamiento de la sala, número de operadores, videowall y flujos de atención según tu operación.'],
            ['Videowall y estaciones de operador', 'Vista unificada de cámaras, sensores y alarmas para reaccionar a tiempo.'],
            ['Integración de plataformas', 'Videovigilancia, control de acceso, LPR y alertamiento en una sola plataforma de gestión.'],
            ['Protocolos y reportes', 'Procedimientos de respuesta, bitácoras e indicadores para la toma de decisiones.'],
            ['Conectividad con respaldo', 'Enlaces dedicados y rutas alternas para que el centro opere 7 x 24.'],
            ['Capacitación de operadores', 'Entrenamiento del personal para operar el centro desde el primer día.'],
        ],
    ],
    'scardetection' => [
        'pilar' => 'iot', 'nombre' => 'Scardetection', 'sub' => 'Detección de daños estéticos en autos', 'icono' => 'bi-car-front',
        'resumen' => 'Arco con cámaras y aplicación que detecta y documenta daños estéticos en cada vehículo.',
        'reto' => 'Inspecciones manuales lentas y sin evidencia que generan disputas por daños al recibir o entregar un vehículo.',
        'alcance' => [
            ['Arco de inspección', 'Estructura con cámaras que captura el vehículo completo a su paso, sin detener la operación.'],
            ['Detección automática de daños', 'Aplicación que identifica y señala daños estéticos en la carrocería.'],
            ['Evidencia con fecha y hora', 'Registro fotográfico de cada entrada y salida que respalda al negocio y al cliente.'],
            ['Historial por vehículo', 'Consulta de inspecciones anteriores para resolver reclamaciones con datos.'],
            ['Integración con tus procesos', 'Conexión con recepción, taller o estacionamiento según cómo opera tu negocio.'],
        ],
        'destacado' => ['Distribuidores de autos', 'Estacionamientos'],
    ],
    'lpr' => [
        'pilar' => 'iot', 'nombre' => 'Cámaras LPR', 'sub' => 'Lectura automática de placas', 'icono' => 'bi-123',
        'resumen' => 'Identifica vehículos por su placa para controlar accesos y generar alertas.',
        'reto' => 'Registro manual de vehículos en accesos, sin forma de saber quién entró, cuándo y si estaba autorizado.',
        'alcance' => [
            ['Lectura de placas', 'Cámaras especializadas para accesos, estacionamientos y vialidades, de día y de noche.'],
            ['Listas y alertas', 'Vehículos autorizados y restringidos con avisos en tiempo real.'],
            ['Automatización de accesos', 'Apertura de plumas y barreras al reconocer un vehículo autorizado.'],
            ['Historial consultable', 'Registro de entradas y salidas con imagen para auditoría.'],
            ['Integración', 'Conexión con centros C2 y control de acceso.'],
        ],
    ],
    'analitica' => [
        'pilar' => 'iot', 'nombre' => 'Analítica de video', 'sub' => 'Conteo de personas, caídas y gestos', 'icono' => 'bi-people',
        'resumen' => 'Convierte tus cámaras en sensores que cuentan, detectan y alertan.',
        'reto' => 'Horas de video que nadie revisa y eventos críticos que se descubren demasiado tarde.',
        'alcance' => [
            ['Conteo de personas', 'Afluencia en tiendas, plazas y oficinas por hora y por zona.'],
            ['Detección de caídas', 'Alertas inmediatas para atender a tiempo a quien lo necesita.'],
            ['Detección de gestos', 'Señales para pedir ayuda o activar acciones sin tocar un botón.'],
            ['Mapas de calor y permanencia', 'Qué zonas se visitan y cuánto tiempo, para mejorar distribución y operación.'],
            ['Tableros y reportes', 'Indicadores claros para la toma de decisiones.'],
            ['Sobre tu infraestructura', 'Cuando las cámaras son compatibles, la analítica se integra sobre lo que ya tienes.'],
        ],
    ],
    'epp' => [
        'pilar' => 'iot', 'nombre' => 'Detección de EPP', 'sub' => 'Seguridad industrial con visión artificial', 'icono' => 'bi-cone-striped',
        'resumen' => 'Verifica que tu personal use casco, chaleco y equipo de protección en planta.',
        'reto' => 'Accidentes y sanciones por personal que no usa su equipo de protección en zonas de riesgo.',
        'alcance' => [
            ['Detección de equipo de protección', 'Casco, chaleco y los elementos que definan tus normas de seguridad.'],
            ['Zonas de riesgo configurables', 'Reglas distintas por área de la planta.'],
            ['Alertas al supervisor', 'Avisos en tiempo real para corregir antes de un accidente.'],
            ['Evidencia para auditorías', 'Registros y reportes para seguridad e higiene.'],
            ['Cámaras existentes o nuevas', 'Aprovecha cámaras IP compatibles o se complementa con equipo nuevo.'],
        ],
    ],
    'acceso' => [
        'pilar' => 'iot', 'nombre' => 'Control de acceso', 'sub' => 'Automatización de entradas', 'icono' => 'bi-door-open',
        'resumen' => 'Tarjetas, biometría y reconocimiento facial para saber quién entra y cuándo.',
        'reto' => 'Accesos controlados con libretas o llaves, sin registro confiable de personas y visitantes.',
        'alcance' => [
            ['Identificación', 'Tarjeta, huella o reconocimiento facial según el nivel de seguridad que requiere cada zona.'],
            ['Barreras físicas', 'Torniquetes, plumas y cerraduras electromagnéticas.'],
            ['Visitantes y horarios', 'Registro de visitas y permisos por zona y horario.'],
            ['Reportes de asistencia', 'Información de accesos para recursos humanos y seguridad.'],
            ['Integración', 'Con videovigilancia, LPR y centros C2.'],
        ],
    ],
    'geoalert' => [
        'pilar' => 'iot', 'nombre' => 'GeoAlert', 'sub' => 'Protección y monitoreo sísmico', 'icono' => 'bi-activity',
        'resumen' => 'Alertamiento y monitoreo estructural para corporativos, industria y edificios públicos.',
        'reto' => 'Riesgo sísmico en corporativos y plantas, maquinaria vulnerable a vibraciones y sin diagnóstico después de un sismo.',
        'alcance' => [
            ['Alertamiento sísmico', 'Recepción de alertas y automatización de protocolos de evacuación y paro seguro.'],
            ['Monitoreo estructural 7 x 24', 'Acelerómetros y sensores de última generación que vigilan el comportamiento del inmueble.'],
            ['Diagnóstico post-sismo', 'Modelos digitales para evaluar el estado del inmueble y recuperar la operación en horas.'],
            ['Para cada sector', 'Corporativos, industria, minas, edificios públicos y transporte.'],
            ['Comunicación de respaldo', 'Internet satelital ante cortes terrestres o desastres naturales.'],
        ],
    ],
    'smarttag' => [
        'pilar' => 'iot', 'nombre' => 'SmartTag', 'sub' => 'Etiquetas electrónicas de anaquel (ESL)', 'icono' => 'bi-tags',
        'resumen' => 'Precios y promociones actualizados en tiempo real en todos tus anaqueles.',
        'reto' => 'Actualizar precios manualmente en cientos de anaqueles consume horas, genera errores y retrasa las promociones.',
        'alcance' => [
            ['Etiquetas de papel electrónico', 'Precios y promociones en tiempo real directamente en el anaquel.'],
            ['Gestión centralizada', 'Miles de etiquetas actualizadas desde una sola plataforma, sin intervención manual.'],
            ['Integración con ERP/POS', 'Precios e inventario sincronizados con tu sistema.'],
            ['Bajo consumo', 'Etiquetas con batería de hasta 5 años.'],
            ['Implementación por etapas', 'Piloto en una tienda y despliegue gradual al resto de la cadena.'],
        ],
    ],

    // --- Cadena de suministro ---
    'omniflow' => [
        'pilar' => 'cadena', 'nombre' => 'OmniFlow', 'sub' => 'Almacenes inteligentes con RFID', 'icono' => 'bi-upc-scan',
        'resumen' => 'Inventarios en minutos con RFID, código de barras y WMS en la nube.',
        'reto' => 'Inventarios que toman días, activos perdidos en el CEDIS y etiquetado que falla en operación.',
        'alcance' => [
            ['Inventario por RFID', 'Conteo masivo e instantáneo de cientos de etiquetas sin línea de vista.'],
            ['Tags activos y pasivos', 'Para inventario, activos, acceso y transporte, elegidos según el material y el entorno.'],
            ['Localización con beacons', 'Activos, personas y vehículos ubicados en tiempo real, con sensores ambientales.'],
            ['WMS en la nube', 'Visibilidad en tiempo real, alertas y tableros operativos.'],
            ['Código de barras', 'Como complemento donde el RFID no es la mejor opción.'],
            ['Soluciones agro', 'Documentación de actividades, personal y producción en campo.'],
        ],
    ],
    'impresion' => [
        'pilar' => 'cadena', 'nombre' => 'Impresión y etiquetado', 'sub' => 'Códigos, RFID y tarjetas', 'icono' => 'bi-printer',
        'resumen' => 'Impresoras móviles, de escritorio e industriales con consumibles certificados.',
        'reto' => 'Etiquetas ilegibles o que se despegan, que frenan la operación y generan errores de envío.',
        'alcance' => [
            ['Impresoras móviles', 'Para etiquetar en cualquier punto de la operación.'],
            ['Impresoras de escritorio', 'Para volúmenes medios en oficina, mostrador o almacén.'],
            ['Impresoras industriales', 'De alta resistencia para entornos exigentes y alto volumen.'],
            ['Consumibles certificados', 'Etiquetas térmicas, plastificadas, mangas y ribbons según la superficie y el ambiente.'],
            ['Etiquetas RFID', 'Impresión y codificación de etiquetas RFID.'],
        ],
    ],
    'movilidad' => [
        'pilar' => 'cadena', 'nombre' => 'Movilidad y captura', 'sub' => 'Hardware de uso rudo', 'icono' => 'bi-phone',
        'resumen' => 'Computadoras móviles, escáneres y wearables para trabajar manos libres.',
        'reto' => 'Captura manual de datos, lenta y con errores, en almacenes y piso de venta.',
        'alcance' => [
            ['Computadoras móviles y tabletas', 'Equipos Android resistentes para entornos extremos.'],
            ['Escáneres híbridos', 'Lectura 1D/2D + RFID en un solo dispositivo.'],
            ['Wearables', 'Escaneo manos libres para picking.'],
            ['Lectores RFID portátiles', 'Inventario y rastreo de activos.'],
            ['Marcas líderes', 'Zebra, Honeywell, Unitech y Datalogic, seleccionadas según tu operación.'],
        ],
    ],
    'amr' => [
        'pilar' => 'cadena', 'nombre' => 'Robots AMR', 'sub' => 'Robots móviles autónomos', 'icono' => 'bi-robot',
        'resumen' => 'Robots que mueven, surten y clasifican mercancía de forma autónoma.',
        'reto' => 'Recorridos largos y tiempos muertos al mover y preparar pedidos dentro del almacén.',
        'alcance' => [
            ['Estudio de flujos', 'Análisis de recorridos y volúmenes para definir cuántos robots y de qué tipo.'],
            ['AMR de transporte', 'Automatizan el movimiento de materiales dentro del almacén.'],
            ['AMR de picking', 'Goods-to-person: llevan el inventario al operario.'],
            ['AMR de clasificación', 'Preparación de pedidos con alta velocidad y precisión.'],
            ['Navegación autónoma', 'Sensores, cámaras y mapas digitales para moverse con seguridad.'],
            ['Integración con tu WMS', 'Los robots reciben y reportan tareas desde tu sistema.'],
        ],
    ],
    'servicio' => [
        'pilar' => 'cadena', 'nombre' => 'Centro de servicio', 'sub' => 'Soporte y mantenimiento certificado', 'icono' => 'bi-tools',
        'resumen' => 'Diagnóstico, mantenimiento y planes de protección para tus equipos.',
        'reto' => 'Equipos detenidos por fallas sin diagnóstico y sin un taller que responda rápido.',
        'alcance' => [
            ['Centro certificado', 'Impresoras de código de barras, lectores y cómputo móvil.'],
            ['Diagnóstico técnico', 'Identificación precisa de fallas y bajo rendimiento.'],
            ['Mantenimiento preventivo y correctivo', 'Con personal certificado.'],
            ['Planes de protección', 'Personalizados según tu parque de equipos.'],
            ['Refacciones', 'Cambio de piezas cuando es necesario.'],
        ],
    ],

    // --- Conectividad ---
    'corenet' => [
        'pilar' => 'conectividad', 'nombre' => 'CoreNet', 'sub' => 'Conectividad continua', 'icono' => 'bi-hdd-network',
        'resumen' => 'Internet dedicado simétrico 7 x 24 con SLA e interconexión entre sedes.',
        'reto' => 'Caídas de Internet que paran la operación, sin interconexión segura entre sedes y sin respaldo ante contingencias.',
        'alcance' => [
            ['Internet dedicado simétrico', 'Enlace 7 x 24 con SLA y concesión oficial, por fibra óptica o microondas.'],
            ['Interconexión entre sedes', 'LAN to LAN por fibra óptica y microondas entre oficinas, sedes y plantas.'],
            ['VPN corporativas', 'Acceso remoto cifrado para home office y sucursales.'],
            ['WiFi 6 de alta densidad', 'Para convenciones, campus industriales y espacios con muchos usuarios.'],
            ['Respaldo automático', 'Satelital y LTE/4G para que la operación no se detenga.'],
            ['Monitoreo NOC 7 x 24', 'Supervisión continua de tus enlaces.'],
        ],
    ],
    'voice365' => [
        'pilar' => 'conectividad', 'nombre' => 'Voice365', 'sub' => 'Comunicación unificada', 'icono' => 'bi-headset',
        'resumen' => 'Telefonía IP en la nube: voz, datos y video con un solo proveedor.',
        'reto' => 'Telefonía obsoleta y costosa, empresa incomunicada ante contingencias y sedes desconectadas entre sí.',
        'alcance' => [
            ['Telefonía IP en la nube', 'IVR, números nacionales e internacionales y extensiones ilimitadas.'],
            ['VPN de voz entre sedes', 'Comunicación cifrada entre oficinas.'],
            ['Respaldo satelital', 'Ante cortes terrestres o desastres naturales.'],
            ['Respaldo LTE/4G', 'Conmutación automática para operaciones críticas.'],
            ['Un solo proveedor', 'Voz, datos y video: un contrato y un soporte.'],
        ],
    ],

    // --- TI y Software ---
    'cyberguard' => [
        'pilar' => 'ti', 'nombre' => 'CyberGuard', 'sub' => 'Ciberseguridad 360°', 'icono' => 'bi-shield-check',
        'resumen' => 'Firewalls de nueva generación, VPN cifradas y cumplimiento normativo.',
        'reto' => 'Miedo al ransomware, personal en home office sin control perimetral y necesidad de cumplir normas corporativas.',
        'alcance' => [
            ['Firewalls NGFW', 'Inspección profunda y bloqueo proactivo de intrusiones 7 x 24.'],
            ['VPN AES-256', 'Trabajo remoto seguro para tu personal.'],
            ['Auditorías y cumplimiento', 'Alineadas a ISO 27001, COBIT 2019 e ITIL.'],
            ['Recuperación ante desastres', 'Planes DRP y pruebas de resiliencia.'],
            ['Diseño de redes seguras', 'Arquitectura definida por ingenieros certificados.'],
        ],
    ],
    'devsoft' => [
        'pilar' => 'ti', 'nombre' => 'DevSoft', 'sub' => 'Software a la medida', 'icono' => 'bi-code-slash',
        'resumen' => 'Sistemas, apps e integraciones que se adaptan a tu operación.',
        'reto' => 'Software comercial que no encaja, operaciones manuales sin automatizar y falta de dirección tecnológica.',
        'alcance' => [
            ['Desarrollo a la medida', 'Sistemas, apps y plataformas de comercio electrónico.'],
            ['Automatización e integración', 'Flujos automatizados e integración con ERP, CRM y terceros vía API.'],
            ['IoT + software', 'Sensores y dispositivos en un sistema unificado.'],
            ['Hosting de alta disponibilidad', 'Servidores para sistemas críticos.'],
            ['vCTO', 'Dirección de tecnología externa bajo demanda.'],
        ],
    ],
];

$INDUSTRIAS = [
    ['icono' => 'bi-shop', 'img' => 'retail', 'nombre' => 'Retail y autoservicio', 'texto' => 'Etiquetas electrónicas, conteo de personas, prevención de pérdidas e inventario RFID.'],
    ['icono' => 'bi-truck', 'img' => 'logistica', 'nombre' => 'Logística y CEDIS', 'texto' => 'Trazabilidad RFID, WMS, robots AMR y gestión de andenes con video.'],
    ['icono' => 'bi-gear-wide-connected', 'img' => 'manufactura', 'nombre' => 'Manufactura', 'texto' => 'Detección de EPP, control de calidad con RFID y monitoreo de maquinaria.'],
    ['icono' => 'bi-car-front', 'img' => 'automotriz', 'nombre' => 'Automotriz y estacionamientos', 'texto' => 'Scardetection, cámaras LPR y control de accesos vehiculares.'],
    ['icono' => 'bi-bank', 'img' => 'gobierno', 'nombre' => 'Gobierno y municipios', 'texto' => 'Centros C2, videovigilancia urbana y monitoreo sísmico.'],
    ['icono' => 'bi-cart-check', 'img' => 'ecommerce', 'nombre' => 'Comercio electrónico', 'texto' => 'Almacenes inteligentes, etiquetado y conectividad de misión crítica.'],
    ['icono' => 'bi-buildings', 'img' => 'corporativos', 'nombre' => 'Corporativos', 'texto' => 'Internet dedicado, ciberseguridad, telefonía IP y control de acceso.'],
    ['icono' => 'bi-flower1', 'img' => 'agro', 'nombre' => 'Agro', 'texto' => 'Aplicaciones para documentar actividades agrícolas, personal y producción.'],
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

/** URL de la pagina de detalle de una solucion. */
function solucion_url($slug)
{
    return '/solucion.php?s=' . rawurlencode($slug);
}

/** URL de la pagina de contacto; guarda la pagina de origen y, si se indica, el interes y un mensaje. */
function contacto_url($interes = '', $mensaje = '')
{
    global $pagina;
    $q = array_filter(['interes' => $interes, 'mensaje' => $mensaje, 'de' => isset($pagina) ? $pagina : '']);
    return '/contacto.php' . ($q ? '?' . http_build_query($q, '', '&', PHP_QUERY_RFC3986) : '');
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
