-----------NUEVO

CREATE OR REPLACE PACKAGE PKG_LEGADO AS

  -- MI PERFIL
  PROCEDURE SP_GET_PEDIDOS_USUARIO(P_DATOS OUT SYS_REFCURSOR, P_ID IN NUMBER);

  -- CRUD PRODUCTOS
  FUNCTION FN_CONTAR_PRODUCTOS_REGISTRADOS RETURN NUMBER;

  PROCEDURE SP_AGREGAR_PRODUCTO(
    P_PRECIO IN NUMBER,
    P_CATEGORIA IN NUMBER,
    P_DESCRIPCION IN VARCHAR2,
    P_NOMBRE IN VARCHAR2,
    P_IMAGEN IN VARCHAR2
  );

  PROCEDURE SP_EDITAR_PRODUCTO(
    P_PRECIO     IN NUMBER,
    P_CATEGORIA  IN NUMBER,
    P_DESCRIPCION IN VARCHAR2,
    P_NOMBRE     IN VARCHAR2,
    P_IMAGEN     IN VARCHAR2,
    P_ESTADO     IN NUMBER,
    P_ID         IN NUMBER
  );

  PROCEDURE SP_ELIMINAR_ACTIVAR_PRODUCTO(P_ID IN NUMBER, P_ESTADO IN NUMBER);

  PROCEDURE SP_GET_PRODUCTOS(P_DATOS OUT SYS_REFCURSOR);
  
  PROCEDURE SP_GET_CATEGORIAS(P_DATOS OUT SYS_REFCURSOR);
  
  PROCEDURE SP_GET_PRODUCTO_ID(P_DATOS OUT SYS_REFCURSOR, P_ID IN NUMBER);

END PKG_LEGADO;

CREATE OR REPLACE PACKAGE BODY PKG_LEGADO AS

----- MI PERFIL -----
PROCEDURE SP_GET_PEDIDOS_USUARIO (P_DATOS OUT SYS_REFCURSOR, P_ID IN NUMBER) AS
V_ERROR VARCHAR2(2000);
BEGIN
    OPEN P_DATOS FOR
    SELECT P.ID_PEDIDO, P.FECHA, E.DESCRIPCION, P.TOTAL
    FROM PEDIDOS P 
    INNER JOIN ESTADOS E ON P.ID_ESTADO = E.ID_ESTADO
    WHERE P.ID_USUARIO = P_ID;
EXCEPTION
    WHEN NO_DATA_FOUND THEN
    V_ERROR := SQLERRM;
    INSERT INTO AUDITORIA_ERRORES_LEGADO (VERROR, ORIGEN, VUSER, FECHA)
        VALUES (V_ERROR, 'PEDIDOS', USER, SYSDATE);
        RAISE_APPLICATION_ERROR(-20002, 'El usuario no tiene pedidos');
        
    WHEN OTHERS THEN 
    V_ERROR := SQLERRM;
     INSERT INTO AUDITORIA_ERRORES_LEGADO (VERROR, ORIGEN, VUSER, FECHA)
        VALUES (V_ERROR, 'PEDIDOS', USER, SYSDATE);
        RAISE_APPLICATION_ERROR(-20001, SQLERRM);
       
END SP_GET_PEDIDOS_USUARIO;


----- CRUD PRODUCTOS -----
FUNCTION FN_CONTAR_PRODUCTOS_REGISTRADOS RETURN NUMBER
AS
V_ERROR VARCHAR2(2000);
    V_CONTAR NUMBER;
BEGIN
    SELECT COUNT(*) INTO V_CONTAR FROM PRODUCTOS WHERE ID_ESTADO = 1;
    RETURN V_CONTAR;
EXCEPTION 
    WHEN OTHERS THEN
    V_ERROR := SQLERRM;
        INSERT INTO AUDITORIA_ERRORES_LEGADO (VERROR, ORIGEN, VUSER, FECHA)
        VALUES (V_ERROR, 'PRODUCTOS', USER, SYSDATE);
        RAISE_APPLICATION_ERROR(-20002, SQLERRM);
END FN_CONTAR_PRODUCTOS_REGISTRADOS;

PROCEDURE SP_AGREGAR_PRODUCTO (
    P_PRECIO IN NUMBER,
    P_CATEGORIA IN NUMBER,
    P_DESCRIPCION IN VARCHAR2,
    P_NOMBRE IN VARCHAR2,
    P_IMAGEN IN VARCHAR2
) 
AS
    VSQL VARCHAR2(400);
    V_ERROR VARCHAR2(2000);
BEGIN
    VSQL := 'INSERT INTO PRODUCTOS (PRECIO, ID_CATEGORIA, ID_ESTADO, DESCRIPCION, NOMBRE, IMAGEN)
             VALUES (:precio, :idCategoria, :estado, :descripcion, :nombre, :imagen)';
             
    EXECUTE IMMEDIATE VSQL USING P_PRECIO, P_CATEGORIA, 1, P_DESCRIPCION, P_NOMBRE, P_IMAGEN;
    COMMIT;

EXCEPTION
    WHEN OTHERS THEN
    V_ERROR := SQLERRM;
    INSERT INTO AUDITORIA_ERRORES_LEGADO (VERROR, ORIGEN, VUSER, FECHA)
        VALUES (V_ERROR, 'PRODUCTOS_ADD', USER, SYSDATE);
        RAISE_APPLICATION_ERROR(-20001, SQLERRM);
        
END SP_AGREGAR_PRODUCTO;

PROCEDURE SP_EDITAR_PRODUCTO (
    P_PRECIO     IN NUMBER,
    P_CATEGORIA  IN NUMBER,
    P_DESCRIPCION IN VARCHAR2,
    P_NOMBRE     IN VARCHAR2,
    P_IMAGEN     IN VARCHAR2,
    P_ESTADO     IN NUMBER,
    P_ID         IN NUMBER
)
AS
    VSQL VARCHAR2(500);
    V_ERROR VARCHAR2(2000);
BEGIN
    VSQL := 'UPDATE PRODUCTOS 
             SET PRECIO = :precio,
                 ID_CATEGORIA = :categoria,
                 DESCRIPCION = :descripcion,
                 NOMBRE = :nombre,
                 IMAGEN = :imagen,
                 ID_ESTADO = :estado
             WHERE ID_PRODUCTO = :idProducto';

    EXECUTE IMMEDIATE VSQL 
        USING P_PRECIO, P_CATEGORIA, P_DESCRIPCION, P_NOMBRE, P_IMAGEN, P_ESTADO, P_ID;

    COMMIT;

EXCEPTION
    WHEN OTHERS THEN
    V_ERROR := SQLERRM;
    INSERT INTO AUDITORIA_ERRORES_LEGADO (VERROR, ORIGEN, VUSER, FECHA) 
        VALUES (V_ERROR, 'PRODUCTOS_EDIT', USER, SYSDATE);
        RAISE_APPLICATION_ERROR(-20002, SQLERRM);
END SP_EDITAR_PRODUCTO;

PROCEDURE SP_ELIMINAR_ACTIVAR_PRODUCTO (P_ID IN NUMBER, P_ESTADO IN NUMBER)
AS
    VSQL VARCHAR2(200);
    V_ERROR VARCHAR2(2000);
BEGIN
    VSQL := 'UPDATE PRODUCTOS SET ID_ESTADO = :estado WHERE ID_PRODUCTO = :idProducto';
    EXECUTE IMMEDIATE VSQL USING P_ESTADO, P_ID;
    COMMIT;

EXCEPTION
    WHEN OTHERS THEN
    V_ERROR := SQLERRM;
    INSERT INTO AUDITORIA_ERRORES_LEGADO (VERROR, ORIGEN, VUSER, FECHA)
        VALUES (V_ERROR, 'PRODUCTOS_DELETE', USER, SYSDATE);
        RAISE_APPLICATION_ERROR(-20003, SQLERRM);
        
END SP_ELIMINAR_ACTIVAR_PRODUCTO;

PROCEDURE SP_GET_PRODUCTOS (P_DATOS OUT SYS_REFCURSOR) AS
V_ERROR VARCHAR2(2000);
BEGIN
    OPEN P_DATOS FOR
    SELECT P.ID_PRODUCTO, P.PRECIO, C.DESCRIPCION AS CATEGORIA, P.DESCRIPCION, P.NOMBRE, P.IMAGEN, E.DESCRIPCION AS ESTADO, P.ID_ESTADO
    FROM PRODUCTOS P
    INNER JOIN CATEGORIAS_PRODUCTOS C ON P.ID_CATEGORIA = C.ID_CATEGORIA
    INNER JOIN ESTADOS E ON P.ID_ESTADO = E.ID_ESTADO;
EXCEPTION
    WHEN NO_DATA_FOUND THEN
    V_ERROR := SQLERRM;
    INSERT INTO AUDITORIA_ERRORES_LEGADO (VERROR, ORIGEN, VUSER, FECHA)
        VALUES (V_ERROR, 'PRODUCTOS_GET', USER, SYSDATE);
        RAISE_APPLICATION_ERROR(-20004, SQLERRM);
        
    WHEN OTHERS THEN
    INSERT INTO AUDITORIA_ERRORES_LEGADO (VERROR, ORIGEN, VUSER, FECHA)
        VALUES (V_ERROR, 'PRODUCTOS_GET', USER, SYSDATE);
        RAISE_APPLICATION_ERROR(-20003, SQLERRM);
        
END SP_GET_PRODUCTOS;

PROCEDURE SP_GET_CATEGORIAS(P_DATOS OUT SYS_REFCURSOR) AS
V_ERROR VARCHAR2(2000);
BEGIN
    OPEN P_DATOS FOR
    SELECT ID_CATEGORIA, DESCRIPCION, ID_ESTADO
    FROM CATEGORIAS_PRODUCTOS;
EXCEPTION
    WHEN OTHERS THEN
    V_ERROR := SQLERRM;
      INSERT INTO AUDITORIA_ERRORES_LEGADO (VERROR, ORIGEN, VUSER, FECHA)
        VALUES (V_ERROR, 'CATEGORIAS', USER, SYSDATE);
        RAISE_APPLICATION_ERROR(-20003, SQLERRM);
END SP_GET_CATEGORIAS;

PROCEDURE SP_GET_PRODUCTO_ID (P_DATOS OUT SYS_REFCURSOR, P_ID IN NUMBER)
IS
BEGIN
    OPEN P_DATOS FOR
    SELECT ID_PRODUCTO, PRECIO, ID_CATEGORIA, ID_ESTADO, DESCRIPCION, NOMBRE, IMAGEN
    FROM PRODUCTOS WHERE ID_PRODUCTO = P_ID;
END;

END PKG_LEGADO;




CREATE OR REPLACE VIEW V_SELECCION_PRODUCTOS AS SELECT 



--ALTERACIONES
ALTER TABLE PRODUCTOS ADD IMAGEN VARCHAR2(400)
ALTER TABLE PRODUCTOS DROP COLUMN CANTIDAD
ALTER TABLE PRODUCTOS MODIFY ID_PRODUCTO DEFAULT SEQ_PRODUCTOS.NEXTVAL




--CREACION DE TABLA AUDITORIA ERRORES

CREATE SEQUENCE SEQ_AUDITORIA_ERRORES INCREMENT BY 1 START WITH 1
MAXVALUE 99999 MINVALUE 0;

CREATE TABLE AUDITORIA_ERRORES_LEGADO (
ID NUMBER DEFAULT SEQ_AUDITORIA_ERRORES.NEXTVAL PRIMARY KEY,
VERROR VARCHAR2(2000),
ORIGEN VARCHAR2(400),
VUSER VARCHAR2(100),
FECHA DATE
) TABLESPACE BD_LEGADO;





CREATE OR REPLACE PROCEDURE SP_LISTAR_USUARIOS (
    P_CURSOR OUT SYS_REFCURSOR
)
AS
BEGIN
    OPEN P_CURSOR FOR
    SELECT 
        U.ID_USUARIO, 
        U.NOMBRE, 
        U.APELLIDO1, 
        U.APELLIDO2,
        U.USERNAME, 
        U.EMAIL, 
        U.ID_ROL,              
        R.DESCRIPCION AS ROL, 
        U.ID_ESTADO,          
        E.DESCRIPCION AS ESTADO
    FROM USUARIOS U
    LEFT JOIN ROLS R ON U.ID_ROL = R.ID_ROL
    LEFT JOIN ESTADOS E ON U.ID_ESTADO = E.ID_ESTADO
    ORDER BY U.ID_USUARIO;
EXCEPTION
    WHEN OTHERS THEN
        RAISE_APPLICATION_ERROR(-20001, 'ERROR AL LISTAR USUARIOS: ' || SQLERRM);
END SP_LISTAR_USUARIOS;


CREATE OR REPLACE PROCEDURE SP_AGREGAR_USUARIO(
    P_NOMBRE IN VARCHAR2,
    P_EMAIL IN VARCHAR2,
    P_ESTADO IN NUMBER,
    P_APELLIDO1 IN VARCHAR2,
    P_APELLIDO2 IN VARCHAR2,
    P_USERNAME IN VARCHAR2,
    P_CONTRASENA IN VARCHAR2,
    P_ROL IN NUMBER,
    P_ID_USUARIO OUT NUMBER
) AS
    V_COUNT_EMAIL NUMBER := 0;
    V_COUNT_USERNAME NUMBER := 0;
BEGIN
    -- Validar si el email ya existe
    SELECT COUNT(*) INTO V_COUNT_EMAIL 
    FROM USUARIOS 
    WHERE UPPER(EMAIL) = UPPER(P_EMAIL);
    
    IF V_COUNT_EMAIL > 0 THEN
        RAISE_APPLICATION_ERROR(-20001, 'El correo electrónico ya está registrado con otro usuario.');
    END IF;
    
    -- Validar si el username ya existe
    SELECT COUNT(*) INTO V_COUNT_USERNAME
    FROM USUARIOS 
    WHERE UPPER(USERNAME) = UPPER(P_USERNAME);
    
    IF V_COUNT_USERNAME > 0 THEN
        RAISE_APPLICATION_ERROR(-20002, 'El nombre de usuario ya está en uso. Por favor elija otro.');
    END IF;
    
    -- Obtener el siguiente ID para el usuario
    SELECT SEQ_USUARIOS.NEXTVAL INTO P_ID_USUARIO FROM DUAL;
    
    -- Insertar el nuevo usuario
    INSERT INTO USUARIOS (ID_USUARIO, NOMBRE, APELLIDO1, APELLIDO2, EMAIL, ID_ESTADO, USERNAME, CONTRASENA, ID_ROL)
    VALUES (P_ID_USUARIO, P_NOMBRE, P_APELLIDO1, P_APELLIDO2, P_EMAIL, P_ESTADO, P_USERNAME, P_CONTRASENA, P_ROL);
    
    COMMIT;
EXCEPTION
    WHEN OTHERS THEN
        ROLLBACK;
        IF SQLCODE = -20001 OR SQLCODE = -20002 THEN
            RAISE;
        ELSE
            RAISE_APPLICATION_ERROR(-20004, 'ERROR AL AGREGAR USUARIO: ' || SQLERRM);
        END IF;
END;


CREATE OR REPLACE PROCEDURE SP_EDITAR_USUARIO(
    P_ID_USUARIO   IN NUMBER,
    P_NOMBRE       IN VARCHAR2,
    P_APELLIDO1    IN VARCHAR2,
    P_APELLIDO2    IN VARCHAR2,
    P_EMAIL        IN VARCHAR2,
    P_ESTADO       IN NUMBER,
    P_USERNAME     IN VARCHAR2,
    P_CONTRASENA   IN VARCHAR2,
    P_ROL          IN NUMBER
)
AS
BEGIN
    IF P_CONTRASENA IS NOT NULL AND LENGTH(TRIM(P_CONTRASENA)) > 0 THEN
        UPDATE USUARIOS
        SET NOMBRE = P_NOMBRE,
            APELLIDO1 = P_APELLIDO1,
            APELLIDO2 = P_APELLIDO2,
            EMAIL = P_EMAIL,
            ID_ESTADO = P_ESTADO,
            USERNAME = P_USERNAME,
            CONTRASENA = P_CONTRASENA,
            ID_ROL = P_ROL
        WHERE ID_USUARIO = P_ID_USUARIO;
    ELSE
        UPDATE USUARIOS
        SET NOMBRE = P_NOMBRE,
            APELLIDO1 = P_APELLIDO1,
            APELLIDO2 = P_APELLIDO2,
            EMAIL = P_EMAIL,
            ID_ESTADO = P_ESTADO,
            USERNAME = P_USERNAME,
            ID_ROL = P_ROL
        WHERE ID_USUARIO = P_ID_USUARIO;
    END IF;

    COMMIT;
EXCEPTION
    WHEN OTHERS THEN
        ROLLBACK;
        RAISE_APPLICATION_ERROR(-20005, 'ERROR AL EDITAR USUARIO: ' || SQLERRM);
END;


CREATE OR REPLACE PROCEDURE SP_CAMBIAR_ESTADO_USUARIO (
    P_ID_USUARIO IN NUMBER,
    P_NUEVO_ESTADO IN NUMBER
)
AS
BEGIN
    UPDATE USUARIOS
    SET ID_ESTADO = P_NUEVO_ESTADO
    WHERE ID_USUARIO = P_ID_USUARIO;

    COMMIT;
EXCEPTION
    WHEN OTHERS THEN
        ROLLBACK;
        RAISE_APPLICATION_ERROR(-20006, 'ERROR AL CAMBIAR ESTADO DEL USUARIO: ' || SQLERRM);
END SP_CAMBIAR_ESTADO_USUARIO;

drop trigger trg_auditoria_productos

select seq_productos.nextval from dual