-- CLIENTES
CREATE TABLE Clientes (
    ClienteID NUMBER GENERATED BY DEFAULT AS IDENTITY PRIMARY KEY,
    Nombre VARCHAR2(100) NOT NULL,
    Apellido VARCHAR2(100) NOT NULL,
    Telefono VARCHAR2(20), -- Cambio de NUM a VARCHAR2(20)
    Email VARCHAR2(100),
    Direccion VARCHAR2(255),
    FechaRegistro DATE DEFAULT SYSDATE -- Se agrega DEFAULT SYSDATE
);

-- MASCOTAS
CREATE TABLE Mascotas (
    MascotaID NUMBER GENERATED BY DEFAULT AS IDENTITY PRIMARY KEY,
    ClienteID NUMBER NOT NULL, -- Se agrega NOT NULL
    Nombre VARCHAR2(100) NOT NULL,
    Raza VARCHAR2(100),
    Edad NUMBER,
    Peso NUMBER(5,2),
    Color VARCHAR2(50),
    FechaNacimiento DATE,
    CONSTRAINT fk_cliente FOREIGN KEY (ClienteID) REFERENCES Clientes(ClienteID)
);

-- HISTORIAL
CREATE TABLE Historial (
    HistorialID NUMBER GENERATED BY DEFAULT AS IDENTITY PRIMARY KEY,
    MascotaID NUMBER NOT NULL, -- Se agrega NOT NULL
    FechaConsulta DATE NOT NULL,
    Diagnostico VARCHAR2(4000),
    Tratamiento VARCHAR2(4000),
    Observaciones VARCHAR2(4000),
    CONSTRAINT fk_mascota FOREIGN KEY (MascotaID) REFERENCES Mascotas(MascotaID)
);

-- INVENTARIO
CREATE TABLE Inventario (
    ProductoID NUMBER GENERATED BY DEFAULT AS IDENTITY PRIMARY KEY,
    Nombre VARCHAR2(100) NOT NULL,
    Descripcion VARCHAR2(4000),
    Cantidad NUMBER NOT NULL CHECK (Cantidad >= 0),
    NivelCritico NUMBER CHECK (NivelCritico >= 0),
    FechaCaducidad DATE,
    Precio NUMBER(10,2) CHECK (Precio >= 0)
);

-- MOVIMIENTOS DE INVENTARIO
CREATE TABLE MovimientosInventario (
    MovimientoID NUMBER GENERATED BY DEFAULT AS IDENTITY PRIMARY KEY,
    ProductoID NUMBER NOT NULL, -- Se agrega NOT NULL
    TipoMovimiento VARCHAR2(10) NOT NULL CHECK (TipoMovimiento IN ('Entrada', 'Salida')), 
    Cantidad NUMBER NOT NULL CHECK (Cantidad > 0),
    FechaMovimiento DATE DEFAULT SYSDATE,
    CONSTRAINT fk_producto FOREIGN KEY (ProductoID) REFERENCES Inventario(ProductoID)
);

-- SERVICIOS
CREATE TABLE Servicios (
    ServicioID NUMBER GENERATED BY DEFAULT AS IDENTITY PRIMARY KEY,
    NombreServicio VARCHAR2(100) NOT NULL,
    Descripcion VARCHAR2(4000),
    Precio NUMBER(10,2) CHECK (Precio >= 0) -- Se define con precisión y restricción CHECK
);

-- AGENDA
CREATE TABLE Citas (
    CitaID NUMBER GENERATED BY DEFAULT AS IDENTITY PRIMARY KEY,
    MascotaID NUMBER NOT NULL,
    ServicioID NUMBER NOT NULL,
    FechaHora TIMESTAMP NOT NULL,
    Estado VARCHAR2(15) DEFAULT 'Programada' CHECK (Estado IN ('Programada', 'Completada', 'Cancelada')),
    CONSTRAINT fk_cita_mascota FOREIGN KEY (MascotaID) REFERENCES Mascotas(MascotaID),
    CONSTRAINT fk_cita_servicio FOREIGN KEY (ServicioID) REFERENCES Servicios(ServicioID)
);

-- USUARIOS
CREATE TABLE Usuarios (
    UsuarioID NUMBER GENERATED BY DEFAULT AS IDENTITY PRIMARY KEY,
    NombreUsuario VARCHAR2(100) NOT NULL UNIQUE,
    Contrasena VARCHAR2(255) NOT NULL,
    Rol VARCHAR2(15) NOT NULL CHECK (Rol IN ('Veterinario', 'Cliente')),
    FechaRegistro DATE DEFAULT SYSDATE
);

-- Validación: Insert de Clientes corregido con números de teléfono como VARCHAR2
INSERT INTO Clientes (Nombre, Apellido, Telefono, Email, Direccion, FechaRegistro) 
VALUES ('Juan', 'Perez', '123456789', 'juan.perez@correo.com', 'Calle Falsa 123', TO_DATE('2025-01-01', 'YYYY-MM-DD'));

--Inserts
-- 📌 CLIENTES
INSERT INTO Clientes (Nombre, Apellido, Telefono, Email, Direccion, FechaRegistro) 
VALUES ('Juan', 'Perez', '123456789', 'juan.perez@correo.com', 'Calle Falsa 123', TO_DATE('2025-01-01', 'YYYY-MM-DD'));

INSERT INTO Clientes (Nombre, Apellido, Telefono, Email, Direccion, FechaRegistro) 
VALUES ('Maria', 'Gomez', '987654321', 'maria.gomez@correo.com', 'Avenida Siempre Viva 456', TO_DATE('2025-02-15', 'YYYY-MM-DD'));

INSERT INTO Clientes (Nombre, Apellido, Telefono, Email, Direccion, FechaRegistro) 
VALUES ('Carlos', 'Lopez', '555444333', 'carlos.lopez@correo.com', 'Boulevard de los Sueños 789', TO_DATE('2025-03-10', 'YYYY-MM-DD'));


-- 📌 MASCOTAS
INSERT INTO Mascotas (ClienteID, Nombre, Raza, Edad, Peso, Color, FechaNacimiento) 
VALUES (1, 'Max', 'Labrador', 3, 25.5, 'Dorado', TO_DATE('2022-05-15', 'YYYY-MM-DD'));

INSERT INTO Mascotas (ClienteID, Nombre, Raza, Edad, Peso, Color, FechaNacimiento) 
VALUES (2, 'Bella', 'Siames', 2, 4.2, 'Blanco', TO_DATE('2023-07-20', 'YYYY-MM-DD'));


-- 📌 HISTORIAL MÉDICO
INSERT INTO Historial (MascotaID, FechaConsulta, Diagnostico, Tratamiento, Observaciones) 
VALUES (1, TO_DATE('2023-01-10', 'YYYY-MM-DD'), 'Control rutinario', 'Vacunación anual', 'Mascota en buen estado');

INSERT INTO Historial (MascotaID, FechaConsulta, Diagnostico, Tratamiento, Observaciones) 
VALUES (2, TO_DATE('2023-02-20', 'YYYY-MM-DD'), 'Problemas digestivos', 'Dieta especial', 'Seguimiento en una semana');


-- 📌 INVENTARIO
INSERT INTO Inventario (Nombre, Descripcion, Cantidad, NivelCritico, FechaCaducidad, Precio) 
VALUES ('Comida para perros', 'Alimento balanceado para perros adultos', 100, 20, TO_DATE('2024-05-01', 'YYYY-MM-DD'), 25.50);

INSERT INTO Inventario (Nombre, Descripcion, Cantidad, NivelCritico, FechaCaducidad, Precio) 
VALUES ('Shampoo para mascotas', 'Shampoo especial para mascotas', 50, 10, NULL, 15.75);


-- 📌 MOVIMIENTOS DE INVENTARIO
INSERT INTO MovimientosInventario (ProductoID, TipoMovimiento, Cantidad, FechaMovimiento) 
VALUES (1, 'Entrada', 50, TO_DATE('2023-01-05', 'YYYY-MM-DD'));

INSERT INTO MovimientosInventario (ProductoID, TipoMovimiento, Cantidad, FechaMovimiento) 
VALUES (2, 'Salida', 10, TO_DATE('2023-02-10', 'YYYY-MM-DD'));


-- 📌 SERVICIOS
INSERT INTO Servicios (NombreServicio, Descripcion, Precio) 
VALUES ('Consulta general', 'Consulta médica general para mascotas', 50.00);

INSERT INTO Servicios (NombreServicio, Descripcion, Precio) 
VALUES ('Vacunación', 'Aplicación de vacunas', 30.00);


-- 📌 CITAS
INSERT INTO Citas (MascotaID, ServicioID, FechaHora, Estado) 
VALUES (1, 1, TO_TIMESTAMP('2023-01-15 10:00:00', 'YYYY-MM-DD HH24:MI:SS'), 'Completada');

INSERT INTO Citas (MascotaID, ServicioID, FechaHora, Estado) 
VALUES (2, 2, TO_TIMESTAMP('2023-02-25 11:00:00', 'YYYY-MM-DD HH24:MI:SS'), 'Completada');


-- 📌 USUARIOS
INSERT INTO Usuarios (NombreUsuario, Contrasena, Rol, FechaRegistro) 
VALUES ('admin', 'admin123', 'Veterinario', TO_DATE('2023-01-01', 'YYYY-MM-DD'));
