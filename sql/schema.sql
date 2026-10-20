-- Esquema CMC Service Network (compatible con MariaDB 5.5 / MySQL 5.5+)
-- Indices de texto limitados a 191 caracteres por utf8mb4 + limite de 767 bytes.

SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS vendedores (
  id            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  slug          VARCHAR(60)  NOT NULL,
  nombre        VARCHAR(120) NOT NULL,
  puesto        VARCHAR(120) NOT NULL DEFAULT '',
  telefono      VARCHAR(30)  NOT NULL DEFAULT '',
  whatsapp      VARCHAR(30)  NOT NULL DEFAULT '',
  email         VARCHAR(150) NOT NULL DEFAULT '',
  foto          MEDIUMBLOB   NULL,
  foto_tipo     VARCHAR(30)  NULL,
  activo        TINYINT(1)   NOT NULL DEFAULT 1,
  creado        TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uk_vendedores_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS eventos (
  id            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  slug          VARCHAR(60)  NOT NULL,
  nombre        VARCHAR(150) NOT NULL,
  lugar         VARCHAR(150) NOT NULL DEFAULT '',
  fecha_inicio  DATE         NULL,
  fecha_fin     DATE         NULL,
  creado        TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uk_eventos_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Clave/valor: tarjeta_vendedor_id, tarjeta_evento_id
CREATE TABLE IF NOT EXISTS ajustes (
  clave         VARCHAR(60)  NOT NULL,
  valor         VARCHAR(255) NULL,
  PRIMARY KEY (clave)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS prospectos (
  id            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  origen        VARCHAR(20)  NOT NULL,              -- web | tarjeta
  pagina        VARCHAR(60)  NOT NULL DEFAULT '',
  nombre        VARCHAR(120) NOT NULL,
  empresa       VARCHAR(150) NOT NULL DEFAULT '',
  email         VARCHAR(150) NOT NULL DEFAULT '',
  telefono      VARCHAR(30)  NOT NULL DEFAULT '',
  interes       VARCHAR(40)  NOT NULL DEFAULT '',
  mensaje       TEXT         NULL,
  vendedor_id   INT UNSIGNED NULL,
  evento_id     INT UNSIGNED NULL,
  estado        VARCHAR(20)  NOT NULL DEFAULT 'nuevo', -- nuevo | contactado | cliente | descartado
  notas         TEXT         NULL,
  notificado    TINYINT(1)   NOT NULL DEFAULT 0,
  ip            VARCHAR(45)  NOT NULL DEFAULT '',
  user_agent    VARCHAR(255) NOT NULL DEFAULT '',
  creado        TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY ix_prospectos_creado (creado),
  KEY ix_prospectos_vendedor (vendedor_id),
  KEY ix_prospectos_evento (evento_id),
  KEY ix_prospectos_ip (ip, creado),
  CONSTRAINT fk_prospectos_vendedor FOREIGN KEY (vendedor_id) REFERENCES vendedores (id) ON DELETE SET NULL,
  CONSTRAINT fk_prospectos_evento FOREIGN KEY (evento_id) REFERENCES eventos (id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS usuarios_admin (
  id             INT UNSIGNED NOT NULL AUTO_INCREMENT,
  usuario        VARCHAR(60)  NOT NULL,
  nombre         VARCHAR(120) NOT NULL DEFAULT '',
  password_hash  VARCHAR(255) NOT NULL,
  ultimo_acceso  DATETIME     NULL,
  creado         TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uk_usuarios_admin_usuario (usuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Intentos de login fallidos (bloqueo por fuerza bruta)
CREATE TABLE IF NOT EXISTS login_intentos (
  id            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  ip            VARCHAR(45)  NOT NULL,
  creado        TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY ix_login_intentos_ip (ip, creado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
