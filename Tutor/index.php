<?php
    session_start();

    if (isset($_SESSION['usuario'])) {
        if ($_SESSION['usuario']['Tipo'] != "Tutor") {

            if ($_SESSION['usuario']['Tipo']=="Admin"){
                header('location: '.$_SERVER["DOCUMENT_ROOT"]."/Admin/");
            }elseif ($_SESSION['usuario']['Tipo']=="Profe"){
                header('location: '.$_SERVER["DOCUMENT_ROOT"].'/Profe/');
            }elseif ($_SESSION['usuario']['Tipo']=="Jefe"){
                header('location: '.$_SERVER["DOCUMENT_ROOT"].'/JefeCar/');
            }elseif ($_SESSION['usuario']['Tipo']=="Dire"){
                header('location: '.$_SERVER["DOCUMENT_ROOT"].'/Direc/');
            }elseif ($_SESSION['usuario']['Tipo']=="Tutoria"){
                header('location: '.$_SERVER["DOCUMENT_ROOT"].'/ATutor/');
            }elseif ($_SESSION['usuario']['Tipo']=="Medic"){
                header('location: ' .$_SERVER["DOCUMENT_ROOT"].'/Medico/');
            }elseif ($_SESSION['usuario']['Tipo']=="Alu"){
                header('location: ' .$_SERVER["DOCUMENT_ROOT"].'/Alumno/');
            }
        }
    }else{
        header('location:/php/CerrarSesion.php');
    }
    require_once $_SERVER["DOCUMENT_ROOT"]."/php/clases/Tutor.php";
    $tut = new tutor();
    require_once $_SERVER["DOCUMENT_ROOT"]."/php/clases/Grupo.php";
    $gpo = new Grupo();
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
    <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet" />
    <style>
        .dash-box {
            position: relative;
            background: rgb(255, 86, 65);
            background: -moz-linear-gradient(top, rgba(255, 86, 65, 1) 0%, rgba(253, 50, 97, 1) 100%);
            background: -webkit-linear-gradient(top, rgba(255, 86, 65, 1) 0%, rgba(253, 50, 97, 1) 100%);
            background: linear-gradient(to bottom, rgba(255, 86, 65, 1) 0%, rgba(253, 50, 97, 1) 100%);
            filter: progid: DXImageTransform.Microsoft.gradient( startColorstr='#ff5641', endColorstr='#fd3261', GradientType=0);
            border-radius: 4px;
            text-align: center;
            margin: 60px 0 50px;
        }
        .dash-box-icon {
            position: absolute;
            transform: translateY(-50%) translateX(-50%);
            left: 50%;
        }
        .dash-box-action {
            transform: translateY(-50%) translateX(-50%);
            position: absolute;
            left: 50%;
        }
        .dash-box-body {
            padding: 50px 20px;
        }
        .dash-box-icon:after {
            width: 60px;
            height: 60px;
            position: absolute;
            background: rgba(247, 148, 137, 0.91);
            content: '';
            border-radius: 50%;
            left: -10px;
            top: -10px;
            z-index: -1;
        }
        .dash-box-icon > i {
            background: #ff5444;
            border-radius: 50%;
            line-height: 40px;
            color: #FFF;
            width: 40px;
            height: 40px;
            font-size:22px;
        }
        .dash-box-icon:before {
            width: 75px;
            height: 75px;
            position: absolute;
            background: rgba(253, 162, 153, 0.34);
            content: '';
            border-radius: 50%;
            left: -17px;
            top: -17px;
            z-index: -2;
        }
        .dash-box-action > button {
            border: none;
            background: #FFF;
            border-radius: 19px;
            padding: 7px 16px;
            text-transform: uppercase;
            font-weight: 500;
            font-size: 11px;
            letter-spacing: .5px;
            color: #003e85;
            box-shadow: 0 3px 5px #d4d4d4;
        }
        .dash-box-body > .dash-box-count {
            display: block;
            font-size: 30px;
            color: #FFF;
            color: #FFF;
            font-weight: 300;
        }
        .dash-box-body > .dash-box-title {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.81);
        }
        .dash-box.dash-box-color-3 {
            background: rgb(183,71,247);
            background: -moz-linear-gradient(top, rgb(19, 143, 20) 0%, rgba(150,220,83,1) 100%);
            background: -webkit-linear-gradient(top, rgb(19, 143, 20) 0%,rgba(150,220,83,1) 100%);
            background: linear-gradient(to bottom, rgb(19, 143, 20) 0%,rgba(150,220,83,1) 100%);
            filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#b747f7', endColorstr='#6c53dc',GradientType=0 );
        }
        .dash-box-color-3 .dash-box-icon:after {
            background: rgba(163, 245, 146, 0.76);
        }
        .dash-box-color-3 .dash-box-icon:before {
            background: rgba(255, 251, 86, 0.66);
        }
        .dash-box-color-3 .dash-box-icon > i {
            background: #e4df8b;
        }
    </style>
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
    <nav class="navbar navbar-toggleable-md navbar-default bg-faded" role="navigation">
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
                            <li class="eborder-top"><a href="profile.php"><i class="icon_profile"></i> Mi Perfil</a></li>
                            <li><a href="/php/CerrarSesion.php"><i class="icon_key_alt"></i> Cerrar Sesión</a></li>
                        </ul>
                    </li>
                </ul>
                <ul class="nav navbar-nav navbar-left">
                    <li class="active"><a href="#"><span class="glyphicon glyphicon-home" aria-hidden="true"></span></a></li>
                    <li><a href="CrearGrupo.php">Crear Grupo</a></li>
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Grupos<span class="caret"></span></a></a>
                        <ul class="dropdown-menu extended logout">
                                <li><a class="dropdown-item" href="Lista.php">Lista</a></li>
                                <li><a class="dropdown-item" href="Materias.php">Materias</a></li>
                            </ul>

                    </li>
                    <li><a href="Formatos.php">Formatos</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- /navbar -->

    <!--  -->
    <div class="container cuerpo">
        <ol class="breadcrumb">
            <li><a href="#"> Inicio Tutor</a></li>
        </ol>

        <div class="row">
            <div class="col-sm-8">
                <br>
                <h3 class="text-center">Alumnos Reprobando</h3>
                <?php $gpo->listar_alumnos2(); ?>
            </div>
            <div class="col-sm-4">
                <div class="dash-box dash-box-color-3">
                    <div class="dash-box-icon">
                        <i class="glyphicon glyphicon-user"></i>
                    </div>
                    <div class="dash-box-body">
                        <span class="dash-box-count"> <?php  echo $tut->newGrupos($_SESSION['usuario']['Clave']); ?> </span>
                        <span class="dash-box-title">Nuevos Grupos</span>
                    </div>
                    <div class="dash-box-action">
                        <button onclick="window.location.assign('CrearGrupo.php')">Ver Mas</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- footer -->
    <div class="clearfix"></div>
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
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="/assets/js/main.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('#mitabla').DataTable({
                "order": [[0, "asc"]],
                "language":{
                "lengthMenu": "Mostrar _MENU_ registros por pagina",
                "info": "Mostrando pagina _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay registros disponibles",
                    "infoFiltered": "(filtrada de _MAX_ registros)",
                    "loadingRecords": "Cargando...",
                    "processing":     "Procesando...",
                    "search": "Buscar:",
                    "zeroRecords":    "No se encontraron registros coincidentes",
                    "paginate": {
                        "next":       "Siguiente",
                        "previous":   "Anterior"
                    },
                }
            });
        });
    </script>
</body>

</html>
