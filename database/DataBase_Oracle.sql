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


