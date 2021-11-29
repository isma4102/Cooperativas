<?php

use PHPMailer\PHPMailer\PHPMailer;

require("../vendor/phpmailer/src/PHPMailer.php");
require("../vendor/phpmailer/src/SMTP.php");
require("../vendor/phpmailer/src/Exception.php");


require_once '../src/Clases/Clase_principal.php';
require_once '../src/Clases/Cotizaciones.php';
require_once '../src/Clases/console.php';




/**
 */ class controlador
{

	public function cooperativas_lista()
	{
		$resultado = Clase_principal::cooperativas_lista();
		return $resultado;
	}
	public function login($nombre,$contraseña)
	{
		$resultado = Clase_principal::login($nombre,$contraseña);
		return $resultado;
	}
	public function socios_lista()
	{
		$resultado = Clase_principal::socios_lista();
		return $resultado;
	}
	public function cotizaciones()
	{
		$resultado = Cotizaciones::getCotizaciones();
		return $resultado;
	}
	public function ingresar_recibo($select_cobro, $select_pago, $socio, $Concepto, $Moneda, $ingreso,$n_cuenta)
	{
		$resultado = Clase_principal::ingresar_recibo($select_cobro, $select_pago, $socio, $Concepto, $Moneda, $ingreso,$n_cuenta);
		return $resultado;
	}
	public function ingresar_socio($nombre,$apellido,$telefono)
	{
		$resultado = Clase_principal::ingresar_socio($nombre,$apellido,$telefono);
		return $resultado;
	}

	public function imprimir_todo($id)
	{
		$resp = Clase_principal::imprimir_todo($id);
		return $resp;
	}
	public function imprimir_uno($id)
	{
		$resp = Clase_principal::imprimir_uno($id);
		return $resp;
	}
	
}
