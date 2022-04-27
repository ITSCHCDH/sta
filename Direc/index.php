<?php
    session_start();

    if (isset($_SESSION['usuario'])) {
        if ($_SESSION['usuario']['Tipo'] != "Dire") {

            if ($_SESSION['usuario']['Tipo']=="Admin"){
                header('location: '.$_SERVER["DOCUMENT_ROOT"]."/Admin/");
            }elseif ($_SESSION['usuario']['Tipo']=="Tutor"){
                header('location: '.$_SERVER["DOCUMENT_ROOT"].'/Tutor/');
            }elseif ($_SESSION['usuario']['Tipo']=="Jefe"){
                header('location: '.$_SERVER["DOCUMENT_ROOT"].'/JefeCar/');
            }elseif ($_SESSION['usuario']['Tipo']=="Profe"){
                header('location: '.$_SERVER["DOCUMENT_ROOT"].'/Profe/');
            }elseif ($_SESSION['usuario']['Tipo']=="Tutoria"){
                header('location: '.$_SERVER["DOCUMENT_ROOT"].'/ATutor/');
            }
        }
    }else {
        header('location: '.$_SERVER["DOCUMENT_ROOT"].'/');
    }
 ?>
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
    <?php echo "<script>var pass='".$_SESSION['usuario']['passw']."'; var userT='".$_SESSION['usuario']['Tipo']."'; var user='".$_SESSION['usuario']['Clave']."'; </script>";
 ?>
</head>

<body>

    <!-- box1 -->
    <div id="box1" class="row home">
        <div class="col-md-6 col-sm-6">
            <a href="index.php"><img src="/assets/images/CABECERA.PNG" class="img-responsive izquierda ciento20" alt="Responsive image"></a>
        </div>
        <div class="col-md-6 col-sm-6">
            <a href="http://www.itsch.edu.mx/"><img src="/assets/images/itsch.png" class="img-responsive derecha ciento20" alt="Responsive image"></a>
        </div>
    </div>
    <!-- /box1 -->

    <!-- navbar -->
    <nav class="navbar navbar-default" role="navigation">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse-main">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                     <span class="icon-bar"></span>
                     <span class="icon-bar"></span>
                 </button>

            </div>
            <div class="collapse navbar-collapse" id="navbar-collapse-main">
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <span class="profile-ava">
                                <img alt="" src="/assets/images/<?php echo $_SESSION['usuario']['img']? 'Users/'.$_SESSION['usuario']['img']:'avatar1_small.jpg'; ?>" style="width:33px;">
                            </span>
                            <span class="username"><?php echo $_SESSION['usuario']['Nombre']; ?></span>
                            <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu extended logout">
                            <div class="log-arrow-up"></div>
                            <li class="eborder-top">
                                <a href="profile.php"><i class="icon_profile"></i> Mi Perfil</a>
                            </li>
                            <li>
                                <a href="/php/CerrarSesion.php"><i class="icon_key_alt"></i> Cerrar Sesión</a>
                            </li>
                        </ul>
                    </li>
                </ul>
                <ul class="nav navbar-nav navbar-left">
                    <li><a href="index.php"><span class="glyphicon glyphicon-home" aria-hidden="true"></span></a></li>
                    <li><a href="carreras.php">Carreras</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- /navbar -->

    <!--  -->
    <div class="container cuerpo">
        <ol class="breadcrumb">
            <li>Inicio</li>
            <li><a href="#" class="active">Academicos</a></li>
        </ol>

        <div class="row">
            <div class="col-sm-6">
                <div class="form-group" >
                    <label for="PScarrera" class="col-sm-2 control-label">Carreras</label>
                    <select class="form-control" id="DirCarrera" name="DirCarrera" required>
                        <option selected="selected" disabled="disabled">-Elija la carrera-</option>
                        <option value="6">INGENIERIA BIOQUIMICA</option>
                        <option value="7">INGENIERIA EN GESTION EMPRESARIAL</option>
                        <option value="2">INGENIERIA INDUSTRIAL</option>
                        <option value="5">INGENIERIA MECATRONICA</option>
                        <option value="10">INGENIERIA NANOTECNOLOGIA</option>
                        <option value="3">INGENIERIA EN SISTEMAS COMPUTACIONALES</option>
                        <option value="9">INGENIERIA EN TECNOLOGIAS DE LA INFORMACION Y COMUNICACION</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group" >
                    <label for="PSGrupo" class="col-sm-2 control-label">Generaciones</label>
                    <select  class="form-control" name="DirGrupo" id="DirGrupo">
                        <option selected>Seleccione una generación</option>
                    </select>
                </div>
            </div>

        </div>
        <hr>
        <div class="panel panel-primary">
            <div class="panel-heading" style="height:30px;">
                <h3 class="panel-title">Alumnos Reprobando</h3>
            </div>
            <div class="panel-body">
                <div id="lista"></div>
            </div>
        </div>

    </div>
    <!-- footer -->
    <footer>
        <img src="/assets/images/cedeit.jpg" class="img-responsive imgfoot">
        <div class="footer">

			<div class="container">
                <div class="row" id="derechos">
                <p class=" text-center text-muted">
                    Av. Ing. Carlos Rojas Gutiérrez 2120 | Fraccionamiento Valle de la Herradura | Ciudad Hidalgo Michoacán | Tel. (786) 154-90-00
                </p>
                <p class=" text-center text-muted">
                    Copyright © 2017. Todos los Derechos Reservados
                </p>
                </div>
			</div>
		</div>

    </footer>
    <!-- /footer -->

    <!-- js -->
    <script src="/assets/js/jquery.js" charset="utf-8"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="/assets/js/main.js"></script>+
</body>

</html>
