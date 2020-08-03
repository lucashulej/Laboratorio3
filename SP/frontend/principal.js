"use strict";
/// <reference path="../node_modules/@types/jquery/index.d.ts" />
Object.defineProperty(exports, "__esModule", { value: true });
function GenerarTablaUsuarios() {
    var jwt = localStorage.getItem('jwt');
    var ajaxLogin = $.ajax({
        type: "get",
        url: "./backend/login/",
        dataType: "json",
        headers: { 'token': jwt },
        async: true
    });
    ajaxLogin.done(function (response) {
        if (response.exito) {
            var ajax = $.ajax({
                type: "get",
                url: "./backend/",
                dataType: "json",
                async: true
            });
            ajax.done(function (response) {
                if (response.exito) {
                    ImprimirTablaUsuarios(response.tabla);
                }
                else {
                    $("#errorTablaUsuarios").removeAttr("hidden");
                }
                console.log(response);
            });
            ajax.fail(function (error) {
                console.log(error);
            });
        }
        else {
            window.location.href = './login.html';
        }
    });
}
function ImprimirTablaUsuarios(tabla) {
    var tablaHtml = "";
    tablaHtml += "<table class='table table-bordered'> <tr> <td>CORREO</td> <td>NOMBRE</td> <td>APELLIDO</td> <td>PERFIL</td> <td>FOTO</td></tr>";
    for (var _i = 0, tabla_1 = tabla; _i < tabla_1.length; _i++) {
        var usuario = tabla_1[_i];
        tablaHtml += "<tr><td>" + usuario.correo + "</td>";
        tablaHtml += "<td>" + usuario.nombre + "</td>";
        tablaHtml += "<td>" + usuario.apellido + "</td>";
        tablaHtml += "<td>" + usuario.perfil + "</td>";
        tablaHtml += "<td><img src='" + './backend/' + usuario.foto + "'height=60px width=60px></td></tr>";
    }
    tablaHtml += "</table>";
    $("#tablaUsuarios").html(tablaHtml);
}
function GenerarTablaBarbijos() {
    var jwt = localStorage.getItem('jwt');
    var ajaxLogin = $.ajax({
        type: "get",
        url: "./backend/login/",
        dataType: "json",
        headers: { 'token': jwt },
        async: true
    });
    ajaxLogin.done(function (response) {
        if (response.exito) {
            var ajax = $.ajax({
                type: "get",
                url: "./backend/barbijos/",
                dataType: "json",
                async: true
            });
            ajax.done(function (response) {
                if (response.exito) {
                    ImprimirTablaBarbijos(response.tabla);
                }
                else {
                    $("#errorTablaBarbijos").removeAttr("hidden");
                }
                console.log(response);
            });
        }
        else {
            window.location.href = './login.html';
        }
    });
}
function ImprimirTablaBarbijos(tabla) {
    var tablaHtml = "";
    tablaHtml += "<table class='table table-striped'> <tr><td>COLOR</td> <td>TIPO</td> <td>PRECIO</td> <td>ELIMINAR</td> <td>MODIFICAR</td></tr>";
    for (var _i = 0, tabla_2 = tabla; _i < tabla_2.length; _i++) {
        var barbijo = tabla_2[_i];
        tablaHtml += "<tr><td>" + barbijo.color + "</td>";
        tablaHtml += "<td>" + barbijo.tipo + "</td>";
        tablaHtml += "<td>" + barbijo.precio + "</td>";
        tablaHtml += "<td><button onclick='Borrar(" + barbijo.id + ")' class='btn btn-danger'>ELIMINAR</button></td>";
        tablaHtml += "<td><button onclick='Modificar(" + barbijo.id + ")' class='btn btn-info'>MODIFICAR</button></td></tr>";
    }
    tablaHtml += "</table>";
    $("#tablaBarbijos").html(tablaHtml);
}
function Borrar(id) {
    var jwt = localStorage.getItem('jwt');
    var ajax = $.ajax({
        type: "get",
        url: "./backend/login/",
        dataType: "json",
        headers: { 'token': jwt },
        async: true
    });
    ajax.done(function (response) {
        if (response.exito) {
            if (confirm('¿Desea eliminar al barbijo ' + id + '?')) {
                var ajax_1 = $.ajax({
                    type: "delete",
                    url: "./backend/",
                    dataType: "json",
                    contentType: "application/x-www-form-urlencoded",
                    data: { 'id_barbijo': id.toString() },
                    headers: { 'token': localStorage.getItem('jwt') },
                    async: true
                });
                ajax_1.done(function (response) {
                    if (response.exito) {
                        GenerarTablaBarbijos();
                        $("#errorEliminacion").addClass('invisible'); //SI SE ELIMINAR SE OCULTA SI ESTA A LA VISTA
                    }
                    else {
                        $("#errorEliminacion").html(response.mensaje);
                        $("#errorEliminacion").removeClass('invisible');
                    }
                    console.log(response);
                });
            }
        }
        else {
            window.location.href = './login.html';
        }
    });
}
function Modificar(id) {
    Limpiar();
    $("#barbijosForm").removeAttr("hidden");
    $("#btnModificar").removeAttr("hidden");
    $("#btnAgregar").attr("hidden", "true");
    $("#id").val(id);
    ObtenerDataBarbijo(id);
}
function ModificarBarbijo() {
    var jwt = localStorage.getItem('jwt');
    var ajax = $.ajax({
        type: "get",
        url: "./backend/login/",
        dataType: "json",
        headers: { 'token': jwt },
        async: true
    });
    ajax.done(function (response) {
        if (response.exito) {
            var id = $('#id').val();
            var color = $('#color').val();
            var tipo = $('#tipo').val();
            var precio = $('#precio').val();
            var barbijo = { "id": id, "color": color, "tipo": tipo, "precio": precio };
            var datoObjeto = { "barbijo": JSON.stringify(barbijo) };
            var ajax_2 = $.ajax({
                type: "put",
                url: "./backend/",
                dataType: "json",
                contentType: "application/x-www-form-urlencoded",
                data: datoObjeto,
                headers: { 'jwt': localStorage.getItem('jwt') },
                async: true
            });
            ajax_2.done(function (response) {
                if (response.exito) {
                    GenerarTablaBarbijos();
                    $('#exito').removeAttr("hidden");
                    $('#exito').html("Exito al modificar barbijo");
                    $('#error').attr("hidden", "true");
                }
                else {
                    $('#error').removeAttr("hidden");
                    $('#error').html("Error al modificar barbijo");
                    $('#exito').attr("hidden", "true");
                }
                console.log(response);
            });
        }
        else {
            window.location.href = './login.html';
        }
    });
}
function MostrarAltaBarbijos() {
    Limpiar();
    $("#barbijosForm").removeAttr("hidden");
    $("#btnAgregar").removeAttr("hidden");
    $("#btnModificar").attr("hidden", "true");
}
function AgregarBarbijo() {
    var jwt = localStorage.getItem('jwt');
    var ajaxLogin = $.ajax({
        type: "get",
        url: "./backend/login/",
        dataType: "json",
        headers: { 'token': jwt },
        async: true
    });
    ajaxLogin.done(function (response) {
        if (response.exito) {
            var color = $('#color').val();
            var tipo = $('#tipo').val();
            var precio = $('#precio').val();
            var barbijo = { 'color': color, 'tipo': tipo, 'precio': precio };
            var datoObjeto = { "barbijo": JSON.stringify(barbijo) };
            var ajax = $.ajax({
                type: "post",
                url: "./backend/",
                dataType: "json",
                data: datoObjeto,
                async: true
            });
            ajax.done(function (response) {
                if (response.exito) {
                    $('#exito').removeAttr("hidden");
                    $('#exito').html("Exito al agregar barbijo");
                    $('#error').attr("hidden", "true");
                }
                else {
                    $('#error').removeAttr("hidden");
                    $('#error').html("Error al agregar barbijo");
                    $('#exito').attr("hidden", "true");
                }
                console.log(response);
            });
        }
        else {
            window.location.href = './login.html';
        }
    });
}
function ObtenerDataBarbijo(id) {
    var ajax = $.ajax({
        type: "get",
        url: "./backend/obtenerData/",
        headers: { "id": id.toString() },
        contentType: false,
        processData: false,
        dataType: "json",
        async: true
    });
    ajax.done(function (response) {
        if (response.barbijo != null) {
            var barbijo = response.barbijo[0];
            $("#color").val(barbijo.color);
            $("#tipo").val(barbijo.tipo);
            $("#precio").val(barbijo.precio);
        }
        console.log(response);
    });
}
function Limpiar() {
    $('#exito').attr("hidden", "true");
    $('#error').attr("hidden", "true");
    $("#color").val("");
    $("#tipo").val("");
    $("#precio").val("");
}
