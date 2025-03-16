-- Eliminar el usuario y su esquema si ya existe
DROP USER admin CASCADE;

--Crear tablespace
CREATE TABLESPACE BD_Legado
DATAFILE 'C:\bd\19c\oradata\ORCL\BD_Legado.DBF'
SIZE 300M --Tamaño inicial del Datafile
AUTOEXTEND ON--Permitir que el Datafile crezca automaticamente cuando se llene
NEXT 30M--Incremento de tamaño cada vez que el Datafile necesite crecer
MAXSIZE 3G; --Tamaño máximo al que el Datafile puede crecer

-- Crear el usuario para la base de datos
CREATE USER admin IDENTIFIED BY admin;

-- Asignar privilegios al usuario
GRANT CONNECT, RESOURCE TO admin;

-- Otorgar permisos adicionales si es necesario
GRANT CREATE SESSION, CREATE TABLE, CREATE VIEW, CREATE SEQUENCE, CREATE PROCEDURE TO admin;

--ALTER USER PARA AGREGAR QUOTE EN EL TBS
ALTER USER ADMIN QUOTA UNLIMITED ON BD_LEGADO;

--CREACION TABLA ESTADOS
CREATE TABLE ESTADOS(
ID_ESTADO NUMBER CONSTRAINT ID_ESTADO_PK PRIMARY KEY,
DESCRIPCION VARCHAR2(100)
) TABLESPACE BD_LEGADO;

--CREACION DE TABLA ROLES
CREATE TABLE ROLES(
ID_ROL NUMBER CONSTRAINT ID_ROL_PK PRIMARY KEY,
DESCRIPCION VARCHAR2(100)
) TABLESPACE BD_LEGADO;

--CREACION DE TABLA USUARIOS
CREATE TABLE USUARIOS (
    ID_USUARIO NUMBER CONSTRAINT ID_USUARIO_PK PRIMARY KEY,
    NOMBRE VARCHAR2(100) NOT NULL,
    EMAIL VARCHAR2(100) UNIQUE,
    ID_ESTADO NUMBER NOT NULL,
    USERNAME VARCHAR2(50) NOT NULL,
    CONTRASENA VARCHAR2(100) NOT NULL,
    ID_ROL NUMBER,
    CONSTRAINT ID_ESTADO_USUARIOS_FK FOREIGN KEY (ID_ESTADO) REFERENCES ESTADOS(ID_ESTADO),
    CONSTRAINT ID_ROL_USUARIOS_FK FOREIGN KEY (ID_ROL) REFERENCES ROLS(ID_ROL)
) TABLESPACE BD_LEGADO;
CREATE SEQUENCE SEQ_USUARIOS START WITH 3 INCREMENT BY 1;

--CREACION DE TABLA CARRITO
CREATE TABLE CARRITO(
    ID_CARRITO NUMBER CONSTRAINT ID_CARRITO_PK PRIMARY KEY,
    ID_USUARIO NUMBER,
    FECHA_CREACION DATE,
    CONSTRAINT ID_USUARIO_CARRITO_FK FOREIGN KEY (ID_USUARIO) REFERENCES USUARIOS(ID_USUARIO)
) TABLESPACE BD_LEGADO;


--CREACION DE TABLA CATEGORIA PRODUCTOS
CREATE TABLE CATEGORIAS_PRODUCTOS (
    ID_CATEGORIA NUMBER CONSTRAINT ID_CATEGORIA_PK PRIMARY KEY,
    DESCRIPCION VARCHAR2(100),
    ID_ESTADO NUMBER, 
    CONSTRAINT ID_ESTADO_CATEGORIA_FK FOREIGN KEY(ID_ESTADO) REFERENCES ESTADOS(ID_ESTADO)
) TABLESPACE BD_LEGADO;


--CREACION DE TABLA PRODUCTOS
CREATE TABLE PRODUCTOS (
    ID_PRODUCTO NUMBER CONSTRAINT ID_PRODUCTO_PK PRIMARY KEY,
    CANTIDAD NUMBER NOT NULL,
    PRECIO DECIMAL(10,2) NOT NULL,
    ID_CATEGORIA NUMBER NOT NULL,
    ID_ESTADO NUMBER NOT NULL,
    DESCRIPCION VARCHAR2(100) NOT NULL,
    NOMBRE VARCHAR2(100) NOT NULL,
    CONSTRAINT ID_CATEGORIA_PRODUCTOS_FK FOREIGN KEY(ID_CATEGORIA) REFERENCES CATEGORIAS_PRODUCTOS(ID_CATEGORIA),
    CONSTRAINT ID_ESTADO_PRODUCTOS_FK FOREIGN KEY(ID_ESTADO) REFERENCES ESTADOS(ID_ESTADO)
) TABLESPACE BD_LEGADO;
CREATE SEQUENCE SEQ_PRODUCTOS START WITH 1 INCREMENT BY 1;


--CREACION DE TABLA ARTICULOS_CARRITO
CREATE TABLE ARTICULOS_CARRITO (
    ID_ARTICULO NUMBER CONSTRAINT ID_ARTICULO_PK PRIMARY KEY,
    ID_CARRITO NUMBER,
    ID_PRODUCTO NUMBER,
    CANTIDAD NUMBER,
    CONSTRAINT ID_CARRITO_ARTICULOS_FK FOREIGN KEY(ID_CARRITO) REFERENCES CARRITO(ID_CARRITO),
    CONSTRAINT ID_PRODUCTO_CARRITO_FK FOREIGN KEY(ID_PRODUCTO) REFERENCES PRODUCTOS(ID_PRODUCTO)
) TABLESPACE BD_LEGADO;
CREATE SEQUENCE SEQ_ARTICULO_CARRITO START WITH 1 INCREMENT BY 1;

--CREACION DE TABLA INVENTARIO
CREATE TABLE INVENTARIO(
    ID_INVENTARIO NUMBER CONSTRAINT ID_INVENTARIO_PK PRIMARY KEY,
    NOMBRE VARCHAR2(100) NOT NULL,
    CANTIDAD NUMBER NOT NULL,
    ID_ESTADO NUMBER,
    CONSTRAINT ID_ESTADO_INVENTARIO_FK FOREIGN KEY (ID_ESTADO) REFERENCES ESTADOS(ID_ESTADO)
) TABLESPACE BD_LEGADO;
CREATE SEQUENCE SEQ_INVENTARIO START WITH 1 INCREMENT BY 1;

--CREACION DE TABLA CONSULTAS

CREATE TABLE CONSULTAS (
    ID_CONSULTA NUMBER CONSTRAINT ID_CONSULTA_PK PRIMARY KEY,  
    ID_USUARIO NUMBER,               
    TIPO VARCHAR2(50),              
    MENSAJE VARCHAR2(500),                   
    ID_ESTADO NUMBER,            
    CONSTRAINT ID_USUARIO_CONSULTAS_FK FOREIGN KEY (ID_USUARIO) REFERENCES USUARIOS(ID_USUARIO),
    CONSTRAINT ID_ESTADO_CONSULTA_FK FOREIGN KEY (ID_ESTADO) REFERENCES ESTADOS(ID_ESTADO)
) TABLESPACE BD_LEGADO;

--CREACION TABLA PEDIDOS
CREATE TABLE PEDIDOS(
    ID_PEDIDO NUMBER CONSTRAINT ID_PEDIDO_PK PRIMARY KEY,
    FECHA DATE NOT NULL,
    ID_USUARIO NUMBER,
    ID_ESTADO NUMBER,
    SUBTOTAL DECIMAL(10,2),
    TOTAL DECIMAL(10,2),
    CONSTRAINT ID_USUARIO_PEDIDOS_FK FOREIGN KEY(ID_USUARIO) REFERENCES USUARIOS(ID_USUARIO),
    CONSTRAINT ID_ESTADO_PEDIDOS_FK FOREIGN KEY(ID_ESTADO) REFERENCES ESTADOS(ID_ESTADO)
) TABLESPACE BD_LEGADO;
CREATE SEQUENCE SEQ_PEDIDOS START WITH 1 INCREMENT BY 1;

--CREACION TABLA PEDIDOS DETALLES
CREATE TABLE PEDIDOS_DETALLES (
    ID_PEDIDO_DETALLE NUMBER CONSTRAINT ID_PEDIDO_DETALLE_PK PRIMARY KEY,
    CANTIDAD NUMBER,
    ID_PEDIDO NUMBER,
    ID_PRODUCTO NUMBER,
    CONSTRAINT ID_PEDIDO_PEDIDOS_DETALLES_FK FOREIGN KEY(ID_PEDIDO) REFERENCES PEDIDOS(ID_PEDIDO),
    CONSTRAINT ID_PRODUCTO_PEDIDOS_FK FOREIGN KEY(ID_PRODUCTO) REFERENCES PRODUCTOS(ID_PRODUCTO)
) TABLESPACE BD_LEGADO;

--CREACION DE TABLA PROVINCIAS
CREATE TABLE PROVINCIAS(
ID_PROVINCIA NUMBER CONSTRAINT ID_PROVINCIA_PK PRIMARY KEY,
NOMBRE VARCHAR2(100)
) TABLESPACE BD_LEGADO;

--CREACION DE TABLA CANTONES

CREATE TABLE CANTONES(
ID_CANTON NUMBER CONSTRAINT ID_CANTON_PK PRIMARY KEY,
NOMBRE VARCHAR2(100),
ID_PROVINCIA NUMBER,
CONSTRAINT ID_PROVINCIA_CANTON_FK FOREIGN KEY (ID_PROVINCIA) REFERENCES PROVINCIAS(ID_PROVINCIA)
) TABLESPACE BD_LEGADO;

--CREACION DE TABLA DISTRITOS
CREATE TABLE DISTRITOS (
ID_DISTRITO NUMBER CONSTRAINT ID_DISTRITO_PK PRIMARY KEY,
NOMBRE VARCHAR2(100),
ID_CANTON NUMBER,
CONSTRAINT ID_CANTON_DISTRITO_FK FOREIGN KEY (ID_CANTON) REFERENCES CANTONES(ID_CANTON)
) TABLESPACE BD_LEGADO;


--CREACION TABLA DIRECCIONES
CREATE TABLE DIRECCIONES(
    ID_DIRECCION NUMBER CONSTRAINT ID_DIRECCION_PK PRIMARY KEY,
    DIRECCION_EXACTA VARCHAR2(255),
    ID_DISTRITO NUMBER,
    ID_USUARIO NUMBER,
    CONSTRAINT ID_DIRECCION_DISTRITO_FK FOREIGN KEY (ID_DISTRITO) REFERENCES DISTRITOS(ID_DISTRITO),
    CONSTRAINT ID_USUARIO_DIRECCION_FK FOREIGN KEY (ID_USUARIO) REFERENCES USUARIOS(ID_USUARIO)
)TABLESPACE BD_LEGADO;


--CREACION DE TABLA TELEFONOS
CREATE TABLE TELEFONOS(
    ID_TELEFONO NUMBER CONSTRAINT ID_TELEFONO_PK PRIMARY KEY,
    TELEFONO VARCHAR2(255),
    ID_ESTADO NUMBER,
    ID_USUARIO NUMBER,
    CONSTRAINT ID_ESTADO_TELEFONO_FK FOREIGN KEY (ID_ESTADO) REFERENCES ESTADOS(ID_ESTADO),
    CONSTRAINT ID_USUARIO_TELEFONO_FK FOREIGN KEY (ID_USUARIO) REFERENCES USUARIOS(ID_USUARIO)
)TABLESPACE BD_LEGADO;


--INSERTS TABLA ESTADOS 

INSERT INTO ESTADOS (ID_ESTADO, DESCRIPCION) VALUES (1,'ACTIVO');
INSERT INTO ESTADOS (ID_ESTADO, DESCRIPCION) VALUES (2,'INACTIVO');
INSERT INTO ESTADOS (ID_ESTADO, DESCRIPCION) VALUES (3,'EN PROCESO');
INSERT INTO ESTADOS (ID_ESTADO, DESCRIPCION) VALUES (4,'NUEVO');

--INSERTS TABLA ROLES 

INSERT INTO ROLES (ID_ROL, DESCRIPCION) VALUES (1,'COMPRADOR');
INSERT INTO ROLES (ID_ROL, DESCRIPCION) VALUES (2,'ADMINISTRADOR');

--INSERTS TABLA USUARIOS 

INSERT INTO USUARIOS (ID_USUARIO, NOMBRE, EMAIL, ID_ESTADO, USERNAME, CONTRASENA, ID_ROL) VALUES (1,'Jean Pool Pérez Carranza','jperez@gmail.com',1,'perezcj','12345',2);
INSERT INTO USUARIOS (ID_USUARIO, NOMBRE, EMAIL, ID_ESTADO, USERNAME, CONTRASENA, ID_ROL) VALUES (2,'Ariana Fallas Calderón','afallas@gmail.com',1,'fallasca','123456',1);

--SP INSERTAR USUARIO

CREATE OR REPLACE PROCEDURE AGREGAR_USUARIO(
    P_NOMBRE IN VARCHAR2,
    P_EMAIL IN VARCHAR2,
    P_ESTADO IN NUMBER,
    P_USERNAME IN VARCHAR2,
    P_CONTRASENA IN VARCHAR2,
    P_ROL IN NUMBER
) AS
    V_ID_USUARIO NUMBER;
BEGIN
    SELECT SEQ_USUARIOS.NEXTVAL INTO V_ID_USUARIO FROM USUARIOS;

    INSERT INTO USUARIOS (ID_USUARIO, NOMBRE, EMAIL, ID_ESTADO, USERNAME, CONTRASENA, ID_ROL)
    VALUES (V_ID_USUARIO, P_NOMBRE, P_EMAIL, P_ESTADO, P_USERNAME, P_CONTRASENA, P_ROL);

    COMMIT;
EXCEPTION
    WHEN OTHERS THEN
        ROLLBACK;
END;

--SP EDITAR USUARIO

CREATE OR REPLACE PROCEDURE EDITAR_USUARIO(
    P_ID_USUARIO IN NUMBER,
    P_NOMBRE IN VARCHAR2,
    P_EMAIL IN VARCHAR2,
    P_ESTADO IN NUMBER,
    P_USERNAME IN VARCHAR2,
    P_CONTRASENA IN VARCHAR2,
    P_ROL IN NUMBER
) AS
BEGIN
    UPDATE USUARIOS
    SET NOMBRE = P_NOMBRE,
        EMAIL = P_EMAIL,
        ID_ESTADO = P_ESTADO,
        USERNAME = P_USERNAME,
        CONTRASENA = P_CONTRASENA,
        ID_ROL = P_ROL
    WHERE ID_USUARIO = P_ID_USUARIO;

        COMMIT;
EXCEPTION
    WHEN OTHERS THEN
        ROLLBACK;
END;

--SP ELIMINAR USUARIO

CREATE OR REPLACE PROCEDURE ELIMINAR_USUARIO(
    P_ID_USUARIO IN NUMBER
) AS
BEGIN

    DELETE FROM USUARIOS
    WHERE ID_USUARIO = P_ID_USUARIO;

        COMMIT;
EXCEPTION
    WHEN OTHERS THEN
        ROLLBACK;
END;

--SP INSERTAR PRODUCTO

CREATE OR REPLACE PROCEDURE AGREGAR_PRODUCTO(
    P_CANTIDAD IN NUMBER,
    P_PRECIO IN DECIMAL,
    P_CATEGORIA IN NUMBER,
    P_ESTADO IN NUMBER,
    P_DESCRIPCION IN VARCHAR2,
    P_NOMBRE IN VARCHAR2
) AS
    V_ID_PRODUCTO NUMBER;
BEGIN
    SELECT SEQ_PRODUCTOS.NEXTVAL INTO V_ID_PRODUCTO FROM PRODUCTOS;

    INSERT INTO PRODUCTOS (ID_PRODUCTO, CANTIDAD, PRECIO, ID_CATEGORIA, ID_ESTADO, DESCRIPCION, NOMBRE)
    VALUES (V_ID_PRODUCTO, P_CANTIDAD, P_PRECIO, P_CATEGORIA, P_ESTADO, P_DESCRIPCION, P_NOMBRE);

    COMMIT;
EXCEPTION
    WHEN OTHERS THEN
        ROLLBACK;
END;

--SP EDITAR PRODUCTO

CREATE OR REPLACE PROCEDURE EDITAR_PRODUCTO(
    P_ID_PRODUCTO IN NUMBER,
    P_CANTIDAD IN NUMBER,
    P_PRECIO IN DECIMAL,
    P_CATEGORIA IN NUMBER,
    P_ESTADO IN NUMBER,
    P_DESCRIPCION IN VARCHAR2,
    P_NOMBRE IN VARCHAR2
) AS
BEGIN
    UPDATE PRODUCTOS
    SET CANTIDAD = P_CANTIDAD,
        PRECIO = P_PRECIO,
        ID_CATEGORIA = P_CATEGORIA,
        ID_ESTADO = P_ESTADO,
        DESCRIPCION = P_DESCRIPCION,
        NOMBRE = P_NOMBRE
    WHERE ID_PRODUCTO = P_ID_PRODUCTO;

        COMMIT;
EXCEPTION
    WHEN OTHERS THEN
        ROLLBACK;
END;

--SP ELIMINAR PRODUCTO

CREATE OR REPLACE PROCEDURE ELIMINAR_PRODUCTO(
    P_ID_PRODUCTO IN NUMBER
) AS
BEGIN

    DELETE FROM PRODUCTOS
    WHERE ID_PRODUCTO = P_ID_PRODUCTO;

        COMMIT;
EXCEPTION
    WHEN OTHERS THEN
        ROLLBACK;
END;


--SP INSERTAR PEDIDO

CREATE OR REPLACE PROCEDURE AGREGAR_PEDIDO(
    P_FECHA IN DATE,
    P_USUARIO IN NUMBER,
    P_ESTADO IN NUMBER,
    P_SUBTOTAL IN DECIMAL,
    P_TOTAL IN DECIMAL
) AS
    V_ID_PEDIDO NUMBER;
BEGIN
    SELECT SEQ_PEDIDOS.NEXTVAL INTO V_ID_PEDIDO FROM PEDIDOS;

    INSERT INTO PEDIDOS (ID_PEDIDO, FECHA, ID_USUARIO, ID_ESTADO, SUBTOTAL, TOTAL)
    VALUES (V_ID_PEDIDO, P_FECHA, P_USUARIO, P_ESTADO, P_SUBTOTAL, P_TOTAL);

    COMMIT;
EXCEPTION
    WHEN OTHERS THEN
        ROLLBACK;
END;

--SP EDITAR PEDIDO

CREATE OR REPLACE PROCEDURE EDITAR_PEDIDO(
    P_ID_PEDIDO IN NUMBER,
    P_FECHA IN DATE,
    P_USUARIO IN NUMBER,
    P_ESTADO IN NUMBER,
    P_SUBTOTAL IN DECIMAL,
    P_TOTAL IN DECIMAL
) AS
BEGIN
    UPDATE PEDIDOS
    SET FECHA = P_FECHA,
        ID_USUARIO = P_USUARIO,
        ID_ESTADO = P_ESTADO,
        SUBTOTAL = P_SUBTOTAL,
        TOTAL = P_TOTAL,
    WHERE ID_PEDIDO = P_ID_PEDIDO;

        COMMIT;
EXCEPTION
    WHEN OTHERS THEN
        ROLLBACK;
END;

--SP ELIMINAR PEDIDO

CREATE OR REPLACE PROCEDURE ELIMINAR_PEDIDO(
    P_ID_PEDIDO IN NUMBER
) AS
BEGIN

    DELETE FROM PEDIDO
    WHERE ID_PEDIDO = P_ID_PEDIDO;

        COMMIT;
EXCEPTION
    WHEN OTHERS THEN
        ROLLBACK;
END;


--SP INSERTAR INVENTARIO

CREATE OR REPLACE PROCEDURE AGREGAR_INVENTARIO(
    P_NOMBRE IN VARCHAR2,
    P_CANTIDAD IN NUMBER,
    P_ESTADO IN NUMBER
) AS
    V_ID_INVENTARIO NUMBER;
BEGIN
    SELECT SEQ_INVENTARIO.NEXTVAL INTO V_ID_INVENTARIO FROM INVENTARIO;

    INSERT INTO INVENTARIO (ID_INVENTARIO, NOMBRE, ID_CANTIDAD, ID_ESTADO)
    VALUES (V_ID_INVENTARIO, P_NOMBRE, P_CANTIDAD, P_ESTADO);

    COMMIT;
EXCEPTION
    WHEN OTHERS THEN
        ROLLBACK;
END;

--SP EDITAR INVENTARIO

CREATE OR REPLACE PROCEDURE EDITAR_INVENTARIO(
    P_ID_INVENTARIO IN NUMBER,
    P_NOMBRE IN VARCHAR2,
    P_CANTIDAD IN NUMBER,
    P_ESTADO IN NUMBER
) AS
BEGIN
    UPDATE INVENTARIO
    SET NOMBRE = P_NOMBRE,
        CANTIDAD = P_CANTIDAD,
        ID_ESTADO = P_ESTADO
    WHERE ID_INVENTARIO = P_ID_INVENTARIO;

        COMMIT;
EXCEPTION
    WHEN OTHERS THEN
        ROLLBACK;
END;

--SP ELIMINAR INVENTARIO

CREATE OR REPLACE PROCEDURE ELIMINAR_INVENTARIO(
    P_ID_INVENTARIO IN NUMBER
) AS
BEGIN

    DELETE FROM INVENTARIO
    WHERE ID_INVENTARIO = P_ID_INVENTARIO;

        COMMIT;
EXCEPTION
    WHEN OTHERS THEN
        ROLLBACK;
END;


--SP INSERTAR PRODUCTO CARRITO

CREATE OR REPLACE PROCEDURE AGREGAR_ARTICULO_CARRITO(
    P_CARRITO IN NUMBER,
    P_PRODUCTO IN NUMBER,
    P_CANTIDAD IN NUMBER
) AS
    V_ID_ARTICULO NUMBER;
BEGIN
    SELECT SEQ_ARTICULO_CARRITO.NEXTVAL INTO V_ID_ARTICULO FROM ARTICULOS_CARRITO;

    INSERT INTO ARTICULOS_CARRITO (ID_ARTICULO, ID_CARRITO, ID_PRODUCTO, CANTIDAD)
    VALUES (V_ID_ARTICULO, P_CARRITO, P_PRODUCTO, P_CANTIDAD);

    COMMIT;
EXCEPTION
    WHEN OTHERS THEN
        ROLLBACK;
END;

--SP EDITAR PRODUCTO CARRITO

CREATE OR REPLACE PROCEDURE EDITAR_ARTICULO_CARRITO(
    P_ID_ARTICULO IN NUMBER,
    P_CARRITO IN NUMBER,
    P_PRODUCTO IN NUMBER,
    P_CANTIDAD IN NUMBER
) AS
BEGIN
    UPDATE ARTICULOS_CARRITO
    SET ID_CARRITO = P_CARRITO,
        ID_PRODUCTO = P_PRODUCTO,
        CANTIDAD = P_CANTIDAD
    WHERE ID_ARTICULO = P_ID_ARTICULO;

        COMMIT;
EXCEPTION
    WHEN OTHERS THEN
        ROLLBACK;
END;

--SP ELIMINAR PRODUCTO CARRITO

CREATE OR REPLACE PROCEDURE ELIMINAR_ARTICULO_CARRITO(
    P_ID_ARTICULO IN NUMBER
) AS
BEGIN

    DELETE FROM ARTICULOS_CARRITO
    WHERE ID_ARTICULO = P_ID_ARTICULO;

        COMMIT;
EXCEPTION
    WHEN OTHERS THEN
        ROLLBACK;
END;
