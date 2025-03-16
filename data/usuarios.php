<?php

session_start();
include("../conexion.php");

$data = $_POST;


switch ($data['action']) {

    case 'getUsuario':
            $sql = "SELECT * FROM V_USUARIOS";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                $users = [];
                $user = $result->fetch_assoc();
                $users[] = [
                    "Nombre" => $user['Nombre'],
                    "Correo" => $user['Email'],
                    "Telefono" => $user['Telefono'],
                    "Direccion" => $user['Direccion_Exacta'],
                    "Provincia" => $user['Provincia'],
                    "Canton" => $user['Canton'],
                    "Distrito" => $user['Distrito'],
                    "Password" => $user['Password']
                ];

                echo json_encode([
                    "status" => "00",
                    "users" => $users
                ]);
            } else {
                echo json_encode([
                    "status" => "99",
                    "users" => "No se encontró información para el usuario especificado."
                ]);
            }
        }
        break;
    default:
        break;



}










?>