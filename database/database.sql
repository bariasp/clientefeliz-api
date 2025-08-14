-- CREACIÓN DE BASE DE DATOS
CREATE DATABASE cliente_feliz;
USE cliente_feliz;

-- TABLA: Usuario
CREATE TABLE Usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    contraseña VARCHAR(255) NOT NULL,
    fecha_nacimiento DATE,
    telefono VARCHAR(20),
    direccion VARCHAR(255),
    rol ENUM('Reclutador', 'Candidato') NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    estado ENUM('Activo', 'Inactivo') DEFAULT 'Activo'
);

-- TABLA: OfertaLaboral
CREATE TABLE OfertaLaboral (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    descripcion TEXT,
    ubicacion VARCHAR(150),
    salario DECIMAL(10,2),
    tipo_contrato ENUM('Indefinido', 'Temporal', 'Honorarios', 'Práctica') DEFAULT 'Indefinido',
    fecha_publicacion DATE DEFAULT CURRENT_DATE,
    fecha_cierre DATE,
    estado ENUM('Vigente', 'Cerrada', 'Baja') DEFAULT 'Vigente',
    reclutador_id INT NOT NULL,
    FOREIGN KEY (reclutador_id) REFERENCES Usuario(id)
);

-- TABLA: Postulacion
CREATE TABLE Postulacion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    candidato_id INT NOT NULL,
    oferta_laboral_id INT NOT NULL,
    estado_postulacion ENUM('Postulando', 'Revisando', 'Entrevista Psicológica', 'Entrevista Personal', 'Seleccionado', 'Descartado') DEFAULT 'Postulando',
    comentario TEXT,
    fecha_postulacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (candidato_id) REFERENCES Usuario(id),
    FOREIGN KEY (oferta_laboral_id) REFERENCES OfertaLaboral(id)
);

-- TABLA: AntecedenteAcademico
CREATE TABLE AntecedenteAcademico (
    id INT AUTO_INCREMENT PRIMARY KEY,
    candidato_id INT NOT NULL,
    institucion VARCHAR(150) NOT NULL,
    titulo_obtenido VARCHAR(150) NOT NULL,
    anio_ingreso YEAR,
    anio_egreso YEAR,
    FOREIGN KEY (candidato_id) REFERENCES Usuario(id)
);

-- TABLA: AntecedenteLaboral
CREATE TABLE AntecedenteLaboral (
    id INT AUTO_INCREMENT PRIMARY KEY,
    candidato_id INT NOT NULL,
    empresa VARCHAR(150) NOT NULL,
    cargo VARCHAR(150) NOT NULL,
    funciones TEXT,
    fecha_inicio DATE,
    fecha_termino DATE,
    FOREIGN KEY (candidato_id) REFERENCES Usuario(id)
);