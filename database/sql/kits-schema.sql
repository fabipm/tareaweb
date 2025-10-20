-- Schema para soporte de Kits Arduino

-- Crear tabla kits
CREATE TABLE IF NOT EXISTS `kits` (
  `id_kit` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_kit` varchar(60) NOT NULL,
  `nombre_kit` varchar(120) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `version` varchar(50) DEFAULT NULL,
  `cantidad_total` int(11) NOT NULL DEFAULT 0,
  `cantidad_disponible` int(11) NOT NULL DEFAULT 0,
  `estado` enum('Activo','Inactivo') NOT NULL DEFAULT 'Activo',
  `ubicacion` varchar(100) DEFAULT NULL,
  `creado_en` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_kit`),
  UNIQUE KEY `codigo_kit` (`codigo_kit`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Componentes por kit
CREATE TABLE IF NOT EXISTS `kit_componentes` (
  `id_componente` int(11) NOT NULL AUTO_INCREMENT,
  `id_kit` int(11) NOT NULL,
  `codigo_component` varchar(60) DEFAULT NULL,
  `nombre_component` varchar(150) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `cantidad_por_kit` int(11) NOT NULL DEFAULT 1,
  `requiere_serial` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_componente`),
  KEY `id_kit` (`id_kit`),
  CONSTRAINT `kit_componentes_ibfk_1` FOREIGN KEY (`id_kit`) REFERENCES `kits` (`id_kit`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Items solicitados por solicitud (detalle de solicitudes de kit)
CREATE TABLE IF NOT EXISTS `solicitud_kit_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_solicitud` int(11) NOT NULL,
  `id_kit` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL DEFAULT 1,
  `nota` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_solicitud` (`id_solicitud`),
  KEY `id_kit` (`id_kit`),
  CONSTRAINT `solicitud_kit_items_ibfk_1` FOREIGN KEY (`id_solicitud`) REFERENCES `solicitudes` (`id_solicitud`) ON DELETE CASCADE,
  CONSTRAINT `solicitud_kit_items_ibfk_2` FOREIGN KEY (`id_kit`) REFERENCES `kits` (`id_kit`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Alter table solicitudes: agregar tipo_recurso, id_kit, proyecto y cantidad_kit
ALTER TABLE `solicitudes`
  ADD COLUMN IF NOT EXISTS `tipo_recurso` enum('Servidor','Kit') NOT NULL DEFAULT 'Servidor',
  ADD COLUMN IF NOT EXISTS `id_kit` int(11) NULL,
  ADD COLUMN IF NOT EXISTS `proyecto` varchar(150) DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS `cantidad_kit` int(11) NOT NULL DEFAULT 1;

ALTER TABLE `solicitudes`
  ADD CONSTRAINT IF NOT EXISTS `fk_solicitudes_kit` FOREIGN KEY (`id_kit`) REFERENCES `kits`(`id_kit`) ON DELETE SET NULL;
