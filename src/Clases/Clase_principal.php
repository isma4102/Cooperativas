<?php
require("../vendor/setasign/fpdf/fpdf.php");
class Clase_principal
{
    private $nombre = '';
    private $imagen = '';
    private $descripcion = '';

    public function __construct($nombre, $imagen, $descripcion, $seccion)
    {
        $this->nombre = $nombre;
        $this->imagen = $imagen;
        $this->descripcion = $descripcion;
    }

    public function cooperativas_lista()
    {

        $consulta = DB::conexion()->prepare("SELECT ID,Nombre, Apellido, Telefono, Cooperativa FROM Socios WHERE Cooperativa = ?");
        $consulta->bind_param('i', $_SESSION['ID']);
        $consulta->execute();
        $ID = 0;
        $Nombre = null;
        $Apellido = null;
        $Telefono = 0;
        $cooperativa = 1;
        $consulta->bind_result($ID, $Nombre, $Apellido, $Telefono, $cooperativa);
        if ($consulta == true) {
            while ($consulta->fetch()) {
                $matriz[] = ['ID' => $ID, 'cooperativa' => $cooperativa, 'nombre' => $Nombre, 'Telefono' => $Telefono, 'Apellido' => $Apellido];
            }
        } else {
            return false;
        }
        $consulta->close();
        if (isset($matriz)) {
            $tamaño = count($matriz);
            for ($i = 0; $i < $tamaño; $i++) {
                $consulta = DB::conexion()->prepare("SELECT ID, Tipo_pago, Concepto, Pago, Tipo_cobro, Socio, Moneda, n_cuenta FROM Recibos WHERE Socio = ?");
                $consulta->bind_param('i', $matriz[$i]['ID']);
                $consulta->execute();
                $ID = null;
                $Tipo_pago = null;
                $Concepto = 0;
                $Pago = 0;
                $Tipo_cobro = null;
                $Socio = 0;
                $n_cuenta = 0;
                $Moneda = null;
                $consulta->bind_result($ID, $Tipo_pago, $Concepto, $Pago, $Tipo_cobro, $Socio, $Moneda, $n_cuenta);
                if ($consulta == true) {
                    while ($consulta->fetch()) {
                        $matriz[$i][] = ['ID' => $ID, 'Tipo_pago' => $Tipo_pago, 'Concepto' => $Concepto, 'Pago' =>  $Pago, 'Tipo_cobro' => $Tipo_cobro, 'Socio' => $Socio, 'Moneda' => $Moneda, 'n_cuenta' => $n_cuenta];
                    }
                } else {
                    return false;
                }
                $consulta->close();
            }
        }
        return $matriz;
    }

    public function socios_lista()
    {
        $consulta = DB::conexion()->prepare("SELECT ID,Nombre, Apellido, Telefono, Cooperativa FROM Socios");
        $consulta->execute();
        $ID = 0;
        $Nombre = null;
        $Apellido = null;
        $Telefono = 0;
        $cooperativa = 1;
        $consulta->bind_result($ID, $Nombre, $Apellido, $Telefono, $cooperativa);
        if ($consulta == true) {
            while ($consulta->fetch()) {
                $ret[] = ['ID' => $ID, 'cooperativa' => $cooperativa, 'nombre' => $Nombre, 'Telefono' => $Telefono, 'Apellido' => $Apellido];
            }
        } else {
            return false;
        }
        $consulta->close();
        return $ret;
    }

    public function ingresar_recibo($select_cobro, $select_pago, $socio, $Concepto, $Moneda, $ingreso, $n_cuenta)
    {
        $sql = DB::conexion()->prepare("INSERT INTO `Recibos` (`Tipo_pago`,`Concepto`,`Pago`,`Tipo_cobro`,`Socio`,`Moneda`,`n_cuenta`) VALUES (?, ?,?, ?,?, ?,?)");
        $sql->bind_param('ssisisi', $select_pago, $Concepto, $ingreso, $select_cobro, $socio, $Moneda, $n_cuenta);
        if ($sql->execute()) {
            return "1";
        } else {
            return "0";
        }
    }
    public function ingresar_socio($nombre, $apellido, $telefono)
    {
        $sql = DB::conexion()->prepare("INSERT INTO `Socios` (`Nombre`,`Apellido`,`Telefono`,`Cooperativa`) VALUES (?, ?,?, ?)");
        $sql->bind_param('ssii', $nombre, $apellido, $telefono, $_SESSION['ID']);
        if ($sql->execute()) {
            return "1";
        } else {
            return "0";
        }
    }
    public function login($nombre_var, $contraseña_var)
    {
        $consulta = DB::conexion()->prepare("SELECT ID,Nombre, Contraseña FROM Cooperativas");
        $consulta->execute();
        $ID = 0;
        $Nombre = null;
        $Contraseña = null;
        $consulta->bind_result($ID, $Nombre, $Contraseña);
        if ($consulta == true) {
            while ($consulta->fetch()) {
                if ($Nombre == $nombre_var && $Contraseña == $contraseña_var) {
                    $ret[] = ['ID' => $ID, 'Nombre' => $Nombre];
                    $consulta->close();
                    return $ret;
                }
            }
        } else {
            return "0";
        }
        $consulta->close();
        return "0";
    }

    public function imprimir_todo($id)
    {
        $consulta = DB::conexion()->prepare("SELECT Tipo_pago, Concepto, Pago, Tipo_cobro, Moneda, n_cuenta, Socios.Nombre FROM Recibos INNER JOIN Socios ON Socios.ID = Recibos.Socio WHERE Socio = ?");
        $consulta->bind_param('i', $id);
        $consulta->execute();
        $ID = null;
        $Tipo_pago = null;
        $Concepto = 0;
        $Pago = 0;
        $Tipo_cobro = null;
        $Socio = 0;
        $n_cuenta = 0;
        $Moneda = null;
        $consulta->bind_result($Tipo_pago, $Concepto, $Pago, $Tipo_cobro, $Moneda, $n_cuenta, $Socio);
        if ($consulta == true) {
            while ($consulta->fetch()) {
                $array[] = ['Tipo_pago' => $Tipo_pago, 'Concepto' => $Concepto, 'Pago' =>  $Pago, 'Tipo_cobro' => $Tipo_cobro, 'Socio' => $Socio, 'Moneda' => $Moneda, 'n_cuenta' => $n_cuenta];
            }
        } else {
            return false;
        }
        $consulta->close();

        $pdf = new FPDF();
        $pdf->AddPage();
        // $pdf->Image('../public/imagenes/Plantilla_original.png', 0, 0, -300);
        $pdf->SetFont('Arial', 'B', 15);
        $pdf->SetXY(10, 23);
        $pdf->Cell(0, 0, "Recibos del Socio " . $array[0]["Socio"], 0, 1, 'C');
        $pdf->SetXY(-150, 33);
        $y = 33;
        $pdf->SetFont('Arial', 'B', 12);
        for ($i = 0; $i < count($array); $i++) {
            $pdf->Cell(0, 0, $array[$i]["Tipo_cobro"], 0, 1, 'C');
            $pdf->SetXY(-100, $y);
            $pdf->Cell(0, 0, $array[$i]["Tipo_pago"], 0, 1, 'C');
            $pdf->SetXY(-50, $y);
            $pdf->Cell(0, 0, $array[$i]["Moneda"], 0, 1, 'C');
            $pdf->SetXY(-0, $y);
            $pdf->Cell(0, 0, $array[$i]["Pago"], 0, 1, 'C');
            $pdf->SetXY(50, $y);
            $pdf->Cell(0, 0, $array[$i]["Concepto"], 0, 1, 'C');
            $pdf->SetXY(100, $y);
            $pdf->Cell(0, 0, $array[$i]["n_cuenta"], 0, 1, 'C');
            $y = $y + 10;
            $pdf->SetXY(-150, $y);
        }

        $title = utf8_decode('Recibos del Socio ') . $array[0]["Socio"];
        $pdf->SetTitle($title);
        $pdf->Output();
        exit;
    }

    public function imprimir_uno($id)
    {
        $consulta = DB::conexion()->prepare("SELECT Tipo_pago, Concepto, Pago, Tipo_cobro, Moneda, n_cuenta, Socios.Nombre, Recibos.ID FROM Recibos INNER JOIN Socios ON Socios.ID = Recibos.Socio WHERE Recibos.ID = ?");
        $consulta->bind_param('i', $id);
        $consulta->execute();
        $ID = null;
        $Tipo_pago = null;
        $Concepto = 0;
        $Pago = 0;
        $Tipo_cobro = null;
        $Socio = 0;
        $n_cuenta = 0;
        $Moneda = null;
        $consulta->bind_result($Tipo_pago, $Concepto, $Pago, $Tipo_cobro, $Moneda, $n_cuenta, $Socio, $ID);
        if ($consulta == true) {
            while ($consulta->fetch()) {
                $array[] = ['ID' => $ID,'Tipo_pago' => $Tipo_pago, 'Concepto' => $Concepto, 'Pago' =>  $Pago, 'Tipo_cobro' => $Tipo_cobro, 'Socio' => $Socio, 'Moneda' => $Moneda, 'n_cuenta' => $n_cuenta];
            }
        } else {
            return false;
        }
        $consulta->close();

        $pdf = new FPDF();
        $pdf->AddPage();
        // $pdf->Image('../public/imagenes/Plantilla_original.png', 0, 0, -300);
        $pdf->SetFont('Arial', 'B', 15);
        $pdf->SetXY(10, 23);
        $pdf->Cell(0, 0, "Recibos del Socio " . $array[0]["Socio"], 0, 1, 'C');
        $pdf->SetXY(-150, 33);
        $y = 33;
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 0, $array[0]["Tipo_cobro"], 0, 1, 'C');
        $pdf->SetXY(-100, $y);
        $pdf->Cell(0, 0, $array[0]["Tipo_pago"], 0, 1, 'C');
        $pdf->SetXY(-50, $y);
        $pdf->Cell(0, 0, $array[0]["Moneda"], 0, 1, 'C');
        $pdf->SetXY(-0, $y);
        $pdf->Cell(0, 0, $array[0]["Pago"], 0, 1, 'C');
        $pdf->SetXY(50, $y);
        $pdf->Cell(0, 0, $array[0]["Concepto"], 0, 1, 'C');
        $pdf->SetXY(100, $y);
        $pdf->Cell(0, 0, $array[0]["n_cuenta"], 0, 1, 'C');
        $y = $y + 10;
        $pdf->SetXY(-150, $y);
        $title = utf8_decode('Recibo ' . $array[0]["ID"] .  ' del Socio ') . $array[0]["Socio"];
        $pdf->SetTitle($title);
        $pdf->Output();
        exit;
    }
}
