CREATE OR REPLACE PACKAGE PKG_LEGADO AS



    -- Procedimientos de gestión de pedidos
    PROCEDURE ACTUALIZAR_ESTADO_PEDIDO(
        P_ID_PEDIDO    NUMBER,
        P_ID_ESTADO    NUMBER
    );

    PROCEDURE OBTENER_PEDIDOS(
        P_CURSOR OUT SYS_REFCURSOR
    );

    -- Procedimientos de gestión de inventario
    PROCEDURE OBTENER_INVENTARIO(
        P_CURSOR OUT SYS_REFCURSOR
    );

    PROCEDURE ACTUALIZAR_ESTADO_INVENTARIO(
        P_ID_INVENTARIO IN NUMBER,
        P_ID_ESTADO     IN NUMBER
    );

    PROCEDURE INSERTAR_PRODUCTO_INVENTARIO(
        v_nombre    IN VARCHAR2,
        v_cantidad  IN NUMBER,
        v_estado    IN NUMBER
    );

    PROCEDURE ACTUALIZAR_CANTIDAD_INVENTARIO(
        P_ID_INVENTARIO IN NUMBER,
        P_CANTIDAD      IN NUMBER
    );

END PKG_LEGADO;
/


------------------

CREATE OR REPLACE PACKAGE BODY PKG_LEGADO AS

    PROCEDURE ACTUALIZAR_ESTADO_PEDIDO(
        P_ID_PEDIDO    NUMBER,
        P_ID_ESTADO    NUMBER
    ) IS
    BEGIN
        UPDATE PEDIDOS
        SET ID_ESTADO = P_ID_ESTADO
        WHERE ID_PEDIDO = P_ID_PEDIDO;
    END;

    PROCEDURE OBTENER_PEDIDOS(P_CURSOR OUT SYS_REFCURSOR) IS
    BEGIN
        OPEN P_CURSOR FOR
            SELECT 
                P.ID_PEDIDO,
                P.FECHA,
                U.NOMBRE || ' ' || U.APELLIDO1 || ' ' || U.APELLIDO2 AS NOMBRE_CLIENTE,
                E.DESCRIPCION AS ESTADO,
                P.SUBTOTAL,
                P.TOTAL
            FROM PEDIDOS P
            JOIN ESTADOS E ON P.ID_ESTADO = E.ID_ESTADO
            JOIN USUARIOS U ON P.ID_USUARIO = U.ID_USUARIO
            WHERE P.ID_ESTADO IN (1, 3, 4, 5, 7, 8)
            ORDER BY P.ID_ESTADO;
    END;

    -- NUEVO: Obtener inventario
    PROCEDURE OBTENER_INVENTARIO(P_CURSOR OUT SYS_REFCURSOR) IS
    BEGIN
        OPEN P_CURSOR FOR
            SELECT 
                I.ID_INVENTARIO,
                I.NOMBRE,
                I.CANTIDAD,
                E.DESCRIPCION AS ESTADO
            FROM INVENTARIO I
            JOIN ESTADOS E ON I.ID_ESTADO = E.ID_ESTADO
            WHERE I.ID_ESTADO IN (1, 2)
            ORDER BY I.CANTIDAD;
    END;

    -- NUEVO: Actualizar estado del inventario
    PROCEDURE ACTUALIZAR_ESTADO_INVENTARIO(
        P_ID_INVENTARIO NUMBER,
        P_ID_ESTADO     NUMBER
    ) IS
    BEGIN
        UPDATE INVENTARIO
        SET ID_ESTADO = P_ID_ESTADO
        WHERE ID_INVENTARIO = P_ID_INVENTARIO;
    END;
    
    PROCEDURE INSERTAR_PRODUCTO_INVENTARIO (
        v_nombre IN VARCHAR2,
        v_cantidad IN NUMBER,
        v_estado IN NUMBER
    ) IS
    BEGIN
        INSERT INTO INVENTARIO (
            ID_INVENTARIO, NOMBRE, CANTIDAD, ID_ESTADO
        ) VALUES (
            SEQ_INVENTARIO.NEXTVAL, v_nombre, v_cantidad, v_estado
        );
    END;


    -- NUEVO: Actualizar cantidad del producto en el inventario
    PROCEDURE ACTUALIZAR_CANTIDAD_INVENTARIO(
        P_ID_INVENTARIO   IN NUMBER,
        P_CANTIDAD        IN NUMBER
    ) IS
    BEGIN
        UPDATE INVENTARIO
        SET CANTIDAD = P_CANTIDAD
        WHERE ID_INVENTARIO = P_ID_INVENTARIO;
    END;

END PKG_LEGADO;
/