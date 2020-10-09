<?php
    session_start();

    if (isset($_SESSION['usuario'])) {
        if ($_SESSION['usuario']['Tipo']=="Admin"){
            header('location: /sta/Admin/');
        }elseif ($_SESSION['usuario']['Tipo']=="Profe"){
            header('location: /Profe/');
        }elseif ($_SESSION['usuario']['Tipo']=="Tutor"){
            header('location: /sta/Tutor/');
        }elseif ($_SESSION['usuario']['Tipo']=="Jefe"){
            header('location: /sta/JefeCar/');
        }elseif ($_SESSION['usuario']['Tipo']=="Dire"){
            header('location: /sta/Direc/');
        }elseif ($_SESSION['usuario']['Tipo']=="Tutoria"){
            header('location: /sta/ATutor/');
        }elseif ($_SESSION['usuario']['Tipo']=="Medic"){
            header('location: /sta/Medico/');
        }elseif ($_SESSION['usuario']['Tipo']=="Alu"){
            header('location: /sta/Alumnos/');
        }
    }
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Inicio sesion STA">
    <meta name="author" content="Jorge Armando Rocha">
    <link rel="shortcut icon" href="img/favicon.png">
    <title>--STA--</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!-- bootstrap theme -->
    <link href="/assets/css/bootstrap-theme.min.css" rel="stylesheet">
    <!--external css-->
    <!-- font icon -->
    <link href="/assets/css/elegant-icons-style.css" rel="stylesheet" />
    <link href="/assets/css/font-awesome.css" rel="stylesheet" />
    <!-- Custom styles -->
    <link href="/assets/css/styleAdm.css" rel="stylesheet">
    <link href="/assets/css/style-responsive.css" rel="stylesheet" />
    <style media="screen">
        .error{
        background-color: #E74F4F;
        position: absolute;
        top: 0;
        padding: 10px 0 ;
        border-radius:  0 0 5px 5px;
        color: #fff;
        width: 100%;
        text-align: center;
        display: none;
    }
    </style>

</head>

<body class="login-img3-body" style="background-image: url('/assets/images/fondo.jpg');background-size: cover;">
    <div class="error" id="error1">
        <span>El usuario no tiene permiso para acceder con este Rol.</span>
    </div>
    <div class="error" id="error2">
        <span>Datos de ingreso no validos, inténtalo de nuevo.</span>
    </div>
    <div class="container">
        <form class="login-form" id="formlogin" method="post">
            <div class="login-wrap">
                <p class="login-img"><i class="icon_lock_alt"></i></p>
                <div class="input-group">
                    <span class="input-group-addon"><i class="icon_profile"></i></span>
                    <input type="text" class="form-control"  pattern="[A-Za-z0-9_-]{1,20}" placeholder="Nombre de Usuario"  id="usuario" name="usuario" autofocus>
                </div>
                <div class="input-group">
                    <span class="input-group-addon"><i class="icon_key_alt"></i></span>
                    <input type="password" class="form-control" placeholder="Password"   id="password" name="password">
                </div>
                <select id="tipoUsuario" name="tipoUsuario" class="form-control">
                    <option value="Alu">Alumno</option>
                    <option value="Profe">Profesor</option>
                    <option value="Tutor">Tutor</option>
                    <option value="Jefe">Jefe de carrera</option>
                    <option value="Dire">Directivo</option>
                    <option value="Tutoria">Tutorias</option>
                    <option value="Admin">Administrador</option>
                    <option value="Medic">Medico</option>
                </select>
                <br>
                <button class="btn btn-primary btn-lg btn-block" type="submit" id="iniciar" name="action">Iniciar</button>
            </div>
        </form>
    </div>
    <script src="assets/js/jquery.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script type="text/javascript">
        var pass='setPass';
    </script>
</body>

</html>
