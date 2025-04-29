CREATE DATABASE IF NOT EXISTS gestion_activos;
USE gestion_activos;

-- Tabla de Administradores para el inicio de sesión
CREATE TABLE administradores (
    id_admin INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    clave VARCHAR(255) NOT NULL
);
INSERT INTO administradores (nombre, usuario, clave)
VALUES (
    'Administrador Principal',
    'admin1',
    '123'
    
);
CREATE TABLE destinatarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    correo VARCHAR(255) NOT NULL
);

INSERT INTO destinatarios (correo) VALUES 
('miguelurea.u@gmail.com');
SELECT * FROM destinatarios;


-- Crear tabla de bajas de activos
CREATE TABLE bajas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero_activo VARCHAR(50) NOT NULL UNIQUE,
    fecha_baja DATE NOT NULL,
    fecha_recepcion DATE NOT NULL,
    oficio_baja VARCHAR(100),
    tipo_baja VARCHAR(50),
    clase_activo VARCHAR(50),
    motivo TEXT,
    modelo VARCHAR(50),
    marca VARCHAR(50),
    serie VARCHAR(50),
    departamento VARCHAR(100),
    checklist_completado ENUM('S', 'N') DEFAULT 'N',
    ubicacion VARCHAR(100),
    ultima_modificacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Crear tabla de traslados de activos
CREATE TABLE traslados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero_activo VARCHAR(50) NOT NULL UNIQUE,
    fecha_aprobacion DATE NOT NULL,
    fecha_traslado DATE NOT NULL,
    canal_comunicacion VARCHAR(100),
    motivo TEXT,
    tipo_activo VARCHAR(50),
    modelo VARCHAR(50),
    marca VARCHAR(50),
    serie VARCHAR(50),
    departamento VARCHAR(100),
    checklist_completado ENUM('S', 'N') DEFAULT 'N',
    ubicacion_anterior VARCHAR(100),
    ubicacion_actual VARCHAR(100),
    ultima_modificacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Crear el TRIGGER para generar el identificador en la tabla de bajas
DELIMITER $$
CREATE TRIGGER before_insert_bajas
BEFORE INSERT ON bajas
FOR EACH ROW
BEGIN
    DECLARE next_id INT;
    DECLARE next_code VARCHAR(50);
    SET next_id = (SELECT COALESCE(MAX(id), 0) + 1 FROM bajas);
    SET next_code = CONCAT('CBA-INF-', YEAR(CURDATE()), '-', LPAD(next_id, 3, '0'));
    SET NEW.numero_activo = next_code;
END $$
DELIMITER ;

-- Crear el TRIGGER para generar el identificador en la tabla de traslados
DELIMITER $$
CREATE TRIGGER before_insert_traslados
BEFORE INSERT ON traslados
FOR EACH ROW
BEGIN
    DECLARE next_id INT;
    DECLARE next_code VARCHAR(50);
    SET next_id = (SELECT COALESCE(MAX(id), 0) + 1 FROM traslados);
    SET next_code = CONCAT('CTA-INF-', YEAR(CURDATE()), '-', LPAD(next_id, 3, '0'));
    SET NEW.numero_activo = next_code;
END $$
DELIMITER ;
