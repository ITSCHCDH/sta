<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="author" content="Jorge Armando Rocha Mendoza" />
    <title>--STA--</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <!-- css -->
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link href="/assets/css/style.css" rel="stylesheet" />
    <link href="/assets/css/styleAlu.css" rel="stylesheet" />
    <style media="screen">
        .table-sortable tbody tr {
            cursor: move;
        }

        #form_AluAcept fieldset:not(:first-of-type) {
            display: none;
        }

        form input[type="email"]:required:valid,
        form input[type="radio"]:required:valid,
        form input[type="combobox"]:required:valid,
        form input[type="text"]:required:valid,
        form input[type="number"]:required:valid,
        form select:required:valid,
        form input[type="date"]:required:valid,
        form input[type="email"]:required:valid,
        form input[type="radio"]:valid,
        form input[type="combobox"]:valid,
        form input[type="text"]:valid,
        form input[type="number"]:valid,
        form select:required:valid,
        form input[type="date"]:valid {
            border: 2px solid green;
            /* otras propiedades */
        }

        /*caso contrario, el color sera rojo*/

        form input[type="email"]:required:invalid,
        form input[type="radio"]:required:invalid,
        form input[type="combobox"]:required:invalid,
        form input[type="text"]:required:invalid,
        form input[type="number"]:required:invalid,
        form select:required:invalid,
        form input[type="date"]:required:invalid,
        form input[type="email"]:required:invalid,
        form input[type="radio"]:invalid,
        form input[type="combobox"]:invalid,
        form input[type="text"]:invalid,
        form input[type="number"]:invalid,
        form select:required:invalid,
        form input[type="date"]:invalid {
            border: 2px solid red;
            /* otras propiedades */
        }

        .bs-float-label {
            position: relative;
            border: 1px solid #ddd;
            margin: 10px;
            padding: 10px;
        }

        .bs-float-label>.float-input {
            margin-top: 10px;
        }

        .bs-float-label>.float-input:focus {
            /*border-color: #f06d06;*/
        }

        .bs-float-label>.float-label {
            position: absolute;
            top: 3px;
            left: 10px;
            background: rgba(255, 255, 255, .32);
            -webkit-transition: top .5s ease-in-out, opacity .5s ease-in-out;
            /* For Safari 3.1 to 6.0 */
            transition: top .5s ease-in-out, opacity .5s ease-in-out;
            opacity: 0;
        }

        .bs-float-label>.float-label.show {
            color: #333;
            top: 1px !important;
            opacity: 1;
            padding: 0 !important;
        }

        .bs-float-label>.float-label.on {
            color: blue;
        }
    </style>
    <script>
        var pass = 'setPass';
        var userT = 'Alu';
        var user = '12030178';
    </script>
</head>

<body><?php
session_start();
require_once($_SERVER["DOCUMENT_ROOT"] . '/php/clases/Alumno.php');
$Alu = new Alumno();
$Alu->aluFiFamilia($_SESSION['usuario']['Clave']);
 ?>
