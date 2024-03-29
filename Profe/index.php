<?php
    session_start();

    if (isset($_SESSION['usuario'])) {
        if ($_SESSION['usuario']['Tipo'] != "Profe") {
            if ($_SESSION['usuario']['Tipo']=="Admin"){
                header('location: '.$_SERVER["DOCUMENT_ROOT"]."/Admin/");
            }elseif ($_SESSION['usuario']['Tipo']=="Tutor"){
                header('location: '.$_SERVER["DOCUMENT_ROOT"].'/Tutor/');
            }elseif ($_SESSION['usuario']['Tipo']=="Jefe"){
                header('location: '.$_SERVER["DOCUMENT_ROOT"].'/JefeCar/');
            }elseif ($_SESSION['usuario']['Tipo']=="Dire"){
                header('location: '.$_SERVER["DOCUMENT_ROOT"].'/Direc/');
            }elseif ($_SESSION['usuario']['Tipo']=="Tutoria"){
                header('location: '.$_SERVER["DOCUMENT_ROOT"].'/ATutor/');
            } elseif ($_SESSION['usuario']['Tipo'] == "Alu") {
                header('location: ' . $_SERVER["DOCUMENT_ROOT"] . '/Alumnos/');
            }
        }
    }else {
        header('location:/php/CerrarSesion.php');
    }
require_once $_SERVER["DOCUMENT_ROOT"].'/php/clases/Profesor.php';
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
        <style media="screen">
            fieldset{
                border: 1px !important;
                border-top: 1px solid #0c0588 !important;
                border-right: 1px solid #0c0588 !important;
                border-bottom: 1px solid #0c0588 !important;
                border-left: 1px solid #0c0588 !important;
                border-radius: 10px !important;
                color: #0c0588 !important;
                height: 116px;
            }
            .warning{
            background-color: rgb(248, 29, 29) !important;
        }
        </style>
        <?php echo "<script>var pass='".$_SESSION['usuario']['passw']."'; var userT='".$_SESSION['usuario']['Tipo']."'; var user='".$_SESSION['usuario']['Clave']."'; </script>";
     ?>
    </head>
<body>
    <div class="alert text-center" id="alert_msg" ></div>
    <!-- box1 -->
    <div id="box1" class="row home">
        <div class="col-md-6 col-sm-6">
            <a href="index"><img src="/assets/images/CABECERA.PNG" class="img-responsive izquierda ciento20" alt="Responsive image"></a>
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
                    <li><a href="#"><span class="glyphicon glyphicon-home" aria-hidden="true"></span></a></li>
                    <li><a href="#">Indice de reprobados</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- /navbar -->
    <div class="container cuerpo">
        <ol class="breadcrumb">
            <li>Inicio</li>
            <li><a href="#" class="active">Profesor</a></li>
        </ol>
        <input id="pro" type="hidden" name="pro" value="<?php echo $_SESSION['usuario']['Cat']; ?>">

        <div class="container cuerpo">
            <div class="row">
                <div class="col-md-4 col-md-offset-4">
                    <label for="Materias" class="control-label">Materias</label>
                    <select class="form-control" id="MatDoce" name="Mat" required>
                        <?php
                        $Mat=new Profesor();
                        $Mat->seleccionarMateria();
                        ?>
                    </select>
                </div>
            </div>
            <br>
            <div id="Parciales"></div>
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
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="/assets/js/main.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('.mitabla').DataTable({
            "order": [[2, "asc"]],
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
    <script language="JavaScript">
        /*window.onbeforeunload = confirmExit;
        function confirmExit(){
            return "Ha intentado salir de esta pagina. Si ha realizado algun cambio en los campos sin hacer clic en el boton Guardar, los cambios se perderan. Seguro que desea salir de esta pagina? ";
        }*/
</script>
</body>

</html>
