    /// <reference path="../node_modules/@types/jquery/index.d.ts" />

function Registrar()
{
    let formUsuario = new FormData();
    let nombre = $('#nombre').val();
    let correo = $('#mailRegistro').val();
    let clave = $('#claveRegistro').val();
    let apellido = $('#apellido').val();
    let perfil = $('#perfil').val();
    let foto = $("#foto").prop('files')[0];
    let user = {'correo':correo,'clave':clave,'nombre':nombre,'apellido':apellido,'perfil':perfil};
    formUsuario.append("usuario",JSON.stringify(user));
    formUsuario.append("foto", foto);

    let ajax = $.ajax({
        type: "post",
        url: "./backend/usuarios/",
        dataType: "json",
        contentType: false,
        processData : false,
        data: formUsuario,
        async: true
    });
    ajax.done(function(response){
        if(response.exito)
        {    
            window.location.href='./login.html';
        }
        else
        {
            $('#errorRegistro').removeAttr("hidden");
        }
        console.log(response);
    });
    ajax.fail(function(error){
        console.log(error);
    });
}