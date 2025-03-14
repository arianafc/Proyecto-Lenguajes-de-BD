<!DOCTYPE html>
<html lang="es">

<head>
<!DOCTYPE html>
<html lang="en">

<?php
// Incluir el archivo de fragmentos
require_once 'fragmentos.php';
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>El Legado</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Averia+Serif+Libre&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <?php incluir_css(); ?>
    <style>
        .sidebar {
            height: 100vh;
            width: 250px;
            background-color: #f0f0f0;
            padding-top: 20px;
            position: fixed;
        }
        
        .navbar-brand {
            display: flex;
            align-items: center;
            padding: 20px;
        }

        .navbar-brand img {
            margin-right: 10px;
        }

        .logo-text {
            font-family: 'Paytone One', sans-serif;
            font-size: 1.6rem;
            margin-right: 10px;
        }

        .logo-text .casa {
            color: #062D3E;
        }

        .logo-text .natura {
            color: #FBBC05;
        }

        Estilo del título principal Dashboard 
        .main-title {
            font-family: 'Josefin Sans', sans-serif;
            font-size: 2rem;
            font-weight: bold;
            color: #062D3E;
            background-color: #f0f0f0;
            padding: 20px;
            margin-top: 20px;
            margin-bottom: 30px;
            text-align: left;
        }

        .sidebar .title {
            font-size: 1.2rem;
            padding: 20px 20px;
            color: #062D3E;
        }

        .sidebar .submenu a {
            font-weight: normal; 
            color: black; 
            display: flex;
            align-items: center;
            padding: 10px;
            text-decoration: none;
            border-radius: 8px; 
            transition: all 0.3s ease; 
        }


        .sidebar .submenu a:hover {
            font-weight: bold; 
            background-color: #062D3E; 
            color: white; 
            border: 2px solid #062D3E;
            border-radius: 8px; 
        }

    
        .sidebar .submenu a:hover i {
            color: white; 
        }
        

        
        .content {
            margin-left: 270px;
            padding: 20px;
        }

        .menu-link {
            color: #062D3E;
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 10px;
            transition: color 0.3s ease;
        }

        .menu-link i {
            margin-right: 8px;
            color: #062D3E;
            font-size: 1.1rem;
            transition: color 0.3s ease;
        }

        .menu-link:hover,
        .menu-link:hover i {
            color: #FFFFFF;
        } 

        /* Alineación horizontal de los cuadros */
        .counter-box-container {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-bottom: 80px;
        }

        .counter-box,
        .counter-box-second {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 40px 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 300px;
            height: 180px;
            text-align: center;
            font-family: 'Public Sans', sans-serif;
        }

        .counter-box {
            background-color: #FFF3EB;
        }

        .counter-box-second {
            background-color: #EAF7E9;
        }

        .counter-box p,
        .counter-box-second p {
            font-size: 3.5rem;
            font-weight: bold;
            margin: 0;
        }

        .counter-box small,
        .counter-box-second small {
            font-size: 1.4rem;
            color: #666;
            margin-top: 10px;
            white-space: nowrap;
        } */

        /* Estilo para la tabla de donaciones */
        .donation-table-container {
            margin-top: 20px;
        }

        .donation-table {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            font-family: 'Public Sans', sans-serif;
        }

        .donation-table th,
        .donation-table td {
            padding: 15px;
            text-align: center;
        }

        .donation-table th {
            background-color: #062D3E;
            color: #fff;
        }

        .donation-table tbody tr:nth-child(even) {
            background-color: #f8f8f8;
        }

        .donation-table tbody tr:nth-child(odd) {
            background-color: #ffffff;
        }

        .total-donations-box {
            background-color: #f8f9fa;
            color: #333;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 250px;
            height: 200px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            margin-top: -40px;
            font-family: 'Public Sans', sans-serif;
        }

        .total-donations-box .total-title {
            font-size: 1.5rem;
            font-weight: bold;
        }

        .total-donations-box .total-amount {
            font-size: 2.5rem;
            margin-top: 10px;
        } 
    </style>
</head>

<body>


    <?php 
       sidebar();
    ?>

    <div class="content">
        <!-- Título principal Dashboard -->
        <div class="main-title">Dashboard</div>

        <!-- Contadores alineados horizontalmente -->
        <div class="counter-box-container">
            <div class="counter-box">
                <p>1200</p>
                <small>Usuarios Registrados</small>
            </div>
            <div class="counter-box-second">
                <p>450</p>
                <small>Animales Registrados</small>
            </div>
            <div class="counter-box-second">
                <p>150</p>
                <small>Animales Apadrinados</small>
            </div>
        </div>

        <!-- Tabla de Donaciones y Total de Donaciones -->
        <div class="row align-items-center donation-table-container">
            <div class="col-md-8 d-flex justify-content-start">
                <div class="donation-table">
                    <h3>Donaciones</h3>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Monto</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Juan Pérez</td>
                                <td>$50</td>
                                <td>2024-11-01</td>
                            </tr>
                            <tr>
                                <td>María López</td>
                                <td>$75</td>
                                <td>2024-10-15</td>
                            </tr>
                            <tr>
                                <td>Carlos Sánchez</td>
                                <td>$100</td>
                                <td>2024-09-20</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Total de Donaciones -->
            <div class="col-md-4 d-flex justify-content-center">
                <div class="total-donations-box">
                    <div class="total-title">Total Donaciones</div>
                    <div class="total-amount">$225</div>
                </div>
            </div>
        </div>
    </div>

    <?php 
    footerAdmin();
    ?>



    <!--JavaScript directo en HTML-->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const menuLinks = document.querySelectorAll('.sidebar .submenu a');
            menuLinks.forEach(link => {
                link.addEventListener('mouseenter', () => {
                    link.style.fontWeight = 'bold';
                });
                link.addEventListener('mouseleave', () => {
                    link.style.fontWeight = 'normal';
                });
            });
        });
    </script>
 
</body>

</html>