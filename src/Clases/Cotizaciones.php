<?php
class Cotizaciones
{
    function getCotizaciones($monedas = true)
    {
        $url = 'https://www.bcu.gub.uy/Estadisticas-e-Indicadores/Paginas/Cotizaciones.aspx?op=getcotizaciones';
        $data = "";
        $last_date_found = false;
        $diff = 1;
        $cotizaciones = array();
        $codigosAceptados = array("USD", "EURO", "CHF", "GBP", "ARS", "BRL", "JPY", "U.I.");
        while (!$last_date_found) {
            $fecha = date("d") - $diff . "/" . date("m") . "/" . date("Y");
            if ($monedas) {
                $data = '{"KeyValuePairs":{"Monedas":[{"Val":"500","Text":"PESO ARGENTINO"},{"Val":"1000","Text":"REAL"},{"Val":"1111","Text":"EURO"},{"Val":"2222","Text":"DOLAR USA"},{"Val":"2700","Text":"LIBRA ESTERLINA"},{"Val":"3600","Text":"YEN"},{"Val":"5900","Text":"FRANCO SUIZO"}],"FechaDesde":"' . $fecha . '","FechaHasta":"' . $fecha . '","Grupo":"1"}}';
            } else {
                $data = '{"KeyValuePairs":{"Monedas":[{"Val":"9800","Text":"UNIDAD INDEXADA"}],"FechaDesde":"' . $fecha . '","FechaHasta":"' . $fecha . '","Grupo":"2"}}';
            }
            $options = array(
                'http' => array(
                    'header' => "Content-type: application/json",
                    'method' => 'POST',
                    'content' => $data
                )
            );
            $context = stream_context_create($options);
            $result = file_get_contents($url, false, $context);
            return $result;
            if ($result !== FALSE) {
                $result = json_decode($result);
                if ($result->cotizacionesoutlist->RespuestaStatus->status === 0) {
                    $diff++;
                } else {
                    $last_date_found = true;
                    foreach ($result->cotizacionesoutlist->Cotizaciones as $cotizacion) {
                        if (in_array($cotizacion->CodigoISO, $codigosAceptados)) {
                            array_push($cotizaciones, $cotizacion);
                        }
                    }
                }
            }
        }
        return ($cotizaciones);
    }
}
