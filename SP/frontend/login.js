"use strict";
/// <reference path="../node_modules/@types/jquery/index.d.ts" />
function Validar() {
    var correo = $('#mailLogin').val();
    var clave = $('#claveLogin').val();
    var datoObjeto = { "user": { "correo": correo, "clave": clave } };
    var ajaxLogin = $.ajax({
        type: "post",
        url: "./backend/login/",
        dataType: "json",
        data: datoObjeto,
        async: true
    });
    ajaxLogin.done(function (response) {
        if (response.exito) {
            localStorage.setItem('jwt', response.jwt);
            window.location.href = './principal.php';
        }
        else {
            $('#errorLogin').removeAttr("hidden");
        }
        console.log(response);
    });
}
function registrarme() {
    window.location.href = './registro.html';
}
