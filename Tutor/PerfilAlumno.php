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
        header('location:/sta/php/CerrarSesion.php');
    }

    require_once $_SERVER["DOCUMENT_ROOT"].'/sta/php/clases/Alumno.php';
    require_once $_SERVER["DOCUMENT_ROOT"].'/sta/php/clases/Grupo.php';
    $Alumno1 = new Alumno();
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
    <link href="/sta/assets/css/style.css" rel="stylesheet" />
    <?php echo "<script>var pass='".$_SESSION['usuario']['passw']."'; var userT='".$_SESSION['usuario']['Tipo']."'; var user='".$_SESSION['usuario']['Clave']."'; </script>";
    ?>
</head>

<body>

    <!-- box1 -->
    <div id="box1" class="row home">
        <div class="col-md-6 col-sm-6">
            <a href="index.php"><img src="/sta/assets/images/CABECERA.PNG" class="img-responsive izquierda ciento20" alt="Responsive image"></a>
        </div>
        <div class="col-md-6 col-sm-6">
            <a href="http://www.itsch.edu.mx/"><img src="/sta/assets/images/itsch.png" class="img-responsive derecha ciento20" alt="Responsive image"></a>
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
                                <img alt="" src="/sta/assets/images/<?php echo $_SESSION['usuario']['img']? 'Users/'.$_SESSION['usuario']['img']:'avatar1_small.jpg'; ?>" style="width:33px;">
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
                                <a href="/sta/php/CerrarSesion.php"><i class="icon_key_alt"></i> Cerrar Sesión</a>
                            </li>
                        </ul>
                    </li>
                </ul>
                <ul class="nav navbar-nav navbar-left">
                    <li><a href="index.php"><span class="glyphicon glyphicon-home" aria-hidden="true"></span></a></li>
                    <li><a href="CrearGrupo.php">Crear Grupo</a></li>
                    <li class="nav-item dropdown">
                        <a href="Grupos.php" class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Grupos<span class="caret"></span></a></a>
                        <ul class="dropdown-menu">
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
    <ol class="breadcrumb">
        <li><a href="index.php"> Inicio Tutor</a></li>
        <li ><a href="#"><?php echo $_GET['Grupo'] ?></a></li>
        <li class="active">Perfil de alumno</li>
    </ol>
    <!--  -->
    <div class='container cuerpo'>
        <center><h1>PERFIL DEL ALUMNO</h1></center>
        <div class='row'>
            <div class='col-md-2 col-lg-3'>
            <?php
                $Alumno1 -> AlumnoDatosPersonales();
             ?>
            </div>
            <div class='col-md-10 col-lg-9'>
             <div class='semaforos'>
                 <h4>SEMAFOROS</h4>
                 <table class='table'>
                     <thead>
                         <tr>
                             <th>Medico</th>
                             <th>Psicologia</th>
                             <th>Calificaciones</th>
                             <th>Servicio social</th>
                             <th>Cultura y deporte</th>
                         </tr>
                     </thead>
                     <tbody>
                         <tr class='active'>
                             <?php $Alumno1 -> AlumnoSemaforos(); ?>
                         </tr>
                     </tbody>
                 </table>
             </div>
             <hr>
             <div class='materias'>
                 <h4>Calificaciones</h4>
                 <table class='table'>
                     <?php
                         $Alumno1 -> AlumnoCalificaciones();
                      ?>
                 </table>
             </div>
             <hr>
             <div class='semestre'>
                 <h4>Semestres</h4>
                 <?php
                     $Alumno1 -> AlumnoSemestre($_GET['NoCon']);
                  ?>
             </div>
             <hr>

         </div>
        </div>
    </div>
    <!-- footer -->
    <footer>
        <img src="/sta/assets/images/cedeit.jpg" class="img-responsive imgfoot">
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
    <script src="/sta/assets/js/jquery.js" charset="utf-8"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="/sta/assets/js/main.js"></script>
    <script type="text/javascript">
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        });
    </script>

</body>

</html>
