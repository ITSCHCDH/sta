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
        header('location:/sta/php/CerrarSesion.php');
    }
    require_once $_SERVER["DOCUMENT_ROOT"].'/sta/php/clases/Alumno.php';
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
    <link href="/sta/assets/css/jquery.loadingModal.min.css" rel="stylesheet">
    <link href="/sta/assets/sweetalert2/sweetalert2.css" rel="stylesheet">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link href="/sta/assets/css/style.css" rel="stylesheet" />
    <link href="/sta/assets/css/styleAlu.css" rel="stylesheet" />
    <style media="screen">
        .table-sortable tbody tr {
            cursor: move;
        }
        #form_AluAcept fieldset:not(:first-of-type) {
            display: none;
        }

        form input[type="email"]:required:valid,
        form input[type="radio"]:required:valid,
        form input[type="combobox"]:required:valid,
        form input[type="text"]:required:valid,
        form input[type="number"]:required:valid,
        form select:required:valid,
        form input[type="date"]:required:valid,
        form input[type="email"]:required:valid,
        form input[type="radio"]:valid,
        form input[type="combobox"]:valid,
        form input[type="text"]:valid,
        form input[type="number"]:valid,
        form select:required:valid,
        form input[type="date"]:valid{
            border: 2px solid green;
            /* otras propiedades */
        }

        /*caso contrario, el color sera rojo*/

        form input[type="email"]:required:invalid,
        form input[type="radio"]:required:invalid,
        form input[type="combobox"]:required:invalid,
        form input[type="text"]:required:invalid,
        form input[type="number"]:required:invalid,
        form select:required:invalid,
        form input[type="date"]:required:invalid,
        form input[type="email"]:required:invalid,
        form input[type="radio"]:invalid,
        form input[type="combobox"]:invalid,
        form input[type="text"]:invalid,
        form input[type="number"]:invalid,
        form select:required:invalid,
        form input[type="date"]:invalid {
            border: 2px solid red;
            /* otras propiedades */
        }

        .bs-float-label {
            position: relative;
            border: 1px solid #ddd;
            margin: 10px;
            padding: 10px;
        }

        .bs-float-label>.float-input {
            margin-top: 10px;
        }

        .bs-float-label>.float-input:focus {
            /*border-color: #f06d06;*/
        }

        .bs-float-label>.float-label {
            position: absolute;
            top: 3px;
            left: 10px;
            background: rgba(255, 255, 255, .32);
            -webkit-transition: top .5s ease-in-out, opacity .5s ease-in-out;
            /* For Safari 3.1 to 6.0 */
            transition: top .5s ease-in-out, opacity .5s ease-in-out;
            opacity: 0;
        }

        .bs-float-label>.float-label.show {
            color: #333;
            top: 1px !important;
            opacity: 1;
            padding: 0 !important;
        }

        .bs-float-label>.float-label.on {
            color: blue;
        }
    </style>
    <script>var pass='setPass'; var userT='Alu'; var user='G16030462'; var caract='si'; </script></head>

<body>
    <div class="alert text-center" id="alert_msg" ></div>
    <!-- box1 -->
    <div id="box1" class="row home">
        <div class="col-md-6 col-sm-6">
            <img src="/sta/assets/images/CABECERA.PNG" class="img-responsive izquierda ciento20" alt="Responsive image">
        </div>
        <div class="col-md-6 col-sm-6">
            <img src="/sta/assets/images/itsch.png" class="img-responsive derecha ciento20" alt="Responsive image">
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
                                <img alt="" src="/sta/assets/images/<?php echo $_SESSION['usuario']['img']? 'Alu/'.$_SESSION['usuario']['img']:'avatar1_small.jpg'; ?>" style="width:33px;">
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
                    <li><a href="#"><span class="glyphicon glyphicon-home" aria-hidden="true"></span></a></li>
                    <li><a href="#">FICHA DE INDENTIFICACIÓN</a></li>
                    <li><a href="upficha.php">SUBIR FICHA ID</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- /navbar -->
    <!--  -->
    <div class="container cuerpo">
        <div class="row">
            <div class="progress">
                <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <form id="form_AluAcept" method="post">
                <fieldset>
                    <h3 class="head text-center">FICHA DE IDENTIFICACIÓN DEL ALUMNO TUTORADO</h3>
                <div class="panel-group" id="personales">
                    <div class="faqHeader">Datos Personales</div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" id="collapseNomA" data-parent="#personales" href="#collapseNom">Nombre Completo</a>
                            </h4>
                        </div>
                        <div id="collapseNom" class="panel-collapse collapse in">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group bs-float-label">
                                            <input type="text" class="form-control float-input" id="dp_nombre" placeholder="Nombre" value="" name="dp_nombre" required readOnly>
                                            <label for="dp_nombre" class="float-label">Nombre(s)</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group bs-float-label">
                                            <input type="text" class="form-control float-input" placeholder="Apellido Paterno" value="" name="dp_ap_paterno" id="dp_ap_paterno" required readOnly>
                                            <label for="dp_ap_paterno" class="float-label">Apellido Paterno</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group bs-float-label">
                                            <input type="text" class="form-control float-input" placeholder="Apellido Materno" value="" name="dp_ap_materno" id="dp_ap_materno" required readOnly>
                                            <label for="dp_ap_materno" class="float-label">Apellido Materno</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" id="fisicA" data-parent="#personales" href="#fisic">Físico</a>
                            </h4>
                        </div>
                        <div id="fisic" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group bs-float-label">
                                            <input type="text" class="form-control float-input" id="dp_edad" placeholder="Edad" value="" name="dp_edad" required>
                                            <label for="dp_edad" class="float-label">Edad</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group bs-float-label">
                                            <input type="text" class="form-control float-input" id="fm_talla" placeholder="Estatura" value="" name="fm_talla" step="0.01" required>
                                            <label for="fm_talla" class="float-label">Estatura <sub>m</sub></label>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group bs-float-label">
                                            <input type="text" class="form-control float-input" id="fm_peso" placeholder="Peso" value="" name="fm_peso" step="0.01" required>
                                            <label for="fm_peso" class="float-label">Peso <sub>kg</sub></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group bs-float-label">
                                            <select id="dp_sexo" name="dp_sexo" class="form-control float-input" required>
                                                <option value="">Seleccione una Opción</option>
                                                <option value="H"selected>Hombre</option>
                                                <option value="M">Mujer</option>
                                            </select>
                                            <label for="dp_sexo" class="float-label">Sexo</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group bs-float-label">
                                            <select class="form-control float-input" id="dp_tipo_sangre" name="dp_tipo_sangre" required>
                                                <option value="" disabled>Seleccione un tipo de sangre</option>
                                                <option value='A+'>A+</option>
                                                <option value='A-'>A-</option>
                                                <option value='B+'>B+</option>
                                                <option value='B-'>B-</option>
                                                <option value='AB+'>AB+</option>
                                                <option value='AB-'>AB-</option>
                                                <option value='O+'>O+</option>
                                                <option value='O-'>O-</option>
                                            </select>
                                        <label for="dp_tipo_sangre" class="float-label">Tipo de sangre</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#personales" id=grupoA href="#grupo">Grupo</a>
                            </h4>
                        </div>
                        <div id="grupo" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group bs-float-label">
                                            <input type="text" class="form-control float-input" id="dp_carrera" placeholder="Carrera" value="" name="dp_carrera" required>
                                            <label for="dp_carrera" class="float-label">Carrera</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group bs-float-label">
                                            <input maxlength="6" type="text" class="form-control float-input" id="dp_grupo" placeholder="Grupo" value="" name="dp_grupo" required>
                                            <label for="dp_grupo" class="float-label">Grupo</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" id="fecnacA" data-parent="#personales" href="#fenac">Fecha de Nacimiento</a>
                            </h4>
                        </div>
                        <div id="fenac" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group bs-float-label">
                                            <input type="date" class="form-control float-input" id="fip_fecha_nac" placeholder="Fecha de Nacimiento" value="" name="fip_fecha_nac" required>
                                            <label for="fip_fecha_nac" class="float-label">Fecha de Nacimiento</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group bs-float-label">
                                            <input type="text" class="form-control float-input" id="fip_lugar_nac" placeholder="Lugar de Nacimiento" value="" name="fip_lugar_nac" required>
                                            <label for="fip_lugar_nac" class="float-label">Lugar de Nacimiento</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" id="edciA" data-parent="#personales" href="#edci">Estado Civil</a>
                            </h4>
                        </div>
                        <div id="edci" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group bs-float-label">
                                            <select class="form-control float-input" id="fip_edo_civil" name="fip_edo_civil" required>
                                                <option disabled>Seleccione una opción</option>
                                                <option value='Soltero'>Soltero</option>
                                                <option value='Casado'>Casado</option>
                                                <option value='Separado'>Separado</option>
                                                <option value='Divorciado'>Divorciado</option>
                                                <option value='Viúdo'>Viúdo</option>
                                                <option value='Unión Libre'>Unión Libre</option>  
                                            </select>
                                            <label for="fip_edo_civil" class="float-label">Estado Civil</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#personales" href="#inputTrabajo">¿ Trabajas ?</a>
                            </h4>
                        </div>
                        <div id="inputTrabajo" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <legend>Indica si o no, si es que trabajas</legend>
                                        <label class="radio-inline">
                                            <input type="radio" name="trAlu" id="trAlu1" value="1" onclick="$('#fip_trabajo').attr('readonly', false)" > Si
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="trAlu" id="trAlu2" value="2" onclick="$('#fip_trabajo').attr('readonly',true).attr('value','')" checked required> No
                                        </label>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group bs-float-label">
                                            <input type="text" name="fip_trabajo"  id="fip_trabajo" class="form-control float-input" value=""/>
                                            <label for="fip_trabajo" class="float-label">En que lugar trabajas</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#personales" href="#domi">Domicilio Actual</a>
                            </h4>
                        </div>
                        <div id="domi" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group bs-float-label">
                                            <input type="text" name="fip_domicilio" id="fip_domicilio" class="form-control float-input" placeholder="Domicilio Actual" value="" required/>
                                            <label for="fip_domicilio" class="float-label">Domicilio Actual</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#personales" href="#casaedo">La casa donde vives es:</a>
                            </h4>
                        </div>
                        <div id="casaedo" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <legend>Elige una opcion de acorde a tu casa</legend>
                                        <label class="radio-inline">
                                        <input type="radio" name="fip_vivienda_tipo" value="Rentada" onclick="$('#casatipo').attr('readonly',true).attr('value','')"> Rentada
                                        </label><label class="radio-inline">
                                        <input type="radio" name="fip_vivienda_tipo" value="Prestada" onclick="$('#casatipo').attr('readonly',true).attr('value','')"> Prestada
                                        </label><label class="radio-inline">
                                        <input type="radio" name="fip_vivienda_tipo" value="Propia" onclick="$('#casatipo').attr('readonly',true).attr('value','')"> Propia
                                        </label><label class="radio-inline">
                                        <input type="radio" name="fip_vivienda_tipo" value="Otro" onclick="$('#casatipo').attr('readonly', false)" required> Otro
                                        </label>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group bs-float-label">
                                                <input type="text" name="fip_vivienda_tipo_otro" id="casatipo" class="form-control float-input" value="" readonly/>
                                                <label for="casatipo" class="float-label">Define tu casa</label>
                                            </div>
                                        </div>   
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#personales" href="#datai2">Datos de identificación 2</a>
                            </h4>
                        </div>
                        <div id="datai2" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group bs-float-label">
                                            <input type="text" name="dp_tel" id="dp_tel" class="form-control float-input" placeholder="Número de Teléfono" value="" required/>
                                            <label for="dp_tel" class="float-label">Número de teléfono</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group bs-float-label">
                                            <input type="email" name="dp_email" id="dp_email" class="form-control float-input" placeholder="Correo Electrónico" value="" required/>
                                            <label for="dp_email" class="float-label">Correo Electrónico</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#personales" href="#nump">Número de personas con las que vives</a>
                            </h4>
                        </div>
                        <div id="nump" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group bs-float-label">
                                            <input type="number" name="fip_per_vivienda" id="fip_per_vivienda" class="form-control float-input" placeholder="Número de personas con las que vives" value="" required/>
                                            <label for="fip_per_vivienda" class="float-label">Número de personas con las que vives</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group bs-float-label">
                                            <input type="text" name="fip_per_parentesco" id="fip_per_parentesco" class="form-control float-input" placeholder="Parentesco" value="" required/>
                                            <label for="fip_per_parentesco" class="float-label">Parentesco con las personas con las que vives</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>        <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#personales" href="#datPad">Datos del Padre</a>
                            </h4>
                        </div>
                        <div id="datPad" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group bs-float-label">
                                            <input type="text" class="form-control float-input" name="nomPa" id="nomPa" placeholder="Nombre" value="">
                                            <label for="nomPa" class="float-label">Nombre</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group bs-float-label">
                                            <input type="text" class="form-control float-input" name="apPa" id="apPa" placeholder="Apellido Paterno" value="">
                                            <label for="SoapParam" class="float-label">Apellido Paterno</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group bs-float-label">
                                            <input type="text" class="form-control float-input" name="amPa" id="amPa" placeholder="Apellido Materno" value="">
                                            <label for="apMa" class="float-label">Apellido Materno</label>
                                        </div>
                                    </div>
                                </div>
                                <br />
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group bs-float-label">
                                            <input type="number" name="EdadPa" name="EdadPa" class="form-control float-input" placeholder="Edad del Padre" value=""/>
                                            <label for="fip_per_parentesco" class="float-label">Edad del Padre</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group bs-float-label">
                                            <input type="text" name="TelPa" name="TelPa" id="TelPa" class="form-control float-input" placeholder="Teléfono del padre" value=""/>
                                            <label for="TelPa" class="float-label">Teléfono del padre</label>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-sm-6">
                                        ¿Trabaja?
                                        <label class="radio-inline">
                                            <input type="radio" name="inlineRadioOptions" name="inlineRadio1" value="1" onclick="$('#trabPatext').attr('readonly', false)" checked="checked"> Si
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="inlineRadioOptions" name="inlineRadio2" value="2" onclick="$('#trabPatext').attr('readonly',true).attr('value','')" checked=""> No
                                        </label>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" name="trabPtext" id="trabPatext" class="form-control float-input"  placeholder="En que trabaja tu Padre" value="" readOnly=""/>
                                        <label for="trabPatext" class="float-label">En que trabaja tu Padre</label>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group bs-float-label">
                                            <input type="text" name="ProfPa" id="ProfPa" class="form-control float-input" placeholder="Profesión del Padre" value=""/>
                                            <label for="ProfPa" class="float-label">Profesión del Padre</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group bs-float-label">
                                            <input type="text" name="DomPa" id="DomPa" class="form-control float-input" placeholder="Domicilio del Padre" value=""/>
                                            <label for="DomPa" class="float-label">Domicilio del Padre</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#personales" href="#datMad">Datos de la Madre</a>
                            </h4>
                        </div>
                        <div id="datMad" class="panel-collapse collapse">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group bs-float-label">
                                        <input type="text" class="form-control float-input" name="nomMa" id="nomMa" placeholder="Nombre" value="">
                                        <label for="nomMa" class="float-label">Nombre</label>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group bs-float-label">
                                        <input type="text" class="form-control float-input" name="apMa" id="amPa" placeholder="Apellido Paterno" value="">
                                        <label for="amPa" class="float-label">Apellido Paterno</label>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group bs-float-label">
                                        <input type="text" class="form-control float-input" name="amMa" id="amMa" placeholder="Apellido Materno" value="">
                                        <label for="apMa" class="float-label">Apellido Materno</label>
                                    </div>
                                </div>
                            </div>
                            <br />
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group bs-float-label">
                                        <input type="number" name="EdadMa" name="EdadMa" id="EdadMa" class="form-control float-input" placeholder="Edad de la Madre" value=""/>
                                        <label for="EdadMa" class="float-label">Edad de la Madre</label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group bs-float-label">
                                        <input type="text" name="TelPa" id="TelMa" class="form-control float-input" placeholder="Teléfono de la Madre" value=""/>
                                        <label for="TelMa" class="float-label">Teléfono de la Madre</label>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-sm-6">
                                    ¿Trabaja?
                                    <label class="radio-inline">
                                        <input type="radio" name="inlineRadioOptions" name="inid=lineRadio1" value="1" onclick="$('#trabMatext').attr('readonly', false)" checked="checked"> Si
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="inlineRadioOptions" name="inlineRadio2" value="2" onclick="$('#trabMatext').attr('readonly',true).attr('value','')" checked=""> No
                                    </label>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group bs-float-label">
                                        <input type="text" name="trabMtext" id="trabMatext" class="form-control float-input"  placeholder="En que trabaja tu Madre" value="" readOnly=""/>
                                        <label for="trabMtext" class="float-label">En que trabaja tu Madre</label>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group bs-float-label">
                                        <input type="text" id="ProfMa" name="ProfMa" class="form-control float-input" placeholder="Profesión de la Madre" value=""/>
                                        <label for="ProfMa" class="float-label">Profesión de la Madre</label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group bs-float-label">
                                        <input type="text" name="DomMa" id="DomMa" class="form-control float-input" placeholder="Domicilio de la Madre" value=""/>
                                        <label for="DomMa" class="float-label">Domicilio de la Madre"</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#personales" href="#relPad">¿Comó es la relación de tus padres?</a>
                            </h4>
                        </div>
                        <div id="relPad" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group bs-float-label">
                                            <div class="form-group bs-float-label">
                                                <input type="text" class="form-control float-input" id="fip_rel_padres" name="fip_rel_padres" id="fip_rel_padres" placeholder="Relación" value="">
                                                <label for="fip_rel_padres" class="float-label">Relación de tus padres</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                                <input type="button" id="nextPerf" name="next" class="btn btn-info" value="Siguiente" />
                </fieldset>
                <fieldset>
                    <h3 class="head text-center">FICHA DE IDENTIFICACIÓN DEL ALUMNO TUTORADO</h3>
                    <div class="panel-group" id="salud">
                        <div class="faqHeader">Datos de Salud</div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#salud" href="#enfermedad">Padeces alguna enfermedad:</a>
                                </h4>
                            </div>
                            <div id="enfermedad" class="panel-collapse">
                                <div class="panel-body">
                                    <div class="row form-group bs-float-label">
                                        <div class="col-sm-4">
                                            <label class="checkbox-inline" for="checkbox1"> <input type="checkbox" name="fm_diabetes" id="checkbox1" value="1"  >  Diabetes </label>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="checkbox-inline" for="checkbox2"> <input type="checkbox" name="fm_hipertencion" id="checkbox2" value="1" >  Hipertensión </label>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="checkbox-inline" for="checkbox3"> <input type="checkbox" name="fm_epilepsia" id="checkbox3" value="1"  >  Epilepsía </label>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="checkbox-inline" for="checkbox4"> <input type="checkbox" name="fm_anorexia" id="checkbox4" value="1"  >  Anorexia </label>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="checkbox-inline" for="checkbox5"> <input type="checkbox" name="fm_bulimia" id="checkbox5" value="1" > Bulimia </label>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="checkbox-inline" for="checkbox6"> <input type="checkbox" name="fm_trans_sexual" id="checkbox6" value="1"  > Enfermedad de Transmisión Sexual </label>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="checkbox-inline" for="checkbox7"> <input type="checkbox" name="fm_depresion" id="checkbox7" value="1"  > Depresión </label>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="checkbox-inline" for="checkbox8"> <input  type="checkbox" name="fm_tristesa" id="checkbox8" value="1"  > Tristeza Profunda </label>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="checkbox-inline" for="checkbox9"> <input type="checkbox" name="otra" id="checkbox9enf" value="otra" onchange="eneableDeseable('checkbox9enf', 'fm_otra')" >  Alguna otra </label>
                                            <input class="form-control float-input" type="text" name="fm_otra" id="fm_otra" value="" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#salud" href="#discapacidad">¿ Cuentas con alguna discapacidad física ?</a>
                                </h4>
                            </div>
                            <div id="discapacidad" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <div class="row"><div class="col-sm-3">
                                            <label class="radio-inline">
                                                <input type="radio" name="fm_discapacidad" id="discRadio1" value="1" onclick="$('#trabtext').attr('readonly', false)" > Si
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="fm_discapacidad" id="discRadio2" value="0" onclick="$('#trabtext').attr('readonly',true)" checked required> No
                                            </label>
                                        </div>
                                        <div class="col-sm-9">Indica cual es:
                                            <div class="row">
                                                <div class="col-sm-2">
                                                    <label class="checkbox-inline" for="inlineRadio1"> <input type="checkbox" name="fm_dis_vista" id="inlineRadio1" value="1">  Vista </label>
                                                </div>
                                                <div class="col-sm-2">
                                                    <label class="checkbox-inline" for="inlineRadio2"> <input type="checkbox" name="fm_dis_oido" id="inlineRadio2" value="1">  Oído </label>
                                                </div>
                                                <div class="col-sm-2">
                                                    <label class="checkbox-inline" for="inlineRadio3"> <input type="checkbox" name="fm_dis_lenguaje" id="inlineRadio3" value="1">  Lenguaje </label>
                                                </div>
                                                <div class="col-sm-2">
                                                    <label class="checkbox-inline" for="discCheck4"> <input type="checkbox" name="fm_dis_motora" id="discCheck4" value="1" >  Motora </label>
                                                </div>
                                                <div class="col-sm-2">
                                                    <label class="checkbox-inline" for="inlineRadio4"> <input type="checkbox" name="inlineRadioOptions" id="inlineRadio4" value="1">  Otro </label>
                                                    <input class="form-control float-input" type="text" name ="fm_dis_otra" value="" placeholder="Otra discapacidad física" readonly="readonly">
                                                </div>
                                            </div>
                                        </div></div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#salud" href="#clinico">¿ Cuentas con algún diagnostico clínico ?</a>
                                </h4>
                            </div>
                            <div id="clinico" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label class="radio-inline">
                                                <input type="radio" name="dx_psicologico" id="inlineRadio1" value="1" onclick="$('#clin').attr('readonly', false);$('#clinT').attr('readonly', false)" > Si
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="dx_psicologico" id="inlineRadio2" value="2" onclick="$('#clin').attr('readonly',true);$('#clinT').attr('readonly',true)" checked> No
                                            </label>
                                        </div>
                                        <div class="col-sm-4">¿Cuál?
                                            <input class="form-control float-input" type="text" name="dx_psicologico" value="" placeholder="¿Cúal?" readonly="readonly" id="clin">
                                        </div>
                                        <div class="col-sm-4">Hace cuanto:
                                            <input class="form-control float-input" type="text" name="dx_psicologico_tm" value="" placeholder="Hace cuanto:" readonly="readonly" id="clinT">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#salud" href="#Medico">¿ Cuentas con algún diagnostico Medico ?</a>
                                </h4>
                            </div>
                            <div id="Medico" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label class="radio-inline">
                                                <input type="radio" name="medRadio" id="medinlineRad1" value="1" onclick="$('#med').attr('readonly', false);$('#medT').attr('readonly', false)" > Si
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="medRadio" id="medinlineRad2" value="2" onclick="$('#med').attr('readonly',true);$('#medT').attr('readonly',true)" checked> No
                                            </label>
                                        </div>
                                        <div class="col-sm-4">¿Cuál?
                                            <input class="form-control float-input" type="text" name="dx_medico" value="" placeholder="Otra discapacidad física" readonly="readonly" id="med">
                                        </div>
                                        <div class="col-sm-4">Hace cuanto:
                                            <input class="form-control float-input" type="text" name="dx_medico_tm" value="" placeholder="Otra discapacidad física" readonly="readonly" id="medT">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                                    <input type="button" name="previous" class="previous btn btn-default" value="Anterior" />
                    <input type="button" id="nextSal" name="next" class="btn btn-info" value="Siguiente" />
                </fieldset>
                <fieldset>
                    
                    <h3 class="head text-center">ÁREAS DE INTEGRACIÓN Y FAMILIAR</h3>
                    <div class="panel-group" id="familia">
                        <div class="faqHeader">DatosFamiliares</div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle collapse in" data-toggle="collapse" data-parent="#familia" href="#tableFam">
                                        Nombra a los integrantes de tu familia (Mamá, Papá, Hermanos, del mayor al menor <strong>incluyéndote a ti</strong>).
                                    </a>
                                </h4>
                            </div>
                            <div id="tableFam" class="panel-collapse collapse in">
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover table-sortable" id="tab_logic">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">Nombre</th>
                                                    <th class="text-center">Fecha de Nacimiento</th>
                                                    <th class="text-center">Sexo</th>
                                                    <th class="text-center">Escolaridad</th>
                                                    <th class="text-center">Parentesco</th>
                                                    <th class="text-center">Actitud con el/ella</th>
                                                    <th class="text-center" style="border-top: 1px solid #ffffff; border-right: 1px solid #ffffff;"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr id='addr0' data-id="0" class="hidden">
                                                    <td data-name="namef">
                                                        <input type="text" name='namef[]' placeholder='Nombre completo' class="form-control float-input" />
                                                    </td>
                                                    <td data-name="nacf">
                                                        <input type="date" name='nacf[]' placeholder='' class="form-control float-input" />
                                                    </td>
                                                    <td data-name="sexf" >
                                                        <select name="sexf[]" class="form-control float-input">
                                                            <option value="">Seleccione una Opcion</option>
                                                            <option value="H">Hombre</option>
                                                            <option value="M">Mujer</option>
                                                        </select>
                                                    </td>
                                                    <td data-name="escf">
                                                        <select name="escf[]" class="form-control float-input">
                                                            <option value="">Seleccione una Opcion</option>
                                                            <option value="1">Sin escolaridad</option>
                                                            <option value="2">Primaria incompleta</option>
                                                            <option value="3">Primaria completa</option>
                                                            <option value="4">Secundaria incompleta</option>
                                                            <option value="5">Secundaria completa</option>
                                                            <option value="6">Media Superior</option>
                                                            <option value="7">Superior</option>
                                                            <option value="8">Maestria</option>
                                                            <option value="9">Doctorado</option>
                                                        </select>
                                                    </td>
                                                    <td data-name="parefam">
                                                        <select name="parefam[]" class="form-control float-input">
                                                            <option value="">Seleccione una Opcion</option>
                                                            <option value="11">Yo</option>
                                                            <option value="1">Madre</option>
                                                            <option value="2">Padre</option>
                                                            <option value="3">Abuela</option>
                                                            <option value="4">Abuelo</option>
                                                            <option value="5">Hermana</option>
                                                            <option value="6">Hermano</option>
                                                            <option value="7">Tía</option>
                                                            <option value="8">Tío</option>
                                                            <option value="9">Yo</option>
                                                            <option value="10">Otro</option>
                                                        </select>
                                                    </td>
                                                    <td data-name="actf">
                                                        <input type="text" name='actf[]' placeholder='Actitud' class="form-control float-input" />
                                                    </td>
                                                    <td data-name="del">
                                                        <button name="del[]" class='btn btn-danger glyphicon glyphicon-remove row-remove' type="button" ></button>
                                                    </td>
                                                </tr>
                                                <tr id="addr1" data-id="1">
                                                    <td data-name="name">
                                                        <input name="namef[]" placeholder="Nombre completo" class="form-control float-input" type="text" value="">
                                                    </td>
                                                    <td data-name="nac">
                                                        <input name="nacf[]" placeholder="" class="form-control float-input" type="date" value="">
                                                    </td>
                                                    <td data-name="sex">
                                                        <select name="sexf[]" class="form-control float-input">
                                                            <option value='H'>Hombre</option>
                                                            <option value='M'>Mujer</option>
                                                        </select>
                                                    </td>
                                                    <td data-name="esc">
                                                        <select name="escf[]" class="form-control float-input">
                                                            <option value='1'>Sin escolaridad</option>
                                                            <option value='2'>Primaria incompleta</option>
                                                            <option value='3'>Primaria completa</option>
                                                            <option value='4'>Secundaria incompleta</option>
                                                            <option value='5'>Secundaria completa</option>
                                                            <option value='6'>Medio Superior</option>
                                                            <option value='7'>Superior</option>
                                                            <option value='8'>Maestria</option>
                                                            <option value='9'>Doctorado</option>
                                                        </select>
                                                    </td>
                                                    <td data-name="pare">
                                                        <select name="parefam[]" class="form-control float-input">
                                                            <option value='11'>Yo</option>
                                                            <option value='1'>Madre</option>
                                                            <option value='2'>Padre</option>
                                                            <option value='3'>Abuela</option>
                                                            <option value='4'>Abuelo</option>
                                                            <option value='5'>Hermana</option>
                                                            <option value='6'>Hermano</option>
                                                            <option value='7'>Tía</option>
                                                            <option value='8'>Tío</option>
                                                            <option value='9'>Otro</option>
                                                        </select>
                                                    </td>
                                                    <td data-name="act">
                                                        <input name="actf[]" placeholder="Actitud" class="form-control float-input" type="text" value="">
                                                    </td>
                                                    <td data-name="del">
                                                        <button name="del[]" class="btn btn-danger glyphicon glyphicon-remove row-remove" type="button" value="" onClick="$('#addr1').remove()"></button>
                                                    </td>
                                                </tr>  
                                            </tbody>
                                        </table>
                                    </div>
                                    <a id="add_row" class="btn btn-default pull-right">Añadir otro</a>
                                </div>
                            </div>
                        </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#familia" href="#fam1">¿Comó es la relación con tu familia?</a>
                            </h4>
                        </div>
                        <div id="fam1" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group bs-float-label">
                                            <textarea name="fiden_relfa" placeholder="" class="form-control float-input" id="fiden_relfa" required> </textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#familia" href="#fam2">¿Existen dificultades?</a>
                            </h4>
                        </div>
                        <div id="fam2" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label class="radio-inline">
                                            <input type="radio" name="dif" id="inlineRadio1" value="1" onclick="$('#fiden_dificultades').attr('readonly', false)" > Si
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="dif" id="inlineRadio2" value="2" onclick="$('#fiden_dificultades').attr('readonly',true)" checked required> No
                                        </label>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group bs-float-label">
                                            <label for="fiden_dificultades">¿De qué tipo?</label>
                                            <textarea name="fiden_dificultades" placeholder="" class="form-control float-input" id="fiden_dificultades" ></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#familia" href="#fam3">¿Qué actitud tienes con tu familia?</a>
                            </h4>
                        </div>
                        <div id="fam3" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group bs-float-label">
                                            <textarea id="fiden_actfa" name="fiden_actfa" placeholder="" class="form-control float-input" required></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#familia" href="#fam4">¿Con quien te sientes mas ligado afectivamente</a>
                            </h4>
                        </div>
                        <div id="fam4" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="col-sm-6">
                                            <select  id="fiden_ligue" name="fiden_ligue" class="form-control float-input" required>
                                                <option value="" disabled> Selecciona una opcion</option>
                                                <option value='1'>Madre</option>
                                                <option value='2'>Padre</option>
                                                <option value='3'>Abuela</option>
                                                <option value='4'>Abuelo</option>
                                                <option value='5'>Hermana</option>
                                                <option value='6'>Hermano</option>
                                                <option value='7'>Tía</option>
                                                <option value='8'>Tío</option>
                                                <option value='9'>Otro</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group bs-float-label">
                                                <label for="fiden_ligue_T">Especifica por qué</label>
                                                <textarea id="fiden_ligue_T" name="fiden_ligue_T" placeholder="" class="form-control float-input" required></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#familia" href="#fam5">¿Quién se ocupa más en tu educación?</a>
                            </h4>
                        </div>
                        <div id="fam5" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group bs-float-label">
                                            <textarea id="fiden_edu" name="fiden_edu" placeholder="" class="form-control float-input" required></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#familia" href="#fam6">¿Quién ha influido más en tu decisión para estudiar esta carrera?</a>
                            </h4>
                        </div>
                        <div id="fam6" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group bs-float-label">
                                            <textarea id="fiden_influ" name="fiden_influ" placeholder="" class="form-control float-input" required></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#familia" href="#fam7">Consideras importante facilitar algún otro dato sobre tu ambiente familiar</a>
                            </h4>
                        </div>
                        <div id="fam7" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group bs-float-label">
                                            <textarea name="fiden_otro_dato" placeholder="" class="form-control float-input"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>                    
                <input type="button" name="previous" class="previous btn btn-default" value="Anterior" />
                    <input type="button" id="nextFami" name="next" class="btn btn-info" value="Siguiente" />
                </fieldset>
                <fieldset>
                    <h3 class="head text-center">ÁREAS PERSONAL Y SOCIAL </h3>
                <div class="panel-group" id="social">
                    <div class="faqHeader">Datos sobre tu vida social</div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapse in" data-toggle="collapse" data-parent="#social" href="#soc0">
                                    ¿Cómo es tu relacion con tus compañeros?
                                </a>
                            </h4>
                        </div>
                        <div id="soc0" class="panel-collapse collapsed in">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <select id="rel_comp" name="rel_comp" class="form-control float-input" required>
                                            <option value=''>Selecciona una opcion</option>
                                            <option value='1'>Buena</option>
                                            <option value='2'>Regular</option>
                                            <option value='3'>Excelente</option>
                                            <option value='4'>Mala</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group bs-float-label">
                                            <label for="rel_com_t">¿Por qué?</label>
                                            <textarea id="rel_comp_t" name="rel_comp_t" placeholder="" class="form-control float-input" ></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#social" href="#soc1">¿Comó es la relación con tus amigos?</a>
                            </h4>
                        </div>
                        <div id="soc1" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <select id="rel_ami" name="rel_ami" class="form-control float-input">
                                            <option value=''>Seleccione una opcion</option>
                                            <option value='1'>Buena</option>
                                            <option value='2'>Regular</option>
                                            <option value='3'>Excelente</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group bs-float-label">
                                            <label for="rel_ami_t">¿Por qué?</label>
                                            <textarea id="rel_ami_t" name="rel_ami_t" placeholder="" class="form-control float-input"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#social" href="#soc2">¿Tienes pareja?</a>
                            </h4>
                        </div>
                        <div id="soc2" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-2">
                                        <label class="radio-inline">
                                            <input type="radio" name="alu_par" id="alu_par1" value="1" onclick="$('#rel_alu_par').attr('readonly', false);$('#parejaText').attr('readonly', false); " > SI
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="alu_par" id="alu_par2" value="0" onclick="$('#rel_alu_par').attr('readonly',true);$('#parejaText').attr('readonly',true);" > NO
                                        </label>
                                    </div>
                                    <div class="col-sm-5">
                                        <select name="rel_alu_par" id="rel_alu_par" class="form-control float-input" >
                                            <option value=''>Selecciona una opcion</option>
                                            <option value='1'>Buena</option>
                                            <option value='2'>Regular</option>
                                            <option value='3'>Excelente</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-5">
                                        <div class="form-group bs-float-label">
                                            <label for="parejaText">¿Comó es la relación con tu pareja?</label>
                                            <textarea id="parejaText" name="rel_alu_par_t" placeholder="" class="form-control float-input" ></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#social" href="#soc3">¿Como es la relación con tus profesores?</a>
                            </h4>
                        </div>
                        <div id="soc3" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <select  id="rel_pro" name="rel_pro" class="form-control float-input" >
                                            <option value=''>Selecciona una opcion</option>
                                            <option value='1'>Buena</option>
                                            <option value='2'>Regular</option>
                                            <option value='3'>Excelente</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group bs-float-label">
                                            <label for="rel_pro_t">¿Por qué?</label>
                                            <textarea id="rel_pro_t" name="rel_pro_t" placeholder="" class="form-control float-input"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#social" href="#soc4">¿Comó es tu relación con las autoridades académicas'</a>
                            </h4>
                        </div>
                        <div id="soc4" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <select name="rel_aut_aca" class="form-control float-input"  id="rel_aut_aca">
                                        <option value=''>Selecciona una opcion</option>
                                        <option value='1'>Buena</option>
                                        <option value='2'>Regular</option>
                                        <option value='3'>Excelente</option>
                                            </select>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group bs-float-label">
                                            <label for="rel_aut_aca_t">¿Por qué?</label>
                                            <textarea id="rel_aut_aca_t" name="rel_aut_aca_t" placeholder="" class="form-control float-input"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#social" href="#soc5">¿Qué haces en tu tiempo libre?</a>
                            </h4>
                        </div>
                        <div id="soc5" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group bs-float-label">
                                            <textarea id="alu_tlibre" name="alu_tlibre" placeholder="" class="form-control float-input" required></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#social" href="#soc6">¿Cuál es tu actividad recreativa?</a>
                            </h4>
                        </div>
                        <div id="soc6" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group bs-float-label">
                                            <textarea id="alu_act_rec" name="alu_act_rec" placeholder="" class="form-control float-input" required></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#social" href="#soc7">¿Cuáles son tus planes inmediatos?</a>
                            </h4>
                        </div>
                        <div id="soc7" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group bs-float-label">
                                            <textarea id="alu_pl_inme" name="alu_pl_inme" placeholder="" class="form-control float-input" required></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#social" href="#soc8">¿Cuáles son tus metas en la vida?</a>
                            </h4>
                        </div>
                        <div id="soc8" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group bs-float-label">
                                            <textarea id="alu_metas" name="alu_metas" placeholder="" class="form-control float-input" required></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#social" href="#soc9">YO SOY...</a>
                            </h4>
                        </div>
                        <div id="soc9" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group bs-float-label">
                                            <textarea id="alu_soy" name="alu_soy" placeholder="" class="form-control float-input" required>gfd</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#social" href="#soc10">MI CARÁCTER ES...?</a>
                            </h4>
                        </div>
                        <div id="soc10" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group bs-float-label">
                                            <textarea id="alu_caracter" name="alu_caracter" placeholder="" class="form-control float-input" required></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#social" href="#soc11">A MÍ ME GUSTA QUE..</a>
                            </h4>
                        </div>
                        <div id="soc11" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group bs-float-label">
                                            <textarea id="alu_gusto" name="alu_gusto" placeholder="" class="form-control float-input" required></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#social" href="#soc12">YO ASPIRO EN LA VIDA...</a>
                            </h4>
                        </div>
                        <div id="soc12" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group bs-float-label">
                                            <textarea id="alu_aspira" name="alu_aspira" placeholder="" class="form-control float-input" required></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#social" href="#soc13">YO TENGO MIEDO QUE...</a>
                            </h4>
                        </div>
                        <div id="soc13" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group bs-float-label">
                                            <textarea id="alu_miedo" name="alu_miedo" placeholder="" class="form-control float-input" required></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                                <input type="button" name="previous" class="previous btn btn-default" value="Anterior" />
                    <input type="button" id="nextSoc" name="next" class="btn btn-info" value="Siguiente" />
                </fieldset>
                <fieldset>
                    <div class="row">
                        <div class="col-sm-6 col-sm-offset-3">
                            <p>
                                <textarea id="rules" readonly class="text-justify">AUTORIZO AL DEPARTAMENTO DE TUTORÍAS Y SERVICIOS PSICOPEDAGÓGICOS HACER USO DE ESTA INFORMACIÓN EN CASO DE SER REQUERIDA CON EL COMPROMISO DE QUE ESTA INFORMCACION ESTARAÁ BAJO LA CONFIDENCIALIDAD DE LA MISMA ÁREA.</textarea>
                            </p>
                            <p>
                                <input type="checkbox" id="agree" name="accept" />
                                <label for="agree">Yo Acepto</label>
                                <input type="submit" id="aceptoAlumno" value="GUARDAR Y FIRMAR" onclick="$('#form_AluAcept').submit();"/>
                            </p>
                        </div>
                    </div>
                    <input type="button" name="previous" class="previous btn btn-default" value="Anterior" />
                </fieldset>
            </form>
            <p class="narrow text-center">
                La información proporcionada es exclusiva del Departamento de Tutorias y Servicios Psicopedagógicos.
            </p>
        </div>
    </div>
    <div id="error"></div>
        <!-- footer -->
    <div class="clearfix"></div>
    <footer>
        <img src="/sta/assets/images/cedeit.jpg" class="img-responsive imgfoot" style="padding-right: 0px !important; padding-left: 0px !important;">
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
    <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.10.4/jquery-ui.min.js" integrity="sha256-oTyWrNiP6Qftu4vs2g0RPCKr3g1a6QTlITNgoebxRc4=" crossorigin="anonymous"></script>
    <script src="/sta/assets/js/jquery.loadingModal.min.js"></script>
    <script src="/sta/assets/sweetalert2/sweetalert2.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="/sta/assets/js/main.js"></script>
    <script src="/sta/assets/js/Alumnos/Ficha.js"></script>
    

</body>

</html>
