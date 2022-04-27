<?php
    session_start();

    if (isset($_SESSION['usuario']['Tipo'])) {
        if ($_SESSION['usuario']['Tipo'] != "Alu") {
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
            }elseif ($_SESSION['usuario']['Tipo']=="Tutoria"){
                header('location: ' .$_SERVER["DOCUMENT_ROOT"].'/ATutor/');
            }elseif ($_SESSION['usuario']['Tipo']=="Medic"){
                header('location: ' .$_SERVER["DOCUMENT_ROOT"].'/Medico/');
            }
        }
    }else{
        header('location:/php/CerrarSesion.php');
    }
    require_once $_SERVER["DOCUMENT_ROOT"].'/php/clases/Alumno.php';
    $Alumno1 = new Alumno();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="author" content="Jorge Armando Rocha Mendoza" />
    <title>--STA--</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <!-- css -->
    <!--link href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" rel="stylesheet"-->
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link href="/assets/css/style.css" rel="stylesheet" />
    <link href="/assets/css/styleAlu.css" rel="stylesheet" />
    <style media="screen">
        .iframe-container {
            padding-bottom: 60%;
            padding-top: 30px; height: 0; overflow: hidden;
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
    <div class="alert text-center" id="alert_msg" ></div>
    <!-- box1 -->
    <div id="box1" class="row home">
        <div class="col-md-6 col-sm-6">
            <img src="/assets/images/CABECERA.PNG" class="img-responsive izquierda ciento20" alt="Responsive image">
        </div>
        <div class="col-md-6 col-sm-6">
            <img src="/assets/images/itsch.png" class="img-responsive derecha ciento20" alt="Responsive image">
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
                                <img alt="" src="/assets/images/<?php echo $_SESSION['usuario']['img']? 'Alu/'.$_SESSION['usuario']['img']:'avatar1_small.jpg'; ?>" style="width:33px;">
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
                    <li><a href="index.php">FICHA DE INDENTIFICACIÓN</a></li>
                    <li><a href="#">SUBIR FICHA ID</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- /navbar -->
    <!--  -->
    <div class="container cuerpo">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-8 col-xs-offset-0 col-sm-offset-0 col-md-offset-3 col-lg-offset-2 toppad">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h3 class="panel-title">Editar perfil</h3>
                        </div>
                        <div class="panel-body">
                            <form id="form_upPDF" class="form-horizontal" enctype="multipart/form-data">
                                <fieldset>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="img">Documento PDF de tu ficha de identificacion</label>
                                        <div class="col-md-9 input-group image-preview">
                                            <span class="input-group-btn" style="margin-right: : 20px;">
                                                <!-- image-preview-clear button -->
                                                <button type="button" class="btn btn-default image-preview-clear" style="display:none;">
                                                    <span class="glyphicon glyphicon-remove"></span> Limpiar
                                                </button>
                                                <!-- image-preview-input -->
                                                <div class="btn btn-default image-preview-input">
                                                    <span class="glyphicon glyphicon-folder-open"></span>
                                                    <span class="image-preview-input-title">Buscar</span>
                                                    <input type="file" accept="application/pdf" name="ficha" id="fichapdf" required/>
                                                    <!-- rename it -->
                                                </div>
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Button -->
                                    <div class="form-group pull-right">
                                        <button type="submit" id="otp" class="btn btn-primary">Enviar</button>
                                    </div>

                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php $Alumno1->showPDF($_SESSION['usuario']['Clave']); ?>
        </div>
    </div>
    <div id="error"></div>
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
    <script src="https://code.jquery.com/ui/1.10.4/jquery-ui.min.js" integrity="sha256-oTyWrNiP6Qftu4vs2g0RPCKr3g1a6QTlITNgoebxRc4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="/assets/js/main.js"></script>
    <script type="text/javascript">
        (function(a){a.createModal=function(b){defaults={title:"",message:"Your Message Goes Here!",closeButton:true,scrollable:false};var b=a.extend({},defaults,b);var c=(b.scrollable===true)?'style="max-height: 420px;overflow-y: auto;"':"";html='<div class="modal fade" id="myModal">';html+='<div class="modal-dialog">';html+='<div class="modal-content">';html+='<div class="modal-header">';html+='<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>';if(b.title.length>0){html+='<h4 class="modal-title">'+b.title+"</h4>"}html+="</div>";html+='<div class="modal-body" '+c+">";html+=b.message;html+="</div>";html+='<div class="modal-footer">';if(b.closeButton===true){html+='<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>'}html+="</div>";html+="</div>";html+="</div>";html+="</div>";a("body").prepend(html);a("#myModal").modal().on("hidden.bs.modal",function(){a(this).remove()})}})(jQuery);

        /*
        * Here is how you use it
        */
        $(function(){

        })
    </script>

</body>

</html>
