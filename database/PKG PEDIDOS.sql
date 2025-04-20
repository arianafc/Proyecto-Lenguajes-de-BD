CREATE OR REPLACE PACKAGE PKG_LEGADO AS
    CURSOR CURSOR_PEDIDOS IS
        SELECT ID_PEDIDO, FECHA, ID_USUARIO, ID_ESTADO, SUBTOTAL, TOTAL
        FROM PEDIDOS
        WHERE ID_ESTADO IN (1, 3, 4, 5, 7, 8);

    PROCEDURE INSERTAR_PEDIDO(
        P_FECHA        DATE,
        P_ID_USUARIO   NUMBER,
        P_ID_ESTADO    NUMBER,
        P_SUBTOTAL     DECIMAL,
        P_TOTAL        DECIMAL
    );

    -- Modificado para solo actualizar el estado
    PROCEDURE ACTUALIZAR_ESTADO_PEDIDO(
        P_ID_PEDIDO    NUMBER,
        P_ID_ESTADO    NUMBER
    );

    PROCEDURE ELIMINAR_PEDIDO(P_ID_PEDIDO NUMBER);

    -- Aquí sí es importante: El procedimiento va a devolver la descripción en lugar del ID_ESTADO
    PROCEDURE OBTENER_PEDIDOS (P_CURSOR OUT SYS_REFCURSOR);
END PKG_LEGADO;
/

-------------------------------------------------------------------

CREATE OR REPLACE PACKAGE BODY PKG_LEGADO AS

    PROCEDURE INSERTAR_PEDIDO(
        P_FECHA        DATE,
        P_ID_USUARIO   NUMBER,
        P_ID_ESTADO    NUMBER,
        P_SUBTOTAL     DECIMAL,
        P_TOTAL        DECIMAL
    ) IS
    BEGIN
        INSERT INTO PEDIDOS (
            ID_PEDIDO, FECHA, ID_USUARIO, ID_ESTADO, SUBTOTAL, TOTAL
        ) VALUES (
            SEQ_PEDIDOS.NEXTVAL, P_FECHA, P_ID_USUARIO, P_ID_ESTADO, P_SUBTOTAL, P_TOTAL
        );
    END;

    -- Modificado para solo actualizar el estado
    PROCEDURE ACTUALIZAR_ESTADO_PEDIDO(
        P_ID_PEDIDO    NUMBER,
        P_ID_ESTADO    NUMBER
    ) IS
    BEGIN
        UPDATE PEDIDOS
        SET ID_ESTADO = P_ID_ESTADO
        WHERE ID_PEDIDO = P_ID_PEDIDO;
    END;

    PROCEDURE ELIMINAR_PEDIDO(P_ID_PEDIDO NUMBER) IS
    BEGIN
        UPDATE PEDIDOS
        SET ID_ESTADO = 2
        WHERE ID_PEDIDO = P_ID_PEDIDO;
    END;

    PROCEDURE OBTENER_PEDIDOS(P_CURSOR OUT SYS_REFCURSOR) IS
    BEGIN
        OPEN P_CURSOR FOR
            SELECT 
                P.ID_PEDIDO,
                P.FECHA,
                -- Concatenar nombre completo del usuario
                U.NOMBRE || ' ' || U.APELLIDO1 || ' ' || U.APELLIDO2 AS NOMBRE_CLIENTE,
                -- Mostrar descripción del estado
                E.DESCRIPCION AS ESTADO,
                P.SUBTOTAL,
                P.TOTAL
            FROM PEDIDOS P
            JOIN ESTADOS E ON P.ID_ESTADO = E.ID_ESTADO
            JOIN USUARIOS U ON P.ID_USUARIO = U.ID_USUARIO
            WHERE P.ID_ESTADO IN (1, 3, 4, 5, 7, 8);
    END;

END PKG_LEGADO;
/