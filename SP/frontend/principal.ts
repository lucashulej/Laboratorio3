/// <reference path="../node_modules/@types/jquery/index.d.ts" />

import { valHooks } from "jquery";

function GenerarTablaUsuarios()
{
    let jwt = localStorage.getItem('jwt');
    let ajaxLogin = $.ajax({
        type: "get",
        url: "./backend/login/",
        dataType: "json",
        headers: {'token':jwt},
        async: true
    });
    ajaxLogin.done(function(response){
        if(response.exito)
        {
            let ajax = $.ajax({
                type: "get",
                url: "./backend/",
                dataType: "json",
                async: true
            });
            ajax.done(function(response){
                if(response.exito)
                {
                    ImprimirTablaUsuarios(response.tabla);   
                }
                else
                {
                    $("#errorTablaUsuarios").removeAttr("hidden");
                }
                console.log(response);
            });
            ajax.fail(function(error){
                console.log(error);
            })
        }
        else
        {
            window.location.href = './login.html';
         }})
}

function ImprimirTablaUsuarios(tabla : any)
{
    let tablaHtml : string = "";
    tablaHtml += "<table class='table table-bordered'> <tr> <td>CORREO</td> <td>NOMBRE</td> <td>APELLIDO</td> <td>PERFIL</td> <td>FOTO</td></tr>";
    for (const usuario of tabla) 
    {
        tablaHtml += "<tr><td>" + usuario.correo + "</td>";
        tablaHtml += "<td>" + usuario.nombre + "</td>";
        tablaHtml += "<td>" + usuario.apellido + "</td>";
        tablaHtml += "<td>" + usuario.perfil + "</td>";
        tablaHtml += "<td><img src='" + './backend/' + usuario.foto + "'height=60px width=60px></td></tr>";
    }
    tablaHtml += "</table>";
    $("#tablaUsuarios").html(tablaHtml);
}

function GenerarTablaBarbijos()
{
    let jwt = localStorage.getItem('jwt');
    let ajaxLogin = $.ajax({
        type: "get",
        url: "./backend/login/",
        dataType: "json",
        headers: {'token':jwt},
        async: true
    });
    ajaxLogin.done(function(response){
        if(response.exito)
        {
            let ajax = $.ajax({
                type: "get",
                url: "./backend/barbijos/",
                dataType: "json",
                async: true
            });
            ajax.done(function(response){
                if(response.exito)
                {
                    ImprimirTablaBarbijos(response.tabla); 
                }
                else
                {
                    $("#errorTablaBarbijos").removeAttr("hidden");
                }
                console.log(response);
            });
        }
        else
        {
            window.location.href = './login.html';
        }})
}


function ImprimirTablaBarbijos(tabla : any)
{
    let tablaHtml : string = "";
    tablaHtml += "<table class='table table-striped'> <tr><td>COLOR</td> <td>TIPO</td> <td>PRECIO</td> <td>ELIMINAR</td> <td>MODIFICAR</td></tr>";
    for (const barbijo of tabla) 
    {
        tablaHtml += "<tr><td>" + barbijo.color + "</td>";
        tablaHtml += "<td>" + barbijo.tipo + "</td>";
        tablaHtml += "<td>" + barbijo.precio + "</td>";
        tablaHtml += "<td><button onclick='Borrar(" + barbijo.id + ")' class='btn btn-danger'>ELIMINAR</button></td>";
        tablaHtml += "<td><button onclick='Modificar(" + barbijo.id + ")' class='btn btn-info'>MODIFICAR</button></td></tr>";
    }
    tablaHtml += "</table>";
    $("#tablaBarbijos").html(tablaHtml);
}


function Borrar(id:number)
{
    let jwt = localStorage.getItem('jwt');
    let ajax = $.ajax({
        type: "get",
        url: "./backend/login/",
        dataType: "json",
        headers: {'token':jwt},
        async: true
       });
    ajax.done(function(response){
    if(response.exito)
    {
        if(confirm('¿Desea eliminar al barbijo '+id+'?'))
        {
            let ajax = $.ajax({
                type: "delete",
                url: "./backend/",
                dataType: "json",
                contentType: "application/x-www-form-urlencoded",
                data: {'id_barbijo': id.toString()},
                headers:{'token': localStorage.getItem('jwt')},
                async: true
            });
            ajax.done(function(response){
                if(response.exito)
                {
                    GenerarTablaBarbijos();
                    $("#errorEliminacion").addClass('invisible'); //SI SE ELIMINAR SE OCULTA SI ESTA A LA VISTA
                }
                else
                {
                    $("#errorEliminacion").html(response.mensaje);
                    $("#errorEliminacion").removeClass('invisible');
                }
                console.log(response);
            });
        }
    }
    else
    {
        window.location.href = './login.html';
    }
    });
}

function Modificar(id:number)
{
    Limpiar();
    $("#barbijosForm").removeAttr("hidden");
    $("#btnModificar").removeAttr("hidden");
    $("#btnAgregar").attr("hidden", "true");
    $("#id").val(id);
    ObtenerDataBarbijo(id);
}

function ModificarBarbijo()
{
    let jwt = localStorage.getItem('jwt');
    let ajax = $.ajax({
        type: "get",
        url: "./backend/login/",
        dataType: "json",
        headers: {'token':jwt},
        async: true
    });
    ajax.done(function(response){
        if(response.exito)
        {
            let id = $('#id').val();
            let color = $('#color').val();
            let tipo = $('#tipo').val();
            let precio = $('#precio').val();
            let barbijo = {"id":id,"color":color,"tipo":tipo,"precio":precio};
            let datoObjeto = {"barbijo":JSON.stringify(barbijo)};
            
            let ajax = $.ajax({
                type: "put",
                url: "./backend/",
                dataType: "json",
                contentType: "application/x-www-form-urlencoded",
                data: datoObjeto,
                headers:{'jwt': localStorage.getItem('jwt')},
                async: true
            });
            ajax.done(function(response){
                if(response.exito)
                {
                    GenerarTablaBarbijos();
                    $('#exito').removeAttr("hidden");
                    $('#exito').html("Exito al modificar barbijo");
                    $('#error').attr("hidden", "true");
                }
                else
                {
                    $('#error').removeAttr("hidden");
                    $('#error').html("Error al modificar barbijo");
                    $('#exito').attr("hidden", "true");
                }
                console.log(response);
            });
        }
        else
        {
            window.location.href = './login.html';
        }})
}

function MostrarAltaBarbijos()
{
    Limpiar();
    $("#barbijosForm").removeAttr("hidden");
    $("#btnAgregar").removeAttr("hidden");
    $("#btnModificar").attr("hidden", "true");
}

function AgregarBarbijo()
{
    let jwt = localStorage.getItem('jwt');
    let ajaxLogin = $.ajax({
        type: "get",
        url: "./backend/login/",
        dataType: "json",
        headers: {'token':jwt},
        async: true
    });
    ajaxLogin.done(function(response){
        if(response.exito)
        {
            let color = $('#color').val();
            let tipo = $('#tipo').val();
            let precio = $('#precio').val();
            let barbijo = {'color':color,'tipo':tipo,'precio':precio};
            let datoObjeto = {"barbijo":JSON.stringify(barbijo)}; 
        
            let ajax = $.ajax({
                type: "post",
                url: "./backend/",
                dataType: "json",
                data: datoObjeto,
                async: true
            });
            ajax.done(function(response){
                if(response.exito)
                {    
                    $('#exito').removeAttr("hidden");
                    $('#exito').html("Exito al agregar barbijo");
                    $('#error').attr("hidden", "true");
                }
                else
                {
                    $('#error').removeAttr("hidden");
                    $('#error').html("Error al agregar barbijo");
                    $('#exito').attr("hidden", "true");
                }
                console.log(response);
            });
        }
        else
        {
            window.location.href = './login.html';
        }})
}

function ObtenerDataBarbijo(id:number)
{
    let ajax = $.ajax({
        type: "get",
        url: "./backend/obtenerData/",
        headers: {"id":id.toString()},
        contentType: false,
        processData: false,
        dataType: "json",
        async: true
    });
    ajax.done(function(response)
    {
        if(response.barbijo != null)
        {
            let barbijo = response.barbijo[0];
            $("#color").val(barbijo.color);
            $("#tipo").val(barbijo.tipo);
            $("#precio").val(barbijo.precio);
        }
        console.log(response);
    });

}

function Limpiar()
{
    $('#exito').attr("hidden", "true");
    $('#error').attr("hidden", "true");
    $("#color").val("");
    $("#tipo").val("");
    $("#precio").val("");
}

