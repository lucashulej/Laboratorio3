"use strict";
/// <reference path="../node_modules/@types/jquery/index.d.ts" />
function Registrar() {
    var formUsuario = new FormData();
    var nombre = $('#nombre').val();
    var correo = $('#mailRegistro').val();
    var clave = $('#claveRegistro').val();
    var apellido = $('#apellido').val();
    var perfil = $('#perfil').val();
    var foto = $("#foto").prop('files')[0];
    var user = { 'correo': correo, 'clave': clave, 'nombre': nombre, 'apellido': apellido, 'perfil': perfil };
    formUsuario.append("usuario", JSON.stringify(user));
    formUsuario.append("foto", foto);
    var ajax = $.ajax({
        type: "post",
        url: "./backend/usuarios/",
        dataType: "json",
        contentType: false,
        processData: false,
        data: formUsuario,
        async: true
    });
    ajax.done(function (response) {
        if (response.exito) {
            window.location.href = './login.html';
        }
        else {
            $('#errorRegistro').removeAttr("hidden");
        }
        console.log(response);
    });
    ajax.fail(function (error) {
        console.log(error);
    });
}
