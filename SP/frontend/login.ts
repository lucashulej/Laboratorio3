/// <reference path="../node_modules/@types/jquery/index.d.ts" />

function Validar()
{ 
    let correo = $('#mailLogin').val();
    let clave = $('#claveLogin').val();
    let datoObjeto = {"user" : {"correo" : correo , "clave" : clave}}
    let ajaxLogin = $.ajax({
        type: "post",
        url: "./backend/login/",
        dataType: "json",
        data: datoObjeto,
        async: true
    });
    ajaxLogin.done(function(response){
        if(response.exito)
        {
            localStorage.setItem('jwt', response.jwt);
            window.location.href = './principal.php';
        }
        else
        {
            $('#errorLogin').removeAttr("hidden");
        }
        console.log(response);
    });
}

function registrarme()
{
    window.location.href = './registro.html';
}