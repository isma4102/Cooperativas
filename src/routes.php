<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

require_once 'Controladores/controlador.php';
require_once 'conexion/abrir_conexion.php';
require_once '../src/Clases/console.php';


return function (App $app) {
  $routes = require_once __DIR__ . "/../src/routes_principal.php";
  $container = $app->getContainer();
  $routes($app);

  $app->get('/', function (Request $request, Response $response, array $args) use ($container) {
    $controlador = new controlador();
    if (isset($_SESSION['ID'])) {
      $nombre = $_SESSION['Nombre'];
      $socios = $controlador->socios_lista();
      $response = $this->view->render($response, 'index.twig', compact('socios','nombre'));
    } else
      $response = $this->view->render($response, 'Login.twig');

    return $response;
  })->setName("inicio");

  $app->get('/inicio', function (Request $request, Response $response, array $args) use ($container) {
    $controlador = new controlador();
    $nombre = $_SESSION['Nombre'];
    $socios = $controlador->socios_lista();
    $response = $this->view->render($response, 'index.twig', compact('socios','nombre'));
    return $response;
  })->setName("inicio");
};
