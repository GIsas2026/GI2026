-- ============================================
-- BASE DE DATOS Y TABLA PARA CONTACTOS
-- ============================================

-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS grandes_ideas CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Usar la base de datos
USE grandes_ideas;

-- ============================================
-- TABLA DE CONTACTOS
-- ============================================
CREATE TABLE IF NOT EXISTS contactos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(255) NOT NULL,
    telefono VARCHAR(20),
    servicio VARCHAR(100) NOT NULL,
    descripcion LONGTEXT NOT NULL,
    estado ENUM('nuevo', 'en_proceso', 'respondido', 'cerrado') DEFAULT 'nuevo',
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    ip_cliente VARCHAR(45),
    notas LONGTEXT,
    
    -- Índices para mejor rendimiento
    INDEX idx_correo (correo),
    INDEX idx_servicio (servicio),
    INDEX idx_estado (estado),
    INDEX idx_fecha_creacion (fecha_creacion),
    INDEX idx_nombre (nombre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLA DE USUARIOS ADMINISTRADORES (OPCIONAL)
-- ============================================
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(50) UNIQUE NOT NULL,
    contrasena VARCHAR(255) NOT NULL,
    nombre_completo VARCHAR(100) NOT NULL,
    correo VARCHAR(255) UNIQUE NOT NULL,
    rol ENUM('admin', 'usuario') DEFAULT 'usuario',
    activo BOOLEAN DEFAULT TRUE,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_ultimo_acceso DATETIME,
    
    INDEX idx_usuario (usuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLA DE CATEGORÍAS DE SERVICIOS (OPCIONAL)
-- ============================================
CREATE TABLE IF NOT EXISTS servicios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) UNIQUE NOT NULL,
    descripcion LONGTEXT,
    icono VARCHAR(50),
    activo BOOLEAN DEFAULT TRUE,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    
    INDEX idx_nombre (nombre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- INSERTAR SERVICIOS POR DEFECTO
-- ============================================
INSERT INTO servicios (nombre, descripcion, icono) VALUES
('sql-server', 'Gestión y administración de bases de datos SQL Server', 'database'),
('analitica-datos', 'Analítica y análisis de datos empresariales', 'chart-line'),
('power-bi', 'Visualización de datos y reportes con Power BI', 'chart-pie'),
('microsoft-365', 'Implementación y gestión de Microsoft 365', 'cloud'),
('soporte-it', 'Soporte técnico y asistencia IT profesional', 'headset'),
('consultoria', 'Consultoría y asesoramiento IT estratégico', 'users');

-- ============================================
-- VISTA PARA RESUMEN DE CONTACTOS (OPCIONAL)
-- ============================================
CREATE VIEW v_resumen_contactos AS
SELECT 
    DATE(fecha_creacion) as fecha,
    servicio,
    estado,
    COUNT(*) as total
FROM contactos
GROUP BY DATE(fecha_creacion), servicio, estado
ORDER BY fecha DESC;