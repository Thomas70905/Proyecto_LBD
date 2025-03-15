--Proyecto SQL

-- Tablas

-- CLIENTES
CREATE TABLE Clientes (
    ClienteID NUMBER GENERATED BY DEFAULT AS IDENTITY PRIMARY KEY,
    Nombre VARCHAR2(100) NOT NULL,
    Apellido VARCHAR2(100) NOT NULL,
    Telefono NUM,
    Email VARCHAR2(100),
    Direccion VARCHAR2(255),
    FechaRegistro DATE
);

-- MASCOTAS
CREATE TABLE Mascotas (
    MascotaID NUMBER GENERATED BY DEFAULT AS IDENTITY PRIMARY KEY,
    ClienteID NUMBER,
    Nombre VARCHAR2(100) NOT NULL,
    Raza VARCHAR2(100),
    Edad NUMBER,
    Peso NUMBER(5,2),
    Color VARCHAR2(50),
    FechaNacimiento DATE,
    FOREIGN KEY (ClienteID) REFERENCES Clientes(ClienteID)
);

-- HISTORIAL
CREATE TABLE Historial (
    HistorialID NUMBER GENERATED BY DEFAULT AS IDENTITY PRIMARY KEY,
    MascotaID NUMBER,
    FechaConsulta DATE NOT NULL,
    Diagnostico VARCHAR2(4000),
    Tratamiento VARCHAR2(4000),
    Observaciones VARCHAR2(4000),
    FOREIGN KEY (MascotaID) REFERENCES Mascotas(MascotaID)
);

-- INVENTARIO
CREATE TABLE Inventario (
    ProductoID NUMBER GENERATED BY DEFAULT AS IDENTITY PRIMARY KEY,
    Nombre VARCHAR2(100) NOT NULL,
    Descripcion VARCHAR2(4000),
    Cantidad NUMBER NOT NULL,
    NivelCritico NUMBER,
    FechaCaducidad DATE,
    Precio NUMBER(10,2)
);

-- MOVIMIENTOSD DE INVENTARIO
CREATE TABLE MovimientosInventario (
    MovimientoID NUMBER GENERATED BY DEFAULT AS IDENTITY PRIMARY KEY,
    ProductoID NUMBER,
    TipoMovimiento VARCHAR2(10) NOT NULL, --  ENTRADA o SALIDA
    Cantidad NUMBER NOT NULL,
    FechaMovimiento DATE,
    FOREIGN KEY (ProductoID) REFERENCES Inventario(ProductoID)
);

-- SERVICIOS
CREATE TABLE Servicios (
    ServicioID NUMBER GENERATED BY DEFAULT AS IDENTITY PRIMARY KEY,
    NombreServicio VARCHAR2(100) NOT NULL,
    Descripcion VARCHAR2(4000),
    PRECIO NUMBER
);

-- AGENDA
CREATE TABLE Citas (
    CitaID NUMBER GENERATED BY DEFAULT AS IDENTITY PRIMARY KEY,
    MascotaID NUMBER,
    ServicioID NUMBER,
    FechaHora TIMESTAMP NOT NULL,
    Estado VARCHAR2(15) DEFAULT 'Programada', -- TIPOS DE STATUS: 'Programada', 'Completada', 'Cancelada'
    FOREIGN KEY (MascotaID) REFERENCES Mascotas(MascotaID),
    FOREIGN KEY (ServicioID) REFERENCES Servicios(ServicioID)
);

-- USERS
CREATE TABLE Usuarios (
    UsuarioID NUMBER GENERATED BY DEFAULT AS IDENTITY PRIMARY KEY,
    NombreUsuario VARCHAR2(100) NOT NULL,
    Contrasena VARCHAR2(255) NOT NULL,
    Rol VARCHAR2(15) NOT NULL, -- ROLES:  'Veterinario', 'Cliente'
    FechaRegistro DATE
);

-- Insert de Datos

-- Clientes

INSERT INTO Clientes (Nombre, Apellido, Telefono, Email, Direccion, FechaRegistro) VALUES ('Juan', 'Perez', 123456789, 'juan.perez@correo.com', 'Calle Falsa 123', TO_DATE('2025-01-01', 'YYYY-MM-DD'));
INSERT INTO Clientes (Nombre, Apellido, Telefono, Email, Direccion, FechaRegistro) VALUES ('Maria', 'Gomez', 987654321, 'maria.gomez@correo.com', 'Avenida Siempre Viva 456', TO_DATE('2025-02-15', 'YYYY-MM-DD'));
INSERT INTO Clientes (Nombre, Apellido, Telefono, Email, Direccion, FechaRegistro) VALUES ('Carlos', 'Lopez', 555444333, 'carlos.lopez@correo.com', 'Boulevard de los Sueños 789', TO_DATE('2025-03-10', 'YYYY-MM-DD'));
INSERT INTO Clientes (Nombre, Apellido, Telefono, Email, Direccion, FechaRegistro) VALUES ('Ana', 'Martinez', 111222333, 'ana.martinez@correo.com', 'Calle de la Luna 321', TO_DATE('2025-04-05', 'YYYY-MM-DD'));
INSERT INTO Clientes (Nombre, Apellido, Telefono, Email, Direccion, FechaRegistro) VALUES ('Luis', 'Rodriguez', 444555666, 'luis.rodriguez@correo.com', 'Avenida del Sol 654', TO_DATE('2025-05-20', 'YYYY-MM-DD'));
INSERT INTO Clientes (Nombre, Apellido, Telefono, Email, Direccion, FechaRegistro) VALUES ('Sofia', 'Hernandez', 777888999, 'sofia.hernandez@correo.com', 'Calle de las Estrellas 987', TO_DATE('2025-06-25', 'YYYY-MM-DD'));
INSERT INTO Clientes (Nombre, Apellido, Telefono, Email, Direccion, FechaRegistro) VALUES ('Pedro', 'Garcia', 222333444, 'pedro.garcia@correo.com', 'Boulevard de las Flores 123', TO_DATE('2025-07-30', 'YYYY-MM-DD'));
INSERT INTO Clientes (Nombre, Apellido, Telefono, Email, Direccion, FechaRegistro) VALUES ('Laura', 'Diaz', 666777888, 'laura.diaz@correo.com', 'Avenida de los Pájaros 456', TO_DATE('2025-08-15', 'YYYY-MM-DD'));
INSERT INTO Clientes (Nombre, Apellido, Telefono, Email, Direccion, FechaRegistro) VALUES ('Jorge', 'Fernandez', 888999000, 'jorge.fernandez@correo.com', 'Calle de los Arboles 789', TO_DATE('2025-09-10', 'YYYY-MM-DD'));
INSERT INTO Clientes (Nombre, Apellido, Telefono, Email, Direccion, FechaRegistro) VALUES ('Carmen', 'Sanchez', 333222111, 'carmen.sanchez@correo.com', 'Avenida de las Montañas 321', TO_DATE('2025-10-05', 'YYYY-MM-DD'));

-- Mascotas

INSERT INTO Mascotas (ClienteID, Nombre, Raza, Edad, Peso, Color, FechaNacimiento) VALUES (1, 'Max', 'Labrador', 3, 25.5, 'Dorado', TO_DATE('2025-05-15', 'YYYY-MM-DD'));
INSERT INTO Mascotas (ClienteID, Nombre, Raza, Edad, Peso, Color, FechaNacimiento) VALUES (2, 'Bella', 'Siames', 2, 4.2, 'Blanco', TO_DATE('2025-07-20', 'YYYY-MM-DD'));
INSERT INTO Mascotas (ClienteID, Nombre, Raza, Edad, Peso, Color, FechaNacimiento) VALUES (3, 'Rocky', 'Bulldog', 5, 30.0, 'Marrón', TO_DATE('2025-03-10', 'YYYY-MM-DD'));
INSERT INTO Mascotas (ClienteID, Nombre, Raza, Edad, Peso, Color, FechaNacimiento) VALUES (4, 'Luna', 'Persa', 1, 3.8, 'Gris', TO_DATE('2025-09-05', 'YYYY-MM-DD'));
INSERT INTO Mascotas (ClienteID, Nombre, Raza, Edad, Peso, Color, FechaNacimiento) VALUES (5, 'Toby', 'Golden Retriever', 4, 28.0, 'Dorado', TO_DATE('2025-11-12', 'YYYY-MM-DD'));
INSERT INTO Mascotas (ClienteID, Nombre, Raza, Edad, Peso, Color, FechaNacimiento) VALUES (6, 'Mimi', 'Angora', 2, 3.5, 'Blanco', TO_DATE('2025-02-25', 'YYYY-MM-DD'));
INSERT INTO Mascotas (ClienteID, Nombre, Raza, Edad, Peso, Color, FechaNacimiento) VALUES (7, 'Thor', 'Pastor Alemán', 6, 35.0, 'Negro', TO_DATE('2025-08-30', 'YYYY-MM-DD'));
INSERT INTO Mascotas (ClienteID, Nombre, Raza, Edad, Peso, Color, FechaNacimiento) VALUES (8, 'Nina', 'Chihuahua', 3, 2.5, 'Café', TO_DATE('2025-04-18', 'YYYY-MM-DD'));
INSERT INTO Mascotas (ClienteID, Nombre, Raza, Edad, Peso, Color, FechaNacimiento) VALUES (9, 'Simba', 'Maine Coon', 2, 5.0, 'Naranja', TO_DATE('2025-06-22', 'YYYY-MM-DD'));
INSERT INTO Mascotas (ClienteID, Nombre, Raza, Edad, Peso, Color, FechaNacimiento) VALUES (10, 'Lola', 'Poodle', 4, 6.5, 'Blanco', TO_DATE('2025-10-10', 'YYYY-MM-DD'));

-- Historial

INSERT INTO Historial (MascotaID, FechaConsulta, Diagnostico, Tratamiento, Observaciones) VALUES (1, TO_DATE('2023-01-10', 'YYYY-MM-DD'), 'Control rutinario', 'Vacunación anual', 'Mascota en buen estado');
INSERT INTO Historial (MascotaID, FechaConsulta, Diagnostico, Tratamiento, Observaciones) VALUES (2, TO_DATE('2023-02-20', 'YYYY-MM-DD'), 'Problemas digestivos', 'Dieta especial', 'Seguimiento en una semana');
INSERT INTO Historial (MascotaID, FechaConsulta, Diagnostico, Tratamiento, Observaciones) VALUES (3, TO_DATE('2023-03-15', 'YYYY-MM-DD'), 'Dolor articular', 'Antiinflamatorios', 'Revisión en 15 días');
INSERT INTO Historial (MascotaID, FechaConsulta, Diagnostico, Tratamiento, Observaciones) VALUES (4, TO_DATE('2023-04-05', 'YYYY-MM-DD'), 'Control de peso', 'Dieta balanceada', 'Mascota con sobrepeso');
INSERT INTO Historial (MascotaID, FechaConsulta, Diagnostico, Tratamiento, Observaciones) VALUES (5, TO_DATE('2023-05-12', 'YYYY-MM-DD'), 'Alergia cutánea', 'Antihistamínicos', 'Evitar contacto con alérgenos');
INSERT INTO Historial (MascotaID, FechaConsulta, Diagnostico, Tratamiento, Observaciones) VALUES (6, TO_DATE('2023-06-18', 'YYYY-MM-DD'), 'Control dental', 'Limpieza dental', 'Mascota con sarro');
INSERT INTO Historial (MascotaID, FechaConsulta, Diagnostico, Tratamiento, Observaciones) VALUES (7, TO_DATE('2023-07-22', 'YYYY-MM-DD'), 'Lesión en pata', 'Reposo y vendaje', 'Revisión en 10 días');
INSERT INTO Historial (MascotaID, FechaConsulta, Diagnostico, Tratamiento, Observaciones) VALUES (8, TO_DATE('2023-08-30', 'YYYY-MM-DD'), 'Control de vacunas', 'Vacunación', 'Mascota en buen estado');
INSERT INTO Historial (MascotaID, FechaConsulta, Diagnostico, Tratamiento, Observaciones) VALUES (9, TO_DATE('2023-09-05', 'YYYY-MM-DD'), 'Problemas de pelo', 'Suplementos vitamínicos', 'Mejorar alimentación');
INSERT INTO Historial (MascotaID, FechaConsulta, Diagnostico, Tratamiento, Observaciones) VALUES (10, TO_DATE('2023-10-10', 'YYYY-MM-DD'), 'Control rutinario', 'Vacunación anual', 'Mascota en buen estado');

-- Iventario

INSERT INTO Inventario (Nombre, Descripcion, Cantidad, NivelCritico, FechaCaducidad, Precio) VALUES ('Comida para perros', 'Alimento balanceado para perros adultos', 100, 20, TO_DATE('2024-05-01', 'YYYY-MM-DD'), 25.50);
INSERT INTO Inventario (Nombre, Descripcion, Cantidad, NivelCritico, FechaCaducidad, Precio) VALUES ('Comida para gatos', 'Alimento balanceado para gatos adultos', 80, 15, TO_DATE('2024-06-01', 'YYYY-MM-DD'), 20.00);
INSERT INTO Inventario (Nombre, Descripcion, Cantidad, NivelCritico, FechaCaducidad, Precio) VALUES ('Shampoo para mascotas', 'Shampoo para perros y gatos', 50, 10, TO_DATE('2025-01-01', 'YYYY-MM-DD'), 15.75);
INSERT INTO Inventario (Nombre, Descripcion, Cantidad, NivelCritico, FechaCaducidad, Precio) VALUES ('Juguetes para perros', 'Juguetes de goma resistente', 120, 30, NULL, 10.00);
INSERT INTO Inventario (Nombre, Descripcion, Cantidad, NivelCritico, FechaCaducidad, Precio) VALUES ('Arena para gatos', 'Arena aglomerante', 60, 10, TO_DATE('2024-12-01', 'YYYY-MM-DD'), 12.50);
INSERT INTO Inventario (Nombre, Descripcion, Cantidad, NivelCritico, FechaCaducidad, Precio) VALUES ('Correas para perros', 'Correas ajustables', 70, 15, NULL, 8.00);
INSERT INTO Inventario (Nombre, Descripcion, Cantidad, NivelCritico, FechaCaducidad, Precio) VALUES ('Cepillos para mascotas', 'Cepillos de cerdas suaves', 90, 20, NULL, 5.50);
INSERT INTO Inventario (Nombre, Descripcion, Cantidad, NivelCritico, FechaCaducidad, Precio) VALUES ('Vacunas para perros', 'Vacuna antirrábica', 40, 5, TO_DATE('2024-08-01', 'YYYY-MM-DD'), 30.00);
INSERT INTO Inventario (Nombre, Descripcion, Cantidad, NivelCritico, FechaCaducidad, Precio) VALUES ('Vacunas para gatos', 'Vacuna triple felina', 35, 5, TO_DATE('2024-09-01', 'YYYY-MM-DD'), 28.00);
INSERT INTO Inventario (Nombre, Descripcion, Cantidad, NivelCritico, FechaCaducidad, Precio) VALUES ('Snacks para perros', 'Snacks de pollo', 150, 25, TO_DATE('2024-07-01', 'YYYY-MM-DD'), 7.00);

-- Movimiento de Inventario

INSERT INTO MovimientosInventario (ProductoID, TipoMovimiento, Cantidad, FechaMovimiento) VALUES (1, 'Entrada', 50, TO_DATE('2023-01-05', 'YYYY-MM-DD'));
INSERT INTO MovimientosInventario (ProductoID, TipoMovimiento, Cantidad, FechaMovimiento) VALUES (2, 'Entrada', 40, TO_DATE('2023-02-10', 'YYYY-MM-DD'));
INSERT INTO MovimientosInventario (ProductoID, TipoMovimiento, Cantidad, FechaMovimiento) VALUES (3, 'Entrada', 30, TO_DATE('2023-03-15', 'YYYY-MM-DD'));
INSERT INTO MovimientosInventario (ProductoID, TipoMovimiento, Cantidad, FechaMovimiento) VALUES (4, 'Entrada', 60, TO_DATE('2023-04-20', 'YYYY-MM-DD'));
INSERT INTO MovimientosInventario (ProductoID, TipoMovimiento, Cantidad, FechaMovimiento) VALUES (5, 'Entrada', 50, TO_DATE('2023-05-25', 'YYYY-MM-DD'));
INSERT INTO MovimientosInventario (ProductoID, TipoMovimiento, Cantidad, FechaMovimiento) VALUES (6, 'Entrada', 40, TO_DATE('2023-06-30', 'YYYY-MM-DD'));
INSERT INTO MovimientosInventario (ProductoID, TipoMovimiento, Cantidad, FechaMovimiento) VALUES (7, 'Entrada', 70, TO_DATE('2023-07-05', 'YYYY-MM-DD'));
INSERT INTO MovimientosInventario (ProductoID, TipoMovimiento, Cantidad, FechaMovimiento) VALUES (8, 'Entrada', 20, TO_DATE('2023-08-10', 'YYYY-MM-DD'));
INSERT INTO MovimientosInventario (ProductoID, TipoMovimiento, Cantidad, FechaMovimiento) VALUES (9, 'Entrada', 25, TO_DATE('2023-09-15', 'YYYY-MM-DD'));
INSERT INTO MovimientosInventario (ProductoID, TipoMovimiento, Cantidad, FechaMovimiento) VALUES (10, 'Entrada', 100, TO_DATE('2023-10-20', 'YYYY-MM-DD'));

-- Servicios

INSERT INTO Servicios (NombreServicio, Descripcion, Precio) VALUES ('Consulta general', 'Consulta médica general para mascotas', 50.00);
INSERT INTO Servicios (NombreServicio, Descripcion, Precio) VALUES ('Vacunación', 'Aplicación de vacunas', 30.00);
INSERT INTO Servicios (NombreServicio, Descripcion, Precio) VALUES ('Cirugía menor', 'Cirugía de baja complejidad', 200.00);
INSERT INTO Servicios (NombreServicio, Descripcion, Precio) VALUES ('Limpieza dental', 'Limpieza y profilaxis dental', 80.00);
INSERT INTO Servicios (NombreServicio, Descripcion, Precio) VALUES ('Desparasitación', 'Tratamiento antiparasitario', 25.00);
INSERT INTO Servicios (NombreServicio, Descripcion, Precio) VALUES ('Ecografía', 'Ecografía diagnóstica', 120.00);
INSERT INTO Servicios (NombreServicio, Descripcion, Precio) VALUES ('Radiografía', 'Radiografía diagnóstica', 100.00);
INSERT INTO Servicios (NombreServicio, Descripcion, Precio) VALUES ('Hospitalización', 'Cuidado y monitoreo hospitalario', 150.00);
INSERT INTO Servicios (NombreServicio, Descripcion, Precio) VALUES ('Peluquería', 'Corte y cuidado del pelaje', 40.00);
INSERT INTO Servicios (NombreServicio, Descripcion, Precio) VALUES ('Control de peso', 'Control y seguimiento de peso', 20.00);

-- Agenda

INSERT INTO Citas (MascotaID, ServicioID, FechaHora, Estado) VALUES (1, 1, TO_TIMESTAMP('2023-01-15 10:00:00', 'YYYY-MM-DD HH24:MI:SS'), 'Completada');
INSERT INTO Citas (MascotaID, ServicioID, FechaHora, Estado) VALUES (2, 2, TO_TIMESTAMP('2023-02-25 11:00:00', 'YYYY-MM-DD HH24:MI:SS'), 'Completada');
INSERT INTO Citas (MascotaID, ServicioID, FechaHora, Estado) VALUES (3, 3, TO_TIMESTAMP('2023-03-20 12:00:00', 'YYYY-MM-DD HH24:MI:SS'), 'Cancelada');
INSERT INTO Citas (MascotaID, ServicioID, FechaHora, Estado) VALUES (4, 4, TO_TIMESTAMP('2023-04-10 13:00:00', 'YYYY-MM-DD HH24:MI:SS'), 'Programada');
INSERT INTO Citas (MascotaID, ServicioID, FechaHora, Estado) VALUES (5, 5, TO_TIMESTAMP('2023-05-15 14:00:00', 'YYYY-MM-DD HH24:MI:SS'), 'Completada');
INSERT INTO Citas (MascotaID, ServicioID, FechaHora, Estado) VALUES (6, 6, TO_TIMESTAMP('2023-06-20 15:00:00', 'YYYY-MM-DD HH24:MI:SS'), 'Programada');
INSERT INTO Citas (MascotaID, ServicioID, FechaHora, Estado) VALUES (7, 7, TO_TIMESTAMP('2023-07-25 16:00:00', 'YYYY-MM-DD HH24:MI:SS'), 'Completada');
INSERT INTO Citas (MascotaID, ServicioID, FechaHora, Estado) VALUES (8, 8, TO_TIMESTAMP('2023-08-30 17:00:00', 'YYYY-MM-DD HH24:MI:SS'), 'Programada');
INSERT INTO Citas (MascotaID, ServicioID, FechaHora, Estado) VALUES (9, 9, TO_TIMESTAMP('2023-09-10 18:00:00', 'YYYY-MM-DD HH24:MI:SS'), 'Cancelada');
INSERT INTO Citas (MascotaID, ServicioID, FechaHora, Estado) VALUES (10, 10, TO_TIMESTAMP('2023-10-15 19:00:00', 'YYYY-MM-DD HH24:MI:SS'), 'Programada');

-- Usuarios

INSERT INTO Usuarios (NombreUsuario, Contrasena, Rol, FechaRegistro) VALUES ('admin', 'admin123', 'Veterinario', TO_DATE('2023-01-01', 'YYYY-MM-DD'));

-- Validar usuarios como manejarlos
