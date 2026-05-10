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

ALTER TABLE usuarios ADD COLUMN creditos INT DEFAULT 0;

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

ALTER TABLE cursos ADD COLUMN creditos INT DEFAULT 0;

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

-- Borramos el usuario previo para limpiar errores
DELETE FROM usuarios WHERE nombre_usuario = 'test_user';

-- Insertamos con el hash exacto de 60 caracteres para "1234"
INSERT INTO usuarios (nombre_completo, nombre_usuario, correo, contrasena, cuenta_activa) 
VALUES 
('Prueba Arachne', 'test_user', 'test@arachne.com', '$2y$10$89JAXR6hMAtVjYmU9D.SreV5/KOfuRjI5.D6MpjzLid.i/L8P4fB6', 1);

-- Cambiamos las contraseñas de los usuarios de prueba a texto simple
UPDATE usuarios SET contrasena = '1234' WHERE nombre_usuario = 'AyariF';
UPDATE usuarios SET contrasena = 'admin123' WHERE nombre_usuario = 'admin_arachne';

select  usuarios where nombre_usuario = 'test_user';

UPDATE cursos SET creditos = 250 WHERE id_curso = 1;
UPDATE cursos SET creditos = 300 WHERE id_curso = 2;

-- 3. Tabla para registrar los cursos comprados
CREATE TABLE inscripciones_cursos (
    id_inscripcion INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    id_curso INT,
    fecha_compra DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario),
    FOREIGN KEY (id_curso) REFERENCES cursos(id_curso)
);

-- 1. Mejoramos la tabla cursos para que tenga espacio para imagen y video
ALTER TABLE cursos ADD COLUMN url_imagen VARCHAR(255) DEFAULT 'img/curso_default.jpg';
ALTER TABLE cursos ADD COLUMN url_video VARCHAR(255);
ALTER TABLE cursos ADD COLUMN contenido_detallado TEXT;

-- 2. Aseguramos que el usuario de prueba tenga créditos para comprar
UPDATE usuarios SET creditos = 1000 WHERE nombre_usuario = 'test_user';

-- 3. Así quedaría tu tabla de "compras" (indispensable para el Perfil)
-- Ya la tienes creada, pero asegúrate de que sea así:
CREATE TABLE IF NOT EXISTS inscripciones_cursos (
    id_inscripcion INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    id_curso INT,
    fecha_compra DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario),
    FOREIGN KEY (id_curso) REFERENCES cursos(id_curso)
);


select	* from usuarios;
select	* from tutoriales;
select	* from prendas;
select	* from foro_comentarios;
select	* from prendas;
select * from cursos;

UPDATE cursos SET 
    descripcion = 'Este curso está diseñado para quienes nunca han tocado una aguja. Aprenderás desde las partes de la máquina de coser hasta la confección de tu primera prenda básica.',
    contenido_detallado = '• Módulo 1: Introducción a herramientas y textiles.\n• Módulo 2: Tipos de puntadas y tensiones.\n• Módulo 3: Elaboración de patrones básicos.\n• Módulo 4: Ensamble de una falda o cojín.',
    url_imagen = 'corte_confeccion.jpg', -- Asegúrate de tener esta imagen en backend/img/
    creditos = 250
WHERE id_curso = 1;

-- 1. Agregar soporte para imágenes en las publicaciones del foro
ALTER TABLE tutoriales ADD COLUMN url_imagen VARCHAR(255) DEFAULT 'img/default_foro.jpg';

-- 2. (Opcional) Si quieres que el foro sea más dinámico, añade categorías más amplias
-- Actualmente tienes: 'Costura', 'Teñido', 'Corte', 'Otros'
-- Podrías agregar 'Dudas', 'Inspiración' o 'Reciclaje'.
ALTER TABLE tutoriales MODIFY COLUMN categoria ENUM('Costura', 'Teñido', 'Corte', 'Dudas', 'Reciclaje', 'Otros');

-- 3. Para que el foro tenga contenido inicial y se vea bien al abrirlo:
INSERT INTO tutoriales (id_autor, titulo, descripcion, contenido_paso_a_paso, categoria, url_imagen) 
VALUES 
(1, 'Mi primer diseño', 'Hola comunidad, les comparto mi avance en el curso.', 'Solo quería mostrarles cómo quedó mi falda después del módulo 4.', 'Otros', 'falda_ayari.jpg');