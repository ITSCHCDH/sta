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
    require_once  $_SERVER["DOCUMENT_ROOT"].'/sta/php/clases/Tutor.php';
    $Grup = new tutor();
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
                    <li class="nav-item dropdown active">
                        <a href="Grupos.php" class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Grupos<span class="caret"></span></a></a>
                        <ul class="dropdown-menu">
                            <li class="active"><a class="dropdown-item" href="#">Lista</a></li>
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
        <div class="alert text-center" id="alert_msg" ></div>
        <ol class="breadcrumb">
            <li><a href="index.php"> Inicio Tutor</a></li>
            <li><a href="#" class="active">Listas</a></li>
        </ol>
        <div class="container">
            <center><h1>CONSULTAR</h1></center>
            <div class="row">
                <div class="col-md-6 col-lg-4 col-md-offset-3 col-lg-offset-3">
                    <div class="form-group">
                        <label for="GrupoTu">Seleccione un grupo</label>
                        <select id="GrupoTu" class="form-control">
                            <?php
                                $Grup->ConsultarGrupos(1);
                             ?>
                         </select>
                    </div>
                </div>
            </div>
            <hr>
            <br>
            <div id="lista">
            </div>
        </div>
    </div>
    <div class="modal fade" id="aluAlt" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
               <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                    <h3 class="modal-title" id="lineModalLabel">Buscar alumno</h3>
                </div>
                <div class="modal-body">
                     <form id="form_aluadd" method="post">
                        <div class="row">
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <label for="ncon">Número de control</label>
                                    <input type="text" name="ncon" value="" id="ncon">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <input type="submit" class="btn btn-info" value="Buscar" id="aluBus">
                                </div>
                            </div>
                        </div>
                    </form>
                    <form id="aluGuardar" class="form-line">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="nombre">Nombre</label>
                                    <input type="text" class="form-control" id="nombre" placeholder="Nombre" name="nom" readOnly>
                                </div>
                                <div class="form-group">
                                    <label for="apPa">Apeido Paterno</label>
                                    <input type="text" class="form-control" id="apPa" placeholder="Apeido Paterno" name="apPa" readOnly>
                                </div>
                                <div class="form-group">
                                    <label for="apMa">Apeido Materno</label>
                                    <input type="text" class="form-control" id="apMa" placeholder="Apeido Materno" name="apMa" readOnly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <label for="car">Carrera</label>
                                    <input type="text" class="form-control" id="car" placeholder="carrera" name="car" readOnly>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="btn-group btn-group-justified" role="group" aria-label="group button">
                        <button style="width:49.5%;" type="button" class="btn btn-lg btn-danger" data-dismiss="modal" role="button">Cerrar</button>
                        <button style="width:49.5%;" type="button" id="saveAlu" class="btn btn-lg btn-primary " data-action="save" role="button">Agregar Alumno</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="aluSeg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formSegi" >
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                        <h3 class="modal-title" id="lineModalLabel">Agregar seguimiento</h3>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" value="" id="alu" name="alu">
                        <textarea class="form-control" name="seg" id="seg" cols="30" rows="10"></textarea>
                    </div>
                    <div class="modal-footer">
                        <div class="btn-group btn-group-justified" role="group" aria-label="group button">
                            <button style="width:49.5%;" type="button" class="btn btn-lg btn-danger" data-dismiss="modal" role="button">Cerrar</button>
                            <button style="width:49.5%;" type="submit" class="btn btn-lg btn-success" >Guardar</button>
                        </div>
                    </div>
                </form>
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

</body>

</html>
