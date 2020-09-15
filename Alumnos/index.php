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
    <?php echo "<script>var pass='".$_SESSION['usuario']['passw']."'; var userT='".$_SESSION['usuario']['Tipo']."'; var user='".$_SESSION['usuario']['Clave']."'; var caract='".$_SESSION['usuario']['caract']."'; </script>";
 ?>
</head>

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
                    <?php $Alumno1->aluFiPerfil($_SESSION['usuario']['Clave'], $_SESSION['usuario']['caract']);?>
                    <input type="button" id="nextPerf" name="next" class="btn btn-info" value="Siguiente" />
                </fieldset>
                <fieldset>
                    <?php  $Alumno1->aluFiSalud($_SESSION['usuario']['Clave']); ?>
                    <input type="button" name="previous" class="previous btn btn-default" value="Anterior" />
                    <input type="button" id="nextSal" name="next" class="btn btn-info" value="Siguiente" />
                </fieldset>
                <fieldset>
                    <?php $Alumno1->aluFiFamilia($_SESSION['usuario']['Clave']); ?>
                    <input type="button" name="previous" class="previous btn btn-default" value="Anterior" />
                    <input type="button" id="nextFami" name="next" class="btn btn-info" value="Siguiente" />
                </fieldset>
                <fieldset>
                    <?php $Alumno1->aluFiSocial($_SESSION['usuario']['Clave']); ?>
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
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="/sta/assets/js/main.js"></script>
    <script type="text/javascript">
        $(function() {
            $('a[title]').tooltip();
        });
        $(document).ready(function() {
            var current = 1,
                current_step, next_step, steps;
            steps = $("fieldset").length;

            $("#add_row").on("click", function() {
                // Dynamic Rows Code
                // Get max row id and set new id
                var newid = 0;
                $.each($("#tab_logic tr"), function() {
                    if (parseInt($(this).data("id")) > newid) {
                        newid = parseInt($(this).data("id"));
                    }
                });
                newid++;

                var tr = $("<tr></tr>", {
                    id: "addr" + newid,
                    "data-id": newid
                });

                // loop through each td and create new elements with name of newid
                $.each($("#tab_logic tbody tr:nth(0) td"), function() {
                    var cur_td = $(this);

                    var children = cur_td.children();

                    // add new td and element if it has a nane
                    if ($(this).data("name") != undefined) {
                        var td = $("<td></td>", {
                            "data-name": $(cur_td).data("name")
                        });
                        var c = $(cur_td).find($(children[0]).prop('tagName')).clone().val("");
                        c.attr("name", $(cur_td).data("name") + "[]");
                        c.appendTo($(td));
                        td.appendTo($(tr));
                    } else {
                        var td = $("<td></td>", {
                            'text': $('#tab_logic tr').length
                        }).appendTo($(tr));
                    }
                });

                // add delete button and td
                /*
                $("<td></td>").append(
                    $("<button class='btn btn-danger glyphicon glyphicon-remove row-remove'></button>")
                        .click(function() {
                            $(this).closest("tr").remove();
                        })
                ).appendTo($(tr));
                */

                // add the new row
                $(tr).appendTo($('#tab_logic'));

                $(tr).find("td button.row-remove").on("click", function() {
                    $(this).closest("tr").remove();
                });
            });
            // Sortable Code
            var fixHelperModified = function(e, tr) {
                var $originals = tr.children();
                var $helper = tr.clone();

                $helper.children().each(function(index) {
                    $(this).width($originals.eq(index).width())
                });

                return $helper;
            };

            $(".table-sortable tbody").sortable({
                helper: fixHelperModified
            }).disableSelection();

            $(".table-sortable thead").disableSelection();

            $("#add_row").trigger("click");

            $(".next").click(function() {
                current_step = $(this).parent();
                next_step = $(this).parent().next();
                next_step.show();
                current_step.hide();
                setProgressBar(++current);
            });
            $(".previous").click(function() {
                current_step = $(this).parent();
                next_step = $(this).parent().prev();
                next_step.show();
                current_step.hide();
                setProgressBar(--current);
            });
            setProgressBar(current);
            // Change progress bar action
            function setProgressBar(curStep) {
                var percent = parseFloat(100 / steps) * curStep;
                percent = percent.toFixed();
                $(".progress-bar")
                    .css("width", percent + "%")
                    .html(percent + "%");
            }

            if ($('.bs-float-label input').length) {
                var bs_float_on_class = "on";
                var bs_float_show_class = "show";

                $('.float-input').on('bs-check-value', function() {
                        var _bs_label = $(this).closest('.bs-float-label').find('.float-label');
                        if (this.value !== '') {
                            _bs_label.addClass(bs_float_show_class);
                        } else {
                            _bs_label.removeClass(bs_float_show_class);
                        }
                    })
                    .on("keyup", function() {
                        $(this).trigger("bs-check-value");
                    })
                    .on("focus", function() {
                        $(this).closest(".bs-float-label").find('.float-label').addClass(bs_float_on_class);
                    })
                    .on("blur", function() {
                        $(this).closest(".bs-float-label").find('.float-label').removeClass(bs_float_on_class);
                    }).trigger("bs-check-value");
                ;
            }

            $("#nextPerf").click( function (event) {
                $('#alert_msg').css('height', '100px').css('font-size', '20px');
                event.preventDefault();
                var todoBien = true;
                if ($('#dp_nombre').val() === "") { todoBien = false; window.location.href='#collapseNomA'; sendMessageR('Debes Introducir un Nombre', "#DA0000"); $("#dp_nombre").focus(); }

                if ($('#dp_ap_paterno').val() === "") { todoBien = false; window.location.href='#collapseNomA'; sendMessageR('Debes Introducir al menos el apellido paterno', "#DA0000"); $("#dp_ap_paterno").focus(); }

                if (isNaN($('#dp_edad').val() ) ){ todoBien = false; window.location.href='#fisicA'; sendMessageR('DebesIntroducir tu edad', "#DA0000"); $("#dp_edad").focus(); }

                if ($('#dp_talla').val() === "") { todoBien = false; window.location.href='#fisicA'; sendMessageR('Debes Introducir tu altura', "#DA0000"); $("#dp_talla").focus(); }

                if ($('#dp_peso').val() === "") { todoBien = false; window.location.href='#fisicA'; sendMessageR('Debes Introducir tu peso', "#DA0000"); $("#dp_peso").focus(); }

                if ($('#dp_sexo').val() === "") { todoBien = false; window.location.href='#fisicA'; sendMessageR('Seleccjone una opcion ', "#DA0000"); $("#dp_sexo").focus(); }

                if ($('#dp_carrera').val() === "") { todoBien = false; window.location.href='#grupoA'; sendMessageR('Introdusca una carrera', "#DA0000"); $("#dp_carrera").focus(); }

                if ($('#fip_edo_civil').val() === "") { todoBien = false; window.location.href='#edci'; sendMessageR('Seleccione su estado civil', "#DA0000"); $("#fip_edo_civil").focus(); }

                if ($('#dp_tipo_sangre').val() === "") { todoBien = false; window.location.href='#grupoA'; sendMessageR('Seleccione su tipo de sangre', "#DA0000"); $("#dp_tipo_sangre").focus(); }

                if ($('#fip_fecha_nac').val() === "") { todoBien = false; window.location.href='#fecnacA'; sendMessageR('Introduzca su fecha de Nacimiemto', "#DA0000"); $("#fip_fecha_nac").focus(); }

                if ($('#fip_lugar_nac').val() === "") { todoBien = false; window.location.href='#fecnacA'; sendMessageR('Introduzca su lugar de nacimiento', "#DA0000"); $("#fip_lugar_nac").focus(); }

                if ($('#trAlu1').is(':checked') && $('#fip_trabajo').val() === "") { todoBien = false; window.location.href='#inputTrabajo'; sendMessageR('Especifica tu trabajo', "#DA0000"); $("#fip_trabajo").focus(); }

                if ($('#fip_domicilio').val() === "") { todoBien = false; window.location.href='#domi'; sendMessageR('Debe ingresar un domicilio', "#DA0000"); $("#fip_domicilio").focus(); }

                if ($('#fip_vivienda_tipo').val() === "") { todoBien = false; window.location.href='#casaedo'; sendMessageR('Selecciona un tipo de vivienda', "#DA0000"); $("#fip_vivienda_tipo").focus(); }

                if( !$("#form_AluAcept input[name='fip_vivienda_tipo']:radio").is(':checked')) { todoBien = false; window.location.href='#casaedo'; sendMessageR('Debe seleccionar un tipo de vivienda', "#DA0000"); $("#fip_vivienda_tipo_otro").focus(); }

                if ($('#fip_telefono').val() === "") { todoBien = false; window.location.href='#datai2'; sendMessageR('Debe Introducir tu número telefonico', "#DA0000"); $("#fip_telefono").focus(); }

                if ($('#dp_email').val() === "") { todoBien = false; window.location.href='#datai2'; sendMessageR('Debe Introducir un correo', "#DA0000"); $("#dp_email").focus(); }

                if ($('input[name=fip_per_vivienda]').is(':checked')) { todoBien = false; window.location.href='#nump'; sendMessageR('Introduce un numero de las personas con las que vives', "#DA0000"); $("#fip_per_vivienda").focus(); }
                if ($('#fip_per_parentesco').val() === "") { todoBien = false; window.location.href='#nump'; sendMessageR('Describe el parentescon con las personas que vives actualemente', "#DA0000"); $("#fip_per_parentesco").focus(); }

                if (($("input [name='nomPa']").val()!= "" &&  $("input [name='nomMa']").val()!= "") && $('#fip_rel_padres').val() === "") { todoBien = false; window.location.href='#relPad'; sendMessageR('Debe seleccionar la relacion de tus padres', "#DA0000"); $("#fip_rel_padres").focus(); }

                if (todoBien ==true) {
                    current_step = $(this).parent();
                    next_step = $(this).parent().next();
                    next_step.show();
                    current_step.hide();
                    setProgressBar(++current);
                }

            });

            $("#nextSal").click( function (event) {
                $('#alert_msg').css('height', '100px').css('font-size', '20px');
                event.preventDefault();
                var todoBien = true;

                if( !$("#form_AluAcept input[name='fm_discapacidad']:radio").is(':checked')) { todoBien = false; window.location.href='#casaedo'; sendMessageR('Selecciona si tienes alguna discapacidad o no', "#DA0000");}

                if( !$("#form_AluAcept input[name='dx_psicologico']:radio").is(':checked')) { todoBien = false; window.location.href='#casaedo'; sendMessageR('Selecciona si tienes algun diagnostico clinico o no', "#DA0000");}

                if( !$("#form_AluAcept input[name='medRadio']:radio").is(':checked')) { todoBien = false; window.location.href='#casaedo'; sendMessageR('Selecciona si tienes algun diagnostico medico o no', "#DA0000");}

                if (todoBien ==true) {
                    current_step = $(this).parent();
                    next_step = $(this).parent().next();
                    next_step.show();
                    current_step.hide();
                    setProgressBar(++current);
                }
            });

            $("#nextFami").click( function (event) {
                $('#alert_msg').css('height', '100px').css('font-size', '20px');
                event.preventDefault();
                var todoBien = true;
                //if ($("input[name='namef[]']").val()=="" || $("input[name='nacf[]']").val()=="" || $("input[name='actf[]']").val()=="" || $("select[name='sexf[]']").val() =="" || $("select[name='parefam[]']").val()==""){ todoBien = false; sendMessageR('Desbes de agregar tus datos en la tabla por lo menos', "#DA0000");}
                if ($('#fiden_relfa').val() === "") { todoBien = false; sendMessageR('Contesta como es tu relación familiar', "#DA0000");}

                if ( !$("#form_AluAcept input[name='dif']:radio").is(':checked')) { todoBien = false; sendMessageR('Indica si es que tienes problemas familiares o no', "#DA0000");}

                if ($('#fiden_actfa').val() === "") { todoBien = false; sendMessageR('Contesta como es tu actitud familiar', "#DA0000");}

                if ($('#fiden_ligue').val() === "") { todoBien = false; sendMessageR('Selecciona una opcion de con quien te sientes mas ligado', "#DA0000");}

                if ($('#fiden_ligue_T').val() === "") { todoBien = false; sendMessageR('Explica porque te sientes mas ligado con la persona seleccionada anteriormente', "#DA0000");}

                if ($('#fiden_edu').val() === "") { todoBien = false; sendMessageR('Indica quien se encarga de tu educacion', "#DA0000");}

                if ($('#fiden_influ').val() === "") { todoBien = false; sendMessageR('Indica quien o quienes han influido para seleccionar esta carrera', "#DA0000");}

                if (todoBien ==true) {
                    current_step = $(this).parent();
                    next_step = $(this).parent().next();
                    next_step.show();
                    current_step.hide();
                    setProgressBar(++current);
                }
            });

            $("#nextSoc").click( function (event) {
                $('#alert_msg').css('height', '100px').css('font-size', '20px');
                event.preventDefault();
                var todoBien = true;

                if ($('#rel_comp').val() === "") { todoBien = false; sendMessageR('Indica tu relacion con tus compañeros y dinos porque', "#DA0000");}

                if ($('#rel_ami').val() === "") { todoBien = false; sendMessageR('Indica tu relacion con tus amigos y dinos porque', "#DA0000");}

                if ( !$("#form_AluAcept input[name='alu_par']:radio").is(':checked')) { todoBien = false; sendMessageR('Indica si tienes pareja o no', "#DA0000");}

                if ($("#alu_par1").is(':checked') && $('#rel_alu_par').val() === "") { todoBien = false; sendMessageR('Indica tu relacion con tu pareja y dinos porque', "#DA0000");}

                if ($('#rel_pro').val() === "") { todoBien = false; sendMessageR('Indica tu relacion con tus profesores y dinos porque', "#DA0000");}

                if ($('#rel_aut_aca').val() === "") { todoBien = false; sendMessageR('Indica tu relacion con las autoridades academicas y dinos porque', "#DA0000");}

                if ($('#alu_tlibre').val() === "") { todoBien = false; sendMessageR('Contesta que haces en tu tiempo libre', "#DA0000");}

                if ($('#alu_act_rec').val() === "") { todoBien = false; sendMessageR('Contesta cuales so tus actividades recreativas', "#DA0000");}

                if ($('#alu_pl_inme').val() === "") { todoBien = false; sendMessageR('Contesta cuales son tus planes inmediatos', "#DA0000");}

                if ($('#alu_metas').val() === "") { todoBien = false; sendMessageR('Contesta cuales son tus metas', "#DA0000");}

                if ($('#alu_soy').val() === "") { todoBien = false; sendMessageR('Completa el recuadro de yo soy', "#DA0000");}

                if ($('#alu_caracter').val() === "") { todoBien = false; sendMessageR('Dinos como es tu caracter', "#DA0000");}

                if ($('#alu_gusto').val() === "") { todoBien = false; sendMessageR('Dinos cuales son tus gustos', "#DA0000");}

                if ($('#alu_aspira').val() === "") { todoBien = false; sendMessageR('Contesta que aspiras en la vida', "#DA0000");}

                if ($('#alu_miedo').val() === "") { todoBien = false; sendMessageR('Contesta cuales son tus miedos', "#DA0000");}

                if (todoBien ==true) {
                    current_step = $(this).parent();
                    next_step = $(this).parent().next();
                    next_step.show();
                    current_step.hide();
                    setProgressBar(++current);
                }
            });

        });
    </script>

</body>

</html>
