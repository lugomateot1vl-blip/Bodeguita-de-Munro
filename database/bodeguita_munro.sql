CREATE DATABASE IF NOT EXISTS bodeguita_munro CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE bodeguita_munro;

CREATE TABLE IF NOT EXISTS administradores (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  usuario VARCHAR(80) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  creado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS bebidas (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(140) NOT NULL,
  bodega VARCHAR(120) NOT NULL DEFAULT '',
  variedad VARCHAR(40) NOT NULL DEFAULT 'Tinto',
  region VARCHAR(120) NOT NULL DEFAULT '',
  anio SMALLINT UNSIGNED NOT NULL,
  precio DECIMAL(12,2) NOT NULL,
  stock INT UNSIGNED NOT NULL DEFAULT 0,
  imagen VARCHAR(500) NOT NULL DEFAULT '',
  descripcion VARCHAR(500) NOT NULL DEFAULT '',
  activa TINYINT(1) NOT NULL DEFAULT 1,
  destacada TINYINT(1) NOT NULL DEFAULT 0,
  creado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_bebidas_tienda (activa, destacada, nombre)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS pedidos (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  cliente_nombre VARCHAR(100) NOT NULL,
  cliente_email VARCHAR(140) NOT NULL,
  cliente_telefono VARCHAR(40) NOT NULL,
  modalidad ENUM('Retiro por el local','Envío a domicilio') NOT NULL,
  domicilio VARCHAR(180) NOT NULL DEFAULT '',
  nota VARCHAR(300) NOT NULL DEFAULT '',
  total DECIMAL(12,2) NOT NULL,
  estado ENUM('Pendiente','Confirmado','Preparando','Listo para retirar','En camino','Entregado','Cancelado') NOT NULL DEFAULT 'Pendiente',
  creado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_pedidos_fecha (creado_en),
  INDEX idx_pedidos_estado (estado)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS pedido_items (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  pedido_id BIGINT UNSIGNED NOT NULL,
  bebida_id INT UNSIGNED NULL,
  nombre_bebida VARCHAR(140) NOT NULL,
  cantidad SMALLINT UNSIGNED NOT NULL,
  precio_unitario DECIMAL(12,2) NOT NULL,
  CONSTRAINT fk_item_pedido FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE,
  CONSTRAINT fk_item_bebida FOREIGN KEY (bebida_id) REFERENCES bebidas(id) ON DELETE SET NULL
) ENGINE=InnoDB;

INSERT INTO bebidas (nombre,bodega,variedad,region,anio,precio,stock,imagen,descripcion,activa,destacada) VALUES
('Malbec de la casa','Selección Bodeguita','Tinto','Mendoza · Argentina',2022,14500,12,'','Fruta roja, un final amable y ganas de otra copa.',1,1),
('Cabernet de altura','Finca Las Nubes','Tinto','Valle de Cafayate · Salta',2021,19800,8,'','Intenso y especiado, ideal para una mesa larga.',1,1),
('Bonarda del Este','Bodega El Viento','Tinto','San Martín · Mendoza',2022,12600,10,'','Jugoso, fresco y muy versátil para compartir.',1,0),
('Blend de montaña','Altos del Camino','Tinto','Valle de Uco · Mendoza',2020,24900,5,'','Un corte elegante para acompañar una buena charla.',1,0),
('Chardonnay fresco','Viñas del Sol','Blanco','Luján de Cuyo · Mendoza',2023,15800,9,'','Notas cítricas, fruta blanca y paso sedoso.',1,0),
('Torrontés de altura','La Quebrada','Blanco','Cafayate · Salta',2023,13200,11,'','Aromático, floral y perfecto para abrir la noche.',1,0),
('Rosado de verano','Cauce','Rosado','Valle de Uco · Mendoza',2023,11900,7,'','Fresco y frutado, para servir bien frío.',1,0),
('Brut naturaleza','Casa del Parque','Espumante','Maipú · Mendoza',2022,21700,6,'','Burbujas delicadas para brindar sin esperar una ocasión.',1,0)
ON DUPLICATE KEY UPDATE nombre=VALUES(nombre);
