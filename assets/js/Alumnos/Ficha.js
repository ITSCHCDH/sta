$(function() {
    $("a[title]").tooltip();
});
$(document).ready(function() {
    var current = 1,
        current_step,
        next_step,
        steps;
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
            "data-id": newid,
        });

        // loop through each td and create new elements with name of newid
        $.each($("#tab_logic tbody tr:nth(0) td"), function() {
            var cur_td = $(this);

            var children = cur_td.children();

            // add new td and element if it has a nane
            if ($(this).data("name") != undefined) {
                var td = $("<td></td>", {
                    "data-name": $(cur_td).data("name"),
                });
                var c = $(cur_td).find($(children[0]).prop("tagName")).clone().val("");
                c.attr("name", $(cur_td).data("name") + "[]");
                c.appendTo($(td));
                td.appendTo($(tr));
            } else {
                var td = $("<td></td>", {
                    text: $("#tab_logic tr").length,
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
        $(tr).appendTo($("#tab_logic"));

        $(tr)
            .find("td button.row-remove")
            .on("click", function() {
                $(this).closest("tr").remove();
            });
    });
    // Sortable Code
    var fixHelperModified = function(e, tr) {
        var $originals = tr.children();
        var $helper = tr.clone();

        $helper.children().each(function(index) {
            $(this).width($originals.eq(index).width());
        });

        return $helper;
    };

    $(".table-sortable tbody")
        .sortable({
            helper: fixHelperModified,
        })
        .disableSelection();

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

    if ($(".bs-float-label input").length) {
        var bs_float_on_class = "on";
        var bs_float_show_class = "show";

        $(".float-input")
            .on("bs-check-value", function() {
                var _bs_label = $(this).closest(".bs-float-label").find(".float-label");
                if (this.value !== "") {
                    _bs_label.addClass(bs_float_show_class);
                } else {
                    _bs_label.removeClass(bs_float_show_class);
                }
            })
            .on("keyup", function() {
                $(this).trigger("bs-check-value");
            })
            .on("focus", function() {
                $(this)
                    .closest(".bs-float-label")
                    .find(".float-label")
                    .addClass(bs_float_on_class);
            })
            .on("blur", function() {
                $(this)
                    .closest(".bs-float-label")
                    .find(".float-label")
                    .removeClass(bs_float_on_class);
            })
            .trigger("bs-check-value");
    }

    $("#nextPerf").click(function(event) {
        $("#alert_msg").css("height", "100px").css("font-size", "20px");
        event.preventDefault();
        var todoBien = true;
        if ($("#dp_nombre").val() === "") {
            todoBien = false;
            $("#collapseNomA").click();
            alerta2("error", "Debes Introducir un Nombre", "#DA0000");
            $("#dp_nombre").focus();
        }

        if ($("#dp_ap_paterno").val() === "") {
            todoBien = false;
            $("#collapseNomA").click();
            alerta2(
                "error",
                "Debes Introducir al menos el apellido paterno",
                "#DA0000"
            );
            $("#dp_ap_paterno").focus();
        }

        if (isNaN($("#dp_edad").val())) {
            todoBien = false;
            $("#fisicA").click();
            alerta2("error", "DebesIntroducir tu edad", "#DA0000");
            $("#dp_edad").focus();
        }

        if ($("#dp_talla").val() === "") {
            todoBien = false;
            $("#fisicA").click();
            alerta2("error", "Debes Introducir tu altura", "#DA0000");
            $("#dp_talla").focus();
        }

        if ($("#dp_peso").val() === "") {
            todoBien = false;
            $("#fisicA").click();
            alerta2("error", "Debes Introducir tu peso", "#DA0000");
            $("#dp_peso").focus();
        }

        if ($("#dp_sexo").val() === "") {
            todoBien = false;
            $("#fisicA").click();
            alerta2("error", "Seleccjone una opcion ", "#DA0000");
            $("#dp_sexo").focus();
        }

        if ($("#dp_carrera").val() === "") {
            todoBien = false;
            $("#grupoA").click();
            alerta2("error", "Introdusca una carrera", "#DA0000");
            $("#dp_carrera").focus();
        }

        if ($("#fip_edo_civil").val() === "") {
            todoBien = false;
            $("#edci").click();
            alerta2("error", "Seleccione su estado civil", "#DA0000");
            $("#fip_edo_civil").focus();
        }

        if ($("#dp_tipo_sangre").val() === "") {
            todoBien = false;
            $("#grupoA").click();
            alerta2("error", "Seleccione su tipo de sangre", "#DA0000");
            $("#dp_tipo_sangre").focus();
        }

        if ($("#fip_fecha_nac").val() === "") {
            todoBien = false;
            $("#fecnacA").click();
            alerta2("error", "Introduzca su fecha de Nacimiemto", "#DA0000");
            $("#fip_fecha_nac").focus();
        }

        if ($("#fip_lugar_nac").val() === "") {
            todoBien = false;
            $("#fecnacA").click();
            alerta2("error", "Introduzca su lugar de nacimiento", "#DA0000");
            $("#fip_lugar_nac").focus();
        }

        if ($("#trAlu1").is(":checked") && $("#fip_trabajo").val() === "") {
            todoBien = false;
            $("#inputTrabajo").click();
            alerta2("error", "Especifica tu trabajo", "#DA0000");
            $("#fip_trabajo").focus();
        }

        if ($("#fip_domicilio").val() === "") {
            todoBien = false;
            $("#domi").click();
            alerta2("error", "Debe ingresar un domicilio", "#DA0000");
            $("#fip_domicilio").focus();
        }

        if ($("#fip_vivienda_tipo").val() === "") {
            todoBien = false;
            $("#casaedo").click();
            alerta2("error", "Selecciona un tipo de vivienda", "#DA0000");
            $("#fip_vivienda_tipo").focus();
        }

        if (!$("#form_AluAcept input[name='fip_vivienda_tipo']:radio").is(":checked")) {
            todoBien = false;
            $("#casaedo").click();
            alerta2("error", "Debe seleccionar un tipo de vivienda", "#DA0000");
            $("#fip_vivienda_tipo_otro").focus();
        }

        if ($("#fip_telefono").val() === "") {
            todoBien = false;
            $("#datai2").click();
            alerta2("error", "Debe Introducir tu número telefonico", "#DA0000");
            $("#fip_telefono").focus();
        }

        if ($("#dp_email").val() === "") {
            todoBien = false;
            $("#datai2").click();
            alerta2("error", "Debe Introducir un correo", "#DA0000");
            $("#dp_email").focus();
        }

        if ($("input[name=fip_per_vivienda]").is(":checked")) {
            todoBien = false;
            $("#nump").click();
            alerta2(
                "error",
                "Introduce un numero de las personas con las que vives",
                "#DA0000"
            );
            $("#fip_per_vivienda").focus();
        }
        if ($("#fip_per_parentesco").val() === "") {
            todoBien = false;
            $("#nump").click();
            alerta2(
                "error",
                "Describe el parentescon con las personas que vives actualemente",
                "#DA0000"
            );
            $("#fip_per_parentesco").focus();
        }

        if (
            $("input [name='nomPa']").val() == "" &&
            $("input [name='nomMa']").val() == "" &&
            $("#fip_rel_padres").val() === ""
        ) {
            todoBien = false;
            $("#relPad").click();
            alerta2("error", "Debe seleccionar la relacion de tus padres", "#DA0000");
            $("#fip_rel_padres").focus();
        }

        if (todoBien == true) {
            current_step = $(this).parent();
            next_step = $(this).parent().next();
            next_step.show();
            current_step.hide();
            setProgressBar(++current);
        }
    });

    $("#nextSal").click(function(event) {
        $("#alert_msg").css("height", "100px").css("font-size", "20px");
        event.preventDefault();
        var todoBien = true;

        if (!$("#form_AluAcept input[name='fm_discapacidad']:radio").is(":checked")) {
            todoBien = false;
            $("#casaedo").click();
            alerta2(
                "error",
                "Selecciona si tienes alguna discapacidad o no",
                "#DA0000"
            );
        }

        if (!$("#form_AluAcept input[name='dx_psicologico']:radio").is(":checked")) {
            todoBien = false;
            $("#casaedo").click();
            alerta2(
                "error",
                "Selecciona si tienes algun diagnostico clinico o no",
                "#DA0000"
            );
        }

        if (!$("#form_AluAcept input[name='medRadio']:radio").is(":checked")) {
            todoBien = false;
            $("#casaedo").click();
            alerta2(
                "error",
                "Selecciona si tienes algun diagnostico medico o no",
                "#DA0000"
            );
        }

        if (todoBien == true) {
            current_step = $(this).parent();
            next_step = $(this).parent().next();
            next_step.show();
            current_step.hide();
            setProgressBar(++current);
        }
    });

    $("#nextFami").click(function(event) {
        $("#alert_msg").css("height", "100px").css("font-size", "20px");
        event.preventDefault();
        var todoBien = true;
        //if ($("input[name='namef[]']").val()=="" || $("input[name='nacf[]']").val()=="" || $("input[name='actf[]']").val()=="" || $("select[name='sexf[]']").val() =="" || $("select[name='parefam[]']").val()==""){ todoBien = false; alerta2("error", 'Desbes de agregar tus datos en la tabla por lo menos', "#DA0000");}
        if ($("#fiden_relfa").val() === "") {
            todoBien = false;
            alerta2("error", "Contesta como es tu relación familiar", "#DA0000");
        }

        if (!$("#form_AluAcept input[name='dif']:radio").is(":checked")) {
            todoBien = false;
            alerta2(
                "error",
                "Indica si es que tienes problemas familiares o no",
                "#DA0000"
            );
        }

        if ($("#fiden_actfa").val() === "") {
            todoBien = false;
            alerta2("error", "Contesta como es tu actitud familiar", "#DA0000");
        }

        if ($("#fiden_ligue").val() === "") {
            todoBien = false;
            alerta2(
                "error",
                "Selecciona una opcion de con quien te sientes mas ligado",
                "#DA0000"
            );
        }

        if ($("#fiden_ligue_T").val() === "") {
            todoBien = false;
            alerta2(
                "error",
                "Explica porque te sientes mas ligado con la persona seleccionada anteriormente",
                "#DA0000"
            );
        }

        if ($("#fiden_edu").val() === "") {
            todoBien = false;
            alerta2("error", "Indica quien se encarga de tu educacion", "#DA0000");
        }

        if ($("#fiden_influ").val() === "") {
            todoBien = false;
            alerta2(
                "error",
                "Indica quien o quienes han influido para seleccionar esta carrera",
                "#DA0000"
            );
        }

        if (todoBien == true) {
            current_step = $(this).parent();
            next_step = $(this).parent().next();
            next_step.show();
            current_step.hide();
            setProgressBar(++current);
        }
    });

    $("#nextSoc").click(function(event) {
        $("#alert_msg").css("height", "100px").css("font-size", "20px");
        event.preventDefault();
        var todoBien = true;

        if ($("#rel_comp").val() === "") {
            todoBien = false;
            alerta2(
                "error",
                "Indica tu relacion con tus compañeros y dinos porque",
                "#DA0000"
            );
        }

        if ($("#rel_ami").val() === "") {
            todoBien = false;
            alerta2(
                "error",
                "Indica tu relacion con tus amigos y dinos porque",
                "#DA0000"
            );
        }

        if (!$("#form_AluAcept input[name='alu_par']:radio").is(":checked")) {
            todoBien = false;
            alerta2("error", "Indica si tienes pareja o no", "#DA0000");
        }

        if ($("#alu_par1").is(":checked") && $("#rel_alu_par").val() === "") {
            todoBien = false;
            alerta2(
                "error",
                "Indica tu relacion con tu pareja y dinos porque",
                "#DA0000"
            );
        }

        if ($("#rel_pro").val() === "") {
            todoBien = false;
            alerta2(
                "error",
                "Indica tu relacion con tus profesores y dinos porque",
                "#DA0000"
            );
        }

        if ($("#rel_aut_aca").val() === "") {
            todoBien = false;
            alerta2(
                "error",
                "Indica tu relacion con las autoridades academicas y dinos porque",
                "#DA0000"
            );
        }

        if ($("#alu_tlibre").val() === "") {
            todoBien = false;
            alerta2("error", "Contesta que haces en tu tiempo libre", "#DA0000");
        }

        if ($("#alu_act_rec").val() === "") {
            todoBien = false;
            alerta2(
                "error",
                "Contesta cuales so tus actividades recreativas",
                "#DA0000"
            );
        }

        if ($("#alu_pl_inme").val() === "") {
            todoBien = false;
            alerta2("error", "Contesta cuales son tus planes inmediatos", "#DA0000");
        }

        if ($("#alu_metas").val() === "") {
            todoBien = false;
            alerta2("error", "Contesta cuales son tus metas", "#DA0000");
        }

        if ($("#alu_soy").val() === "") {
            todoBien = false;
            alerta2("error", "Completa el recuadro de yo soy", "#DA0000");
        }

        if ($("#alu_caracter").val() === "") {
            todoBien = false;
            alerta2("error", "Dinos como es tu caracter", "#DA0000");
        }

        if ($("#alu_gusto").val() === "") {
            todoBien = false;
            alerta2("error", "Dinos cuales son tus gustos", "#DA0000");
        }

        if ($("#alu_aspira").val() === "") {
            todoBien = false;
            alerta2("error", "Contesta que aspiras en la vida", "#DA0000");
        }

        if ($("#alu_miedo").val() === "") {
            todoBien = false;
            alerta2("error", "Contesta cuales son tus miedos", "#DA0000");
        }

        if (todoBien == true) {
            current_step = $(this).parent();
            next_step = $(this).parent().next();
            next_step.show();
            current_step.hide();
            setProgressBar(++current);
        }
    });
});