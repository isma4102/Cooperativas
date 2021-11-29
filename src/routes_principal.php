<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

require_once 'Controladores/controlador.php';
require_once '../src/Clases/console.php';

return function (App $app) {
	$container = $app->getContainer();
	$app->get('/socios', function (Request $request, Response $response, array $args) use ($container) {
		$controlador = new controlador();
		if (isset($_SESSION['ID'])) {
			$nombre = $_SESSION['Nombre'];
			$resultado = $controlador->cooperativas_lista();
			$response = $this->view->render($response, 'socios.twig', compact('resultado', 'nombre'));
		} else
			$response = $this->view->render($response, 'Login.twig');


		return $response;
	})->setName("socios");

	$app->get('/cotizaciones', function (Request $request, Response $response, array $args) use ($container) {
		$controlador = new controlador();
		$resultado = $controlador->cotizaciones();
		return $resultado;
	})->setName("cotizaciones");

	$app->post('/ingresar_recibo', function (Request $request, Response $response, array $args) use ($container) {
		$data = $request->getParams();
		$select_cobro = $data['select_cobro'];
		$select_pago = $data['select_pago'];
		$socio = $data['socio'];
		$Concepto = $data['Concepto'];
		$Moneda = $data['Moneda'];
		$ingreso = $data['ingreso'];
		$n_cuenta = $data['n_cuenta'];
		$controlador = new controlador();
		$resultado = $controlador->ingresar_recibo($select_cobro, $select_pago, $socio, $Concepto, $Moneda, $ingreso,$n_cuenta);
		return $resultado;
	})->setName("ingresar_recibo");

	$app->post('/ingresar_socio', function (Request $request, Response $response, array $args) use ($container) {
		$data = $request->getParams();
		$nombre = $data['nombre'];
		$apellido = $data['apellido'];
		$telefono = $data['telefono'];
		$controlador = new controlador();
		$resultado = $controlador->ingresar_socio($nombre,$apellido,$telefono);
		return $resultado;
	})->setName("ingresar_socio");

	$app->post('/login', function (Request $request, Response $response, $args) use ($container) {
		$data = $request->getParams();
		$nombre = $data['nombre'];
		$contraseña = $data['contraseña'];
		$controlador = new controlador();
		$resultado = $controlador->login($nombre, $contraseña);
		if ($resultado != "0") {
			$_SESSION['Nombre'] = $resultado[0]['Nombre'];
			$_SESSION['ID'] = $resultado[0]['ID'];
			return json_encode($resultado);
		} else {
			return "0";
		}
	})->setName("login_twig");

	$app->get('/cerrar_sesion', function (Request $request, Response $response, $args) use ($container) {
		unset($_SESSION['Nombre']);
		unset($_SESSION['ID']);
		$response = $this->view->render($response, 'Login.twig');
		return $response;
	})->setName("cerrar_sesion");

	$app->get('/imprimir_todo', function (Request $request, Response $response, $args) use ($container) {
		$controlador = new controlador();
		$ret = $controlador->imprimir_todo($_GET["id"]);
		return $ret;
	})->setName("imprimir_todo");

	$app->get('/imprimir_uno', function (Request $request, Response $response, $args) use ($container) {
		$controlador = new controlador();
		$ret = $controlador->imprimir_uno($_GET["id"]);
		return $ret;
	})->setName("imprimir_uno");
};
