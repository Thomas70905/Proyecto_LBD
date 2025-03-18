-- USER en DB
/*

Create user Proyecto_LBD IDENTIFIED BY Fidelitas123;

Grant create  session to Proyecto_LBD; 

Grant DBA to Proyecto_LBD; 

Commit;

*/
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

INSERT INTO Clientes (Nombre, Apellido, Telefono, Email, Direccion, FechaRegistro) 
VALUES ('Ana', 'Martinez', '111222333', 'ana.martinez@correo.com', 'Calle Primavera 789', TO_DATE('2025-04-05', 'YYYY-MM-DD'));

INSERT INTO Clientes (Nombre, Apellido, Telefono, Email, Direccion, FechaRegistro) 
VALUES ('Luis', 'Rodriguez', '444555666', 'luis.rodriguez@correo.com', 'Avenida Libertad 101', TO_DATE('2025-05-12', 'YYYY-MM-DD'));

INSERT INTO Clientes (Nombre, Apellido, Telefono, Email, Direccion, FechaRegistro) 
VALUES ('Sofia', 'Fernandez', '777888999', 'sofia.fernandez@correo.com', 'Calle Luna 234', TO_DATE('2025-06-20', 'YYYY-MM-DD'));

INSERT INTO Clientes (Nombre, Apellido, Telefono, Email, Direccion, FechaRegistro) 
VALUES ('Pedro', 'Garcia', '222333444', 'pedro.garcia@correo.com', 'Boulevard Sol 567', TO_DATE('2025-07-25', 'YYYY-MM-DD'));

INSERT INTO Clientes (Nombre, Apellido, Telefono, Email, Direccion, FechaRegistro) 
VALUES ('Laura', 'Diaz', '888999000', 'laura.diaz@correo.com', 'Calle Estrella 890', TO_DATE('2025-08-30', 'YYYY-MM-DD'));


-- 📌 MASCOTAS
INSERT INTO Mascotas (ClienteID, Nombre, Raza, Edad, Peso, Color, FechaNacimiento) 
VALUES (1, 'Max', 'Labrador', 3, 25.5, 'Dorado', TO_DATE('2022-05-15', 'YYYY-MM-DD'));

INSERT INTO Mascotas (ClienteID, Nombre, Raza, Edad, Peso, Color, FechaNacimiento) 
VALUES (2, 'Bella', 'Siames', 2, 4.2, 'Blanco', TO_DATE('2023-07-20', 'YYYY-MM-DD'));

INSERT INTO Mascotas (ClienteID, Nombre, Raza, Edad, Peso, Color, FechaNacimiento) 
VALUES (3, 'Rocky', 'Bulldog', 4, 30.0, 'Blanco', TO_DATE('2021-03-12', 'YYYY-MM-DD'));

INSERT INTO Mascotas (ClienteID, Nombre, Raza, Edad, Peso, Color, FechaNacimiento) 
VALUES (4, 'Luna', 'Persa', 1, 3.8, 'Gris', TO_DATE('2024-01-10', 'YYYY-MM-DD'));

INSERT INTO Mascotas (ClienteID, Nombre, Raza, Edad, Peso, Color, FechaNacimiento) 
VALUES (5, 'Thor', 'Golden Retriever', 5, 28.5, 'Dorado', TO_DATE('2020-09-22', 'YYYY-MM-DD'));

INSERT INTO Mascotas (ClienteID, Nombre, Raza, Edad, Peso, Color, FechaNacimiento) 
VALUES (6, 'Mimi', 'Sphynx', 2, 3.5, 'Rosa', TO_DATE('2023-04-18', 'YYYY-MM-DD'));

INSERT INTO Mascotas (ClienteID, Nombre, Raza, Edad, Peso, Color, FechaNacimiento) 
VALUES (7, 'Toby', 'Beagle', 3, 12.0, 'Tricolor', TO_DATE('2022-11-05', 'YYYY-MM-DD'));


-- 📌 HISTORIAL MÉDICO
INSERT INTO Historial (MascotaID, FechaConsulta, Diagnostico, Tratamiento, Observaciones) 
VALUES (1, TO_DATE('2023-01-10', 'YYYY-MM-DD'), 'Control rutinario', 'Vacunación anual', 'Mascota en buen estado');

INSERT INTO Historial (MascotaID, FechaConsulta, Diagnostico, Tratamiento, Observaciones) 
VALUES (2, TO_DATE('2023-02-20', 'YYYY-MM-DD'), 'Problemas digestivos', 'Dieta especial', 'Seguimiento en una semana');

INSERT INTO Historial (MascotaID, FechaConsulta, Diagnostico, Tratamiento, Observaciones) 
VALUES (3, TO_DATE('2023-03-15', 'YYYY-MM-DD'), 'Dolor articular', 'Antiinflamatorios', 'Reposo por una semana');

INSERT INTO Historial (MascotaID, FechaConsulta, Diagnostico, Tratamiento, Observaciones) 
VALUES (4, TO_DATE('2023-04-10', 'YYYY-MM-DD'), 'Control de peso', 'Dieta balanceada', 'Mascota en buen estado');

INSERT INTO Historial (MascotaID, FechaConsulta, Diagnostico, Tratamiento, Observaciones) 
VALUES (5, TO_DATE('2023-05-20', 'YYYY-MM-DD'), 'Problemas de piel', 'Crema dermatológica', 'Seguimiento en dos semanas');

INSERT INTO Historial (MascotaID, FechaConsulta, Diagnostico, Tratamiento, Observaciones) 
VALUES (6, TO_DATE('2023-06-05', 'YYYY-MM-DD'), 'Infección ocular', 'Gotas oftálmicas', 'Revisión en una semana');

INSERT INTO Historial (MascotaID, FechaConsulta, Diagnostico, Tratamiento, Observaciones) 
VALUES (7, TO_DATE('2023-07-12', 'YYYY-MM-DD'), 'Control de vacunas', 'Vacunación anual', 'Mascota en buen estado');

-- 📌 INVENTARIO
INSERT INTO Inventario (Nombre, Descripcion, Cantidad, NivelCritico, FechaCaducidad, Precio) 
VALUES ('Comida para perros', 'Alimento balanceado para perros adultos', 100, 20, TO_DATE('2024-05-01', 'YYYY-MM-DD'), 25.50);

INSERT INTO Inventario (Nombre, Descripcion, Cantidad, NivelCritico, FechaCaducidad, Precio) 
VALUES ('Shampoo para mascotas', 'Shampoo especial para mascotas', 50, 10, NULL, 15.75);

INSERT INTO Inventario (Nombre, Descripcion, Cantidad, NivelCritico, FechaCaducidad, Precio) 
VALUES ('Juguetes para gatos', 'Juguetes interactivos para gatos', 200, 50, NULL, 10.00);

INSERT INTO Inventario (Nombre, Descripcion, Cantidad, NivelCritico, FechaCaducidad, Precio) 
VALUES ('Cama para perros', 'Cama cómoda para perros medianos', 30, 5, NULL, 45.00);

INSERT INTO Inventario (Nombre, Descripcion, Cantidad, NivelCritico, FechaCaducidad, Precio) 
VALUES ('Snacks para perros', 'Snacks saludables para perros', 150, 30, TO_DATE('2024-08-01', 'YYYY-MM-DD'), 12.75);

INSERT INTO Inventario (Nombre, Descripcion, Cantidad, NivelCritico, FechaCaducidad, Precio) 
VALUES ('Arena para gatos', 'Arena aglomerante', 100, 20, NULL, 8.50);

INSERT INTO Inventario (Nombre, Descripcion, Cantidad, NivelCritico, FechaCaducidad, Precio) 
VALUES ('Correa para perros', 'Correa ajustable', 80, 10, NULL, 18.00);

-- 📌 MOVIMIENTOS DE INVENTARIO
INSERT INTO MovimientosInventario (ProductoID, TipoMovimiento, Cantidad, FechaMovimiento) 
VALUES (1, 'Entrada', 50, TO_DATE('2023-01-05', 'YYYY-MM-DD'));

INSERT INTO MovimientosInventario (ProductoID, TipoMovimiento, Cantidad, FechaMovimiento) 
VALUES (2, 'Salida', 10, TO_DATE('2023-02-10', 'YYYY-MM-DD'));

INSERT INTO MovimientosInventario (ProductoID, TipoMovimiento, Cantidad, FechaMovimiento) 
VALUES (3, 'Entrada', 100, TO_DATE('2023-03-01', 'YYYY-MM-DD'));

INSERT INTO MovimientosInventario (ProductoID, TipoMovimiento, Cantidad, FechaMovimiento) 
VALUES (4, 'Salida', 5, TO_DATE('2023-04-15', 'YYYY-MM-DD'));

INSERT INTO MovimientosInventario (ProductoID, TipoMovimiento, Cantidad, FechaMovimiento) 
VALUES (5, 'Entrada', 50, TO_DATE('2023-05-10', 'YYYY-MM-DD'));

INSERT INTO MovimientosInventario (ProductoID, TipoMovimiento, Cantidad, FechaMovimiento) 
VALUES (6, 'Salida', 10, TO_DATE('2023-06-20', 'YYYY-MM-DD'));

INSERT INTO MovimientosInventario (ProductoID, TipoMovimiento, Cantidad, FechaMovimiento) 
VALUES (7, 'Entrada', 30, TO_DATE('2023-07-05', 'YYYY-MM-DD'));


-- 📌 SERVICIOS
INSERT INTO Servicios (NombreServicio, Descripcion, Precio) 
VALUES ('Consulta general', 'Consulta médica general para mascotas', 50.00);

INSERT INTO Servicios (NombreServicio, Descripcion, Precio) 
VALUES ('Vacunación', 'Aplicación de vacunas', 30.00);

INSERT INTO Servicios (NombreServicio, Descripcion, Precio) 
VALUES ('Corte de pelo', 'Corte de pelo para mascotas', 40.00);

INSERT INTO Servicios (NombreServicio, Descripcion, Precio) 
VALUES ('Limpieza dental', 'Limpieza dental profesional', 80.00);

INSERT INTO Servicios (NombreServicio, Descripcion, Precio) 
VALUES ('Ecografía', 'Ecografía diagnóstica', 120.00);

INSERT INTO Servicios (NombreServicio, Descripcion, Precio) 
VALUES ('Cirugía menor', 'Cirugía ambulatoria', 200.00);

INSERT INTO Servicios (NombreServicio, Descripcion, Precio) 
VALUES ('Adiestramiento', 'Sesiones de adiestramiento básico', 60.00);


-- 📌 CITAS
INSERT INTO Citas (MascotaID, ServicioID, FechaHora, Estado) 
VALUES (1, 1, TO_TIMESTAMP('2023-01-15 10:00:00', 'YYYY-MM-DD HH24:MI:SS'), 'Completada');

INSERT INTO Citas (MascotaID, ServicioID, FechaHora, Estado) 
VALUES (2, 2, TO_TIMESTAMP('2023-02-25 11:00:00', 'YYYY-MM-DD HH24:MI:SS'), 'Completada');

INSERT INTO Citas (MascotaID, ServicioID, FechaHora, Estado) 
VALUES (3, 3, TO_TIMESTAMP('2023-03-20 09:00:00', 'YYYY-MM-DD HH24:MI:SS'), 'Completada');

INSERT INTO Citas (MascotaID, ServicioID, FechaHora, Estado) 
VALUES (4, 4, TO_TIMESTAMP('2023-04-25 10:30:00', 'YYYY-MM-DD HH24:MI:SS'), 'Programada');

INSERT INTO Citas (MascotaID, ServicioID, FechaHora, Estado) 
VALUES (5, 5, TO_TIMESTAMP('2023-05-30 11:00:00', 'YYYY-MM-DD HH24:MI:SS'), 'Cancelada');

INSERT INTO Citas (MascotaID, ServicioID, FechaHora, Estado) 
VALUES (6, 6, TO_TIMESTAMP('2023-06-15 12:00:00', 'YYYY-MM-DD HH24:MI:SS'), 'Completada');

INSERT INTO Citas (MascotaID, ServicioID, FechaHora, Estado) 
VALUES (7, 7, TO_TIMESTAMP('2023-07-22 13:30:00', 'YYYY-MM-DD HH24:MI:SS'), 'Programada');


-- 📌 USUARIOS
INSERT INTO Usuarios (NombreUsuario, Contrasena, Rol, FechaRegistro) 
VALUES ('admin', 'admin123', 'Veterinario', TO_DATE('2023-01-01', 'YYYY-MM-DD'));


--- CRUD Cliente ---

SET SERVEROUTPUT ON;

-- CREATE

CREATE OR REPLACE PROCEDURE InsertarCliente (
    p_Nombre IN VARCHAR2,
    p_Apellido IN VARCHAR2,
    p_Telefono IN VARCHAR2,
    p_Email IN VARCHAR2,
    p_Direccion IN VARCHAR2
) AS
BEGIN
    INSERT INTO Clientes (Nombre, Apellido, Telefono, Email, Direccion)
    VALUES (p_Nombre, p_Apellido, p_Telefono, p_Email, p_Direccion);
    COMMIT;
    DBMS_OUTPUT.PUT_LINE('Cliente Ingresado');
EXCEPTION
    WHEN OTHERS THEN
        DBMS_OUTPUT.PUT_LINE('No es posible ingresar al Cliente' || SQLERRM);
END;
/

--Ejemplo de CREATE

BEGIN
    InsertarCliente('Juan', 'Perez', '123456789', 'juan.perez@correo.com', 'Calle 123');
END;
/

-- READ

CREATE OR REPLACE PROCEDURE ConsultarCliente (
    p_ClienteID IN NUMBER,
    p_Nombre OUT VARCHAR2,
    p_Apellido OUT VARCHAR2,
    p_Telefono OUT VARCHAR2,
    p_Email OUT VARCHAR2,
    p_Direccion OUT VARCHAR2,
    p_FechaRegistro OUT DATE
) AS
BEGIN
    SELECT Nombre, Apellido, Telefono, Email, Direccion, FechaRegistro
    INTO p_Nombre, p_Apellido, p_Telefono, p_Email, p_Direccion, p_FechaRegistro
    FROM Clientes
    WHERE ClienteID = p_ClienteID;
EXCEPTION
    WHEN NO_DATA_FOUND THEN
        DBMS_OUTPUT.PUT_LINE('Cliente no encontrado.');
    WHEN OTHERS THEN
        DBMS_OUTPUT.PUT_LINE('Error al consultar cliente: ' || SQLERRM);
END;
/

--Ejemplo de READ

DECLARE
    v_Nombre VARCHAR2(100);
    v_Apellido VARCHAR2(100);
    v_Telefono VARCHAR2(20);
    v_Email VARCHAR2(100);
    v_Direccion VARCHAR2(255);
    v_FechaRegistro DATE;
BEGIN
    ConsultarCliente(1, v_Nombre, v_Apellido, v_Telefono, v_Email, v_Direccion, v_FechaRegistro);
    DBMS_OUTPUT.PUT_LINE('Nombre: ' || v_Nombre || ', Apellido: ' || v_Apellido);
END;
/

-- UPDATE

CREATE OR REPLACE PROCEDURE ActualizarCliente (
    p_ClienteID IN NUMBER,
    p_Nombre IN VARCHAR2,
    p_Apellido IN VARCHAR2,
    p_Telefono IN VARCHAR2,
    p_Email IN VARCHAR2,
    p_Direccion IN VARCHAR2
) AS
BEGIN
    UPDATE Clientes
    SET Nombre = p_Nombre,
        Apellido = p_Apellido,
        Telefono = p_Telefono,
        Email = p_Email,
        Direccion = p_Direccion
    WHERE ClienteID = p_ClienteID;
    COMMIT;
    DBMS_OUTPUT.PUT_LINE('Cliente actualizado');
EXCEPTION
    WHEN OTHERS THEN
        DBMS_OUTPUT.PUT_LINE('No es posible actualizar el cliente' || SQLERRM);
END;
/

-- Ejemplo de UPDATE

BEGIN
    ActualizarCliente(1, 'Juan Carlos', 'Perez', '987654321', 'juan.carlos@correo.com', 'Avenida Siempre Viva 456');
END;
/


-- DELETE

CREATE OR REPLACE PROCEDURE EliminarCliente (
    p_ClienteID IN NUMBER
) AS
BEGIN
    DELETE FROM Clientes
    WHERE ClienteID = p_ClienteID;
    COMMIT;
    DBMS_OUTPUT.PUT_LINE('Cliente eliminado');
EXCEPTION
    WHEN OTHERS THEN
        DBMS_OUTPUT.PUT_LINE('No es posible eliminar el cliente ' || SQLERRM);
END;
/

-- Ejemplo DELETE

BEGIN
    EliminarCliente(1);
END;
/

--- CRUD MASCOTAS ---
-- CREATE

CREATE OR REPLACE PROCEDURE InsertarMascota (
    p_ClienteID IN NUMBER,
    p_Nombre IN VARCHAR2,
    p_Raza IN VARCHAR2,
    p_Edad IN NUMBER,
    p_Peso IN NUMBER,
    p_Color IN VARCHAR2,
    p_FechaNacimiento IN DATE
) AS
BEGIN
    INSERT INTO Mascotas (ClienteID, Nombre, Raza, Edad, Peso, Color, FechaNacimiento)
    VALUES (p_ClienteID, p_Nombre, p_Raza, p_Edad, p_Peso, p_Color, p_FechaNacimiento);
    COMMIT;
    DBMS_OUTPUT.PUT_LINE('Mascota insertada correctamente.');
EXCEPTION
    WHEN OTHERS THEN
        DBMS_OUTPUT.PUT_LINE('Error al insertar mascota: ' || SQLERRM);
        ROLLBACK;
END;
/

-- Ejemplo de Create

BEGIN
    InsertarMascota(1, 'Max', 'Labrador', 3, 25.5, 'Dorado', TO_DATE('2020-05-15', 'YYYY-MM-DD'));
END;
/

-- READ

CREATE OR REPLACE PROCEDURE ConsultarMascota (
    p_MascotaID IN NUMBER,
    p_ClienteID OUT NUMBER,
    p_Nombre OUT VARCHAR2,
    p_Raza OUT VARCHAR2,
    p_Edad OUT NUMBER,
    p_Peso OUT NUMBER,
    p_Color OUT VARCHAR2,
    p_FechaNacimiento OUT DATE
) AS
BEGIN
    SELECT ClienteID, Nombre, Raza, Edad, Peso, Color, FechaNacimiento
    INTO p_ClienteID, p_Nombre, p_Raza, p_Edad, p_Peso, p_Color, p_FechaNacimiento
    FROM Mascotas
    WHERE MascotaID = p_MascotaID;
EXCEPTION
    WHEN NO_DATA_FOUND THEN
        DBMS_OUTPUT.PUT_LINE('Mascota no encontrada.');
    WHEN OTHERS THEN
        DBMS_OUTPUT.PUT_LINE('Error al consultar mascota: ' || SQLERRM);
END;
/

-- Ejemplo de READ

DECLARE
    v_ClienteID NUMBER;
    v_Nombre VARCHAR2(100);
    v_Raza VARCHAR2(100);
    v_Edad NUMBER;
    v_Peso NUMBER;
    v_Color VARCHAR2(50);
    v_FechaNacimiento DATE;
BEGIN
    ConsultarMascota(1, v_ClienteID, v_Nombre, v_Raza, v_Edad, v_Peso, v_Color, v_FechaNacimiento);
    DBMS_OUTPUT.PUT_LINE('Nombre: ' || v_Nombre || ', Raza: ' || v_Raza);
END;
/

-- UPDATE

CREATE OR REPLACE PROCEDURE ActualizarMascota (
    p_MascotaID IN NUMBER,
    p_ClienteID IN NUMBER,
    p_Nombre IN VARCHAR2,
    p_Raza IN VARCHAR2,
    p_Edad IN NUMBER,
    p_Peso IN NUMBER,
    p_Color IN VARCHAR2,
    p_FechaNacimiento IN DATE
) AS
BEGIN
    UPDATE Mascotas
    SET ClienteID = p_ClienteID,
        Nombre = p_Nombre,
        Raza = p_Raza,
        Edad = p_Edad,
        Peso = p_Peso,
        Color = p_Color,
        FechaNacimiento = p_FechaNacimiento
    WHERE MascotaID = p_MascotaID;
    COMMIT;
    DBMS_OUTPUT.PUT_LINE('Mascota actualizada correctamente.');
EXCEPTION
    WHEN OTHERS THEN
        DBMS_OUTPUT.PUT_LINE('Error al actualizar mascota: ' || SQLERRM);
        ROLLBACK;
END;
/

-- Ejemplo de UPDATE

BEGIN
    ActualizarMascota(1, 1, 'Maximus', 'Labrador Retriever', 4, 28.0, 'Dorado', TO_DATE('2022-05-15', 'YYYY-MM-DD'));
END;
/

-- DELETE

CREATE OR REPLACE PROCEDURE EliminarMascota (
    p_MascotaID IN NUMBER
) AS
BEGIN
    DELETE FROM Mascotas
    WHERE MascotaID = p_MascotaID;
    COMMIT;
    DBMS_OUTPUT.PUT_LINE('Mascota eliminada correctamente.');
EXCEPTION
    WHEN OTHERS THEN
        DBMS_OUTPUT.PUT_LINE('Error al eliminar mascota: ' || SQLERRM);
        ROLLBACK;
END;
/

-- Ejemplo de DELETE

BEGIN
    EliminarMascota(1);
END;
/

--- CRUD HISTORIAL ---
-- CREATE

CREATE OR REPLACE PROCEDURE InsertarHistorial (
    p_MascotaID IN NUMBER,
    p_FechaConsulta IN DATE,
    p_Diagnostico IN VARCHAR2,
    p_Tratamiento IN VARCHAR2,
    p_Observaciones IN VARCHAR2
) AS
BEGIN
    INSERT INTO Historial (MascotaID, FechaConsulta, Diagnostico, Tratamiento, Observaciones)
    VALUES (p_MascotaID, p_FechaConsulta, p_Diagnostico, p_Tratamiento, p_Observaciones);
    COMMIT;
    DBMS_OUTPUT.PUT_LINE('Historial insertado correctamente.');
EXCEPTION
    WHEN OTHERS THEN
        DBMS_OUTPUT.PUT_LINE('Error al insertar historial: ' || SQLERRM);
        ROLLBACK;
END;
/

-- Ejemplo de Create

BEGIN
    InsertarHistorial(1, TO_DATE('2023-01-10', 'YYYY-MM-DD'), 'Control rutinario', 'Vacunación anual', 'Mascota en buen estado');
END;
/

-- READ

CREATE OR REPLACE PROCEDURE ConsultarHistorial (
    p_HistorialID IN NUMBER,
    p_MascotaID OUT NUMBER,
    p_FechaConsulta OUT DATE,
    p_Diagnostico OUT VARCHAR2,
    p_Tratamiento OUT VARCHAR2,
    p_Observaciones OUT VARCHAR2
) AS
BEGIN
    SELECT MascotaID, FechaConsulta, Diagnostico, Tratamiento, Observaciones
    INTO p_MascotaID, p_FechaConsulta, p_Diagnostico, p_Tratamiento, p_Observaciones
    FROM Historial
    WHERE HistorialID = p_HistorialID;
EXCEPTION
    WHEN NO_DATA_FOUND THEN
        DBMS_OUTPUT.PUT_LINE('Historial no encontrado.');
    WHEN OTHERS THEN
        DBMS_OUTPUT.PUT_LINE('Error al consultar historial: ' || SQLERRM);
END;
/

-- Ejemplo de READ

DECLARE
    v_MascotaID NUMBER;
    v_FechaConsulta DATE;
    v_Diagnostico VARCHAR2(4000);
    v_Tratamiento VARCHAR2(4000);
    v_Observaciones VARCHAR2(4000);
BEGIN
    ConsultarHistorial(1, v_MascotaID, v_FechaConsulta, v_Diagnostico, v_Tratamiento, v_Observaciones);
    DBMS_OUTPUT.PUT_LINE('Diagnóstico: ' || v_Diagnostico);
END;
/

-- UPDATE

CREATE OR REPLACE PROCEDURE ActualizarHistorial (
    p_HistorialID IN NUMBER,
    p_MascotaID IN NUMBER,
    p_FechaConsulta IN DATE,
    p_Diagnostico IN VARCHAR2,
    p_Tratamiento IN VARCHAR2,
    p_Observaciones IN VARCHAR2
) AS
BEGIN
    UPDATE Historial
    SET MascotaID = p_MascotaID,
        FechaConsulta = p_FechaConsulta,
        Diagnostico = p_Diagnostico,
        Tratamiento = p_Tratamiento,
        Observaciones = p_Observaciones
    WHERE HistorialID = p_HistorialID;
    COMMIT;
    DBMS_OUTPUT.PUT_LINE('Historial actualizado correctamente.');
EXCEPTION
    WHEN OTHERS THEN
        DBMS_OUTPUT.PUT_LINE('Error al actualizar historial: ' || SQLERRM);
        ROLLBACK;
END;
/

-- Ejemplo de UPDATE

BEGIN
    ActualizarHistorial(1, 1, TO_DATE('2023-01-10', 'YYYY-MM-DD'), 'Control general', 'Vacunación y desparasitación', 'Mascota saludable');
END;
/

-- DELETE

CREATE OR REPLACE PROCEDURE EliminarHistorial (
    p_HistorialID IN NUMBER
) AS
BEGIN
    DELETE FROM Historial
    WHERE HistorialID = p_HistorialID;
    COMMIT;
    DBMS_OUTPUT.PUT_LINE('Historial eliminado correctamente.');
EXCEPTION
    WHEN OTHERS THEN
        DBMS_OUTPUT.PUT_LINE('Error al eliminar historial: ' || SQLERRM);
        ROLLBACK;
END;
/

-- Ejemplo de DELETE

BEGIN
    EliminarHistorial(1);
END;
/

--- CRUD INVENTARIO ---
-- CREATE

CREATE OR REPLACE PROCEDURE InsertarProducto (
    p_Nombre IN VARCHAR2,
    p_Descripcion IN VARCHAR2,
    p_Cantidad IN NUMBER,
    p_NivelCritico IN NUMBER,
    p_FechaCaducidad IN DATE,
    p_Precio IN NUMBER
) AS
BEGIN
    INSERT INTO Inventario (Nombre, Descripcion, Cantidad, NivelCritico, FechaCaducidad, Precio)
    VALUES (p_Nombre, p_Descripcion, p_Cantidad, p_NivelCritico, p_FechaCaducidad, p_Precio);
    COMMIT;
    DBMS_OUTPUT.PUT_LINE('Producto insertado correctamente.');
EXCEPTION
    WHEN OTHERS THEN
        DBMS_OUTPUT.PUT_LINE('Error al insertar producto: ' || SQLERRM);
        ROLLBACK;
END;
/

-- Ejemplo de Create

BEGIN
    InsertarProducto('Comida para perros', 'Alimento balanceado', 100, 20, TO_DATE('2024-05-01', 'YYYY-MM-DD'), 25.50);
END;
/

-- READ

CREATE OR REPLACE PROCEDURE ConsultarProducto (
    p_ProductoID IN NUMBER,
    p_Nombre OUT VARCHAR2,
    p_Descripcion OUT VARCHAR2,
    p_Cantidad OUT NUMBER,
    p_NivelCritico OUT NUMBER,
    p_FechaCaducidad OUT DATE,
    p_Precio OUT NUMBER
) AS
BEGIN
    SELECT Nombre, Descripcion, Cantidad, NivelCritico, FechaCaducidad, Precio
    INTO p_Nombre, p_Descripcion, p_Cantidad, p_NivelCritico, p_FechaCaducidad, p_Precio
    FROM Inventario
    WHERE ProductoID = p_ProductoID;
EXCEPTION
    WHEN NO_DATA_FOUND THEN
        DBMS_OUTPUT.PUT_LINE('Producto no encontrado.');
    WHEN OTHERS THEN
        DBMS_OUTPUT.PUT_LINE('Error al consultar producto: ' || SQLERRM);
END;
/

-- Ejemplo de READ

DECLARE
    v_Nombre VARCHAR2(100);
    v_Descripcion VARCHAR2(4000);
    v_Cantidad NUMBER;
    v_NivelCritico NUMBER;
    v_FechaCaducidad DATE;
    v_Precio NUMBER;
BEGIN
    ConsultarProducto(1, v_Nombre, v_Descripcion, v_Cantidad, v_NivelCritico, v_FechaCaducidad, v_Precio);
    DBMS_OUTPUT.PUT_LINE('Nombre: ' || v_Nombre || ', Precio: ' || v_Precio);
END;
/

-- UPDATE

CREATE OR REPLACE PROCEDURE ActualizarProducto (
    p_ProductoID IN NUMBER,
    p_Nombre IN VARCHAR2,
    p_Descripcion IN VARCHAR2,
    p_Cantidad IN NUMBER,
    p_NivelCritico IN NUMBER,
    p_FechaCaducidad IN DATE,
    p_Precio IN NUMBER
) AS
BEGIN
    UPDATE Inventario
    SET Nombre = p_Nombre,
        Descripcion = p_Descripcion,
        Cantidad = p_Cantidad,
        NivelCritico = p_NivelCritico,
        FechaCaducidad = p_FechaCaducidad,
        Precio = p_Precio
    WHERE ProductoID = p_ProductoID;
    COMMIT;
    DBMS_OUTPUT.PUT_LINE('Producto actualizado correctamente.');
EXCEPTION
    WHEN OTHERS THEN
        DBMS_OUTPUT.PUT_LINE('Error al actualizar producto: ' || SQLERRM);
        ROLLBACK;
END;
/

-- Ejemplo de UPDATE

BEGIN
    ActualizarProducto(1, 'Comida para perros premium', 'Alimento de alta calidad', 150, 25, TO_DATE('2024-05-01', 'YYYY-MM-DD'), 30.00);
END;
/

-- DELETE

CREATE OR REPLACE PROCEDURE EliminarProducto (
    p_ProductoID IN NUMBER
) AS
BEGIN
    DELETE FROM Inventario
    WHERE ProductoID = p_ProductoID;
    COMMIT;
    DBMS_OUTPUT.PUT_LINE('Producto eliminado correctamente.');
EXCEPTION
    WHEN OTHERS THEN
        DBMS_OUTPUT.PUT_LINE('Error al eliminar producto: ' || SQLERRM);
        ROLLBACK;
END;
/

-- Ejemplo de DELETE

BEGIN
    EliminarProducto(1);
END;
/

--- CRUD  MOVIMIENTOS DE INVENTARIO ---
-- CREATE

CREATE OR REPLACE PROCEDURE InsertarMovimiento (
    p_ProductoID IN NUMBER,
    p_TipoMovimiento IN VARCHAR2,
    p_Cantidad IN NUMBER
) AS
BEGIN
    INSERT INTO MovimientosInventario (ProductoID, TipoMovimiento, Cantidad)
    VALUES (p_ProductoID, p_TipoMovimiento, p_Cantidad);
    COMMIT;
    DBMS_OUTPUT.PUT_LINE('Movimiento insertado correctamente.');
EXCEPTION
    WHEN OTHERS THEN
        DBMS_OUTPUT.PUT_LINE('Error al insertar movimiento: ' || SQLERRM);
        ROLLBACK;
END;
/

-- Ejemplo de Create

BEGIN
    InsertarMovimiento(1, 'Entrada', 50);
END;
/

-- READ

CREATE OR REPLACE PROCEDURE ConsultarMovimiento (
    p_MovimientoID IN NUMBER,
    p_ProductoID OUT NUMBER,
    p_TipoMovimiento OUT VARCHAR2,
    p_Cantidad OUT NUMBER,
    p_FechaMovimiento OUT DATE
) AS
BEGIN
    SELECT ProductoID, TipoMovimiento, Cantidad, FechaMovimiento
    INTO p_ProductoID, p_TipoMovimiento, p_Cantidad, p_FechaMovimiento
    FROM MovimientosInventario
    WHERE MovimientoID = p_MovimientoID;
EXCEPTION
    WHEN NO_DATA_FOUND THEN
        DBMS_OUTPUT.PUT_LINE('Movimiento no encontrado.');
    WHEN OTHERS THEN
        DBMS_OUTPUT.PUT_LINE('Error al consultar movimiento: ' || SQLERRM);
END;
/

-- Ejemplo de READ

DECLARE
    v_ProductoID NUMBER;
    v_TipoMovimiento VARCHAR2(10);
    v_Cantidad NUMBER;
    v_FechaMovimiento DATE;
BEGIN
    ConsultarMovimiento(1, v_ProductoID, v_TipoMovimiento, v_Cantidad, v_FechaMovimiento);
    DBMS_OUTPUT.PUT_LINE('Tipo: ' || v_TipoMovimiento || ', Cantidad: ' || v_Cantidad);
END;
/

-- UPDATE

CREATE OR REPLACE PROCEDURE ActualizarMovimiento (
    p_MovimientoID IN NUMBER,
    p_ProductoID IN NUMBER,
    p_TipoMovimiento IN VARCHAR2,
    p_Cantidad IN NUMBER
) AS
BEGIN
    UPDATE MovimientosInventario
    SET ProductoID = p_ProductoID,
        TipoMovimiento = p_TipoMovimiento,
        Cantidad = p_Cantidad
    WHERE MovimientoID = p_MovimientoID;
    COMMIT;
    DBMS_OUTPUT.PUT_LINE('Movimiento actualizado correctamente.');
EXCEPTION
    WHEN OTHERS THEN
        DBMS_OUTPUT.PUT_LINE('Error al actualizar movimiento: ' || SQLERRM);
        ROLLBACK;
END;
/

-- Ejemplo de UPDATE

BEGIN
    ActualizarMovimiento(1, 1, 'Salida', 10);
END;
/

-- DELETE

CREATE OR REPLACE PROCEDURE EliminarMovimiento (
    p_MovimientoID IN NUMBER
) AS
BEGIN
    DELETE FROM MovimientosInventario
    WHERE MovimientoID = p_MovimientoID;
    COMMIT;
    DBMS_OUTPUT.PUT_LINE('Movimiento eliminado correctamente.');
EXCEPTION
    WHEN OTHERS THEN
        DBMS_OUTPUT.PUT_LINE('Error al eliminar movimiento: ' || SQLERRM);
        ROLLBACK;
END;
/

-- Ejemplo de DELETE

BEGIN
    EliminarMovimiento(1);
END;
/

--- CRUD SERVICIOS ---
-- CREATE

CREATE OR REPLACE PROCEDURE InsertarServicio (
    p_NombreServicio IN VARCHAR2,
    p_Descripcion IN VARCHAR2,
    p_Precio IN NUMBER
) AS
BEGIN
    INSERT INTO Servicios (NombreServicio, Descripcion, Precio)
    VALUES (p_NombreServicio, p_Descripcion, p_Precio);
    COMMIT;
    DBMS_OUTPUT.PUT_LINE('Servicio insertado correctamente.');
EXCEPTION
    WHEN OTHERS THEN
        DBMS_OUTPUT.PUT_LINE('Error al insertar servicio: ' || SQLERRM);
        ROLLBACK;
END;
/

-- Ejemplo de Create

BEGIN
    InsertarServicio('Consulta general', 'Consulta médica general para mascotas', 50.00);
END;
/

-- READ

CREATE OR REPLACE PROCEDURE ConsultarServicio (
    p_ServicioID IN NUMBER,
    p_NombreServicio OUT VARCHAR2,
    p_Descripcion OUT VARCHAR2,
    p_Precio OUT NUMBER
) AS
BEGIN
    SELECT NombreServicio, Descripcion, Precio
    INTO p_NombreServicio, p_Descripcion, p_Precio
    FROM Servicios
    WHERE ServicioID = p_ServicioID;
EXCEPTION
    WHEN NO_DATA_FOUND THEN
        DBMS_OUTPUT.PUT_LINE('Servicio no encontrado.');
    WHEN OTHERS THEN
        DBMS_OUTPUT.PUT_LINE('Error al consultar servicio: ' || SQLERRM);
END;
/

-- Ejemplo de READ

DECLARE
    v_NombreServicio VARCHAR2(100);
    v_Descripcion VARCHAR2(4000);
    v_Precio NUMBER;
BEGIN
    ConsultarServicio(1, v_NombreServicio, v_Descripcion, v_Precio);
    DBMS_OUTPUT.PUT_LINE('Servicio: ' || v_NombreServicio || ', Precio: ' || v_Precio);
END;
/

-- UPDATE

CREATE OR REPLACE PROCEDURE ActualizarServicio (
    p_ServicioID IN NUMBER,
    p_NombreServicio IN VARCHAR2,
    p_Descripcion IN VARCHAR2,
    p_Precio IN NUMBER
) AS
BEGIN
    UPDATE Servicios
    SET NombreServicio = p_NombreServicio,
        Descripcion = p_Descripcion,
        Precio = p_Precio
    WHERE ServicioID = p_ServicioID;
    COMMIT;
    DBMS_OUTPUT.PUT_LINE('Servicio actualizado correctamente.');
EXCEPTION
    WHEN OTHERS THEN
        DBMS_OUTPUT.PUT_LINE('Error al actualizar servicio: ' || SQLERRM);
        ROLLBACK;
END;
/

-- Ejemplo de UPDATE

BEGIN
    ActualizarServicio(1, 'Consulta general', 'Consulta médica general para mascotas', 60.00);
END;
/

-- DELETE

CREATE OR REPLACE PROCEDURE EliminarServicio (
    p_ServicioID IN NUMBER
) AS
BEGIN
    DELETE FROM Servicios
    WHERE ServicioID = p_ServicioID;
    COMMIT;
    DBMS_OUTPUT.PUT_LINE('Servicio eliminado correctamente.');
EXCEPTION
    WHEN OTHERS THEN
        DBMS_OUTPUT.PUT_LINE('Error al eliminar servicio: ' || SQLERRM);
        ROLLBACK;
END;
/

-- Ejemplo de DELETE

BEGIN
    EliminarServicio(1);
END;
/

--- CRUD AGENDA ---
-- CREATE

CREATE OR REPLACE PROCEDURE InsertarCita (
    p_MascotaID IN NUMBER,
    p_ServicioID IN NUMBER,
    p_FechaHora IN TIMESTAMP
) AS
BEGIN
    INSERT INTO Citas (MascotaID, ServicioID, FechaHora)
    VALUES (p_MascotaID, p_ServicioID, p_FechaHora);
    COMMIT;
    DBMS_OUTPUT.PUT_LINE('Cita insertada correctamente.');
EXCEPTION
    WHEN OTHERS THEN
        DBMS_OUTPUT.PUT_LINE('Error al insertar cita: ' || SQLERRM);
        ROLLBACK;
END;
/

-- Ejemplo de Create

BEGIN
    InsertarCita(1, 1, TO_TIMESTAMP('2023-01-15 10:00:00', 'YYYY-MM-DD HH24:MI:SS'));
END;
/

-- READ

CREATE OR REPLACE PROCEDURE ConsultarCita (
    p_CitaID IN NUMBER,
    p_MascotaID OUT NUMBER,
    p_ServicioID OUT NUMBER,
    p_FechaHora OUT TIMESTAMP,
    p_Estado OUT VARCHAR2
) AS
BEGIN
    SELECT MascotaID, ServicioID, FechaHora, Estado
    INTO p_MascotaID, p_ServicioID, p_FechaHora, p_Estado
    FROM Citas
    WHERE CitaID = p_CitaID;
EXCEPTION
    WHEN NO_DATA_FOUND THEN
        DBMS_OUTPUT.PUT_LINE('Cita no encontrada.');
    WHEN OTHERS THEN
        DBMS_OUTPUT.PUT_LINE('Error al consultar cita: ' || SQLERRM);
END;
/

-- Ejemplo de READ

DECLARE
    v_MascotaID NUMBER;
    v_ServicioID NUMBER;
    v_FechaHora TIMESTAMP;
    v_Estado VARCHAR2(15);
BEGIN
    ConsultarCita(1, v_MascotaID, v_ServicioID, v_FechaHora, v_Estado);
    DBMS_OUTPUT.PUT_LINE('Fecha: ' || TO_CHAR(v_FechaHora, 'YYYY-MM-DD HH24:MI:SS') || ', Estado: ' || v_Estado);
END;
/

-- UPDATE

CREATE OR REPLACE PROCEDURE ActualizarCita (
    p_CitaID IN NUMBER,
    p_MascotaID IN NUMBER,
    p_ServicioID IN NUMBER,
    p_FechaHora IN TIMESTAMP,
    p_Estado IN VARCHAR2
) AS
BEGIN
    UPDATE Citas
    SET MascotaID = p_MascotaID,
        ServicioID = p_ServicioID,
        FechaHora = p_FechaHora,
        Estado = p_Estado
    WHERE CitaID = p_CitaID;
    COMMIT;
    DBMS_OUTPUT.PUT_LINE('Cita actualizada correctamente.');
EXCEPTION
    WHEN OTHERS THEN
        DBMS_OUTPUT.PUT_LINE('Error al actualizar cita: ' || SQLERRM);
        ROLLBACK;
END;
/

-- Ejemplo de UPDATE

BEGIN
    ActualizarCita(1, 1, 1, TO_TIMESTAMP('2023-01-15 11:00:00', 'YYYY-MM-DD HH24:MI:SS'), 'Completada');
END;
/

-- DELETE

CREATE OR REPLACE PROCEDURE EliminarCita (
    p_CitaID IN NUMBER
) AS
BEGIN
    DELETE FROM Citas
    WHERE CitaID = p_CitaID;
    COMMIT;
    DBMS_OUTPUT.PUT_LINE('Cita eliminada correctamente.');
EXCEPTION
    WHEN OTHERS THEN
        DBMS_OUTPUT.PUT_LINE('Error al eliminar cita: ' || SQLERRM);
        ROLLBACK;
END;
/

-- Ejemplo de DELETE

BEGIN
    EliminarCita(1);
END;
/


-- PROCEDIMINETOS ALMACENADOS

-- Procedimientos creados dentro de CRUD

-- VISTAS

--1
CREATE OR REPLACE VIEW Vista_Clientes_Mascotas AS -- Muestra a que cliente pertenece que Mascota
SELECT 
    c.ClienteID,
    c.Nombre AS NombreCliente,
    c.Apellido AS ApellidoCliente,
    m.MascotaID,
    m.Nombre AS NombreMascota,
    m.Raza,
    m.Edad,
    m.Peso,
    m.Color
FROM 
    Clientes c
LEFT JOIN 
    Mascotas m ON c.ClienteID = m.ClienteID;

SELECT * FROM Vista_Clientes_Mascotas;

--2
CREATE OR REPLACE VIEW Vista_Historial_Completo AS -- Muestra un historial de todos los datos de la mascota
SELECT 
    h.HistorialID,
    m.MascotaID,
    m.Nombre AS NombreMascota,
    c.ClienteID,
    c.Nombre AS NombreCliente,
    c.Apellido AS ApellidoCliente,
    h.FechaConsulta,
    h.Diagnostico,
    h.Tratamiento,
    h.Observaciones
FROM 
    Historial h
JOIN 
    Mascotas m ON h.MascotaID = m.MascotaID
JOIN 
    Clientes c ON m.ClienteID = c.ClienteID;
    
SELECT * FROM Vista_Historial_Completo;

--3
CREATE OR REPLACE VIEW Vista_Inventario_Critico AS -- Muestra los datos de cuando la cantidad del inventario es menos a lo que se posee en el inventario, para saber que hace falta producto
SELECT 
    ProductoID,
    Nombre,
    Descripcion,
    Cantidad,
    NivelCritico,
    FechaCaducidad,
    Precio
FROM 
    Inventario
WHERE 
    Cantidad <= NivelCritico;
    
SELECT * FROM Vista_Inventario_Critico;

--4
CREATE OR REPLACE VIEW Vista_Movimientos_Resumidos AS -- Movimientos de los productos si entran o salen
SELECT 
    p.ProductoID,
    p.Nombre AS NombreProducto,
    SUM(CASE WHEN m.TipoMovimiento = 'Entrada' THEN m.Cantidad ELSE 0 END) AS Total_Entradas,
    SUM(CASE WHEN m.TipoMovimiento = 'Salida' THEN m.Cantidad ELSE 0 END) AS Total_Salidas
FROM 
    MovimientosInventario m
JOIN 
    Inventario p ON m.ProductoID = p.ProductoID
GROUP BY 
    p.ProductoID, p.Nombre;

SELECT * FROM Vista_Movimientos_Resumidos;

--5
CREATE OR REPLACE VIEW Vista_Citas_Detalladas AS --Muestra un detalle completo de todo lo realizado en las citas a las mascotas
SELECT 
    ci.CitaID,
    ci.FechaHora,
    ci.Estado,
    m.MascotaID,
    m.Nombre AS NombreMascota,
    s.ServicioID,
    s.NombreServicio,
    s.Descripcion AS DescripcionServicio,
    s.Precio AS PrecioServicio,
    c.ClienteID,
    c.Nombre AS NombreCliente,
    c.Apellido AS ApellidoCliente
FROM 
    Citas ci
JOIN 
    Mascotas m ON ci.MascotaID = m.MascotaID
JOIN 
    Servicios s ON ci.ServicioID = s.ServicioID
JOIN 
    Clientes c ON m.ClienteID = c.ClienteID;
    
SELECT * FROM Vista_Citas_Detalladas;

-- FUNCIONES
--1
CREATE OR REPLACE FUNCTION TotalMascotasCliente ( --Muestra el nuemero de total de mascotas
    p_ClienteID IN NUMBER
) RETURN NUMBER
AS
    v_TotalMascotas NUMBER;
BEGIN
    SELECT COUNT(*)
    INTO v_TotalMascotas
    FROM Mascotas
    WHERE ClienteID = p_ClienteID;

    RETURN v_TotalMascotas;
EXCEPTION
    WHEN OTHERS THEN
        RETURN 'ERROR'; 
END;
/

SELECT TotalMascotasCliente(1) AS TotalMascotas FROM dual;

--2
CREATE OR REPLACE FUNCTION TotalMovimientosProducto ( --CANTIDAD DE VECES QUE SE MOVIO UN PRODUCTO
    p_ProductoID IN NUMBER
) RETURN NUMBER
AS
    v_TotalMovimientos NUMBER;
BEGIN
    SELECT COUNT(*)
    INTO v_TotalMovimientos
    FROM MovimientosInventario
    WHERE ProductoID = p_ProductoID;

    RETURN v_TotalMovimientos;
EXCEPTION
    WHEN OTHERS THEN
        RETURN 'ERROR'; 
END;
/

SELECT TotalMovimientosProducto(3) AS TotalMovimientos FROM dual;

--3
CREATE OR REPLACE FUNCTION TotalVentasServicio ( --CANTIDAD DE VENTAS POR SERVICIO
    p_ServicioID IN NUMBER
) RETURN NUMBER
AS
    v_TotalVentas NUMBER;
BEGIN
    SELECT SUM(s.Precio)
    INTO v_TotalVentas
    FROM Citas c
    JOIN Servicios s ON c.ServicioID = s.ServicioID
    WHERE c.ServicioID = p_ServicioID;

    RETURN NVL(v_TotalVentas, 0); 
EXCEPTION
    WHEN OTHERS THEN
        RETURN 'ERROR'; 
END;
/

SELECT TotalVentasServicio(1) AS TotalVentas FROM dual;

--4
CREATE OR REPLACE FUNCTION EstadoCita ( -- El estado de una CITA
    p_CitaID IN NUMBER
) RETURN VARCHAR2
AS
    v_Estado VARCHAR2(15);
BEGIN
    SELECT Estado
    INTO v_Estado
    FROM Citas
    WHERE CitaID = p_CitaID;

    RETURN v_Estado;
EXCEPTION
    WHEN NO_DATA_FOUND THEN
        RETURN 'No encontrada'; 
    WHEN OTHERS THEN
        RETURN 'Error'; 
END;
/

SELECT EstadoCita(&1) AS Estado FROM dual;


--5
CREATE OR REPLACE FUNCTION TotalGastosCliente ( --Calcular el gasto de un cliente
    p_ClienteID IN NUMBER
) RETURN NUMBER
AS
    v_TotalGastos NUMBER := 0;
BEGIN

    SELECT NVL(SUM(s.Precio), 0)
    INTO v_TotalGastos
    FROM Citas c
    JOIN Servicios s ON c.ServicioID = s.ServicioID
    JOIN Mascotas m ON c.MascotaID = m.MascotaID
    WHERE m.ClienteID = p_ClienteID;

    RETURN v_TotalGastos;
EXCEPTION
    WHEN OTHERS THEN
        RETURN -1; -- Retorna -1 en caso de error
END;
/

SELECT TotalGastosCliente(&1) AS TotalGastos FROM dual;

-- PAQUETES
-- TRIGGERS
-- CURSORES

