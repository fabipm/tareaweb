-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         10.4.32-MariaDB - mariadb.org binary distribution
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.11.0.7065
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para prestamo_servidores
CREATE DATABASE IF NOT EXISTS `prestamo_servidores` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `prestamo_servidores`;

-- Volcando estructura para tabla prestamo_servidores.autorizaciones
CREATE TABLE IF NOT EXISTS `autorizaciones` (
  `id_autorizacion` int(11) NOT NULL AUTO_INCREMENT,
  `id_solicitud` int(11) NOT NULL,
  `autorizado_por` int(11) NOT NULL,
  `observaciones` text DEFAULT NULL,
  `fecha_autorizacion` datetime DEFAULT NULL,
  `estado` enum('Pendiente','Aprobada','Rechazada') DEFAULT 'Pendiente',
  PRIMARY KEY (`id_autorizacion`),
  KEY `id_solicitud` (`id_solicitud`),
  KEY `autorizado_por` (`autorizado_por`),
  CONSTRAINT `autorizaciones_ibfk_1` FOREIGN KEY (`id_solicitud`) REFERENCES `solicitudes` (`id_solicitud`),
  CONSTRAINT `autorizaciones_ibfk_2` FOREIGN KEY (`autorizado_por`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla prestamo_servidores.autorizaciones: ~4 rows (aproximadamente)
INSERT INTO `autorizaciones` (`id_autorizacion`, `id_solicitud`, `autorizado_por`, `observaciones`, `fecha_autorizacion`, `estado`) VALUES
	(1, 1, 1, 'En revisión técnica.', '2025-10-08 14:30:00', 'Pendiente'),
	(2, 6, 1, NULL, '2025-10-15 19:12:40', 'Rechazada'),
	(3, 4, 1, NULL, '2025-10-15 19:14:09', 'Aprobada'),
	(4, 1, 1, NULL, '2025-10-15 19:14:31', 'Aprobada');

-- Volcando estructura para tabla prestamo_servidores.autorizaciones_kit
CREATE TABLE IF NOT EXISTS `autorizaciones_kit` (
  `id_autorizacion_kit` int(11) NOT NULL AUTO_INCREMENT,
  `id_solicitud_kit` int(11) NOT NULL,
  `autorizado_por` int(11) NOT NULL,
  `observaciones` text DEFAULT NULL,
  `fecha_autorizacion` datetime DEFAULT NULL,
  `estado` enum('Pendiente','Aprobada','Rechazada') DEFAULT 'Pendiente',
  PRIMARY KEY (`id_autorizacion_kit`),
  KEY `idx_autkit_solicitud` (`id_solicitud_kit`),
  KEY `idx_autkit_autorizado_por` (`autorizado_por`),
  CONSTRAINT `fk_autorizaciones_kit_solicitud` FOREIGN KEY (`id_solicitud_kit`) REFERENCES `solicitudes_kit` (`id_solicitud_kit`) ON DELETE CASCADE,
  CONSTRAINT `fk_autorizaciones_kit_user` FOREIGN KEY (`autorizado_por`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla prestamo_servidores.autorizaciones_kit: ~0 rows (aproximadamente)

-- Volcando estructura para tabla prestamo_servidores.integrantes
CREATE TABLE IF NOT EXISTS `integrantes` (
  `id_integrante` int(11) NOT NULL AUTO_INCREMENT,
  `id_solicitud` int(11) NOT NULL,
  `codigo_estudiante` varchar(20) DEFAULT NULL,
  `nombre_estudiante` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_integrante`),
  KEY `id_solicitud` (`id_solicitud`),
  CONSTRAINT `integrantes_ibfk_1` FOREIGN KEY (`id_solicitud`) REFERENCES `solicitudes` (`id_solicitud`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla prestamo_servidores.integrantes: ~4 rows (aproximadamente)
INSERT INTO `integrantes` (`id_integrante`, `id_solicitud`, `codigo_estudiante`, `nombre_estudiante`) VALUES
	(1, 1, '456615644', 'Brat Chata'),
	(2, 1, '4544', 'Chite'),
	(3, 5, '2222222', 'asdsad'),
	(4, 6, '2222222', 'asdsad');

-- Volcando estructura para tabla prestamo_servidores.integrantes_kit
CREATE TABLE IF NOT EXISTS `integrantes_kit` (
  `id_integrante_kit` int(11) NOT NULL AUTO_INCREMENT,
  `id_solicitud_kit` int(11) NOT NULL,
  `codigo_estudiante` varchar(20) DEFAULT NULL,
  `nombre_estudiante` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_integrante_kit`),
  KEY `fk_integrantes_kit_solicitud` (`id_solicitud_kit`),
  CONSTRAINT `fk_integrantes_kit_solicitud` FOREIGN KEY (`id_solicitud_kit`) REFERENCES `solicitudes_kit` (`id_solicitud_kit`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla prestamo_servidores.integrantes_kit: ~0 rows (aproximadamente)

-- Volcando estructura para tabla prestamo_servidores.kits
CREATE TABLE IF NOT EXISTS `kits` (
  `id_kit` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_kit` varchar(150) NOT NULL,
  `codigo_kit` varchar(80) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `stock_total` int(11) DEFAULT 1,
  `stock_disponible` int(11) DEFAULT 1,
  `disponible` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_kit`),
  UNIQUE KEY `uniq_codigo_kit` (`codigo_kit`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla prestamo_servidores.kits: ~2 rows (aproximadamente)
INSERT INTO `kits` (`id_kit`, `nombre_kit`, `codigo_kit`, `descripcion`, `stock_total`, `stock_disponible`, `disponible`, `created_at`, `updated_at`) VALUES
	(1, 'Kit Arduino Uno R3 - Estándar', 'KIT-ARDUINO-UNO-R3', 'Kit con Arduino Uno, protoboard, cables, resistencias y sensores básicos', 10, 10, 1, '2025-10-15 21:45:36', NULL),
	(2, 'Kit Arduino IoT (ESP8266)', 'KIT-ARDUINO-IOT', 'Kit con módulo WiFi/ESP, sensores y accesorios', 5, 5, 1, '2025-10-15 21:45:36', NULL);

-- Volcando estructura para tabla prestamo_servidores.kit_componentes
CREATE TABLE IF NOT EXISTS `kit_componentes` (
  `id_componente` int(11) NOT NULL AUTO_INCREMENT,
  `id_kit` int(11) NOT NULL,
  `nombre_componente` varchar(150) NOT NULL,
  `cantidad_en_kit` int(11) DEFAULT 1,
  `unidad` varchar(40) DEFAULT 'unidad',
  `descripcion` text DEFAULT NULL,
  PRIMARY KEY (`id_componente`),
  KEY `fk_kit_componentes_kit` (`id_kit`),
  CONSTRAINT `fk_kit_componentes_kit` FOREIGN KEY (`id_kit`) REFERENCES `kits` (`id_kit`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla prestamo_servidores.kit_componentes: ~7 rows (aproximadamente)
INSERT INTO `kit_componentes` (`id_componente`, `id_kit`, `nombre_componente`, `cantidad_en_kit`, `unidad`, `descripcion`) VALUES
	(1, 1, 'Arduino Uno R3', 1, 'unidad', 'Placa Arduino Uno R3'),
	(2, 1, 'Protoboard 830', 1, 'unidad', 'Protoboard de pruebas 830 puntos'),
	(3, 1, 'Jumpers (pack)', 1, 'pack', 'Set de jumpers macho-hembra'),
	(4, 1, 'Resistencias assorted', 20, 'pieza', 'Resistencias varias (packs)'),
	(5, 1, 'LEDs assorted', 10, 'pieza', 'LEDs de colores'),
	(6, 2, 'ESP8266 módulo', 1, 'unidad', 'Módulo WiFi ESP8266'),
	(7, 2, 'Sensor DHT11', 1, 'unidad', 'Sensor de temperatura/humedad');

-- Volcando estructura para tabla prestamo_servidores.servidores
CREATE TABLE IF NOT EXISTS `servidores` (
  `id_servidor` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_servidor` varchar(50) NOT NULL,
  `serie_servidor` varchar(50) NOT NULL,
  `tipo_servidor` enum('Torre','Rack','Virtual') DEFAULT 'Torre',
  `caracteristicas` varchar(150) DEFAULT NULL,
  `disponible` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id_servidor`),
  UNIQUE KEY `serie_servidor` (`serie_servidor`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla prestamo_servidores.servidores: ~3 rows (aproximadamente)
INSERT INTO `servidores` (`id_servidor`, `nombre_servidor`, `serie_servidor`, `tipo_servidor`, `caracteristicas`, `disponible`) VALUES
	(1, 'Server1', 'wdeew44', 'Torre', '4CPU, 8GB RAM', 1),
	(2, 'Server2', 'wdeew45', 'Torre', '8CPU, 16GB RAM', 1),
	(3, 'Server3', 'srvx100', 'Virtual', '16CPU, 32GB RAM', 1);

-- Volcando estructura para tabla prestamo_servidores.solicitudes
CREATE TABLE IF NOT EXISTS `solicitudes` (
  `id_solicitud` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `id_servidor` int(11) NOT NULL,
  `semestre_academico` varchar(10) NOT NULL,
  `fecha` date NOT NULL,
  `hora_entrada` time NOT NULL,
  `hora_salida` time NOT NULL,
  `incluir_monitor` tinyint(1) DEFAULT 0,
  `incluir_teclado` tinyint(1) DEFAULT 0,
  `incluir_mouse` tinyint(1) DEFAULT 0,
  `codigo_responsable` varchar(20) DEFAULT NULL,
  `nombre_responsable` varchar(120) DEFAULT NULL,
  `estado` enum('Pendiente','Autorizada','Rechazada') DEFAULT 'Pendiente',
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_solicitud`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_servidor` (`id_servidor`),
  CONSTRAINT `solicitudes_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  CONSTRAINT `solicitudes_ibfk_2` FOREIGN KEY (`id_servidor`) REFERENCES `servidores` (`id_servidor`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla prestamo_servidores.solicitudes: ~6 rows (aproximadamente)
INSERT INTO `solicitudes` (`id_solicitud`, `id_usuario`, `id_servidor`, `semestre_academico`, `fecha`, `hora_entrada`, `hora_salida`, `incluir_monitor`, `incluir_teclado`, `incluir_mouse`, `codigo_responsable`, `nombre_responsable`, `estado`, `fecha_registro`) VALUES
	(1, 2, 2, '2025-II', '2025-10-08', '15:00:00', '19:00:00', 1, 1, 1, '2015251455', 'Arce Bracamonte', 'Autorizada', '2025-10-15 06:01:57'),
	(2, 4, 1, '2025-I', '2025-10-17', '08:27:00', '11:23:00', 0, 0, 1, '231', 'sadda', 'Autorizada', '2025-10-15 13:23:14'),
	(3, 2, 1, '2025-I', '2025-10-16', '09:59:00', '12:59:00', 0, 1, 0, '123', 'Arce Bracamonte', 'Pendiente', '2025-10-15 13:59:58'),
	(4, 2, 1, '2025-I', '2025-10-16', '09:59:00', '12:59:00', 0, 1, 0, '123', 'Arce Bracamonte', 'Autorizada', '2025-10-15 13:59:58'),
	(5, 2, 2, '2025-I', '2025-10-15', '10:00:00', '13:00:00', 0, 0, 1, '123', 'Arce Bracamonte', 'Pendiente', '2025-10-15 14:01:17'),
	(6, 2, 2, '2025-I', '2025-10-15', '10:00:00', '13:00:00', 0, 0, 1, '123', 'Arce Bracamonte', 'Rechazada', '2025-10-15 14:01:17');

-- Volcando estructura para tabla prestamo_servidores.solicitudes_kit
CREATE TABLE IF NOT EXISTS `solicitudes_kit` (
  `id_solicitud_kit` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `id_kit` int(11) NOT NULL,
  `semestre_academico` varchar(10) NOT NULL,
  `fecha` date NOT NULL,
  `hora_entrada` time NOT NULL,
  `hora_salida` time NOT NULL,
  `codigo_responsable` varchar(20) DEFAULT NULL,
  `nombre_responsable` varchar(120) DEFAULT NULL,
  `estado` enum('Pendiente','Autorizada','Rechazada') DEFAULT 'Pendiente',
  `estado_kit` varchar(60) DEFAULT NULL,
  `personal_soporte` int(11) DEFAULT NULL,
  `observacion` text DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_solicitud_kit`),
  KEY `idx_solicitudes_kit_usuario` (`id_usuario`),
  KEY `idx_solicitudes_kit_kit` (`id_kit`),
  KEY `fk_solicitudes_kit_personal` (`personal_soporte`),
  CONSTRAINT `fk_solicitudes_kit_kit` FOREIGN KEY (`id_kit`) REFERENCES `kits` (`id_kit`),
  CONSTRAINT `fk_solicitudes_kit_personal` FOREIGN KEY (`personal_soporte`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL,
  CONSTRAINT `fk_solicitudes_kit_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla prestamo_servidores.solicitudes_kit: ~0 rows (aproximadamente)

-- Volcando estructura para tabla prestamo_servidores.solicitud_kit_componentes
CREATE TABLE IF NOT EXISTS `solicitud_kit_componentes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_solicitud_kit` int(11) NOT NULL,
  `id_componente` int(11) NOT NULL,
  `cantidad_solicitada` int(11) DEFAULT 1,
  `cantidad_entregada` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_skc_solicitud_kit` (`id_solicitud_kit`),
  KEY `fk_skc_componente` (`id_componente`),
  CONSTRAINT `fk_skc_componente` FOREIGN KEY (`id_componente`) REFERENCES `kit_componentes` (`id_componente`),
  CONSTRAINT `fk_skc_solicitud_kit` FOREIGN KEY (`id_solicitud_kit`) REFERENCES `solicitudes_kit` (`id_solicitud_kit`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla prestamo_servidores.solicitud_kit_componentes: ~0 rows (aproximadamente)

-- Volcando estructura para tabla prestamo_servidores.usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_usuario` varchar(20) NOT NULL,
  `nombre_completo` varchar(120) NOT NULL,
  `correo` varchar(120) DEFAULT NULL,
  `clave` varchar(255) NOT NULL,
  `rol` enum('Administrador','Estudiante') NOT NULL,
  `estado` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `codigo_usuario` (`codigo_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla prestamo_servidores.usuarios: ~4 rows (aproximadamente)
INSERT INTO `usuarios` (`id_usuario`, `codigo_usuario`, `nombre_completo`, `correo`, `clave`, `rol`, `estado`) VALUES
	(1, 'admin01', 'Administrador General', 'admin@servidores.edu', '0192023a7bbd73250516f069df18b500', 'Administrador', 1),
	(2, '123', 'Arce Bracamonte', 'arce@servidores.edu', 'f84b437e1ce2643aa5572ad53dd7d4ce', 'Estudiante', 1),
	(3, 'est002', 'Brat Chata', 'brat@servidores.edu', 'f84b437e1ce2643aa5572ad53dd7d4ce', 'Estudiante', 1),
	(4, 'est003', 'Chite', 'chite@servidores.edu', 'f84b437e1ce2643aa5572ad53dd7d4ce', 'Estudiante', 1);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;



ALTER TABLE `solicitudes_kit`
  ADD COLUMN `docente_responsable` VARCHAR(150) DEFAULT NULL,
  ADD COLUMN `curso` VARCHAR(120) DEFAULT NULL;

    

ALTER TABLE solicitudes_kit
  ADD COLUMN created_at TIMESTAMP NULL DEFAULT NULL AFTER fecha_registro,
  ADD COLUMN updated_at TIMESTAMP NULL DEFAULT NULL AFTER created_at;
