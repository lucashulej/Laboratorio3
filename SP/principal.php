<!DOCTYPE html>
<html>

<head>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <script type="text/javascript" src="./frontend/principal.js"></script>
</head>

<body>

    <body style="background-color:beige">
        <!-- NAVBAR -->
        <div class="container-fluid" style="margin-top:30px">
            <nav class="navbar navbar-expand-md bg-dark navbar-dark fixed-top">

                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="collapsibleNavbar">
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown" style="color: royalblue;">
                                Listados <b class="caret"></b>
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" onclick="GenerarTablaUsuarios()">Usuarios</a>
                                <a class="dropdown-item" onclick="GenerarTablaBarbijos()">Barbijos</a>
                            </div>
                        </li>
                        <button class="btn nav-item" style="color: royalblue;" onclick="MostrarAltaBarbijos()"> Alta Barbijos</button>
                    </ul>
                </div>

            </nav>
        </div>

        <!-- SECCIONES -->
        <div class="container col-md-12 d-flex" style="height: 500px; margin-top: 10%;">

            <!-- IZQUIERDA -->
            <div class="col-md-6 table-responsive" style="background-color: red;">
                <div id="tablaBarbijos"></div>
                <div class="alert alert-danger" id="errorTablaBarbijos" hidden>No se pudo cargar la tabla de barbijos</div>
            </div>

            <!-- DERECHA -->
            <div class="col-md-6 table-responsive" style="background-color: green;">
                <div id="tablaUsuarios"></div>
                <div class="alert alert-danger" id="errorTablaUsuarios" hidden>No se pudo cargar la tabla de usuarios</div>

                <!-- ABARBIJOS -->
                <form id="barbijosForm" method="post" style="background-color: darkcyan;" hidden>
                    <!-- ID -->
                    <input id="id" class="form-control" type="text" hidden>
                    <!-- COLOR -->
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-palette"></i></span>
                            <input id="color" class="form-control" type="text" placeholder="Color">
                        </div>
                    </div>
                    <!-- TIPO -->
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-list"></i></span>
                            <input id="tipo" class="form-control" type="text" placeholder="Tipo">
                        </div>
                    </div>
                    <!-- PRECIO -->
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                            <input id="precio" class="form-control" type="number" placeholder="Precio">
                        </div>
                    </div>
                    <!-- BOTONES -->
                    <div class="form-group text-center">
                        <button class="btn btn-success col-5" type="button" id="btnAgregar" onclick="AgregarBarbijo()" hidden>Agregar</button>
                        <button class="btn btn-success col-5" type="button" id="btnModificar" onclick="ModificarBarbijo()" hidden>Modificar</button>
                        <span class="col-2"></span>
                        <button class="btn btn-warning col-5" type="button" id="btnLimpiar" onclick="Limpiar()">Limpiar</button>
                    </div> 
                    <div class="alert alert-danger" id="error" hidden></div>
                    <div class="alert alert-success" id="exito" hidden></div>
                </form>            
                <!--////////////////////////////////////////////////////////////////////////////////////////////////////////-->
            </div>

        </div>
        <div class="alert alert-danger invisible" id="errorEliminacion"></div>
        <div class="alert alert-info" id="alertPromedioPrecios" hidden></div>
        
    </body>
</html>