PKG_CARRITO.SP_GET_CARRITO_USUARIO (DATOS OUT SYS_REFCURSOR, VID IN NUMBER);

ALTER TABLE PEDIDOS ADD METODO_PAGO VARCHAR2(100);
COMMIT;

VAR v_cursor REFCURSOR;

EXEC PKG_LEGADO.SP_GET_PEDIDOS_DETALLES(:v_cursor, 25); 

PRINT v_cursor;

EXEC PKG_LEGADO.SP_AGREGAR_CONSULTA(13, 'COTIzación', 'HOLAA');

CREATE SEQUENCE SEQ_DIRECCIONES INCREMENT BY 1 START WITH 1 MAXVALUE 99999 MINVALUE 0;
CREATE SEQUENCE SEQ_CANTONES INCREMENT BY 1 START WITH 1 MAXVALUE 99999 MINVALUE 0;
CREATE SEQUENCE SEQ_PROVINCIAS INCREMENT BY 1 START WITH 1 MAXVALUE 99999 MINVALUE 0;
CREATE SEQUENCE SEQ_DISTRITOS INCREMENT BY 1 START WITH 1 MAXVALUE 99999 MINVALUE 0;

ALTER TABLE DIRECCIONES MODIFY ID_DIRECCION DEFAULT SEQ_DIRECCIONES.NEXTVAL
ALTER TABLE CANTONES MODIFY ID_CANTON DEFAULT SEQ_CANTONES.NEXTVAL
ALTER TABLE PROVINCIAS MODIFY ID_PROVINCIA DEFAULT SEQ_PROVINCIAS.NEXTVAL
ALTER TABLE DISTRITOS MODIFY ID_DISTRITO DEFAULT SEQ_DISTRITOS.NEXTVAL

INSERT INTO cantones (nombre, id_provincia) VALUES ('San José', 1);
INSERT INTO cantones (nombre, id_provincia) VALUES ('Escazú', 1);
INSERT INTO cantones (nombre, id_provincia) VALUES ('Desamparados', 1);
INSERT INTO cantones (nombre, id_provincia) VALUES ('Puriscal', 1);
INSERT INTO cantones (nombre, id_provincia) VALUES ('Tarrazú', 1);
INSERT INTO cantones (nombre, id_provincia) VALUES ('Aserrí', 1);
INSERT INTO cantones (nombre, id_provincia) VALUES ('Mora', 1);
INSERT INTO cantones (nombre, id_provincia) VALUES ('Goicoechea', 1);
INSERT INTO cantones (nombre, id_provincia) VALUES ('Santa Ana', 1);
INSERT INTO cantones (nombre, id_provincia) VALUES ('Alajuelita', 1);
INSERT INTO cantones (nombre, id_provincia) VALUES ('Vázquez de Coronado', 1);
INSERT INTO cantones (nombre, id_provincia) VALUES ('Acosta', 1);
INSERT INTO cantones (nombre, id_provincia) VALUES ('Tibás', 1);
INSERT INTO cantones (nombre, id_provincia) VALUES ('Moravia', 1);
INSERT INTO cantones (nombre, id_provincia) VALUES ('Montes de Oca', 1);
INSERT INTO cantones (nombre, id_provincia) VALUES ('Turrubares', 1);
INSERT INTO cantones (nombre, id_provincia) VALUES ('Dota', 1);
INSERT INTO cantones (nombre, id_provincia) VALUES ('Curridabat', 1);
INSERT INTO cantones (nombre, id_provincia) VALUES ('Pérez Zeledón', 1);
INSERT INTO cantones (nombre, id_provincia) VALUES ('León Cortés Castro', 1);

INSERT INTO cantones (nombre, id_provincia) VALUES ('Alajuela', 2);
INSERT INTO cantones (nombre, id_provincia) VALUES ('San Ramón', 2);
INSERT INTO cantones (nombre, id_provincia) VALUES ('Grecia', 2);
INSERT INTO cantones (nombre, id_provincia) VALUES ('San Mateo', 2);
INSERT INTO cantones (nombre, id_provincia) VALUES ('Atenas', 2);
INSERT INTO cantones (nombre, id_provincia) VALUES ('Naranjo', 2);
INSERT INTO cantones (nombre, id_provincia) VALUES ('Palmares', 2);
INSERT INTO cantones (nombre, id_provincia) VALUES ('Poás', 2);
INSERT INTO cantones (nombre, id_provincia) VALUES ('Orotina', 2);
INSERT INTO cantones (nombre, id_provincia) VALUES ('San Carlos', 2);
INSERT INTO cantones (nombre, id_provincia) VALUES ('Zarcero', 2);
INSERT INTO cantones (nombre, id_provincia) VALUES ('Valverde Vega', 2);
INSERT INTO cantones (nombre, id_provincia) VALUES ('Upala', 2);
INSERT INTO cantones (nombre, id_provincia) VALUES ('Los Chiles', 2);
INSERT INTO cantones (nombre, id_provincia) VALUES ('Guatuso', 2);
INSERT INTO cantones (nombre, id_provincia) VALUES ('Río Cuarto', 2);

INSERT INTO cantones (nombre, id_provincia) VALUES ('Cartago', 3);
INSERT INTO cantones (nombre, id_provincia) VALUES ('Paraíso', 3);
INSERT INTO cantones (nombre, id_provincia) VALUES ('La Unión', 3);
INSERT INTO cantones (nombre, id_provincia) VALUES ('Jiménez', 3);
INSERT INTO cantones (nombre, id_provincia) VALUES ('Turrialba', 3);
INSERT INTO cantones (nombre, id_provincia) VALUES ('Alvarado', 3);
INSERT INTO cantones (nombre, id_provincia) VALUES ('Oreamuno', 3);
INSERT INTO cantones (nombre, id_provincia) VALUES ('El Guarco', 3);

INSERT INTO cantones (nombre, id_provincia) VALUES ('Heredia', 4);
INSERT INTO cantones (nombre, id_provincia) VALUES ('Barva', 4);
INSERT INTO cantones (nombre, id_provincia) VALUES ('Santo Domingo', 4);
INSERT INTO cantones (nombre, id_provincia) VALUES ('Santa Bárbara', 4);
INSERT INTO cantones (nombre, id_provincia) VALUES ('San Rafael', 4);
INSERT INTO cantones (nombre, id_provincia) VALUES ('San Isidro', 4);
INSERT INTO cantones (nombre, id_provincia) VALUES ('Belén', 4);
INSERT INTO cantones (nombre, id_provincia) VALUES ('Flores', 4);
INSERT INTO cantones (nombre, id_provincia) VALUES ('Sarapiquí', 4);

INSERT INTO cantones (nombre, id_provincia) VALUES ('Liberia', 5);
INSERT INTO cantones (nombre, id_provincia) VALUES ('Nicoya', 5);
INSERT INTO cantones (nombre, id_provincia) VALUES ('Santa Cruz', 5);
INSERT INTO cantones (nombre, id_provincia) VALUES ('Bagaces', 5);
INSERT INTO cantones (nombre, id_provincia) VALUES ('Carrillo', 5);
INSERT INTO cantones (nombre, id_provincia) VALUES ('Cañas', 5);
INSERT INTO cantones (nombre, id_provincia) VALUES ('Abangares', 5);
INSERT INTO cantones (nombre, id_provincia) VALUES ('Tilarán', 5);
INSERT INTO cantones (nombre, id_provincia) VALUES ('Nandayure', 5);
INSERT INTO cantones (nombre, id_provincia) VALUES ('La Cruz', 5);
INSERT INTO cantones (nombre, id_provincia) VALUES ('Hojancha', 5);


INSERT INTO cantones (nombre, id_provincia) VALUES ('Puntarenas', 6);
INSERT INTO cantones (nombre, id_provincia) VALUES ('Esparza', 6);
INSERT INTO cantones (nombre, id_provincia) VALUES ('Buenos Aires', 6);
INSERT INTO cantones (nombre, id_provincia) VALUES ('Montes de Oro', 6);
INSERT INTO cantones (nombre, id_provincia) VALUES ('Osa', 6);
INSERT INTO cantones (nombre, id_provincia) VALUES ('Quepos', 6);
INSERT INTO cantones (nombre, id_provincia) VALUES ('Golfito', 6);
INSERT INTO cantones (nombre, id_provincia) VALUES ('Coto Brus', 6);
INSERT INTO cantones (nombre, id_provincia) VALUES ('Parrita', 6);
INSERT INTO cantones (nombre, id_provincia) VALUES ('Corredores', 6);
INSERT INTO cantones (nombre, id_provincia) VALUES ('Garabito', 6);


INSERT INTO cantones (nombre, id_provincia) VALUES ('Limón', 7);
INSERT INTO cantones (nombre, id_provincia) VALUES ('Pococí', 7);
INSERT INTO cantones (nombre, id_provincia) VALUES ('Siquirres', 7);
INSERT INTO cantones (nombre, id_provincia) VALUES ('Talamanca', 7);
INSERT INTO cantones (nombre, id_provincia) VALUES ('Matina', 7);
INSERT INTO cantones (nombre, id_provincia) VALUES ('Guácimo', 7);


INSERT INTO distritos (nombre, id_canton) VALUES ('Carmen', 1);
INSERT INTO distritos (nombre, id_canton) VALUES ('Merced', 1);
INSERT INTO distritos (nombre, id_canton) VALUES ('Hospital', 1);
INSERT INTO distritos (nombre, id_canton) VALUES ('Catedral', 1);
INSERT INTO distritos (nombre, id_canton) VALUES ('Zapote', 1);
INSERT INTO distritos (nombre, id_canton) VALUES ('San Francisco de Dos Ríos', 1);
INSERT INTO distritos (nombre, id_canton) VALUES ('Uruca', 1);
INSERT INTO distritos (nombre, id_canton) VALUES ('Mata Redonda', 1);
INSERT INTO distritos (nombre, id_canton) VALUES ('Pavas', 1);
INSERT INTO distritos (nombre, id_canton) VALUES ('Hatillo', 1);
INSERT INTO distritos (nombre, id_canton) VALUES ('San Sebastián', 1);


INSERT INTO distritos (nombre, id_canton) VALUES ('Escazú', 2);
INSERT INTO distritos (nombre, id_canton) VALUES ('San Antonio', 2);
INSERT INTO distritos (nombre, id_canton) VALUES ('San Rafael', 2);

INSERT INTO distritos (nombre, id_canton) VALUES ('Desamparados', 3);
INSERT INTO distritos (nombre, id_canton) VALUES ('San Miguel', 3);
INSERT INTO distritos (nombre, id_canton) VALUES ('San Juan de Dios', 3);
INSERT INTO distritos (nombre, id_canton) VALUES ('San Rafael Arriba', 3);
INSERT INTO distritos (nombre, id_canton) VALUES ('San Antonio', 3);
INSERT INTO distritos (nombre, id_canton) VALUES ('Frailes', 3);
INSERT INTO distritos (nombre, id_canton) VALUES ('Patarrá', 3);
INSERT INTO distritos (nombre, id_canton) VALUES ('San Cristóbal', 3);
INSERT INTO distritos (nombre, id_canton) VALUES ('Rosario', 3);
INSERT INTO distritos (nombre, id_canton) VALUES ('Damas', 3);
INSERT INTO distritos (nombre, id_canton) VALUES ('San Rafael Abajo', 3);
INSERT INTO distritos (nombre, id_canton) VALUES ('Gravilias', 3);
INSERT INTO distritos (nombre, id_canton) VALUES ('Los Guido', 3);

INSERT INTO distritos (nombre, id_canton) VALUES ('Santiago', 4);
INSERT INTO distritos (nombre, id_canton) VALUES ('Mercedes Sur', 4);
INSERT INTO distritos (nombre, id_canton) VALUES ('Barbacoas', 4);
INSERT INTO distritos (nombre, id_canton) VALUES ('Grifo Alto', 4);
INSERT INTO distritos (nombre, id_canton) VALUES ('San Rafael', 4);
INSERT INTO distritos (nombre, id_canton) VALUES ('Candelarita', 4);
INSERT INTO distritos (nombre, id_canton) VALUES ('Desamparaditos', 4);
INSERT INTO distritos (nombre, id_canton) VALUES ('San Antonio', 4);
INSERT INTO distritos (nombre, id_canton) VALUES ('Chires', 4);

INSERT INTO distritos (nombre, id_canton) VALUES ('San Marcos', 5);
INSERT INTO distritos (nombre, id_canton) VALUES ('San Lorenzo', 5);
INSERT INTO distritos (nombre, id_canton) VALUES ('San Carlos', 5);

commit


CREATE SEQUENCE SEQ_TELEFONOS INCREMENT BY 1 START WITH 1
MAXVALUE 99999 MINVALUE 0;

ALTER TABLE TELEFONOS MODIFY ID_TELEFONO DEFAULT SEQ_TELEFONOS.NEXTVAL