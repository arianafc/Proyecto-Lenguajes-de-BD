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
    APELLIDO1 VARCHAR2(100) NOT NULL,
    APELLIDO2 VARCHAR2(100),
    EMAIL VARCHAR2(100) UNIQUE,
    ID_ESTADO NUMBER NOT NULL,
    USERNAME VARCHAR2(50) NOT NULL,
    CONTRASENA VARCHAR2(100) NOT NULL,
    ID_ROL NUMBER,
    CONSTRAINT ID_ESTADO_USUARIOS_FK FOREIGN KEY (ID_ESTADO) REFERENCES ESTADOS(ID_ESTADO),
    CONSTRAINT ID_ROL_USUARIOS_FK FOREIGN KEY (ID_ROL) REFERENCES ROLES(ID_ROL)
) TABLESPACE BD_LEGADO;
CREATE SEQUENCE SEQ_USUARIOS START WITH 3 INCREMENT BY 1;

--CREACION DE TABLA CARRITO
CREATE TABLE CARRITO(
    ID_CARRITO NUMBER CONSTRAINT ID_CARRITO_PK PRIMARY KEY,
    ID_USUARIO NUMBER,
    FECHA_CREACION DATE,
    CONSTRAINT ID_USUARIO_CARRITO_FK FOREIGN KEY (ID_USUARIO) REFERENCES USUARIOS(ID_USUARIO)
) TABLESPACE BD_LEGADO;
CREATE SEQUENCE SEQ_CARRITO START WITH 1 INCREMENT BY 1;

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

--CREACION DE TABLA USUARIOS PARA AUDITORIA

CREATE TABLE AUDITORIA_USUARIOS (
    ID_AUDITORIA NUMBER CONSTRAINT ID_USUARIO_AUDITORIA_PK PRIMARY KEY,
    ID_USUARIO NUMBER NOT NULL,
    NOMBRE_ANTERIOR VARCHAR2(100),
    NOMBRE_NUEVO VARCHAR2(100),
    APELLIDO1_ANTERIOR VARCHAR2(100),
    APELLIDO1_NUEVO VARCHAR2(100),
    APELLIDO2_ANTERIOR VARCHAR2(100),
    APELLIDO2_NUEVO VARCHAR2(100),
    EMAIL_ANTERIOR VARCHAR2(100),
    EMAIL_NUEVO VARCHAR2(100),
    ID_ESTADO_ANTERIOR NUMBER,
    ID_ESTADO_NUEVO NUMBER,
    USERNAME_ANTERIOR VARCHAR2(50),
    USERNAME_NUEVO VARCHAR2(50),
    CONTRASENA_ANTERIOR VARCHAR2(100),
    CONTRASENA_NUEVO VARCHAR2(100),
    ID_ROL_ANTERIOR NUMBER,
    ID_ROL_NUEVO NUMBER,
    TIPO_OPERACION VARCHAR2(10) NOT NULL, 
    FECHA_OPERACION DATE DEFAULT SYSDATE NOT NULL,
    USUARIO_OPERACION VARCHAR2(100) NOT NULL
) TABLESPACE BD_LEGADO;
CREATE SEQUENCE SEQ_USUARIOS_AUDITORIA START WITH 1 INCREMENT BY 1;

CREATE TABLE AUDITORIA_INVENTARIO (
    ID_AUDITORIA NUMBER CONSTRAINT ID_INVENTARIO_AUDITORIA_PK PRIMARY KEY,
    ID_INVENTARIO NUMBER NOT NULL,
    NOMBRE_ANTERIOR VARCHAR2(100),
    NOMBRE_NUEVO VARCHAR2(100),
    CANTIDAD_ANTERIOR NUMBER,
    CANTIDAD_NUEVA NUMBER, 
    ID_ESTADO_ANTERIOR NUMBER,
    ID_ESTADO_NUEVO NUMBER,
    TIPO_OPERACION VARCHAR2(10) NOT NULL, 
    FECHA_OPERACION DATE DEFAULT SYSDATE NOT NULL,
    USUARIO_OPERACION VARCHAR2(100) NOT NULL
) TABLESPACE BD_LEGADO;
CREATE SEQUENCE SEQ_INVENTARIOS_AUDITORIA START WITH 1 INCREMENT BY 1;

CREATE TABLE AUDITORIA_PRODUCTOS (
    ID_AUDITORIA NUMBER CONSTRAINT ID_PRODUCTO_AUDITORIA_PK PRIMARY KEY,
    ID_PRODUCTO NUMBER NOT NULL,
    NOMBRE_ANTERIOR VARCHAR2(100),
    NOMBRE_NUEVO VARCHAR2(100),
    CANTIDAD_ANTERIOR NUMBER,
    CANTIDAD_NUEVA NUMBER, 
    PRECIO_ANTERIOR DECIMAL(10,2),
    PRECIO_NUEVO DECIMAL(10,2),
    ID_CATEGORIA_ANTERIOR NUMBER,
    ID_CATEGORIA_NUEVA NUMBER,
    ID_ESTADO_ANTERIOR NUMBER,
    ID_ESTADO_NUEVO NUMBER,
    DESCRIPCION_ANTERIOR VARCHAR2(100),
    DESCRIPCION_NUEVA VARCHAR2(100),
    TIPO_OPERACION VARCHAR2(10) NOT NULL, 
    FECHA_OPERACION DATE DEFAULT SYSDATE NOT NULL,
    USUARIO_OPERACION VARCHAR2(100) NOT NULL
) TABLESPACE BD_LEGADO;
CREATE SEQUENCE SEQ_PRODUCTOS_AUDITORIA START WITH 1 INCREMENT BY 1;


--INSERTS TABLA ESTADOS 

INSERT INTO ESTADOS (ID_ESTADO, DESCRIPCION) VALUES (1,'ACTIVO');
INSERT INTO ESTADOS (ID_ESTADO, DESCRIPCION) VALUES (2,'INACTIVO');
INSERT INTO ESTADOS (ID_ESTADO, DESCRIPCION) VALUES (3,'EN PROCESO');
INSERT INTO ESTADOS (ID_ESTADO, DESCRIPCION) VALUES (4,'NUEVO');

--INSERTS TABLA ROLES 

INSERT INTO ROLES (ID_ROL, DESCRIPCION) VALUES (1,'COMPRADOR');
INSERT INTO ROLES (ID_ROL, DESCRIPCION) VALUES (2,'ADMINISTRADOR');

--INSERTS TABLA USUARIOS 

INSERT INTO USUARIOS (ID_USUARIO, NOMBRE, APELLIDO1, APELLIDO2, EMAIL, ID_ESTADO, USERNAME, CONTRASENA, ID_ROL) VALUES (1,'Jean Pool','Perez', 'Carranza','jperez@gmail.com',1,'perezcj','12345',2);
INSERT INTO USUARIOS (ID_USUARIO, NOMBRE, APELLIDO1,APELLIDO2, EMAIL, ID_ESTADO, USERNAME, CONTRASENA, ID_ROL) VALUES (2,'Ariana','Fallas', 'Calderon','afallas@gmail.com',1,'fallasca','123456',1);

--SP INSERTAR USUARIO

CREATE OR REPLACE PROCEDURE AGREGAR_USUARIO(
    P_NOMBRE IN VARCHAR2,
    P_EMAIL IN VARCHAR2,
    P_ESTADO IN NUMBER,
    P_APELLIDO1 IN VARCHAR2,
    P_APELLIDO2 IN VARCHAR2,
    P_USERNAME IN VARCHAR2,
    P_CONTRASENA IN VARCHAR2,
    P_ROL IN NUMBER
) AS
    V_ID_USUARIO NUMBER;
BEGIN
    SELECT SEQ_USUARIOS.NEXTVAL INTO V_ID_USUARIO FROM USUARIOS;

    INSERT INTO USUARIOS (ID_USUARIO, NOMBRE, APELLIDO1, APELLIDO2, EMAIL, ID_ESTADO, USERNAME, CONTRASENA, ID_ROL)
    VALUES (V_ID_USUARIO, P_NOMBRE, P_APELLIDO1, P_APELLIDO2, P_EMAIL, P_ESTADO, P_USERNAME, P_CONTRASENA, P_ROL);

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

-- SP CREAR CARRITO

CREATE OR REPLACE PROCEDURE CREAR_CARRITO(
    P_ID_USUARIO IN NUMBER
) AS
    V_ID_CARRITO NUMBER;
    V_FECHA_ACTUAL DATE;
BEGIN
    -- Obtener la fecha actual
    SELECT SYSDATE INTO V_FECHA_ACTUAL FROM DUAL;
    
    -- Obtener el siguiente ID para el carrito
    SELECT SEQ_CARRITO.NEXTVAL INTO V_ID_CARRITO FROM CARRITO;
    
    -- Insertar el nuevo carrito
    INSERT INTO CARRITO (ID_CARRITO, ID_USUARIO, FECHA_CREACION)
    VALUES (V_ID_CARRITO, P_ID_USUARIO, V_FECHA_ACTUAL);
    
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
        TOTAL = P_TOTAL
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

    DELETE FROM PEDIDOS
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

    INSERT INTO INVENTARIO (ID_INVENTARIO, NOMBRE, CANTIDAD, ID_ESTADO)
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

--CREACION DE VISTAS

--VISTA DE USUARIOS CLIENTES
CREATE OR REPLACE VIEW V_CLIENTES AS SELECT U.ID_USUARIO,U.NOMBRE, U.APELLIDO1, U.APELLIDO2, U.EMAIL, E.DESCRIPCION AS ESTADO, R.DESCRIPCION AS ROL, T.TELEFONO, D.DIRECCION_EXACTA
FROM  USUARIOS U INNER JOIN ESTADOS E ON U.ID_ESTADO = E.ID_ESTADO
INNER JOIN ROLS R ON U.ID_ROL = R.ID_ROL
LEFT JOIN TELEFONOS T ON T.ID_USUARIO = U.ID_USUARIO
LEFT JOIN DIRECCIONES D ON D.ID_USUARIO = U.ID_USUARIO
WHERE R.ID_ROL = 1;

--VISTA DE USUARIOS LOGIN
CREATE OR REPLACE VIEW V_USUARIOS_ROLES AS
SELECT 
    U.ID_USUARIO,
    U.NOMBRE,
    U.APELLIDO1,
    U.APELLIDO2,
    U.EMAIL,
    U.ID_ESTADO,
    U.USERNAME,
    U.CONTRASENA,
    U.ID_ROL,
    R.DESCRIPCION AS ROL_DESCRIPCION
FROM 
    USUARIOS U
JOIN 
    ROLES R ON U.ID_ROL = R.ID_ROL;

--CREACION DE TRIGGERS

--TRIGGER QUE CALCULA EL SUBTOTAL Y TOTAL DE LOS PEDIDOS SEGUN PEDIDOS DETALLES

CREATE OR REPLACE TRIGGER TG_CALCULAR_SUBTOTAL_TOTAL_PEDIDOS
AFTER INSERT ON PEDIDOS_DETALLES
FOR EACH ROW
DECLARE
    v_subtotal NUMBER(10,2);
    v_total NUMBER(10,2);
    v_impuesto NUMBER(5,2) := 0.13;
BEGIN
    -- Calcular el subtotal sumando los productos del pedido
    SELECT SUM(PD.CANTIDAD * P.PRECIO)
    INTO v_subtotal
    FROM PEDIDOS_DETALLES PD
    JOIN PRODUCTOS P ON PD.ID_PRODUCTO = P.ID_PRODUCTO
    WHERE PD.ID_PEDIDO = :NEW.ID_PEDIDO;

    -- Calcular el total con el impuesto del 13%
    v_total := v_subtotal + (v_subtotal * v_impuesto);

    -- Actualizar la tabla PEDIDOS con los valores calculados
    UPDATE PEDIDOS
    SET SUBTOTAL = v_subtotal,
        TOTAL = v_total
    WHERE ID_PEDIDO = :NEW.ID_PEDIDO;
END;

--TRIGGER PARA VALIDAR CANTIDAD EN STOCK
CREATE OR REPLACE TRIGGER TG_VALIDAR_CANTIDAD_CARRITO
BEFORE INSERT OR UPDATE ON ARTICULOS_CARRITO
FOR EACH ROW
DECLARE
    v_stock_disponible NUMBER;
BEGIN
    -- Obtener la cantidad disponible en inventario del producto
    SELECT CANTIDAD 
    INTO v_stock_disponible
    FROM PRODUCTOS
    WHERE ID_PRODUCTO = :NEW.ID_PRODUCTO;

    -- Validar si la cantidad requerida supera la disponible
    IF :NEW.CANTIDAD > v_stock_disponible THEN
        RAISE_APPLICATION_ERROR(-20001, 'No hay suficiente stock disponible para este producto.');
    END IF;
END;


--TRIGGER PARA ACTUALIZAR STOCK DE PRODUCTOS AL REALIZAR LA COMPRA
CREATE OR REPLACE TRIGGER TG_ACTUALIZAR_STOCK_AL_COMPRAR
AFTER INSERT ON PEDIDOS_DETALLES
FOR EACH ROW
BEGIN
    UPDATE PRODUCTOS
    SET CANTIDAD = CANTIDAD - :NEW.CANTIDAD
    WHERE ID_PRODUCTO = :NEW.ID_PRODUCTO;
END;
--TRIGGER QUE REGISTRA EL MOVIMIENTO REALIZADO EN LA TABLA USUARIOS PARA ALMACENARLO EN LA TABLA AUDITORIA

CREATE OR REPLACE TRIGGER TRG_USUARIOS_AUDITORIA
AFTER INSERT OR UPDATE OR DELETE ON USUARIOS
FOR EACH ROW
BEGIN
    IF INSERTING THEN
        -- Registrar una inserción
        INSERT INTO AUDITORIA_USUARIOS (
            ID_AUDITORIA, ID_USUARIO, NOMBRE_NUEVO, APELLIDO1_NUEVO, APELLIDO2_NUEVO,
            EMAIL_NUEVO, ID_ESTADO_NUEVO, USERNAME_NUEVO, CONTRASENA_NUEVO, ID_ROL_NUEVO,
            TIPO_OPERACION, USUARIO_OPERACION
        ) VALUES (
            seq_auditoria_usuarios.NEXTVAL, :NEW.ID_USUARIO, :NEW.NOMBRE, :NEW.APELLIDO1, :NEW.APELLIDO2,
            :NEW.EMAIL, :NEW.ID_ESTADO, :NEW.USERNAME, :NEW.CONTRASENA, :NEW.ID_ROL,
            'INSERT', USER
        );
    ELSIF UPDATING THEN
        -- Registrar una actualización
        INSERT INTO AUDITORIA_USUARIOS (
            ID_AUDITORIA, ID_USUARIO, NOMBRE_ANTERIOR, NOMBRE_NUEVO, APELLIDO1_ANTERIOR, APELLIDO1_NUEVO,
            APELLIDO2_ANTERIOR, APELLIDO2_NUEVO, EMAIL_ANTERIOR, EMAIL_NUEVO, ID_ESTADO_ANTERIOR, ID_ESTADO_NUEVO,
            USERNAME_ANTERIOR, USERNAME_NUEVO, CONTRASENA_ANTERIOR, CONTRASENA_NUEVO, ID_ROL_ANTERIOR, ID_ROL_NUEVO,
            TIPO_OPERACION, USUARIO_OPERACION
        ) VALUES (
            seq_auditoria_usuarios.NEXTVAL, :OLD.ID_USUARIO, :OLD.NOMBRE, :NEW.NOMBRE, :OLD.APELLIDO1, :NEW.APELLIDO1,
            :OLD.APELLIDO2, :NEW.APELLIDO2, :OLD.EMAIL, :NEW.EMAIL, :OLD.ID_ESTADO, :NEW.ID_ESTADO,
            :OLD.USERNAME, :NEW.USERNAME, :OLD.CONTRASENA, :NEW.CONTRASENA, :OLD.ID_ROL, :NEW.ID_ROL,
            'UPDATE', USER
        );
    ELSIF DELETING THEN
        -- Registrar una eliminación
        INSERT INTO AUDITORIA_USUARIOS (
            ID_AUDITORIA, ID_USUARIO, NOMBRE_ANTERIOR, APELLIDO1_ANTERIOR, APELLIDO2_ANTERIOR,
            EMAIL_ANTERIOR, ID_ESTADO_ANTERIOR, USERNAME_ANTERIOR, CONTRASENA_ANTERIOR, ID_ROL_ANTERIOR,
            TIPO_OPERACION, USUARIO_OPERACION
        ) VALUES (
            seq_auditoria_usuarios.NEXTVAL, :OLD.ID_USUARIO, :OLD.NOMBRE, :OLD.APELLIDO1, :OLD.APELLIDO2,
            :OLD.EMAIL, :OLD.ID_ESTADO, :OLD.USERNAME, :OLD.CONTRASENA, :OLD.ID_ROL,
            'DELETE', USER
        );
    END IF;
END;

--TRIGGER QUE REGISTRA EL MOVIMIENTO REALIZADO EN LA TABLA PRODUCTOS PARA ALMACENARLO EN LA TABLA AUDITORIA

CREATE OR REPLACE TRIGGER TRG_AUDITORIA_PRODUCTOS
AFTER INSERT OR UPDATE OR DELETE ON PRODUCTOS
FOR EACH ROW
BEGIN
    IF INSERTING THEN
        INSERT INTO AUDITORIA_PRODUCTOS (
            ID_AUDITORIA, ID_PRODUCTO, NOMBRE_NUEVO, CANTIDAD_NUEVA, PRECIO_NUEVO, 
            ID_CATEGORIA_NUEVA, ID_ESTADO_NUEVO, DESCRIPCION_NUEVA, 
            TIPO_OPERACION, USUARIO_OPERACION
        ) VALUES (
            SEQ_AUDITORIA_PRODUCTOS.NEXTVAL, :NEW.ID_PRODUCTO, :NEW.NOMBRE, :NEW.CANTIDAD, :NEW.PRECIO, 
            :NEW.ID_CATEGORIA, :NEW.ID_ESTADO, :NEW.DESCRIPCION,
            'INSERT', USER
        );

    ELSIF UPDATING THEN
        INSERT INTO AUDITORIA_PRODUCTOS (
            ID_AUDITORIA, ID_PRODUCTO, NOMBRE_ANTERIOR, NOMBRE_NUEVO, CANTIDAD_ANTERIOR, CANTIDAD_NUEVA, 
            PRECIO_ANTERIOR, PRECIO_NUEVO, ID_CATEGORIA_ANTERIOR, ID_CATEGORIA_NUEVA, 
            ID_ESTADO_ANTERIOR, ID_ESTADO_NUEVO, DESCRIPCION_ANTERIOR, DESCRIPCION_NUEVA, 
            TIPO_OPERACION, USUARIO_OPERACION
        ) VALUES (
            SEQ_AUDITORIA_PRODUCTOS.NEXTVAL, :OLD.ID_PRODUCTO, :OLD.NOMBRE, :NEW.NOMBRE, :OLD.CANTIDAD, :NEW.CANTIDAD, 
            :OLD.PRECIO, :NEW.PRECIO, :OLD.ID_CATEGORIA, :NEW.ID_CATEGORIA, 
            :OLD.ID_ESTADO, :NEW.ID_ESTADO, :OLD.DESCRIPCION, :NEW.DESCRIPCION, 
            'UPDATE', USER
        );

    ELSIF DELETING THEN
        INSERT INTO AUDITORIA_PRODUCTOS (
            ID_AUDITORIA, ID_PRODUCTO, NOMBRE_ANTERIOR, CANTIDAD_ANTERIOR, PRECIO_ANTERIOR, 
            ID_CATEGORIA_ANTERIOR, ID_ESTADO_ANTERIOR, DESCRIPCION_ANTERIOR, 
            TIPO_OPERACION, USUARIO_OPERACION
        ) VALUES (
            SEQ_AUDITORIA_PRODUCTOS.NEXTVAL, :OLD.ID_PRODUCTO, :OLD.NOMBRE, :OLD.CANTIDAD, :OLD.PRECIO, 
            :OLD.ID_CATEGORIA, :OLD.ID_ESTADO, :OLD.DESCRIPCION, 
            'DELETE', USER
        );
    END IF;
END;

--TRIGGER QUE REGISTRA EL MOVIMIENTO REALIZADO EN LA TABLA INVENTARIOS PARA ALMACENARLO EN LA TABLA AUDITORIA

CREATE OR REPLACE TRIGGER TRG_INVENTARIO_AUDITORIA
AFTER INSERT OR UPDATE OR DELETE ON INVENTARIO
FOR EACH ROW
BEGIN
    IF INSERTING THEN
        INSERT INTO AUDITORIA_INVENTARIO (
            ID_AUDITORIA, ID_INVENTARIO, NOMBRE_NUEVO, CANTIDAD_NUEVA, ID_ESTADO_NUEVO,
            TIPO_OPERACION, USUARIO_OPERACION
        ) VALUES (
            SEQ_AUDITORIA_INVENTARIO.NEXTVAL, :NEW.ID_INVENTARIO, :NEW.NOMBRE, :NEW.CANTIDAD, :NEW.ID_ESTADO,
            'INSERT', USER
        );
    
    ELSIF UPDATING THEN
        INSERT INTO AUDITORIA_INVENTARIO (
            ID_AUDITORIA, ID_INVENTARIO, NOMBRE_ANTERIOR, NOMBRE_NUEVO, CANTIDAD_ANTERIOR, CANTIDAD_NUEVA, 
            ID_ESTADO_ANTERIOR, ID_ESTADO_NUEVO, TIPO_OPERACION, USUARIO_OPERACION
        ) VALUES (
            SEQ_AUDITORIA_INVENTARIO.NEXTVAL, :OLD.ID_INVENTARIO, :OLD.NOMBRE, :NEW.NOMBRE, :OLD.CANTIDAD, :NEW.CANTIDAD,
            :OLD.ID_ESTADO, :NEW.ID_ESTADO, 'UPDATE', USER
        );
    
    ELSIF DELETING THEN
        INSERT INTO AUDITORIA_INVENTARIO (
            ID_AUDITORIA, ID_INVENTARIO, NOMBRE_ANTERIOR, CANTIDAD_ANTERIOR, ID_ESTADO_ANTERIOR, 
            TIPO_OPERACION, USUARIO_OPERACION
        ) VALUES (
            SEQ_AUDITORIA_INVENTARIO.NEXTVAL, :OLD.ID_INVENTARIO, :OLD.NOMBRE, :OLD.CANTIDAD, :OLD.ID_ESTADO,
            'DELETE', USER
        );
    END IF;
END;

--Funciones
-- 1. Funcion para calcular el total de un carrito de compras
CREATE OR REPLACE FUNCTION CALCULAR_TOTAL_CARRITO(p_id_carrito NUMBER) RETURN NUMBER IS
    v_total NUMBER := 0;
BEGIN
    SELECT COALESCE(SUM(ac.CANTIDAD * p.PRECIO), 0)
    INTO v_total
    FROM ARTICULOS_CARRITO ac
    JOIN PRODUCTOS p ON ac.ID_PRODUCTO = p.ID_PRODUCTO
    WHERE ac.ID_CARRITO = p_id_carrito;
    
    RETURN v_total;
END;
/

-- 2. Funcion para verificar el stock de un producto
CREATE OR REPLACE FUNCTION VERIFICAR_STOCK_PRODUCTO(p_id_producto NUMBER, p_cantidad NUMBER) RETURN VARCHAR2 IS
    v_stock NUMBER;
BEGIN
    SELECT CANTIDAD INTO v_stock FROM PRODUCTOS WHERE ID_PRODUCTO = p_id_producto;
    
    IF v_stock >= p_cantidad THEN
        RETURN 'Disponible';
    ELSE
        RETURN 'No disponible';
    END IF;
END;
/

-- 3. Funcion para obtener el estado de un pedido
CREATE OR REPLACE FUNCTION OBTENER_ESTADO_PEDIDO(p_id_pedido NUMBER) RETURN VARCHAR2 IS
    v_estado VARCHAR2(100);
BEGIN
    SELECT e.DESCRIPCION INTO v_estado
    FROM PEDIDOS p
    JOIN ESTADOS e ON p.ID_ESTADO = e.ID_ESTADO
    WHERE p.ID_PEDIDO = p_id_pedido;
    
    RETURN v_estado;
END;
/

-- 4. Funcion para contar productos por categoria
CREATE OR REPLACE FUNCTION CONTAR_PRODUCTOS_POR_CATEGORIA(p_id_categoria NUMBER) RETURN NUMBER IS
    v_cantidad NUMBER;
BEGIN
    SELECT COUNT(*) INTO v_cantidad FROM PRODUCTOS WHERE ID_CATEGORIA = p_id_categoria;
    
    RETURN v_cantidad;
END;
/

-- 5. Funcion para obtener el telefono de un usuario
CREATE OR REPLACE FUNCTION OBTENER_TELEFONO_USUARIO(p_id_usuario NUMBER) RETURN VARCHAR2 IS
    v_telefono VARCHAR2(255);
BEGIN
    SELECT TELEFONO INTO v_telefono FROM TELEFONOS WHERE ID_USUARIO = p_id_usuario AND ROWNUM = 1;
    
    RETURN v_telefono;
END;
/

