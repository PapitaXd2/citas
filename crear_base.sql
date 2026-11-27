-- crear_base.sql
-- Ejecutar conectado a la base "citas" como postgres

CREATE TABLE usuarios (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(80) NOT NULL,
    correo VARCHAR(120) NOT NULL UNIQUE,
    telefono VARCHAR(10) NOT NULL,
    contrasena VARCHAR(255) NOT NULL,          -- cifrada con password_hash
    rol VARCHAR(10) NOT NULL DEFAULT 'cliente'
        CHECK (rol IN ('cliente', 'admin')),
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE servicios (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(80) NOT NULL,
    descripcion VARCHAR(300),
    duracion INTEGER NOT NULL CHECK (duracion BETWEEN 10 AND 240),   -- minutos
    precio NUMERIC(8,2) NOT NULL CHECK (precio >= 0),
    imagen VARCHAR(255),                       -- nombre del archivo en uploads/
    activo BOOLEAN NOT NULL DEFAULT TRUE
);

CREATE TABLE citas (
    id SERIAL PRIMARY KEY,
    usuario_id INTEGER NOT NULL REFERENCES usuarios(id) ON DELETE CASCADE,
    servicio_id INTEGER NOT NULL REFERENCES servicios(id),
    fecha DATE NOT NULL,
    hora TIME NOT NULL,
    notas VARCHAR(300),
    estado VARCHAR(12) NOT NULL DEFAULT 'pendiente'
        CHECK (estado IN ('pendiente', 'confirmada', 'cancelada')),
    creada_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Un solo cliente por horario; las citas canceladas no cuentan
CREATE UNIQUE INDEX citas_horario_unico ON citas (fecha, hora)
    WHERE estado <> 'cancelada';

-- Permisos mínimos para el usuario de la aplicación
GRANT SELECT, INSERT, UPDATE, DELETE ON usuarios, servicios, citas TO citas_app;
GRANT USAGE, SELECT ON SEQUENCE usuarios_id_seq, servicios_id_seq, citas_id_seq TO citas_app;

-- Servicios de ejemplo
INSERT INTO servicios (nombre, descripcion, duracion, precio) VALUES
('Corte de cabello', 'Corte clásico o moderno con lavado incluido', 30, 150.00),
('Arreglo de barba', 'Perfilado y afeitado con toalla caliente', 30, 100.00),
('Corte y barba', 'Paquete completo de corte y arreglo de barba', 60, 220.00);
