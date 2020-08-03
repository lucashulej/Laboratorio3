<?php
    use \Psr\Http\Message\ServerRequestInterface as Request;
    use \Psr\Http\Message\ResponseInterface as Response;
    use \Firebase\JWT\JWT;
    require_once './vendor/autoload.php';
    require_once './clases/usuario.php';
    require_once './clases/barbijo.php';

    
    $config['displayErrorDetails'] = true;
    $config['addContentLengthHeader'] = false;
    
    $app = new \Slim\App(["settings" => $config]);

    $app->post('/login/', \Usuario::class . ':Login');

    $app->post('/', \Barbijo::class . ':CargarBarbijo');

    $app->post('/usuarios/', \Usuario::class . ':CargarUsuario');

    $app->get('/', \Usuario::class . ':Lista');

    $app->get('/barbijos/', \Barbijo::class . ':Lista');

    $app->post('/userValidation/', \Usuario::class . ':Validar');

    $app->get('/login/', \Usuario::class . ':VerificarToken');

    $app->delete('/', \Barbijo::class . ':BorrarUno');
    
    $app->put('/', \Barbijo::class . ':ModificarUno');

    $app->get('/obtenerData/', \Barbijo::class . ':ObtenerData');

    $app->run();