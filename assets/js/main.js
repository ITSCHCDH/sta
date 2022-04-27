function initializeJS() {
  //tool tips
  jQuery(".tooltips").tooltip();

  //popovers
  jQuery(".popovers").popover();

  //sidebar dropdown menu
  jQuery("#sidebar .sub-menu > a").click(function () {
    var last = jQuery(".sub-menu.open", jQuery("#sidebar"));
    jQuery(".menu-arrow").removeClass("arrow_carrot-right");
    jQuery(".sub", last).slideUp(200);
    var sub = jQuery(this).next();
    if (sub.is(":visible")) {
      jQuery(".menu-arrow").addClass("arrow_carrot-right");
      sub.slideUp(200);
    } else {
      jQuery(".menu-arrow").addClass("arrow_carrot-down");
      sub.slideDown(200);
    }
    var o = jQuery(this).offset();
    diff = 200 - o.top;
    if (diff > 0) jQuery("#sidebar").scrollTo("-=" + Math.abs(diff), 500);
    else jQuery("#sidebar").scrollTo("+=" + Math.abs(diff), 500);
  });

  // sidebar menu toggle
  jQuery(function () {
    function responsiveView() {
      var wSize = jQuery(window).width();
      if (wSize <= 768) {
        jQuery("#container").addClass("sidebar-close");
        jQuery("#sidebar > ul").hide();
      }

      if (wSize > 768) {
        jQuery("#container").removeClass("sidebar-close");
        jQuery("#sidebar > ul").show();
      }
    }
    jQuery(window).on("load", responsiveView);
    jQuery(window).on("resize", responsiveView);
  });

  jQuery(".toggle-nav").click(function () {
    if (jQuery("#sidebar > ul").is(":visible") === true) {
      jQuery("#main-content").css({
        "margin-left": "0px",
      });
      jQuery("#sidebar").css({
        "margin-left": "-180px",
      });
      jQuery("#sidebar > ul").hide();
      jQuery("#container").addClass("sidebar-closed");
    } else {
      jQuery("#main-content").css({
        "margin-left": "180px",
      });
      jQuery("#sidebar > ul").show();
      jQuery("#sidebar").css({
        "margin-left": "0",
      });
      jQuery("#container").removeClass("sidebar-closed");
    }
  });

  //bar chart
  if (jQuery(".custom-custom-bar-chart")) {
    jQuery(".bar").each(function () {
      var i = jQuery(this).find(".value").html();
      jQuery(this).find(".value").html("");
      jQuery(this).find(".value").animate(
        {
          height: i,
        },
        2000
      );
    });
  }
}

jQuery(document).ready(function () {
  initializeJS();
});

$(document).ready(function () {
  if (pass != "setPass") {
    $("body").append(
      '<div class="modal fade" id="PassChange" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"> <div class="modal-dialog" role="document" style="width: 70%"> <div class="modal-content"> <div class="modal-header text-center" style="background-color: #b40b0b; color: #fff;"> <h3 class="modal-title" id="myModalLabel"> IMPORTANTE. </h3> </div> <div class="modal-body"> <div class="container" style="width:100%"> <div class="row"> <div class="col-md-12"> <dl> <dt>Detalles</dt> <dd>Por tu seguridad, debes de cambiar tu contraseña.</dd> <dd>Debe incluir números. <br /> Utilice una combinación de letras mayúsculas y minúsculas. <br /> Incluya caracteres especiales. ¿Cuáles son los caracteres especiales? <br /> Cualquiera de los siguientes caracteres: <br /> - * ? ! @ # $ / () {} = . , ; : <br /> Tenga una longitud mayor o igual a 8 caracteres. No debe tener espacios en blanco. </dd> </dl> </div> </div> <div class="row"> <div class="col-md-12"> <form role="form" id ="formChangePass"> <div class="row"> <div class="col-sm-5 col-sm-offset-1"> <div class="form-group"> <label for="pas1"> Contraseña </label> <input class="form-control" id="pas1" type="password" required /> </div> </div> <div class="col-sm-5"> <div class=" form-group"> <label for="pas2"> Repetir contraseña </label> <input class="form-control" id="pas2" type="password" required /> </div> </div> </div> </form> </div> </div> </div> </div> <div class="modal-footer"> <button type="submit" class="btn btn-primary" onClick="$(\'#formChangePass\').submit();"> Cambiar contraseña </button> </div> </div> </div> </div>'
    );
    $("#PassChange").modal({
      backdrop: "static",
      keyboard: false,
    });
    $("#PassChange").show();
  }

  $(window).bind("scroll", function () {
    var navHeight = $("#box1").height();
    $(window).scrollTop() > navHeight + 20
      ? $("nav").addClass("goToTop").addClass("nav-2")
      : $("nav").removeClass("goToTop").removeClass("nav-2");
  });

  $(".submenu > a").click(function (e) {
    e.preventDefault();
    var $li = $(this).parent("li");
    var $ul = $(this).next("ul");

    if ($li.hasClass("open")) {
      $ul.slideUp(350);
      $li.removeClass("open");
    } else {
      $(".nav > li > ul").slideUp(350);
      $(".nav > li").removeClass("open");
      $ul.slideDown(350);
      $li.addClass("open");
    }
  });
});
//Eventos onchange de combobox o select
$("#DocSelc").on("change", function () {
  if (!$(this).prop("checked")) {
    $("#DocSelec").hide();
    $("#NewJef").show();
  } else {
    $("#DocSelec").show();
    $("#NewJef").hide();
  }
});

$("#Carreras").on("change", function () {
  var idC = $("#Carreras").val();
  var idS = $("#Semestre").val();
  var url = "/sta/php/GrupoSQL.php";
  $.ajax({
    type: "POST",
    url: url,
    data: {
      Car: idC,
      Sem: idS,
    },
    success: function (data) {
      $("#Grupo option").remove();
      $("#Grupo").append(data);
    },
  });
  return false;
});

$("#Semestre").on("change", function () {
  var idC = $("#Carreras").val();
  var idS = $("#Semestre").val();
  var url = "/sta/php/GrupoSQL.php";
  $.ajax({
    type: "POST",
    url: url,
    data: {
      Car: idC,
      Sem: idS,
    },
    success: function (data) {
      $("#Grupo option").remove();
      $("#Grupo").append(data);
    },
  });
  return false;
});

$("#GrupoTu").on("change", function () {
  var idG = $("#GrupoTu").val();
  var url = "/sta/php/init/GROUP_Listar.php";
  $.ajax({
    type: "POST",
    url: url,
    data: {
      Grup: idG,
    },
    success: function (data) {
      $("#lista").empty();
      $("#lista").append(data);
      $("#mitabla").DataTable({
        order: [[1, "asc"]],
        destroy: true,
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

$("#GrupoGE").on("change", function () {
  var idG = $("#GrupoGE").val();
  var url = "/sta/php/init/GROUP_ListarGen.php";
  $.ajax({
    type: "POST",
    url: url,
    data: {
      Gen: idG,
    },
    success: function (data) {
      $("#lista").empty();
      $("#lista").append(data);
      $("#mitabla").DataTable({
        order: [[1, "asc"]],
        destroy: true,
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

$("#GrupoGE2").on("change", function () {
  var idG = $("#GrupoGE2").val();
  var url = "/sta/php/init/GROUP_ListarGen2.php";
  $.ajax({
    type: "POST",
    url: url,
    data: {
      Gen: idG,
    },
    success: function (data) {
      $("#lista").empty();
      $("#lista").append(data);
      $("#mitabla").DataTable({
        order: [[1, "asc"]],
        destroy: true,
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

$("#GrupoTuPDF").on("change", function () {
  var idG = $("#GrupoTuPDF").val();
  var url = "/sta/php/init/GROUP_ListarPDF.php";
  $.ajax({
    type: "POST",
    url: url,
    data: {
      Grup: idG,
    },
    success: function (data) {
      $("#lista").empty();
      $("#lista").append(data);
      $("#mitabla").DataTable({
        order: [[1, "asc"]],
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

$("#gpoAsignS").on("change", function () {
  var combo = document.getElementById("gpoAsignS");
  var idG = combo.options[combo.selectedIndex].text;
  var url = "/sta/php/init/tutorGpoAsign.php";
  $.ajax({
    type: "POST",
    url: url,
    data: {
      Grup: idG,
    },
    success: function (data) {
      $("#alu").empty();
      $("#alu").append(data);
      $("#mitabla").DataTable({
        order: [[1, "asc"]],
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
        paging: false,
      });
      $("#Guardar").show();
    },
  });
  return false;
});

$("#GrupoMat").on("change", function () {
  var idG = $("#GrupoMat").val();
  var url = "/sta/php/init/tutor.Materias-Listar_Materia.php";
  $.ajax({
    type: "POST",
    url: url,
    data: {
      Grup: idG,
    },
    success: function (data) {
      $("#CalMat option").remove();
      $("#CalMat").append(data);
    },
    error: function (xhr, status) {
      alert("Disculpe, existió un problema");
      alert(xhr);
      alert(status);
    },
  });
  return false;
});

$("#GrupoGen").on("change", function () {
  var idG = $("#GrupoGen").val();
  var url = "/sta/php/init/Jfe_CalMat.php";
  $.ajax({
    type: "POST",
    url: url,
    data: {
      Gen: idG,
    },
    success: function (data) {
      $("#lista").empty();
      $("#lista").append(data);
      $("#mitabla").DataTable({
        order: [[1, "asc"]],
        destroy: true,
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
        scroller: true,
        scrollCollapse: true,
        paging: false,
        fixedColumns: {
          leftColumns: 2,
        },
      });
    },
    error: function (xhr, status) {
      alert("Disculpe, existió un problema");
      alert(xhr);
      alert(status);
    },
  });
  return false;
});

$("#CalMat").on("change", function () {
  var gpo = $("#GrupoMat").val();
  var mate = $("#CalMat").val();
  if ($("#CalMat").val() == "todas") {
    var url = "/sta/php/init/TU_CalMat2.php";
  } else {
    var url = "/sta/php/init/tutor.Grupo-CalificacionMaterias.php";
  }
  $.ajax({
    type: "POST",
    url: url,
    data: {
      Gpo: gpo,
      Mat: mate,
    },
    success: function (data) {
      $("#CalMaterias").empty();
      $("#CalMaterias").append(data);
      if (mate == "todas") {
        $("#mitabla").DataTable({
          order: [[1, "asc"]],
          destroy: true,
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
          paging: false,
          info: false,
          searching: false,
          colReorder: true,
        });
      } else {
        $("#mitabla").DataTable({
          order: [[1, "asc"]],
          destroy: true,
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
          paging: false,
          info: false,
          searching: false,
          colReorder: true,
        });
      }
    },
    error: function (xhr, status) {
      console.log(xhr + " " + status);
      //alert('Disculpe, existió un problema ' + status + ' ' + xhr + ' ');
      alert(
        "Disculpe, existió un problema, Favor de notificarlo al área de sistemas  "
      );
    },
  });
  return false;
});

$("#MatDoce").on("change", function () {
  var Pro = $("#pro").val();
  var mate = $("#MatDoce").val();
  var nommate = $("#MatDoce option:selected").text();
  var url = "/sta/php/init/PRO_calificacion.php";
  $.ajax({
    type: "POST",
    url: url,
    data: {
      Mat: mate,
      Pro: Pro,
      NomMat: nommate,
    },
    success: function (data) {
      document.getElementById("Parciales").innerHTML = "";
      $("#Parciales").append(data);
    },
    error: function (xhr, status) {
      console.log("-> log");
      console.debug("-> debug");
      console.warn("-> warm");
      console.error("-> error");
      alert("Disculpe, existió un problema " + status + " " + xhr + " ");
    },
    complete: function (xhr, status) {
      /*alert('Petición realizada');*/
    },
  });
  return false;
});

$("#PScarrera").on("change", function () {
  var idC = $("#PScarrera").val();
  var url = "/sta/php/init/ps_grupoXCarrera2.php";
  $.ajax({
    type: "POST",
    url: url,
    data: {
      Car: idC,
    },
    success: function (data) {
      $("#PSGrupo option").remove();
      $("#PSGrupo").append(data);
    },
  });
});

$("#PSGrupo").on("change", function () {
  var idG = $("#PSGrupo").val();
  var idC = $("#PScarrera").val();
  var url = "/sta/php/init/GROUP_ListarGenCar.php";
  $.ajax({
    type: "POST",
    url: url,
    data: {
      Gen: idG,
      Car: idC,
    },
    success: function (data) {
      $("#lista").empty();
      $("#lista").append(data);
      $("#mitabla").DataTable({
        order: [[2, "asc"]],
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

$("#DirCarrera").on("change", function () {
  var idC = $("#DirCarrera").val();
  var url = "/sta/php/init/ps_grupoXCarrera.php";
  $.ajax({
    type: "POST",
    url: url,
    data: {
      Car: idC,
    },
    success: function (data) {
      $("#DirGrupo option").remove();
      $("#DirGrupo").append(data);
    },
  });
});

$("#DirGrupo").on("change", function () {
  var idG = $("#DirGrupo").val();
  var idC = $("#DirCarrera").val();
  var url = "/sta/php/init/GROUP_ListarGen3.php";
  $.ajax({
    type: "POST",
    url: url,
    data: {
      Gen: idG,
      Car: idC,
    },
    success: function (data) {
      $("#lista").empty();
      $("#lista").append(data);
      $("#mitabla").DataTable({
        order: [[2, "asc"]],
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

$("#nomcat").on("input", function () {
  var valor = $("#nomcat").val();
  var texto = $("#catedraticos")
    .find('option[value="' + valor + '"]')
    .attr("label");
  if (texto) {
    $("#nomcat").val(texto);
    $("#clavcat").attr("value", valor);
  }
});

$("#nomcatEdit").on("input", function () {
  var valor = $("#nomcatEdit").val();
  var texto = $("#catedraticosEdit")
    .find('option[value="' + valor + '"]')
    .attr("label");
  if (texto) {
    $("#nomcatEdit").val(texto);
    $("#clavcatEdit").attr("value", valor);
  }
});

function mtvRepChange(no, par, mr, mc) {
  var id_alu = no;
  var par = par;
  var mo_rep = mr;
  var mate = $("#MatDoce").val();
  var motc = prompt("Introdusca algun comentario si es necesario.", "");
  $("#motcom" + no + par).attr("readonly", "false");
  $("#motcom" + no + par).val(motc);
  $("#motcom" + no + par).attr("readonly", "true");

  $.ajax({
    type: "POST",
    url: "/sta/php/init/PRO_motivos.php",
    dataType: "JSON",
    data: {
      mat: mate,
      par: par,
      nc: id_alu,
      mrep: mo_rep,
      crep: motc,
    },
    success: function (data) {
      if (!data.error) {
        sendMessageR(data.mensaje, "#00ff00");
      } else {
        sendMessageR(data.mensaje + "error", "#DA0000");
      }
    },
    error: function (xhr, status) {
      console.log(xhr.responseText);
    },
    complete: function (xhr, status) {
      console.log("Exito");
    },
  });
}

$("#carac").on("click", function (event) {
  event.preventDefault();

  jQuery
    .ajax({
      url: "/sta/php/init/ADM_Caracterizacion.php",
      type: "POST",
      dataType: "json",
      beforeSend: function () {
        myFunction();
      },
    })
    .done(function (respuesta) {
      if (!respuesta.error) {
        myFunctionClose(respuesta.res);
        //sendMessageR(respuesta.res, "default");
      } else {
        myFunctionClose(respuesta.er);
        //sendMessageR(respuesta.er, "default");
      }
    })
    .fail(function (resp) {
      console.log(resp.responseText);
    })
    .always(function () {
      console.log("complete");
    });
});

$("#sice").on("click", function (event) {
  event.preventDefault();

  jQuery
    .ajax({
      url: "/sta/php/init/ADM_Sice.php",
      type: "POST",
      dataType: "json",
      beforeSend: function () {
        myFunction();
      },
    })
    .done(function (respuesta) {
      if (!respuesta.error) {
        myFunctionClose(respuesta.res);
        //sendMessageR(respuesta.res, "default");
      } else {
        myFunctionClose(respuesta.er);
        //sendMessageR(respuesta.er, "default");
      }
    })
    .fail(function (resp) {
      console.log(resp.responseText);
    })
    .always(function () {
      console.log("complete");
    });
});

jQuery(document).on("submit", "#form_conte", function (event) {
  event.preventDefault();

  //Obtenemos el valor del campo nombre
  var NomGrup = ".Nom_Grupo";

  //Validamos el campo Nombre, simplemente miramos que no esté vacío
  if (NomGrup === "") {
    sendMessageR("Debes Introducir un Nombre", "");
    $("input").focus();
    return false;
  }

  jQuery
    .ajax({
      url: "/sta/php/init/GROUP_Insertar.php",
      type: "POST",
      dataType: "json",
      data: $(this).serialize(),
    })
    .done(function (respuesta) {
      if (!respuesta.error) {
        alert(respuesta.res);
        setTimeout(function () {
          location.href = "/sta/Tutor/Lista.php";
        }, 5000);
      } else {
        alert(respuesta.er);
      }
    })
    .fail(function (resp) {
      console.log(resp.responseText);
    })
    .always(function () {
      console.log("complete");
    });
});

jQuery(document).on("submit", "#indiceRep", function (event) {
  event.preventDefault();

  //Obtenemos el valor del campo nombre
  var NomGrup = ".Nom_Grupo";
  var Doc = $(".ClavDoc").val();
  var Sem = $(".Sem");
  var Car = ".Car";
  var Ide = ".Identificador";
  var NumControl = ".NumCon";

  //Validamos el campo Nombre, simplemente miramos que no esté vacío
  if (NomGrup === "") {
    sendMessageR("Debes Introducir un Nombre", "");
    $("input").focus();
    return false;
  }
  if (Doc === "") {
    sendMessageR(
      "Hay algun problema con el docente. Verifiquelo con el Administrador",
      ""
    );
    $("input").focus();
    return false;
  }
  if (Sem === "") {
    sendMessageR(
      "Hay algun problema con el semestre. Verifiquelo con el Administrador",
      ""
    );
    $("input").focus();
    return false;
  }
  if (Car === "") {
    sendMessageR(
      "Hay algun problema con la carrera. Verifiquelo con el Administrador",
      ""
    );
    $("input").focus();
    return false;
  }
  if (Ide === "") {
    sendMessageR(
      "Hay algun problema con el identificador. Verifiquelo con el Administrador",
      ""
    );
    $("input").focus();
    return false;
  }
  jQuery
    .ajax({
      url: "/sta/php/GrupoMySql.php",
      type: "POST",
      dataType: "json",
      data: $(this).serialize(),
      beforeSend: function () {
        $(".botonlg").val("Validando....");
      },
    })
    .done(function (respuesta) {
      if (!respuesta.error) {
        setTimeout(function () {
          alert("iNSERTANDO");
          location.href = "index.php";
        }, 3000);
      } else {
        alert(respuesta.er);
        alert(respuesta.er);
      }
    })
    .fail(function (resp) {
      console.log(resp.responseText);
    })
    .always(function () {
      console.log("complete");
    });
});

jQuery(document).on("submit", "#formlogin", function (event) {
  event.preventDefault();
  user = $("#tipoUsuario").val();
  urls = "";
  if (user == "Alu") {
    urls = "/sta/php/Login2.php";
  } else {
    urls = "/sta/php/Login.php";
  }
  jQuery
    .ajax({
      url: urls,
      type: "POST",
      dataType: "json",
      data: $(this).serialize(),
    })
    .done(function (respuesta) {
      if (!respuesta.error) {
        if (respuesta.tipo == "NoUs") {
          $("#error1").slideDown("slow");
          setTimeout(function () {
            $("#error1").slideUp("slow");
          }, 6000);
        } else if (respuesta.tipo == "Alu") {
          location.href = "Alumnos/profile.php";
        } else if (respuesta.tipo == "Admin") {
          location.href = "Admin/";
        } else if (respuesta.tipo == "Profe") {
          location.href = "Profe/";
        } else if (respuesta.tipo == "Tutor") {
          location.href = "Tutor/";
        } else if (respuesta.tipo == "Jefe") {
          location.href = "JefeCar/";
        } else if (respuesta.tipo == "Dire") {
          location.href = "Direc/";
        } else if (respuesta.tipo == "Tutoria") {
          location.href = "ATutor/";
        } else if (respuesta.tipo == "Medic") {
          location.href = "Medico/";
        }
      } else {
        $("#error2").text(respuesta.text);
        $("#error2").slideDown("slow");
        setTimeout(function () {
          $("#error2").slideUp("slow");
        }, 6000);
      }
    })
    .fail(function (resp) {
      console.log(resp.responseText);
    })
    .always(function () {
      console.log("complete");
    });
});

jQuery(document).on("submit", "#form_aluadd", function (event) {
  event.preventDefault();
  ncon = $("#ncon").val();

  if (ncon == "") {
    sendMessageR("Debes Introducir un Numero de control", "");
    $("#ncon").focus();
    return false;
  }
  jQuery
    .ajax({
      url: "/sta/php/init/GROUP_AddAlu.php",
      type: "POST",
      dataType: "json",
      data: $(this).serialize(),
      beforeSend: function () {
        $("#nombre").attr("value", "");
        $("#apPa").attr("value", "");
        $("#apMa").attr("value", "");
        $("#car").attr("value", "");
      },
    })
    .done(function (respuesta) {
      if (!respuesta.error) {
        $("#nombre").attr("value", respuesta.nom);
        $("#apPa").attr("value", respuesta.ap);
        $("#apMa").attr("value", respuesta.am);
        $("#car").attr("value", respuesta.car);
      } else {
        sendMessageR(respuesta.men, "alert-warning");
      }
    })
    .fail(function (resp) {
      console.log(resp.responseText);
    })
    .always(function () {
      console.log("complete");
    });
});

jQuery(document).on("submit", "#form_AluAcept", function (event) {
  event.preventDefault();
  if (!$("#agree").is(":checked")) {
    alert(
      "Deves aceptar los terminos para continuar, de lo contrario no se guardaran tus datos"
    );
    return false;
  }
  jQuery
    .ajax({
      url: "/sta/php/init/Alu_Registrar.php",
      type: "POST",
      dataType: "json",
      data: $(this).serialize(),
      beforeSend: function () {
        myFunction();
      },
    })
    .done(function (res) {
      if (!res.error) {
        var delay = function (ms) {
          return new Promise(function (r) {
            setTimeout(r, ms);
          });
        };
        var time = 1000;
        delay(time).then(function () {
          $("body")
            .loadingModal("color", "black")
            .loadingModal(
              "text",
              "Tus datos se han guardado correctamente,<br> Espera mientras se genera tu documento."
            )
            .loadingModal("backgroundColor", "rgb(158, 130, 14)");
          return delay(time);
        });

        setTimeout(function () {
          window.open("print_pdf.php", "_blanck");
          $("body").loadingModal("destroy");
        }, 10000);
      } else {
        myFunctionClose(
          "Existieron algunos problemas favor de contactar con el area de sistemas"
        );
        console.log(res.text);
        //$('#error').empty();
        //$('#error').append(res.text);
      }

      myFunction();
    })
    .fail(function (resp) {
      console.log(resp.responseText);
      myFunctionClose(resp.responseText);
      //$('#error').empty();
      //$('#error').append(resp.responseText);
    })
    .always(function () {
      console.log("complete");
    });
});

jQuery(document).on("submit", "#formChangePass", function (event) {
  event.preventDefault();

  if (
    $("#pas1").val() == "" ||
    $("#pas2").val() == "" ||
    $("#pas1").val() !== $("#pas2").val()
  ) {
    sendMessageR(
      "La contraseña es incorrecta en el campo de confirmacion o esta vacio.",
      "#DA0000"
    );
    return false;
  }
  jQuery
    .ajax({
      url: "/sta/php/init/User_SetPass.php",
      type: "POST",
      dataType: "json",
      data: {
        user: user,
        userT: userT,
        pass: $("#pas1").val(),
      },
    })
    .done(function (respuesta) {
      if (!respuesta.error) {
        sendMessageR(
          "La contraseña a sido cambiada correctamente proceda con su visita, muchas gracias.",
          "#00ff00"
        );
        $("#PassChange").modal("hide");
        pass = "setPass";
      } else {
        sendMessageR("Algo salio mal vuelva a intentar.", "#DA0000");
      }
    })
    .fail(function (resp) {
      console.log(resp.responseText);
    })
    .always(function () {
      console.log("complete");
    });
});

jQuery(document).on("submit", "#form_editUser", function (event) {
  event.preventDefault();

  if ($("#firstname").val() == "") {
    sendMessageR("El campo de nombre esta vacio.", "#DA0000");
    return false;
  }
  if ($("#lastname").val() == "") {
    sendMessageR("El campo de apellido paterno esta vacio.", "#DA0000");
    return false;
  }
  if ($("#mobno").val() == "") {
    sendMessageR("El campo de numero telefonico esta vacio.", "#DA0000");
    return false;
  }
  if ($("#emailid").val() == "") {
    sendMessageR("El campo de correo electronico esta vacio.", "#DA0000");
    return false;
  }
  if (
    userT == "Alu" &&
    !$("#form_editUser input[name='radios']:radio").is(":checked")
  ) {
    sendMessageR("Selecciona un tipo de sexo.", "#DA0000");
    return false;
  }

  jQuery
    .ajax({
      url: "/sta/php/init/User_EditPer.php",
      type: "POST",
      dataType: "json",
      data: $(this).serialize(),
    })
    .done(function (respuesta) {
      if (!respuesta.error) {
        sendMessageR("Se guardaron los cambios correctamente.", "#00ff00");
        setTimeout(function () {
          location.reload();
        }, 5000);
      } else {
        sendMessageR(respuesta.men, "#DA0000");
      }
    })
    .fail(function (resp) {
      $("#error").empty();
      $("#error").append(resp.responseText);
      console.log(resp.responseText);
    })
    .always(function () {
      console.log("complete");
    });
});

jQuery(document).on("submit", "#form_editUser_img", function (event) {
  event.preventDefault();

  if ($("#perfil").val() > 0) {
    sendMessageR("Introduca una imagen valida por favor.", "#DA0000");
    return false;
  }
  var formData = new FormData($("#form_editUser_img")[0]);

  jQuery
    .ajax({
      url: "/sta/php/init/User_EditImg.php",
      type: "POST",
      dataType: "json",
      data: formData,
      contentType: false,
      processData: false,
    })
    .done(function (respuesta) {
      if (!respuesta.error) {
        sendMessageR(respuesta.men, "#00ff00");
        setTimeout(function () {
          location.reload();
        }, 5000);
      } else {
        sendMessageR(respuesta.men, "#DA0000");
      }
    })
    .fail(function (resp) {
      $("#error").empty();
      $("#error").append(resp.responseText);
      console.log(resp.responseText);
    })
    .always(function () {
      console.log("complete");
    });
});

jQuery(document).on("submit", "#form_upPDF", function (event) {
  event.preventDefault();

  if ($("#fichapdf").val() > 0) {
    sendMessageR("Introdusca un archivo porfavor.", "#DA0000");
    return false;
  }
  var formData = new FormData($("#form_upPDF")[0]);

  jQuery
    .ajax({
      url: "/sta/php/init/Alu_UpPDF.php",
      type: "POST",
      dataType: "json",
      data: formData,
      contentType: false,
      processData: false,
    })
    .done(function (respuesta) {
      if (!respuesta.error) {
        sendMessageR(respuesta.men, "#00ff00");
        setTimeout(function () {
          location.reload();
        }, 5000);
      } else {
        sendMessageR(respuesta.men, "#DA0000");
      }
    })
    .fail(function (resp) {
      $("#error").empty();
      $("#error").append(resp.responseText);
      console.log(resp.responseText);
    })
    .always(function () {
      console.log("complete");
    });
});

jQuery(document).on("submit", "#formSegi", function (event) {
  event.preventDefault();
  jQuery
    .ajax({
      url: "/sta/php/init/Tut_Seg.php",
      type: "POST",
      dataType: "json",
      data: {
        alu: $("#alu").val(),
        seg: $("#seg").val(),
      },
    })
    .done(function (respuesta) {
      if (!respuesta.error) {
        sendMessageR("Se agrego el seguimiento al alumno.", "#00ff00");
        $("#aluSeg").modal("hide");
      } else {
        sendMessageR("Algo salio mal vuelva a intentar.", "#DA0000");
      }
    })
    .fail(function (resp) {
      console.log(resp.responseText);
    })
    .always(function () {
      console.log("complete");
    });
});

$("#saveAlu").click(function (event) {
  event.preventDefault();
  var nocon = $("#ncon").val();
  var nombre = $("#nombre").val();
  var ap = $("#apPa").val();
  var am = $("#apMa").val();
  var car = $("#car").val();
  var gpo = $("#GrupoTu").val();

  if (nocon === "") {
    sendMessageR("No a introducido ningun numero de control para buscar", "");
    return false;
  }

  if (nombre === "" || ap == "" || am === "") {
    sendMessageR("No a introducido ningun numero de control para buscar", "");
    return false;
  }
  jQuery
    .ajax({
      url: "/sta/php/init/AL_Registrar.php",
      type: "POST",
      dataType: "json",
      data: {
        control: nocon,
        gpo: gpo,
        nom: nombre,
        ap: ap,
        am: am,
        car: car,
      },
    })
    .done(function (respuesta) {
      if (!respuesta.error) {
        sendMessageR(respuesta.res, "default");
        ActTabAlu();
        $("#aluAlt").modal("toggle");
        $("#nombre").attr("readonly", "false");
        $("#apPa").attr("readonly", "false");
        $("#apMa").attr("readonly", "false");
        $("#car").attr("readonly", "false");
        $("#ncon").attr("val", "");
        $("#nombre").attr("val", "");
        $("#apPa").attr("val", "");
        $("#apMa").attr("val", "");
        $("#car").attr("val", "");
        $("#nombre").attr("readonly", "true");
        $("#apPa").attr("readonly", "true");
        $("#apMa").attr("readonly", "true");
        $("#car").attr("readonly", "true");
      } else {
        sendMessageR(respuesta.men, "default");
        setTimeout(function () {
          $("#aluAlt").modal("toggle");
        }, 5000);
      }
    })
    .fail(function (resp) {
      console.log(resp.responseText);
    })
    .always(function () {
      console.log("complete");
    });
});

$("#saveUserAT").click(function (event) {
  event.preventDefault();

  var clave = $("#clavcat").val();
  var contrasena = $("#pass1").val();
  var contrasena2 = $("#pass2").val();
  var correo = $("#correo").val();

  if (clave === "") {
    alert(
      "El usuario que intentas registrar no se encuentra dentro de los docentes"
    );
    return false;
  }

  if (contrasena != contrasena2) {
    alert("La contraseña deben coincidir en caso de que las ayas modificado");
    return false;
  }

  if (correo === "") {
    alert(
      "debes introducir un correo para poder enviarle sus credenciales al usuario"
    );
    return false;
  }
  jQuery
    .ajax({
      url: "/sta/php/init/AT_Registrar.php",
      type: "POST",
      dataType: "json",
      data: {
        user: clave,
        pass: contrasena,
        correo: correo,
      },
    })
    .done(function (respuesta) {
      if (!respuesta.error) {
        sendMessageR(respuesta.res, "default");
        console.log(respuesta.res);
        $("#userAlt").modal("toggle");
        setTimeout(function () {
          location.reload();
        }, 5000);
      } else {
        sendMessageR(respuesta.er, "default");
        console.log(respuesta.er);
        setTimeout(function () {
          $("#userAlt").modal("toggle");
        }, 5000);
      }
    })
    .fail(function (resp) {
      console.log(resp.responseText);
    })
    .always(function () {
      console.log("complete");
    });
});

$("#saveUserTU").click(function (event) {
  event.preventDefault();

  if ($("#DocSelc").is(":checked")) {
    url = "/sta/php/init/TU_Registrar.php";
    var clave = $("#clavcat").val();

    if (clave === "") {
      alert(
        "El usuario que intentas registrar no se encuentra dentro de los docentes"
      );
      return false;
    }
  } else {
    url = "/sta/php/init/TU_Registrar2.php";
    var nom = $("#nombreDoc").val();
    var ap = $("#APa").val();
    var am = $("#AMa").val();
    var contrasena = $("#Pass").val();
    var correo = $("#correo").val();

    if (nom === "") {
      alert("debes introducir un Nombre");
      return false;
    }
    if (ap === "") {
      alert("debes introducir un Apellido");
      return false;
    }
    if (contrasena === "") {
      alert("debes introducir una contraseña");
      return false;
    }
    if (correo === "") {
      alert("debes introducir un correo");
      return false;
    }
  }
  jQuery
    .ajax({
      url: url,
      type: "POST",
      dataType: "json",
      data: $("#form_atuto").serialize(),
    })
    .done(function (respuesta) {
      if (!respuesta.error) {
        sendMessageR(respuesta.res, "default");
        console.log(respuesta.res);
        $("#userAlt").modal("toggle");
        setTimeout(function () {
          location.reload();
        }, 5000);
      } else {
        sendMessageR(respuesta.er, "default");
        console.log(respuesta.er);
        setTimeout(function () {
          $("#userAlt").modal("toggle");
        }, 5000);
      }
    })
    .fail(function (resp) {
      console.log(resp.responseText);
    })
    .always(function () {
      console.log("complete");
    });
});

$("#saveUserJefe").click(function (event) {
  event.preventDefault();

  if ($("#DocSelc").is(":checked")) {
    url = "/sta/php/init/JFE_Actualizar.php";
    var clave = $("#clavcat").val();

    if (clave === "") {
      alert(
        "El usuario que intentas registrar no se encuentra dentro de los docentes"
      );
      return false;
    }
  } else {
    url = "/sta/php/init/JFE_Registrar.php";
    var nom = $("#nombreDoc").val();
    var ap = $("#APa").val();
    var am = $("#AMa").val();
    var contrasena = $("#Pass").val();
    var correo = $("#correo").val();

    if (nom === "") {
      alert("debes introducir un Nombre");
      return false;
    }
    if (ap === "") {
      alert("debes introducir un Apellido");
      return false;
    }
    if (contrasena === "") {
      alert("debes introducir una contraseña");
      return false;
    }
    if (correo === "") {
      alert("debes introducir un correo");
      return false;
    }
  }

  jQuery
    .ajax({
      url: url,
      type: "POST",
      dataType: "json",
      data: $("#JefeCarForm").serialize(),
    })
    .done(function (respuesta) {
      if (!respuesta.error) {
        sendMessageR(respuesta.res, "default");
        console.log(respuesta.res);
        $("#userAlt").modal("toggle");
        setTimeout(function () {
          location.reload();
        }, 5000);
      } else {
        sendMessageR(respuesta.er, "default");
        console.log(respuesta.er);
        setTimeout(function () {
          $("#userAlt").modal("toggle");
        }, 5000);
      }
    })
    .fail(function (resp) {
      console.log(resp.responseText);
    })
    .always(function () {
      console.log("complete");
    });
});

$("#gpo_save").click(function (event) {
  var Doc = $("#clavcat").val();
  var Sem = $("#Semestre").val();
  var Car = $("#Carreras").val();
  var Ide = $("#Grupo").val();

  jQuery
    .ajax({
      url: "/sta/php/init/GROUP_Registrar.php",
      type: "POST",
      dataType: "json",
      data: {
        clavcat: Doc,
        semestre: Sem,
        carrera: Car,
        Grupo: Ide,
      },
      error: function (xhr) {
        console.log(xhr);
      },
    })
    .done(function (respuesta) {
      if (!respuesta.error) {
        sendMessageR(respuesta.res, "default");
        $("#asingGroup").modal("toggle");
        setTimeout(function () {
          location.reload();
        }, 5000);
      } else {
        sendMessageR(respuesta.er, "default");
      }
    })
    .fail(function (resp) {
      console.log(resp);
    })
    .always(function () {
      console.log("complete");
    });
});

$("#gpoE_save").click(function (event) {
  var Doc = $("#clavcatEdit").val();
  var Ide = $("#idGrupEdit").val();
  jQuery
    .ajax({
      url: "/sta/php/init/GROUP_Editar.php",
      type: "POST",
      dataType: "json",
      data: {
        clavcatEdit: Doc,
        idGrupEdit: Ide,
      },
      error: function (xhr) {
        console.log(xhr);
      },
    })
    .done(function (respuesta) {
      if (!respuesta.error) {
        sendMessageR(respuesta.res, "default");
        $("#EditGroup").modal("toggle");
        setTimeout(function () {
          location.reload();
        }, 5000);
      } else {
        sendMessageR(respuesta.er, "default");
      }
    })
    .fail(function (resp) {
      console.log(resp);
    })
    .always(function () {
      console.log("complete");
    });
});

$("#gpo_update").click(function (event) {
  var Doc = $("#clavcat").val();
  var Sem = $("#Semestre").val();
  var Car = $("#Carreras").val();
  var Ide = $("#Grupo").val();

  jQuery
    .ajax({
      url: "/sta/php/init/GROUP_Registrar.php",
      type: "POST",
      dataType: "json",
      data: {
        clavcat: Doc,
        semestre: Sem,
        carrera: Car,
        Grupo: Ide,
      },
      error: function (xhr) {
        console.log(xhr);
      },
    })
    .done(function (respuesta) {
      if (!respuesta.error) {
        sendMessageR(respuesta.res, "default");
        $("#asingGroup").modal("toggle");
        setTimeout(function () {
          location.reload();
        }, 5000);
      } else {
        sendMessageR(respuesta.er, "default");
      }
    })
    .fail(function (resp) {
      console.log(resp);
    })
    .always(function () {
      console.log("complete");
    });
});

$("#saveUserMedico").click(function (event) {
  event.preventDefault();

  var nombre = $("#nombredoc").val();
  var ap = $("#APa").val();
  var am = $("#AMa").val();
  var cedula = $("#Ced").val();

  if (nombre === "") {
    alert("Ingresa por favor el Nombre");
    return false;
  }

  if (ap === "") {
    alert("Debes ingresar al menos el Apellido paterno");
    return false;
  }

  if (cedula === "") {
    alert("Debes ingresar la cedula profecional para futuras acciones");
    return false;
  }
  jQuery
    .ajax({
      url: "/sta/php/init/MED_Registrar.php",
      type: "POST",
      dataType: "json",
      data: {
        nom: nombre,
        ap: ap,
        am: am,
        ced: cedula,
      },
    })
    .done(function (res) {
      if (res.error == false) {
        sendMessageR(res.res, "");
        console.log(res.res);
        $("#AgregarDoc").modal("toggle");
        setTimeout(function () {
          location.reload();
        }, 3000);
      } else {
        sendMessageR(res.er, "");
      }
    })
    .fail(function (resp) {
      console.log(resp.responseText);
    })
    .always(function () {
      console.log("complete");
    });
});

$("#saveUserPsico").click(function (event) {
  event.preventDefault();

  var nombre = $("#nombrepsi").val();
  var ap = $("#APa").val();
  var am = $("#AMa").val();
  var cedula = $("#Ced").val();

  if (nombre === "") {
    alert("Ingresa por favor el Nombre");
    return false;
  }

  if (ap === "") {
    alert("Debes ingresar al menos el Apellido paterno");
    return false;
  }

  if (cedula === "") {
    alert("Debes ingresar la cedula profecional para futuras acciones");
    return false;
  }
  jQuery
    .ajax({
      url: "/sta/php/init/PSI_Registrar.php",
      type: "POST",
      dataType: "json",
      data: {
        nom: nombre,
        ap: ap,
        am: am,
        ced: cedula,
      },
    })
    .done(function (res) {
      if (res.error == false) {
        sendMessageR(res.res, "");
        console.log(res.res);
        $("#AgregarPsi").modal("toggle");
        setTimeout(function () {
          location.reload();
        }, 3000);
      } else {
        sendMessageR(res.er, "");
        console.log(res);
      }
    })
    .fail(function (resp) {
      console.log(resp.responseText);
      console.log("error");
    })
    .always(function () {
      console.log("complete");
    });
});

function TU_edit_asign(idgrup, grup) {
  $("#grupEdit").attr("value", grup);
  $("#idGrupEdit").attr("value", idgrup);

  $("#editgGroup").modal("show");
}

function EliminarAlu(num) {
  var idG = $("#GrupoTu").val();
  var url = "/sta/php/init/Alu_Eliminar.php";
  var respuesta = confirm("Confirme el borrado.");

  if (respuesta) {
    $.ajax({
      type: "POST",
      url: url,
      data: {
        Grup: idG,
        nom: num,
      },
      success: function (data) {
        if (data.err !== false) {
          sendMessageR("El alumno a sido eliminado correctamente.", "#5cb85c");
          ActTabAlu();
        } else {
          sendMessageR(data.text, data.class);
        }
        console.log(data.responseText);
      },
    });
    return false;
  } else {
    return false;
  }
}

function ActTabAlu() {
  var idG = $("#GrupoTu").val();
  var url = "/sta/php/init/GROUP_Listar.php";
  $.ajax({
    type: "POST",
    url: url,
    data: {
      Grup: idG,
    },
    success: function (data) {
      $("#lista").empty();
      $("#lista").append(data);
      $("#mitabla").DataTable({
        order: [[1, "asc"]],
        destroy: true,
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
}

function ActTabAluPDF() {
  var idG = $("#GrupoTuPDF").val();
  var url = "/sta/php/init/GROUP_ListarPDF.php";
  $.ajax({
    type: "POST",
    url: url,
    data: {
      Grup: idG,
    },
    success: function (data) {
      $("#lista").empty();
      $("#lista").append(data);
      $("#mitabla").DataTable({
        order: [[1, "asc"]],
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
}

function AT_Eliminar(id, nom) {
  var opcion = confirm(
    "Desea eliminar el acceso al Area de tutorias  a " + nom
  );
  if (opcion == true) {
    jQuery
      .ajax({
        url: "/sta/php/init/AT_Eliminar.php",
        type: "POST",
        dataType: "json",
        data: {
          id_cat: id,
        },
      })
      .done(function (res) {
        sendMessageR(res.text, res.class);

        console.log(res.text);

        if (res.err == false) {
          setTimeout(function () {
            location.reload();
          }, 3000);
        }
      })
      .fail(function (resp) {
        console.log(resp.responseText);
      })
      .always(function () {
        console.log("complete");
      });
  }
}

function TU_Eliminar(id, nom, tip) {
  var opcion = confirm("Desea eliminar el acceso de Tutor  a " + nom);
  if (opcion == true) {
    jQuery
      .ajax({
        url: "/sta/php/init/TU_Eliminar.php",
        type: "POST",
        dataType: "json",
        data: {
          id_cat: id,
          tip: tip,
        },
      })
      .done(function (res) {
        sendMessageR(res.text, res.class);

        console.log(res.text);

        if (res.err == false) {
          setTimeout(function () {
            location.reload();
          }, 3000);
        }
      })
      .fail(function (resp) {
        console.log(resp.responseText);
      })
      .always(function () {
        console.log("complete");
      });
  }
}

function MED_Eliminar(id, ced, nom) {
  var opcion = confirm("Desea eliminar el acceso de Medico  a " + nom);
  if (opcion == true) {
    jQuery
      .ajax({
        url: "/sta/php/init/MED_Eliminar.php",
        type: "POST",
        dataType: "json",
        data: {
          id_cat: id,
          ced: ced,
        },
      })
      .done(function (res) {
        sendMessageR(res.text, res.class);

        console.log(res.text);

        if (res.err == false) {
          setTimeout(function () {
            location.reload();
          }, 3000);
        }
      })
      .fail(function (resp) {
        console.log(resp.responseText);
      })
      .always(function () {
        console.log("complete");
      });
  }
}

function PSI_Eliminar(id, ced, nom) {
  var opcion = confirm("Desea eliminar el acceso de Medico  a " + nom);
  if (opcion == true) {
    jQuery
      .ajax({
        url: "/sta/php/init/PSI_Eliminar.php",
        type: "POST",
        dataType: "json",
        data: {
          id_cat: id,
          ced: ced,
        },
      })
      .done(function (res) {
        sendMessageR(res.text, res.class);

        console.log(res.text);

        if (res.err == false) {
          setTimeout(function () {
            location.reload();
          }, 3000);
        }
      })
      .fail(function (resp) {
        console.log(resp.responseText);
      })
      .always(function () {
        console.log("complete");
      });
  }
}

function JFE_Eliminar(id, car, nom) {
  var opcion = confirm("Desea eliminar el acceso de Jefe de carrera  a " + nom);
  if (opcion == true) {
    jQuery
      .ajax({
        url: "/sta/php/init/JFE_Eliminar.php",
        type: "POST",
        dataType: "json",
        data: {
          id_cat: id,
          car: car,
        },
        beforeSend: function () {
          myFunction();
        },
      })
      .done(function (res) {
        myFunctionClose(res.text);
        sendMessageR(res.text, res.class);

        console.log(res.text);

        if (res.err == false) {
          setTimeout(function () {
            location.reload();
          }, 3000);
        }
      })
      .fail(function (resp) {
        console.log(resp.responseText);
      })
      .always(function () {
        console.log("complete");
      });
  }
}

function AT_Edit(id, nom) {
  var opcion = confirm(
    "Desea eliminar el acceso al Area de tutorias  a " + nom
  );
  if (opcion == true) {
    jQuery
      .ajax({
        url: "/sta/php/init/AT_Eliminar.php",
        type: "POST",
        dataType: "json",
        data: {
          id_cat: id,
        },
      })
      .done(function (res) {
        sendMessageR(res.text, res.class);

        console.log(res.text);

        if (res.err == false) {
          setTimeout(function () {
            location.reload();
          }, 3000);
        }
      })
      .fail(function (resp) {
        console.log(resp.responseText);
      })
      .always(function () {
        console.log("complete");
      });
  }
}

function TU_Edit(id, nom) {
  var opcion = confirm("Desea eliminar el acceso de Tutor  a " + nom);
  if (opcion == true) {
    jQuery
      .ajax({
        url: "/sta/php/init/TU_Eliminar.php",
        type: "POST",
        dataType: "json",
        data: {
          id_cat: id,
        },
      })
      .done(function (res) {
        sendMessageR(res.text, res.class);

        console.log(res.text);

        if (res.err == false) {
          setTimeout(function () {
            location.reload();
          }, 3000);
        }
      })
      .fail(function (resp) {
        console.log(resp.responseText);
      })
      .always(function () {
        console.log("complete");
      });
  }
}

function MED_Edit(id, nom, ap, am, ced) {
  $("#id").attr("value", id);
  $("#nombredoctor").attr("value", nom);
  $("#paterno").attr("value", ap);
  $("#materno").attr("value", am);
  $("#cedula").attr("value", ced);

  $("#editarDoctor").modal("show");

  $("#updateUserMedico").click(function (event) {
    event.preventDefault();
    var ide = $("#id").val();
    var nombre = $("#nombredoctor").val();
    var apa = $("#paterno").val();
    var ama = $("#materno").val();
    var cedula = $("#cedula").val();

    if (nombre === nom && apa === ap && ama === am && cedula === ced) {
      sendMessageR("No has modificado ningun dato", "");
      return false;
    }

    if (nombre === "") {
      alert("Ingresa por favor el Nombre");
      return false;
    }

    if (apa === "") {
      alert("Debes ingresar al menos el Apellido paterno");
      return false;
    }

    if (cedula === "") {
      alert("deves ingresar la cedula profecional para futuras acciones");
      return false;
    }
    jQuery
      .ajax({
        url: "/sta/php/init/MED_Editar.php",
        type: "POST",
        dataType: "json",
        data: {
          id: ide,
          nom: nombre,
          ap: apa,
          am: ama,
          ced: cedula,
          ced2: ced,
        },
      })
      .done(function (res) {
        if (res.error == false) {
          sendMessageR(res.res, "");
          console.log(res.res);
          setTimeout(function () {
            location.reload();
          }, 3000);
        } else {
          sendMessageR(res.er, "");
        }
      })
      .fail(function (resp) {
        console.log(resp.responseText);
      })
      .always(function () {
        console.log("complete");
      });
  });
}

function PSI_Edit(id, nom, ap, am, ced) {
  $("#id").attr("value", id);
  $("#nombrepsicologo").attr("value", nom);
  $("#paterno").attr("value", ap);
  $("#materno").attr("value", am);
  $("#cedula").attr("value", ced);

  $("#editarPsicologo").modal("show");

  $("#updateUserPsico").click(function (event) {
    event.preventDefault();
    var ide = $("#id").val();
    var nombre = $("#nombrepsicologo").val();
    var apa = $("#paterno").val();
    var ama = $("#materno").val();
    var cedula = $("#cedula").val();

    if (nombre === nom && apa === ap && ama === am && cedula === ced) {
      sendMessageR("No has modificado ningun dato", "");
      return false;
    }

    if (nombre === "") {
      alert("Ingresa por favor el Nombre");
      return false;
    }

    if (apa === "") {
      alert("Debes ingresar al menos el Apellido paterno");
      return false;
    }

    if (cedula === "") {
      alert("deves ingresar la cedula profecional para futuras acciones");
      return false;
    }
    jQuery
      .ajax({
        url: "/sta/php/init/PSI_Editar.php",
        type: "POST",
        dataType: "json",
        data: {
          id: ide,
          nom: nombre,
          ap: apa,
          am: ama,
          ced: cedula,
          ced2: ced,
        },
      })
      .done(function (res) {
        if (res.error == false) {
          sendMessageR(res.res, "");
          console.log(res.res);
          setTimeout(function () {
            location.reload();
          }, 3000);
        } else {
          sendMessageR(res.er, "");
        }
      })
      .fail(function (resp) {
        console.log(resp.responseText);
      })
      .always(function () {
        console.log("complete");
      });
  });
}

function JFE_Edit(id, nom, ap, am) {
  $("#id").attr("value", id);
  $("#nombredocente").attr("value", nom);
  $("#paterno").attr("value", ap);
  $("#materno").attr("value", am);

  $("#editarDocente").modal("show");

  $("#updateUserDoce").click(function (event) {
    event.preventDefault();
    var ide = $("#id").val();
    var nombre = $("#nombredocente").val();
    var apa = $("#paterno").val();
    var ama = $("#materno").val();

    if (nombre === nom && apa === ap && ama === am) {
      sendMessageR("No has modificado ningun dato", "");
      return false;
    }

    if (nombre === "") {
      alert("Ingresa por favor el Nombre");
      return false;
    }

    if (apa === "") {
      alert("Debes ingresar al menos el Apellido paterno");
      return false;
    }

    jQuery
      .ajax({
        url: "/sta/php/init/DOC_Editar.php",
        type: "POST",
        dataType: "json",
        data: {
          id: ide,
          nom: nombre,
          ap: apa,
          am: ama,
        },
      })
      .done(function (res) {
        if (res.error == false) {
          sendMessageR(res.res, "");
          console.log(res.res);
          setTimeout(function () {
            location.reload();
          }, 3000);
        } else {
          sendMessageR(res.er, "");
        }
      })
      .fail(function (resp) {
        console.log(resp.responseText);
      })
      .always(function () {
        console.log("complete");
      });
  });
}

function myFunction() {
  $("body").loadingModal({
    text: "Cargando...",
    animation: "wave",
    backgroundColor: "rgb(107, 5, 5)",
  });
}

function myFunctionClose(text) {
  var delay = function (ms) {
    return new Promise(function (r) {
      setTimeout(r, ms);
    });
  };
  var time = 5000;
  delay(time)
    .then(function () {
      $("body")
        .loadingModal("color", "black")
        .loadingModal("text", text)
        .loadingModal("backgroundColor", "rgb(158, 130, 14)");
      return delay(time);
    })
    .then(function () {
      $("body").loadingModal("hide");
      return delay(time);
    })
    .then(function () {
      $("body").loadingModal("destroy");
    });
}

function sendMessageR(text, color) {
  $("#alert_msg").empty().html(text);
  $("#alert_msg").removeClass();
  $("#alert_msg").addClass("alert text-center");
  $("#alert_msg").css("background-color", color);

  $("#alert_msg").css("display", "");
  $("#alert_msg").fadeIn(500);
  setTimeout(function () {
    $("#alert_msg").fadeOut(5000);
  }, 5000);
}

function eneableDeseable(chec, text) {
  if (document.getElementById(chec).checked) {
    document.getElementById(text).readOnly = false;
  } else {
    document.getElementById(text).readOnly = true;
    document.getElementById(text).value = "";
  }
}

function pdf_validet($nom, $val) {
  $pdf = 0;
  if ($val) {
    $pdf = 1;
  }
  jQuery.ajax({
    url: "/sta/php/init/pdf_validate.php",
    type: "POST",
    dataType: "json",
    data: {
      ncon: $nom,
      val: $pdf,
    },
    success: function (data) {
      if (!data.error) {
        sendMessageR(data.mensaje, "#00ff00");
      } else {
        sendMessageR(data.mensaje + "error", "#DA0000");
      }
    },
    error: function (xhr, status) {
      console.log(xhr.responseText);
    },
    complete: function (xhr, status) {
      console.log("Exito");
    },
  });
}

function tutSegi(nc, seg) {
  $("#alu").attr("value", nc);
  $("#seg").attr("value", seg);
  $("#aluSeg").modal("show");
}
