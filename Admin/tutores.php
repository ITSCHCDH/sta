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

    require_once $_SERVER["DOCUMENT_ROOT"].'/sta/php/clases/admin.php';
    $Adm = new AdminUser();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SISTEMA DE TRAYECTORIA ACADEMICA">
    <meta name="author" content="Jorge Armando Rocha Mendoza">

    <link rel="shortcut icon" href="/sta/assets/images/favicon.png">

    <title>Sistema de  Trayectoria Academica</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!-- bootstrap theme -->
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <!--external css-->
    <!-- font icon -->
    <link href="/sta/assets/css/elegant-icons-style.css" rel="stylesheet" />
    <link rel="stylesheet" href="/sta/assets/css/font-awesome.min.css">

    <!-- Custom styles -->
    <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="/sta/assets/css/styleAdm.css" rel="stylesheet">
    <link href="/sta/assets/css/style-responsive.css" rel="stylesheet" />
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 -->
    <!--[if lt IE 9]>
      <script src="/sta/assets/js/html5shiv.js"></script>
      <script src="/sta/assets/js/respond.min.js"></script>
      <script src="/sta/assets/js/lte-ie7.js"></script>
    <![endif]-->
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
            <a href="/sta/Admin/" class="logo">STA <span class="lite">Admin</span></a>
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
                                <img alt="" src="/sta/assets/images/avatar1_small.jpg">
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
                    <li class="active sub-menu">
                        <a href="#" class=""> <i class="icon_document_alt"></i> <span>Usuarios</span> <span class="menu-arrow arrow_carrot-right"></span> </a>
                        <ul class="sub">
                            <li><a class="" href="medicos.php">Medicos</a></li>
                            <li><a class="" href="psicos.php">Psicologos</a></li>
                            <li><a class="" href="docentes.php">Docentes general</a></li>
                            <li><a class="" href="Jefes.php">Jefes de carrera</a></li>
                            <li class="active"><a class="" href="atutorias.php">Área Tutorías</a></li>
                            <li><a class="" href="tutores.php">Tutores</a></li>
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
                    <li>
                        <a href="mensajes.php" class=""> <i class="icon_comment_alt"></i> <span>Mensajes</span> </a>
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
                        <h3 class="page-header"><i class="fa fa fa-bars"></i> Dashboard</h3>
                        <ol class="breadcrumb">
                            <li><i class="fa fa-home"></i><a href="/sta/Admin/">Inicio</a></li>
                            <li><i class="icon_document_alt"></i>Usuarios</li>
                            <li>Tutores</li>
                        </ol>
                    </div>
                </div>
                <!-- page start-->
                <div class="content-box-large">
                    <div class="panel-heading">
                        <!--div class="pull-right">
                            <button type="button" data-toggle="modal" data-target="#userAlt" class="btn btn-primary center-block">Agregar</button>
                        </div-->
                        <div class="panel-title">Tutores dados de alta</div>
                    </div>
                    <div class="panel-body">
                        <table cellpadding="0" cellspacing="0" border="0" class="display responsive" id="mitabla">
                            <thead>
                                <tr>
                                    <th><i class="icon_profile"></i>  Nombre</th>
                                    <th><i class="icon_bag_alt"></i>  Departamento</th>
                                    <th><i class="icon_id"></i>  Nombre de Usuario</th>
                                    <!--th><i class="icon_cogs"></i>  Acciones</--th-->
                                </tr>
                            </thead>
                            <tbody>
                                <?php $Adm->tuto_crud(); ?>
                            </tbody>
                        </table>
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
    <div class="modal fade" id="userAlt" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog" style=" margin: 100px auto;">
            <div class="modal-content">
                <form id="form_atuto" method="post">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                        <h3 class="modal-title" id="lineModalLabel">Nuevo Usuario del Área de Tutorias</h3>
                    </div>
                    <div class="modal-body">
                        <!-- content goes here -->
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="nombre">Nombre(s)</label>
                                    <input type="text" class="form-control" id="nomcat" placeholder="Nombre" name="nombre" list="catedraticos">
                                    <datalist id="catedraticos">
                                        <?php $Adm->catedraticos(); ?>
                                    </datalist>
                                    <input type="hidden" name="clavcat" value="" id="clavcat">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="correo">Correo</label>
                                    <input type="email" class="form-control" id="correo" placeholder="correo@itsch.edu.mx" name="correo" value="correo@itsch.edu.mx">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="contrasena">Contraseña</label>
                                    <input type="password" class="form-control" placeholder="Password" id="pass1" readonly="readonly" value="TrayectoriaITSCH">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="contrasena2">Repite la contraseña</label>
                                    <input type="password" class="form-control" placeholder="Password" id="pass2" readonly="readonly" value="TrayectoriaITSCH">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="btn-group btn-group-justified" role="group" aria-label="group button">
                            <button style="width:49.5%;" type="button" class="btn btn-lg btn-danger" data-dismiss="modal" role="button">Cerrar</button>
                            <button style="width:49.5%;" type="button" id="saveUserTU" class="btn btn-lg btn-primary " data-action="save" role="button">Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- container section end -->
    <!-- javascripts -->
    <script src="/sta/assets/js/jquery.js" charset="utf-8"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <!-- nice scroll -->
    <script src="/sta/assets/js/jquery.scrollTo.min.js"></script>
    <script src="/sta/assets/js/jquery.nicescroll.js" type="text/javascript"></script>
    <!--custome script for all page-->
    <script src="/sta/assets/js/scripts.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="/sta/assets/js/main.js"></script>
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
    <script type="text/javascript">
        jQuery(window).load(function() {
            //Cuando se carga todo el contenido.
            jQuery("#main-content").css("min-height", (jQuery(window).height() - jQuery("footer").outerHeight() - jQuery("header").outerHeight() + "px"));
        }).resize(function() {
            //Cuando se escala la pantalla.
            jQuery("#main-content").css("min-height", (jQuery(window).height() - jQuery("footer").outerHeight() - jQuery("header").outerHeight() + "px"));
        });
        jQuery(document).ready(function() {
            //Cuando el DOM está disponible.
            jQuery("#main-content").css("min-height", (jQuery(window).height() - jQuery("footer").outerHeight() - jQuery("header").outerHeight() + "px"));
        })
    </script>
</body>

</html>
