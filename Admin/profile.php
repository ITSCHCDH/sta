<?php
    session_start();

    if (isset($_SESSION['usuario'])) {
        if ($_SESSION['usuario']['Tipo'] != "Admin") {
            if ($_SESSION['usuario']['Tipo']=="Tutoria"){
                header('location: ' .$_SERVER["DOCUMENT_ROOT"].'/ATutor/');
            }elseif ($_SESSION['usuario']['Tipo']=="Tutor"){
                header('location: ' .$_SERVER["DOCUMENT_ROOT"].'/Tutor/');
            }elseif ($_SESSION['usuario']['Tipo']=="Jefe"){
                header('location: ' .$_SERVER["DOCUMENT_ROOT"].'/JefeCar/');
            }elseif ($_SESSION['usuario']['Tipo']=="Dire"){
                header('location: ' .$_SERVER["DOCUMENT_ROOT"].'/Direc/');
            }elseif ($_SESSION['usuario']['Tipo']=="Profe"){
                header('location: ' .$_SERVER["DOCUMENT_ROOT"].'/Profe/');
            } elseif ($_SESSION['usuario']['Tipo'] == "Alu") {
                header('location: ' . $_SERVER["DOCUMENT_ROOT"] . '/Alumnos/');
            }
        }
    }else {
        header('location: ../');
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Creative - Bootstrap 3 Responsive Admin Template">
    <meta name="author" content="GeeksLabs">
    <meta name="keyword" content="Creative, Dashboard, Admin, Template, Theme, Bootstrap, Responsive, Retina, Minimal">
    <link rel="shortcut icon" href="/assets/images/favicon.png">

    <title>Profile | Creative - Bootstrap 3 Responsive Admin Template</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!-- bootstrap theme -->
    <link href="/assets/css/bootstrap-theme.min.css" rel="stylesheet">
    <!--external css-->
    <!-- font icon -->
    <link href="/assets/css/elegant-icons-style.css" rel="stylesheet" />
    <link rel="stylesheet" href="/assets/css/font-awesome.min.css">

    <!-- Custom styles -->
    <link href="/assets/css/styleAdm.css" rel="stylesheet">
    <link href="/assets/css/style-responsive.css" rel="stylesheet" />

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 -->
    <!--[if lt IE 9]>
      <script src="/assets/js/html5shiv.js"></script>
      <script src="/assets/js/respond.min.js"></script>
      <script src="/assets/js/lte-ie7.js"></script>
    <![endif]-->

    <!-- =======================================================
      Theme Name: NiceAdmin
      Theme URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
      Author: BootstrapMade
      Author URL: https://bootstrapmade.com
    ======================================================= -->
    <?php echo "<script>var pass='".$_SESSION['usuario']['passw']."'; var userT='".$_SESSION['usuario']['Tipo']."'; var user='".$_SESSION['usuario']['Clave']."'; </script>";
 ?>
</head>

<body>
    <div class="alert text-center" id="alert_msg" ></div>
    <!-- container section start -->
    <section id="container" class="">
        <!--header start-->
        <header class="header dark-bg">
            <div class="toggle-nav">
                <div class="icon-reorder tooltips" data-original-title="Toggle Navigation" data-placement="bottom"><i class="icon_menu"></i></div>
            </div>
            <!--logo start-->
            <a href="/Admin/" class="logo">STA <span class="lite">Admin</span></a>
            <!--logo end-->
            <div class="nav search-row" id="top_menu">
                <!--  search form start -->
                <ul class="nav top-menu">
                    <li>
                        <form class="navbar-form">
                            <input class="form-control" placeholder="Buscar" type="text">
                        </form>
                    </li>
                </ul>
                <!--  search form end -->
            </div>
            <div class="top-nav notification-row">
                <!-- notificatoin dropdown start-->
                <ul class="nav pull-right top-menu">
                    <!-- user login dropdown start-->
                    <li class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <span class="profile-ava">
                                <img alt="" src="/assets/images/avatar1_small.jpg">
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
                    <!-- user login dropdown end -->
                </ul>
                <!-- notificatoin dropdown end-->
            </div>
        </header>
        <!--header end-->

        <!--sidebar start-->
        <aside>
            <div id="sidebar" class="nav-collapse ">
                <!-- sidebar menu start-->
                <ul class="sidebar-menu">
                    <li>
                        <a class="" href="index"> <i class="icon_house_alt"></i> <span>Dashboard</span> </a>
                    </li>
                    <li class="sub-menu">
                        <a href="#" class=""> <i class="icon_document_alt"></i> <span>Usuarios</span> <span class="menu-arrow arrow_carrot-right"></span> </a>
                        <ul class="sub">
                            <li><a class="" href="medicos.php">Medicos</a></li>
                            <li><a class="" href="psicos.php">Psicologos</a></li>
                            <li><a class="" href="docentes.php">Docentes general</a></li>
                            <li><a class="" href="tutores.php">Tutores</a></li>
                            <li><a class="" href="atutorias.php">Área Tutorías</a></li>
                            <li><a class="" href="Jefes.php">Jefes de carrera</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#" class=""> <i class="icon_contacts_alt"></i> <span>Alumnos</span> </a>
                    </li>
                    <li>
                        <a class="" href="sice.php"> <i class="icon_genius"></i> <span>SICE</span> </a>
                    </li>
                    <li>
                        <a class="" href="ciclo.php"> <i class="icon_genius"></i> <span>Cierre de ciclo</span> </a>
                    </li>
                    <li>
                        <a class="" href="caracterizacion.php"> <i class="icon_cloud-download_alt"></i> <span>Caracterización</span> </a>
                    </li>
                </ul>
                <!-- sidebar menu end-->
            </div>
        </aside>
        <!--sidebar end-->

        <!--main content start-->
        <section id="main-content">
            <section class="wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <h3 class="page-header"><i class="fa fa-user-md"></i> Profile</h3>
                        <ol class="breadcrumb">
                            <li><i class="fa fa-home"></i><a href="/Admin/">Home</a></li>
                            <li><i class="icon_documents_alt"></i>Pages</li>
                            <li><i class="fa fa-user-md"></i>Profile</li>
                        </ol>
                    </div>
                </div>
                <div class="row">
                    <!-- profile-widget -->
                    <div class="col-lg-12">
                        <div class="profile-widget profile-widget-info">
                            <div class="panel-body">
                                <div class="col-lg-2 col-sm-2">
                                    <h4><?php echo $_SESSION['usuario']['Tipo']; ?></h4>
                                    <div class="follow-ava">
                                        <img src="/assets/images/profile-widget-avatar.jpg" alt="">
                                    </div>
                                    <h6>Administrator</h6>
                                </div>
                                <div class="col-lg-4 col-sm-4 follow-info">
                                    <p>Hello I’m Jenifer Smith, a leading expert in interactive and creative design.</p>
                                    <p>@jenifersmith</p>
                                    <p><i class="fa fa-twitter">jenifertweet</i></p>
                                    <h6>
                                    <span><i class="icon_clock_alt"></i>11:05 AM</span>
                                    <span><i class="icon_calendar"></i>25.10.13</span>
                                    <span><i class="icon_pin_alt"></i>NY</span>
                                </h6>
                                </div>
                                <div class="col-lg-2 col-sm-6 follow-info weather-category">
                                    <ul>
                                        <li class="active">

                                            <i class="fa fa-comments fa-2x"> </i><br> Contrary to popular belief, Lorem Ipsum is not simply
                                        </li>

                                    </ul>
                                </div>
                                <div class="col-lg-2 col-sm-6 follow-info weather-category">
                                    <ul>
                                        <li class="active">

                                            <i class="fa fa-bell fa-2x"> </i><br> Contrary to popular belief, Lorem Ipsum is not simply
                                        </li>

                                    </ul>
                                </div>
                                <div class="col-lg-2 col-sm-6 follow-info weather-category">
                                    <ul>
                                        <li class="active">

                                            <i class="fa fa-tachometer fa-2x"> </i><br> Contrary to popular belief, Lorem Ipsum is not simply
                                        </li>

                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- page start-->
                <div class="row">
                    <div class="col-lg-12">
                        <section class="panel">
                            <header class="panel-heading tab-bg-info">
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a data-toggle="tab" href="#edit-profile">
                                          <i class="icon-envelope"></i>
                                          Edit Profile
                                      </a>
                                    </li>
                                </ul>
                            </header>
                            <div class="panel-body">
                                <div class="tab-content">
                                    <!-- edit-profile -->
                                    <div id="edit-profile" class="tab-pane active">
                                        <section class="panel">
                                            <div class="panel-body bio-graph-info">
                                                <h1> Profile Info</h1>
                                                <form class="form-horizontal" role="form">
                                                    <div class="form-group">
                                                        <label class="col-lg-2 control-label">Nombre</label>
                                                        <div class="col-lg-6">
                                                            <input type="text" class="form-control" id="f-name" placeholder=" ">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-lg-2 control-label">Apellido Paterno</label>
                                                        <div class="col-lg-6">
                                                            <input type="text" class="form-control" id="l-name" placeholder=" ">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-lg-2 control-label">Apellido Materno</label>
                                                        <div class="col-lg-6">
                                                            <input type="text" class="form-control" id="l-name" placeholder=" ">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-lg-2 control-label">Correo</label>
                                                        <div class="col-lg-6">
                                                            <input type="text" class="form-control" id="email" placeholder=" ">
                                                        </div>
                                                    </div><div class="form-group">
                                                        <div class="col-lg-offset-2 col-lg-10">
                                                            <button type="submit" class="btn btn-primary">Guardar</button>
                                                            <button type="button" class="btn btn-danger">Cancelar</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </section>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>

                <!-- page end-->
            </section>
        </section>
        <!--main content end-->
        <footer style="margin-top: 170px;">
            <div class="container">
                <div class="row" id="derechos">
                    <p class=" text-center text-muted">
                        Av. Ing. Carlos Rojas Gutiérrez 2120 | Fraccionamiento Valle de la Herradura | Ciudad Hidalgo Michoacán | Tel. (786) 154-90-00
                        <br> Copyright © 2017. Todos los Derechos Reservados
                    </p>
                </div>
            </div>
            <div class="text-right">
                <div class="credits">
                    <!--
                        All the links in the footer should remain intact.
                        You can delete the links only if you purchased the pro version.
                        Licensing information: https://bootstrapmade.com/license/
                        Purchase the pro version form: https://bootstrapmade.com/buy/?theme=NiceAdmin
                      -->
                    <a href="https://bootstrapmade.com/">Free Bootstrap Templates</a> by <a href="https://bootstrapmade.com/">BootstrapMade</a>
                </div>
            </div>
        </footer>
    </section>
    <!-- container section end -->
    <!-- javascripts -->
    <script src="/assets/js/jquery.js" charset="utf-8"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <!-- nice scroll -->
    <script src="/assets/js/jquery.scrollTo.min.js"></script>
    <script src="/assets/js/jquery.nicescroll.js" type="text/javascript"></script>
    <!-- jquery knob -->
    <script src="/assets/js/jquery.knob.js"></script>
    <!--custome script for all page-->
    <script src="/assets/js/scripts.js"></script>

    <script>
        //knob
        $(".knob").knob();
    </script>


</body>

</html>
