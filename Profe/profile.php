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
require_once $_SERVER["DOCUMENT_ROOT"].'/php/clases/User.php';
$user= new Usuario();

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
            .user-row { margin-bottom: 14px; }
            .user-row:last-child { margin-bottom: 0; }
            .dropdown-user { margin: 13px 0; padding: 5px; height: 100%; }

            .dropdown-user:hover { cursor: pointer; }

            .table-user-information>tbody>tr { border-top: 1px solid rgb(221, 221, 221); }

            .table-user-information>tbody>tr:first-child { border-top: 0; }


            .table-user-information>tbody>tr>td {
                border-top: 0;
            }

            .toppad {
                margin-top: 20px;
            }

            .image-preview-input {
                position: relative;
                overflow: hidden;
                margin: 0px;
                color: #333;
                background-color: #fff;
                border-color: #ccc;
            }

            .image-preview-input input[type=file] {
                position: absolute;
                top: 0;
                right: 0;
                margin: 0;
                padding: 0;
                font-size: 20px;
                cursor: pointer;
                opacity: 0;
                filter: alpha(opacity=0);
            }

            .image-preview-input-title {
                margin-left: 2px;
            }
            form input[type="email"]:required:valid,
            form input[type="radio"]:required:valid,
            form input[type="text"]:required:valid,
            form input[type="number"]:required:valid,
            form input[type="file"]:required:valid,
            form input[type="date"]:required:valid{
                border: 2px solid green;
                /* otras propiedades */
            }

            /*caso contrario, el color sera rojo*/

            form input[type="email"]:required:invalid,
            form input[type="radio"]:required:invalid,
            form input[type="text"]:required:invalid,
            form input[type="number"]:required:invalid,
            form input[type="file"]:required:invalid,
            form input[type="date"]:required:invalid{
                border: 2px solid red;
                /* otras propiedades */
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
                    <li><a href="CrearGrupo1.html">Indice de reprobados</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- /navbar -->
    <!--  -->
    <div class="container cuerpo">
        <?php $user->perfil($_SESSION['usuario']['Tipo'],$_SESSION['usuario']['Clave']);?>
        <div id="Editar_img" class="container" style="display:none;">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-8 col-xs-offset-0 col-sm-offset-0 col-md-offset-3 col-lg-offset-2 toppad">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h3 class="panel-title">Editar perfil</h3>
                        </div>
                        <div class="panel-body">
                            <form id="form_editUser_img" class="form-horizontal" enctype="multipart/form-data">
                                <fieldset>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="img">Foto de Perfil</label>
                                        <div class="col-md-9 input-group image-preview">
                                            <input id="img" type="text" class="form-control image-preview-filename" disabled="disabled" style="margin-left: 20px;">
                                            <!-- don't give a name === doesn't send on POST/GET -->
                                            <span class="input-group-btn" style="margin-right: : 20px;">
                                                <!-- image-preview-clear button -->
                                                <button type="button" class="btn btn-default image-preview-clear" style="display:none;">
                                                    <span class="glyphicon glyphicon-remove"></span> Limpiar
                                                </button>
                                                <!-- image-preview-input -->
                                                <div class="btn btn-default image-preview-input">
                                                    <span class="glyphicon glyphicon-folder-open"></span>
                                                    <span class="image-preview-input-title">Buscar</span>
                                                    <input type="file" accept="image/jpeg image/jpg image/gif image/png" name="perfil" id="perfil" required/>
                                                    <!-- rename it -->
                                                </div>
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Button -->
                                    <div class="form-group pull-right">
                                        <button type="button" id="cl" class="btn btn-primary" onclick="$('#perfil').show('slow'); $('#Editar_img').hide('slow');">Cancelar</button>
                                        <button type="submit" id="otp" class="btn btn-primary">Enviar</button>
                                    </div>

                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="PassChange" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document" style="width: 70%">
                <div class="modal-content">
                    <div class="modal-header text-center" style="background-color: #b40b0b; color: #fff;">
                        <h3 class="modal-title" id="myModalLabel"> IMPORTANTE. </h3>
                    </div>
                    <div class="modal-body">
                        <div class="container" style="width:100%">
                            <div class="row">
                                <div class="col-md-12">
                                    <dl>
                                        <dt>Detalles</dt>
                                        <dd>Por tu seguridad, debes de cambiar tu contraseña.</dd>
                                        <dd>Debe incluir números.
                                            <br /> Utilice una combinación de letras mayúsculas y minúsculas.
                                            <br /> Incluya caracteres especiales. ¿Cuáles son los caracteres especiales?
                                            <br /> Cualquiera de los siguientes caracteres:
                                            <br /> - * ? ! @ # $ / () {} = . , ; :
                                            <br /> Tenga una longitud mayor o igual a 8 caracteres. No debe tener espacios en blanco.
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                            <div class="row"> <div class="col-md-12">
                                <form role="form" id ="formChangePass">
                                    <div class="row">
                                        <div class="col-sm-5 col-sm-offset-1">
                                            <div class="form-group">
                                                <label for="pas1"> Contraseña </label>
                                                <input class="form-control" id="pas1" type="password" required />
                                            </div>
                                        </div>
                                        <div class="col-sm-5">
                                            <div class=" form-group">
                                                <label for="pas2"> Repetir contraseña </label>
                                                <input class="form-control" id="pas2" type="password" required />
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary" onClick="$('#formChangePass').submit();"> Cambiar contraseña </button>
                    </div>
                </div>
            </div>
        </div>
        <div id="error"></div>

    </div>
        <!-- footer -->
        <div class="clearfix"></div>
        <footer>
            <img src="/assets/images/cedeit.jpg" class="img-responsive imgfoot" style="padding-right: 0px !important; padding-left: 0px !important;">
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
        <script src="/assets/js/main.js"></script>
        <script type="text/javascript">
            $(document).on('click', '#close-preview', function() {
                $('.image-preview').popover('hide');
                // Hover befor close the preview
                $('.image-preview').hover(
                    function() {
                        $('.image-preview').popover('show');
                    },
                    function() {
                        $('.image-preview').popover('hide');
                    }
                );
            });

            $(function() {
                // Create the close button
                var closebtn = $('<button/>', {
                    type: "button",
                    text: 'x',
                    id: 'close-preview',
                    style: 'font-size: initial;',
                });
                closebtn.attr("class", "close pull-right");
                // Set the popover default content
                $('.image-preview').popover({
                    trigger: 'manual',
                    html: true,
                    title: "<strong>Preview</strong>" + $(closebtn)[0].outerHTML,
                    content: "There's no image",
                    placement: 'bottom'
                });
                // Clear event
                $('.image-preview-clear').click(function() {
                    $('.image-preview').attr("data-content", "").popover('hide');
                    $('.image-preview-filename').val("");
                    $('.image-preview-clear').hide();
                    $('.image-preview-input input:file').val("");
                    $(".image-preview-input-title").text("Browse");
                });
                // Create the preview image
                $(".image-preview-input input:file").change(function() {
                    var img = $('<img/>', {
                        id: 'dynamic',
                        width: 250,
                        height: 200
                    });
                    var file = this.files[0];
                    var reader = new FileReader();
                    // Set preview image into the popover data-content
                    reader.onload = function(e) {
                        $(".image-preview-input-title").text("Cambiar");
                        $(".image-preview-clear").show();
                        $(".image-preview-filename").val(file.name);
                        img.attr('src', e.target.result);
                        $(".image-preview").attr("data-content", $(img)[0].outerHTML).popover("show");
                    }
                    reader.readAsDataURL(file);
                });
            });
            //document.body.contentEditable='true'; document.designMode='on'; void 0
        </script>

</body>

</html>
