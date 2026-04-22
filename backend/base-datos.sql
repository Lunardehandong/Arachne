CREATE DATABASE IF NOT EXISTS arachne_db;
USE arachne_db;

CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre_completo VARCHAR(150) NOT NULL,
    nombre_usuario VARCHAR(50) UNIQUE NOT NULL,
    correo VARCHAR(100) UNIQUE NOT NULL,
    contrasena VARCHAR(255) NOT NULL, 
    biografia TEXT,
    foto_perfil VARCHAR(255) DEFAULT '#C0CCDA', 
  
    talla_superior VARCHAR(10),
    talla_inferior VARCHAR(10),
    talla_calzado VARCHAR(10),
    calificacion_promedio DECIMAL(3,2) DEFAULT 0.0, 
    cuenta_activa BOOLEAN DEFAULT TRUE, 
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE tutoriales (
    id_tutorial INT AUTO_INCREMENT PRIMARY KEY,
    id_autor INT,
    titulo VARCHAR(150) NOT NULL,
    descripcion TEXT, 
    contenido_paso_a_paso TEXT, 
    url_video VARCHAR(255), 
    categoria ENUM('Costura', 'Teñido', 'Corte', 'Otros'),
    fecha_publicacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_autor) REFERENCES usuarios(id_usuario)
);

CREATE TABLE cursos (
    id_curso INT AUTO_INCREMENT PRIMARY KEY,
    nombre_curso VARCHAR(150) NOT NULL,
    descripcion TEXT,
    nivel ENUM('Principiante', 'Intermedio', 'Avanzado'),
    color_etiqueta VARCHAR(7) DEFAULT '#388697'
);

CREATE TABLE prendas (
    id_prenda INT AUTO_INCREMENT PRIMARY KEY,
    id_propietario INT,
    nombre_prenda VARCHAR(100) NOT NULL,
    descripcion TEXT,
    estado_prenda ENUM('Nuevo', 'Excelente', 'Bueno', 'Desgastado'),
    tipo_servicio ENUM('Donacion', 'Intercambio', 'Ambos'),
    disponible BOOLEAN DEFAULT TRUE,
    fecha_publicacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_propietario) REFERENCES usuarios(id_usuario)
);

CREATE TABLE foro_comentarios (
    id_comentario INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    id_tutorial INT,
    comentario VARCHAR(500), 
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario),
    FOREIGN KEY (id_tutorial) REFERENCES tutoriales(id_tutorial)
);

INSERT INTO usuarios (nombre_completo, nombre_usuario, correo, contrasena, biografia, talla_superior, talla_inferior) 
VALUES 
('Ayari Fierro', 'AyariF', 'ayari@email.com', '$2y$10$enc_pass1', 'Entusiasta del diseño sustentable.', 'M', '30'),
('Daniel Vega', 'DanyV', 'daniel@email.com', '$2y$10$enc_pass2', 'Especialista en reciclaje textil.', 'L', '34');

INSERT INTO tutoriales (id_autor, titulo, descripcion, contenido_paso_a_paso, url_video, categoria) 
VALUES 
(1, 'Puntada Invisible', 'Repara prendas sin que se note.', '1. Enhebrar aguja. 2. Insertar por dentro...', 'youtube.com/v1', 'Costura'),
(2, 'Tinte con Cebolla', 'Colores ocre naturales.', '1. Hervir cáscaras. 2. Sumergir prenda...', 'youtube.com/v2', 'Teñido');

INSERT INTO cursos (nombre_curso, descripcion, nivel) 
VALUES 
('Corte y Confección Básica', 'Aprende a usar la máquina desde cero.', 'Principiante'),
('Sastrería Avanzada', 'Creación de sacos y abrigos estructurados.', 'Avanzado');

INSERT INTO prendas (id_propietario, nombre_prenda, descripcion, estado_prenda, tipo_servicio) 
VALUES 
(1, 'Sudadera Vintage', 'Color azul fuerte #08415C, muy cuidada.', 'Excelente', 'Intercambio'),
(2, 'Retazos de Lino', 'Cortes de tela nuevos para manualidades.', 'Nuevo', 'Donacion');

INSERT INTO foro_comentarios (id_usuario, id_tutorial, comentario) 
VALUES 
(2, 1, '¡Me funcionó perfecto para mis jeans!'),
(1, 2, '¿Se puede usar con tela sintética?');

select	* from usuarios;
select	* from tutoriales;
select	* from prendas;
select	* from foro_comentarios;