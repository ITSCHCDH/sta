<?php
    session_start();

    if (isset($_SESSION['usuario'])) {
        if ($_SESSION['usuario']['Tipo'] != "Tutoria") {

            if ($_SESSION['usuario']['Tipo']=="Admin"){
                header('location: '.$_SERVER["DOCUMENT_ROOT"]."/Admin/");
            }elseif ($_SESSION['usuario']['Tipo']=="Profe"){
                header('location: '.$_SERVER["DOCUMENT_ROOT"].'/Profe/');
            }elseif ($_SESSION['usuario']['Tipo']=="Tutor"){
                header('location: '.$_SERVER["DOCUMENT_ROOT"].'/Tutor/');
            }elseif ($_SESSION['usuario']['Tipo']=="Jefe"){
                header('location: '.$_SERVER["DOCUMENT_ROOT"].'/JefeCar/');
            }elseif ($_SESSION['usuario']['Tipo']=="Dire"){
                header('location: '.$_SERVER["DOCUMENT_ROOT"].'/Direc/');
            }elseif ($_SESSION['usuario']['Tipo']=="Medic"){
                header('location: ' .$_SERVER["DOCUMENT_ROOT"].'/Medico/');
            }elseif ($_SESSION['usuario']['Tipo']=="Alu"){
                header('location: ' .$_SERVER["DOCUMENT_ROOT"].'/Alumno/');
            }
        }
    }else{
        header('location:/php/CerrarSesion.php');
    }
    require_once  $_SERVER["DOCUMENT_ROOT"].'/php/clases/areaPS.php';
    $Grup = new ps();
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
    <style media="screen">
    .iframe-container {
        padding-bottom: 60%;
        padding-top: 30px;
        height: 0;
        overflow: hidden;
    }

    .iframe-container iframe,
    .iframe-container object,
    .iframe-container embed {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }
    </style>
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
                    <li><a href="asing.php">Asignar Grupos</a></li>
                    <li><a href="Formatos.php">Formatos</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- /navbar -->

    <!--  -->
    <div class="container cuerpo">
        <div class="alert text-center" id="alert_msg"></div>
        <ol class="breadcrumb">
            <li><a href="index.php"> Inicio Tutor</a></li>
            <li><a href="#" class="active">Formatos</a></li>
        </ol>
        <div class="container">
            <center>
                <h1>CONSULTAR</h1>
            </center>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="PScarrera2" class="col-sm-2 control-label">Carreras</label>
                        <select class="form-control" id="PScarrera2" name="PScarrera2" required>
                            <option selected="selected" disabled="disabled">-Elija la carrera-</option>
                            <option value="6">INGENIERÍA BIOQUÍMICA</option>
                            <option value="7">INGENIERÍA EN GESTIÓN EMPRESARIAL</option>
                            <option value="2">INGENIERÍA INDUSTRIAL</option>
                            <option value="5">INGENIERÍA MACARRÓNICA</option>
                            <option value="10">INGENIERÍA NANOTECNOLOGIA</option>
                            <option value="3">INGENIERÍA EN SISTEMAS COMPUTACIONES</option>
                            <option value="9">INGENIERÍA EN TECNOLOGÍAS DE LA INFORMACIÓN Y COMUNICACIÓN</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="GrupoTuPDF2" class="col-sm-2 control-label">Generaciones</label>
                        <select class="form-control" name="GrupoTuPDF2" id="GrupoTuPDF2">
                            <option selected>Seleccione una generación</option>
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
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Ficha de identificación</h4>
                </div>
                <div class="modal-body">

                    <embed id='pdf' src="/pdf/Article List.pdf" frameborder="0" width="100%" height="400px">

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>

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
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
        integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous">
    </script>
    <script src="/assets/js/main.js"></script>
    <script type="text/javascript">
    $(document).ready(function() {
        $('#mitabla').DataTable({
            "order": [
                [0, "asc"]
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



    function ActTab() {
        ActTabAluPDF();
    }

    $("#PScarrera2").on("change", function() {
        var idC = $("#PScarrera2").val();
        var url = "/php/init/ps_grupoXCarrera2.php";
        $.ajax({
            type: "POST",
            url: url,
            data: {
                Car: idC,
            },
            success: function(data) {
                $("#GrupoTuPDF2 option").remove();
                $("#GrupoTuPDF2").append(data);
            },
        });
    });
    $("#GrupoTuPDF2").on("change", function() {
        var url = "/php/init/GROUP_ListarPDF2.php";
        $.ajax({
            type: "POST",
            url: url,
            data: {
                car: $("#PScarrera2").val(),
                anio: $("#GrupoTuPDF2").val(),
            },
            success: function(data) {
                $("#lista").empty();
                $("#lista").append(data);
                $("#mitabla").DataTable({
                    order: [
                        [1, "asc"]
                    ],
                    language: {
                        lengthMenu: "Mostrar _MENU_ registros por pagina",
                        info: "Mostrando pagina _PAGE_ de _PAGES_",
                        infoEmpty: "No hay registros disponibles",
                        infoFiltered: "(filtrada de _MAX_ registros)",
                        loadingRecords: "Cargando...",
                        processing: "Procesando...",
                        search: "Buscar:",
                        zeroRecords: "No se encontraron registros coincidentes",
                        paginate: {
                            next: "Siguiente",
                            previous: "Anterior",
                        },
                    },
                });
            },
        });
        return false;
    });
    </script>
</body>

</html>