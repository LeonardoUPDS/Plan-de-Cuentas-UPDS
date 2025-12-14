CREATE DATABASE PlataformaEducativa;
USE PlataformaEducativa;

CREATE TABLE Rol (
    idRol INT AUTO_INCREMENT PRIMARY KEY,
    Descripcion VARCHAR(60) UNIQUE NOT NULL,
    Estado BOOL DEFAULT TRUE
);

CREATE TABLE Usuario (
    idUsuario INT AUTO_INCREMENT PRIMARY KEY,
    Correo VARCHAR(100) UNIQUE NOT NULL,
    Contrasena VARCHAR(255) NOT NULL,
    idRol INT NOT NULL,
    EmailVerificado BOOL DEFAULT FALSE,
    Estado BOOL DEFAULT TRUE,

    FOREIGN KEY (idRol) REFERENCES Rol(idRol)
);

CREATE TABLE Permiso (
    permiso_id INT AUTO_INCREMENT PRIMARY KEY,
    descripcion VARCHAR(255) UNIQUE NOT NULL,
    Estado BOOL DEFAULT TRUE
);

CREATE TABLE Detalle (
    detalle_id INT AUTO_INCREMENT PRIMARY KEY,
    idRol INT NOT NULL,
    permiso_id INT NOT NULL,
    Estado BOOL DEFAULT TRUE,

    FOREIGN KEY (idRol) REFERENCES Rol(idRol),
    FOREIGN KEY (permiso_id) REFERENCES Permiso(permiso_id)
);

CREATE TABLE TokenRecuperacion (
    idToken INT AUTO_INCREMENT PRIMARY KEY,
    idUsuario INT NOT NULL,
    Token VARCHAR(255) NOT NULL,
    FechaExpiracion DATETIME NOT NULL,
    Usado BOOL DEFAULT FALSE,

    FOREIGN KEY (idUsuario) REFERENCES Usuario(idUsuario)
);

CREATE TABLE Plan (
    idPlan INT AUTO_INCREMENT PRIMARY KEY,
    Nombre VARCHAR(50) NOT NULL,
    MaxSesiones INT NOT NULL,
    Precio DECIMAL(10,2),
    Estado BOOL DEFAULT TRUE
);

CREATE TABLE Suscripcion (
    idSuscripcion INT AUTO_INCREMENT PRIMARY KEY,
    idUsuario INT NOT NULL,
    idPlan INT NOT NULL,
    FechaInicio DATETIME DEFAULT CURRENT_TIMESTAMP,
    FechaFin DATETIME,
    Estado BOOL DEFAULT TRUE,

    FOREIGN KEY (idUsuario) REFERENCES Usuario(idUsuario),
    FOREIGN KEY (idPlan) REFERENCES Plan(idPlan)
);

CREATE TABLE SesionUsuario (
    idSesion INT AUTO_INCREMENT PRIMARY KEY,
    idUsuario INT NOT NULL,
    TokenSesion VARCHAR(255) NOT NULL,
    IP VARCHAR(45),
    Navegador VARCHAR(255),
    FechaInicio DATETIME DEFAULT CURRENT_TIMESTAMP,
    UltimaActividad DATETIME DEFAULT CURRENT_TIMESTAMP,
    Activa BOOL DEFAULT TRUE,

    FOREIGN KEY (idUsuario) REFERENCES Usuario(idUsuario)
);

INSERT INTO Rol (Descripcion, Estado) VALUES
('Estudiante', TRUE),
('Profesor', TRUE),
('Administrador', TRUE);

INSERT INTO Permiso (descripcion, Estado) VALUES
('Crear', TRUE),
('Editar', TRUE),
('Inactivar', TRUE);

-- Estudiante
INSERT INTO Detalle (idRol, permiso_id, Estado) VALUES
(1, 2, TRUE);  -- Editar

-- Profesor
INSERT INTO Detalle (idRol, permiso_id, Estado) VALUES
(2, 1, TRUE),  -- Crear
(2, 2, TRUE);  -- Editar

-- Administrador
INSERT INTO Detalle (idRol, permiso_id, Estado) VALUES
(3, 1, TRUE),  -- Crear
(3, 2, TRUE),  -- Editar
(3, 3, TRUE);  -- Inactivar

INSERT INTO Usuario (Correo, Contrasena, idRol, Estado, EmailVerificado) VALUES
('Leonardo@gmail.com', '$2y$10$e0NRFQ2vxR8vR8ZGk1kjIehHTfQX0g6f1jF3y6OjG6H/wRC9ZnqU6', 3, TRUE, TRUE);

INSERT INTO Plan (Nombre, MaxSesiones, Precio, Estado) VALUES
('Básico', 1, 0.00, TRUE),
('Pro', 3, 50.00, TRUE),
('Premium', 5, 100.00, TRUE);

INSERT INTO Suscripcion (idUsuario, idPlan, FechaInicio, FechaFin, Estado) VALUES
(1, 3, NOW(), DATE_ADD(NOW(), INTERVAL 30 DAY), TRUE);

INSERT INTO SesionUsuario (idUsuario, TokenSesion, IP, Navegador, Activa) VALUES
(1, 'token_leonardo_1', '127.0.0.1', 'Chrome', TRUE);
