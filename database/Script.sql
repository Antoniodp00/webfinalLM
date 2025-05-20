-- =======================================
-- Base de datos: Proyecto Fitness360
-- Script SQL con campo estado para Cliente y Empleado
-- =======================================

-- 1) Crear base de datos y seleccionarla
DROP DATABASE IF EXISTS Fitness360;
CREATE DATABASE IF NOT EXISTS Fitness360;
USE Fitness360;

-- 2) Tabla Cliente (datos demográficos + auditoría)
CREATE TABLE Cliente (
    idCliente INT PRIMARY KEY AUTO_INCREMENT,
    nombreUsuario VARCHAR(50) NOT NULL UNIQUE,
    nombre VARCHAR(50) NOT NULL,
    apellidos VARCHAR(50) NOT NULL,
    correo VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(100) NOT NULL,
    telefono VARCHAR(20),
    fechaNacimiento DATE,
    sexo ENUM('M','F','O','NS') DEFAULT 'NS',
    altura DECIMAL(4,1),
    estado ENUM('ACTIVO','INACTIVO','SUSPENDIDO') DEFAULT 'ACTIVO',
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 3) Tabla Empleado (especialidad, contratación + auditoría)
CREATE TABLE Empleado (
    idEmpleado INT PRIMARY KEY AUTO_INCREMENT,
    nombreUsuario VARCHAR(50) NOT NULL UNIQUE,
    nombre VARCHAR(50) NOT NULL,
    apellidos VARCHAR(50) NOT NULL,
    correo VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(100) NOT NULL,
    telefono VARCHAR(20),
    fechaNacimiento DATE,
    sexo ENUM('M','F','O','NS') DEFAULT 'NS',
    descripcion TEXT,
    rol VARCHAR(50),
    especialidad ENUM('ENTRENADOR','DIETISTA','AMBOS') NOT NULL DEFAULT 'AMBOS',
    estado ENUM('ACTIVO','INACTIVO','SUSPENDIDO') DEFAULT 'ACTIVO',
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 4) Tabla Tarifa (periodo + auditoría)
CREATE TABLE Tarifa (
    idTarifa INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    precio DECIMAL(8,2) NOT NULL,
    descripcion TEXT,
    periodo ENUM('MENSUAL','TRIMESTRAL','ANUAL','UNICO') NOT NULL DEFAULT 'MENSUAL',
    idEmpleado INT,
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (idEmpleado) REFERENCES Empleado(idEmpleado)
        ON DELETE SET NULL ON UPDATE CASCADE
);

-- 5) Tabla Dieta (auditoría)
CREATE TABLE Dieta (
    idDieta INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    archivo VARCHAR(255),
    idEmpleado INT,
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (idEmpleado) REFERENCES Empleado(idEmpleado)
        ON DELETE SET NULL ON UPDATE CASCADE
);

-- 6) Tabla Rutina (auditoría)
CREATE TABLE Rutina (
    idRutina INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    idCliente INT,
    idEmpleado INT,
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (idCliente) REFERENCES Cliente(idCliente)
        ON DELETE CASCADE ON UPDATE CASCADE,
     FOREIGN KEY (idEmpleado) REFERENCES Empleado(idEmpleado)
        ON DELETE CASCADE ON UPDATE CASCADE

);

-- 7) Relación ClienteDieta
CREATE TABLE ClienteDieta (
    idCliente INT,
    idDieta INT,
    fechaAsignacion DATE,
    fechaFin DATE,
    PRIMARY KEY (idCliente, idDieta),
    FOREIGN KEY (idCliente) REFERENCES Cliente(idCliente)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (idDieta) REFERENCES Dieta(idDieta)
        ON DELETE CASCADE ON UPDATE CASCADE
);

-- 8) Relación UsuarioRutina
CREATE TABLE UsuarioRutina (
    idUsuario INT,
    idRutina INT,
    fechaAsignacion DATE,
    fechaFin DATE,
    PRIMARY KEY (idUsuario, idRutina),
    FOREIGN KEY (idUsuario) REFERENCES Cliente(idCliente)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (idRutina) REFERENCES Rutina(idRutina)
        ON DELETE CASCADE ON UPDATE CASCADE
);

-- 9) Relación ClienteTarifa
CREATE TABLE ClienteTarifa (
    idCliente INT,
    idTarifa INT,
    estado ENUM('ACTIVA','INACTIVA','SUSPENDIDA') NOT NULL DEFAULT 'ACTIVA',
    fechaContratacion DATE,
    fechaRenovacion DATE,
    fechaFin DATE,
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (idCliente, idTarifa),
    FOREIGN KEY (idCliente) REFERENCES Cliente(idCliente)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (idTarifa) REFERENCES Tarifa(idTarifa)
        ON DELETE CASCADE ON UPDATE CASCADE
);

-- 10) Tabla Revision
CREATE TABLE Revision (
    idRevision INT PRIMARY KEY AUTO_INCREMENT,
    fecha DATE NOT NULL,
    peso DECIMAL(5,2),
    grasa DECIMAL(5,2),
    musculo DECIMAL(5,2),
    mPecho DECIMAL(5,2),
    mCintura DECIMAL(5,2),
    mCadera DECIMAL(5,2),
    observaciones TEXT,
    imagen VARCHAR(255),
    idCliente INT NOT NULL,
    idEmpleado INT,
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (idCliente) REFERENCES Cliente(idCliente)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (idEmpleado) REFERENCES Empleado(idEmpleado)
        ON DELETE SET NULL  ON UPDATE CASCADE
);