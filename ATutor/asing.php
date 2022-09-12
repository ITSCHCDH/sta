<?php
    session_start();

    if (isset($_SESSION['usuario'])) {
        if ($_SESSION['usuario']['Tipo'] != "Tutoria") {
            if ($_SESSION['usuario']['Tipo']=="Admin"){
                header('location: ' .$_SERVER["DOCUMENT_ROOT"].'/Admin/');
            }elseif ($_SESSION['usuario']['Tipo']=="Profe"){
                header('location: ' .$_SERVER["DOCUMENT_ROOT"].'/Profe/');
            }elseif ($_SESSION['usuario']['Tipo']=="Tutor"){
                header('location: ' .$_SERVER["DOCUMENT_ROOT"].'/Tutor/');
            }elseif ($_SESSION['usuario']['Tipo']=="Jefe"){
                header('location: ' .$_SERVER["DOCUMENT_ROOT"].'/JefeCar/');
            }elseif ($_SESSION['usuario']['Tipo']=="Dire"){
                header('location: ' .$_SERVER["DOCUMENT_ROOT"].'/Direc/');
            }elseif ($_SESSION['usuario']['Tipo']=="Alu"){
                header('location: ' .$_SERVER["DOCUMENT_ROOT"].'/Alumnos/');
            }elseif ($_SESSION['usuario']['Tipo']=="Medic"){
                header('location: ' .$_SERVER["DOCUMENT_ROOT"].'/Medico/');
            }
        }
    }else{
        header('location:/php/CerrarSesion.php');
    }
    require_once $_SERVER["DOCUMENT_ROOT"].'/php/clases/areaPS.php';
    $PS = new ps();
 ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="author" content="Jorge Armando Rocha Mendoza" />
    <title>--STA--</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <!-- css -->
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
        integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet" />
    <?php echo "<script>var pass='".$_SESSION['usuario']['passw']."'; var userT='".$_SESSION['usuario']['Tipo']."'; var user='".$_SESSION['usuario']['Clave']."'; </script>";
 ?>
</head>

<body>

    <!-- box1 -->
    <div id="box1" class="row home">
        <div class="col-md-6 col-sm-6">
            <a href="index.php"><img src="/assets/images/CABECERA.PNG" class="img-responsive izquierda ciento20"
                    alt="Responsive image"></a>
        </div>
        <div class="col-md-6 col-sm-6">
            <a href="http://www.itsch.edu.mx/"><img src="/assets/images/itsch.png"
                    class="img-responsive derecha ciento20" alt="Responsive image"></a>
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
                                <img alt=""
                                    src="/assets/images/<?php echo $_SESSION['usuario']['img']? 'Users/'.$_SESSION['usuario']['img']:'avatar1_small.jpg'; ?>"
                                    style="width:33px;">
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
                    <li><a href="adminTutor.php">Administrar Tutores</a></li>
                    <li><a href="Formatos.php">Formatos</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- /navbar -->
    <div class="alert text-center" id="alert_msg"></div>
    <!--  -->
    <div class="container cuerpo">
        <ol class="breadcrumb">
            <li><a href="index.php">Inicio</a></li>
            <li><a href="#" class="active">Admin Tutores</a></li>
        </ol>

        <div class="" style="padding-left: 0 !important;">
            <center>
                <h1>Administracion de tutores</h1>
            </center>
            <div class="panel-heading" style="height:30px;">
                <div class="pull-right">
                    <button type="button" data-toggle="modal" data-target="#asingGroup"
                        class="btn btn-primary center-block">Agregar</button>
                </div>
                <div class="panel-title">Área de Tutorías</div>
            </div>
            <br>
            <div class="panel panel-primary">
                <div class="panel-heading" style="height:30px;">
                    <h3 class="panel-title">Lista de Grupos</h3>
                </div>
                <div class="panel-body">
                    <table class="display responsive" id='mitabla' style="display: inline-table;">
                        <thead>
                            <th>Nombre</th>
                            <th>Semestre</th>
                            <th>Carrera</th>
                            <th>Identificador</th>
                            <th>Tutor</th>
                            <th>Acciones</th>
                        </thead>
                        <tbody>
                            <?php
                                $PS->tuto_crud2();
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <div class="modal fade" id="asingGroup" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog" style=" margin: 100px auto;">
            <div class="modal-content">
                <form id="form_AltaGrupoTutor" method="post">
                    <div class="modal-header" style="background:#428bca ;">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span
                                class="sr-only">Close</span></button>
                        <h3 class="modal-title" id="lineModalLabel">Asignar Grupos</h3>
                    </div>
                    <div class="modal-body">
                        <!-- content goes here -->
                        <div class="form-group">
                            <label for="nomcat">Asesor</label>
                            <input type="text" class="form-control" id="nomcat" placeholder="Nombre" name="nombre"
                                list="catedraticos">
                            <datalist id="catedraticos">
                                <?php $PS->tutores(); ?>
                            </datalist>
                            <input type="hidden" name="clavcat" value="" id="clavcat">
                        </div>
                        <div class="form-group">
                            <label for="Carreras" class="col-sm-2 control-label">Carrera</label>
                            <select class="form-control" id="Carreras" name="carrera" required>
                                <option selected="selected" disabled="disabled">-Elija la carrera-</option>
                               <option value="06">INGENIERÍA BIOQUÍMICA</option>
                                <option value="38">INGENIERÍA EN GESTIÓN EMPRESARIAL</option>
                                <option value="16">INGENIERÍA INDUSTRIAL</option>
                                <option value="35">INGENIERÍA MACARRÓNICA</option>
                                <option value="NANO">INGENIERÍA NANOTECNOLOGIA</option>
                                <option value="07">INGENIERÍA EN SISTEMAS COMPUTACIONES</option>
                                <option value="TICS">INGENIERÍA EN TECNOLOGÍAS DE LA INFORMACIÓN Y COMUNICACIÓN</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="Semestre" class="col-sm-2 control-label">Semestre</label>
                            <select class="form-control" id="Semestre" name="semestre" required>
                                <?php
                                    require_once $_SERVER["DOCUMENT_ROOT"].'/php/Semestre.php';
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="Grupo" class="col-sm-2 control-label">Grupo</label>
                            <select class="form-control Grupo" id="Grupo" name="Grupo">
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="btn-group btn-group-justified" role="group" aria-label="group button">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-danger" data-dismiss="modal"
                                    role="button">Cerrar</button>
                            </div>
                            <div class="btn-group btn-delete hidden" role="group">
                                <button type="button" id="delImage" class="btn btn-warning" data-dismiss="modal"
                                    role="button">Delete</button>
                            </div>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-primary " id="gpo_save"
                                    role="button">Guardar</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editgGroup" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog" style=" margin: 100px auto;">
            <div class="modal-content">
                <form id="form_EditGrupoTutor" method="post">
                    <div class="modal-header" style="background:#428bca ;">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span
                                class="sr-only">Close</span></button>
                        <h3 class="modal-title" id="lineModalLabel">Editar el asesor</h3>
                    </div>
                    <div class="modal-body">
                        <!-- content goes here -->
                        <div class="form-group">
                            <label for="nomcat">Asesor</label>
                            <input type="text" class="form-control" id="nomcatEdit" placeholder="Nombre" name="nombre"
                                list="catedraticos">
                            <datalist id="catedraticosEdit">
                                <?php $PS->tutores(); ?>
                            </datalist>
                            <input type="hidden" name="clavcatEdit" value="" id="clavcatEdit">
                        </div>
                        <div class="form-group">
                            <label for="grupEdit" class="col-sm-2 control-label">Grupo</label>
                            <input type="text" name="grupoEdit" value="" id="grupEdit" class="form-control"
                                placeholder="Grupo" readonly>
                            <input type="hidden" name="idGrupoEdit" value="" id="idGrupEdit" class="form-control"
                                placeholder="Grupo">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="btn-group btn-group-justified" role="group" aria-label="group button">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-danger" data-dismiss="modal"
                                    role="button">Cerrar</button>
                            </div>
                            <div class="btn-group btn-delete hidden" role="group">
                                <button type="button" id="delImage" class="btn btn-warning" data-dismiss="modal"
                                    role="button">Delete</button>
                            </div>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-primary " id="gpoE_save"
                                    role="button">Guardar</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editGroup2" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog" style=" margin: 100px auto;">
            <div class="modal-content">
                <form id="form_AltaGrupoTutor" method="post">
                    <div class="modal-header" style="background:#428bca ;">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span
                                class="sr-only">Close</span></button>
                        <h3 class="modal-title" id="lineModalLabel">Editar Grupo</h3>
                    </div>
                    <div class="modal-body">
                        <!-- content goes here -->
                        <div class="form-group">
                            <label for="nomcat">Asesor</label>
                            <input type="text" class="form-control" id="Snomcat" placeholder="Nombre" name="nombre"
                                list="Scatedraticos">
                            <datalist id="Scatedraticos">
                                <?php $PS->tutores(); ?>
                            </datalist>
                            <input type="hidden" name="clavcat" value="" id="clavcat">
                        </div>
                        <div class="form-group">
                            <label for="Carreras" class="col-sm-2 control-label">Carrera</label>
                            <select class="form-control" id="SCarreras" name="carrera" required>
                                <option selected="selected" disabled="disabled">-Elija la carrera-</option>
                                <option value="06">INGENIERÍA BIOQUÍMICA</option>
                                <option value="38">INGENIERÍA EN GESTIÓN EMPRESARIAL</option>
                                <option value="16">INGENIERÍA INDUSTRIAL</option>
                                <option value="35">INGENIERÍA MACARRÓNICA</option>
                                <option value="NANO">INGENIERÍA NANOTECNOLOGIA</option>
                                <option value="07">INGENIERÍA EN SISTEMAS COMPUTACIONES</option>
                                <option value="TICS">INGENIERÍA EN TECNOLOGÍAS DE LA INFORMACIÓN Y COMUNICACIÓN</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="Semestre" class="col-sm-2 control-label">Semestre</label>
                            <select class="form-control" id="SSemestre" name="semestre" required>
                                <?php
                                    require_once $_SERVER["DOCUMENT_ROOT"].'/php/Semestre.php';
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="Grupo" class="col-sm-2 control-label">Grupo</label>
                            <select class="form-control Grupo" id="SGrupo" name="Grupo">
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="btn-group btn-group-justified" role="group" aria-label="group button">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-danger" data-dismiss="modal"
                                    role="button">Cerrar</button>
                            </div>
                            <div class="btn-group btn-delete hidden" role="group">
                                <button type="button" id="delImage" class="btn btn-warning" data-dismiss="modal"
                                    role="button">Delete</button>
                            </div>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-primary " id="gpo_save"
                                    role="button">Guardar</button>
                            </div>
                        </div>
                    </div>
                </form>
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
                        Av. Ing. Carlos Rojas Gutiérrez 2120 | Fraccionamiento Valle de la Herradura | Ciudad Hidalgo
                        Michoacán | Tel. (786) 154-90-00
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
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
        integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous">
    </script>
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="/assets/js/main.js"></script>
    <script type="text/javascript">
    $(document).ready(function() {
        $('#mitabla').DataTable({
            "order": [
                [2, "asc"]
            ],
            "language": {
                "lengthMenu": "Mostrar _MENU_ registros por pagina",
                "info": "Mostrando pagina _PAGE_ de _PAGES_",
                "infoEmpty": "No hay registros disponibles",
                "infoFiltered": "(filtrada de _MAX_ registros)",
                "loadingRecords": "Cargando...",
                "processing": "Procesando...",
                "search": "Buscar:",
                "zeroRecords": "No se encontraron registros coincidentes",
                "paginate": {
                    "next": "Siguiente",
                    "previous": "Anterior"
                },
            }
        });
    });

    function GPO_Eliminar(id, nom, cat) {
        var opcion = confirm("Desea eliminar el grupo  " + nom + " asignado a " + cat);
        if (opcion == true) {
            jQuery
                .ajax({
                    url: "/php/init/GPO_Eliminar.php",
                    type: "POST",
                    dataType: "json",
                    data: {
                        gpo: id,
                    },
                })
                .done(function(res) {
                    sendMessageR(res.text, res.class);
                    if (res.err == false) {
                        setTimeout(function() {
                            location.reload();
                        }, 3000);
                    }
                })
                .fail(function(resp) {
                    console.log(resp.responseText);
                })
                .always(function() {
                    console.log("complete");
                });
        }
    }
    </script>
</body>

</html>