<?php include_once("seguridad.php");?>
<?php
/*
   Fech@: 05/08/2010
   @utor: Pablo Contreras - GT
   Em@il: pablo-contreras@aimargroup.com
*/
include_once("conect_db.php");
$nombres = $_SESSION["nombres"];
$id = $_GET["id"];
$awb = $_GET["awbnumber"];

$link = conect_aereo_local();

include("db-mysql-fecha.php");

$query = mysqli_query($link,"SELECT vuelo, conocimiento, ubicacion, fecha_arribo_tica FROM manifiestos_import WHERE parcializado = 1 AND id = $id AND awbnumber = '$awb' AND import_export = '{$_SESSION['ie']}'");
while ($row = mysqli_fetch_array($query))
{
	$vuelo = $row["vuelo"];
	$farribo = cambia_a_normal($row["fecha_arribo_tica"]);
	$idubicacion = $row["ubicacion"];
	$conocimiento = $row["conocimiento"];
}

$link2 = conect_localhost();

$query_hwb = "SELECT m.hawbnumber, b.consignerdata, b.consignerid, b.natureqtygoods, m.peso, m.piezas, m.puerto_origen, m.puerto_destino, m.bultos, m.almacen, m.fecha_endoso, m.statusmanif FROM {$_SESSION['air']} b, manifiestos_import m WHERE b.awbnumber = m.awbnumber AND b.hawbnumber = m.hawbnumber AND b.expired = 0 AND m.parcializado = 1 AND m.awbnumber = '$awb' AND m.id = $id AND import_export = '{$_SESSION['ie']}'";
$result = mysqli_query($link,$query_hwb);
$row = mysqli_num_rows($result);

$query_hwb2 = "SELECT SUM(peso) AS totalpeso, SUM(piezas) AS totalpiezas FROM manifiestos_import WHERE parcializado = 1 AND awbnumber = '$awb' AND id = $id AND import_export = '{$_SESSION['ie']}'";
$result2 = mysqli_query($link,$query_hwb2);
while ($row2 = mysqli_fetch_array($result2)) 
{
	$totalpeso = $row2["totalpeso"];
	$totalpiezas = $row2["totalpiezas"];
}

$hora_guatemala = strtotime("-1 hours");
$now = date("d/m/Y H:i:s", $hora_guatemala);

$link1 = conect_master_local();

$imagen = "imagenes/{$_SESSION['empresa']}.jpg";

$por_pagina = 35;

$paginas = ceil($row/$por_pagina);

ob_end_clean();
define("FPDF_FONTPATH", "fpdf/font/");
require('fpdf/fpdf.php');
$pdf = new FPDF('P', 'mm', 'letter');

$linea_actual = 0;
$inicio = 0;

for ($x = 1; $x <= $paginas; $x++) 
{
	$inicio = $linea_actual;
	$fin = $linea_actual + $por_pagina;
	if ($fin > $row) $fin = $row;

	$pdf->Open();
	$pdf->AddPage();

	/* Recuadro. */
	$pdf->SetY(20);
	$pdf->SetX(19);
	$pdf->Cell(180, 230, '', 1, 0, '', 0);	

	$pdf->SetY(32);
	$pdf->SetX(20);
	$pdf->Image($imagen, 20, 22, 40);
	$pdf->SetFont("Arial", "", 8);
	$pdf->SetY(22);
	$pdf->SetX(155);
	$pdf->Cell(40, 3, 'Generado Por: '.$nombres, 0, 0, 'L', 0);	
	$pdf->SetY(25);
	$pdf->SetX(163);
	$pdf->Cell(40, 3, $now, 0, 0, 'L', 0);	
	$pdf->SetFont("Arial", "", 14);
	$pdf->SetY(40);
	$pdf->SetX(84);
	$pdf->Cell(40, 3, 'MANIFIESTO AEREO', 0, 0, 'L', 0);	
	$pdf->SetFont("Arial", "B", 10);
	$pdf->SetY(47);
	$pdf->SetX(97);
	$pdf->Cell(40, 3, 'No. '.$vuelo, 0, 0, 'L', 0);	
	$pdf->Ln(10);

	if ($x == 1)
	{
		$pdf->SetY(57);
		$pdf->SetFillColor(232, 232, 232);
		$pdf->SetFont('Arial', '', 10);
		$pdf->SetX(20);
		$pdf->Cell(52, 6, "AWB", 1, 0, 'C', 1);
		$pdf->Cell(30, 6, "Fecha Arribo", 1, 0, 'C', 1);
		$pdf->Cell(32, 6, "Ubicacion Tica", 1, 0, 'C', 1);
		$pdf->Cell(32, 6, "Secuencia Interna", 1, 0, 'C', 1);
		$pdf->Cell(32, 6, "Secuencia Tica", 1, 1, 'C', 1);
		$pdf->SetFont('Arial', '', 8);
		$pdf->SetY(62);
		$pdf->SetFillColor(255, 255, 255);
		$pdf->SetX(20);
		$pdf->Cell(52, 6, $awb, 1, 0, 'C', 1);
		$pdf->Cell(30, 6, $farribo, 1, 0, 'C', 1);
		$pdf->Cell(32, 6, $idubicacion, 1, 0, 'C', 1);
		$pdf->Cell(32, 6, "1", 1, 0, 'C', 1);
		$pdf->Cell(32, 6, $conocimiento, 1, 1, 'C', 1);
		$pdf->Ln(10);
	}															

	$pdf->SetFillColor(232, 232, 232);
	$pdf->SetFont('Arial', '', 7);
	$pdf->SetX(20);
	$pdf->Cell(8, 4, "Sec", 1, 0, 'C', 1);
	$pdf->Cell(20, 4, "Hawb", 1, 0, 'C', 1);
	$pdf->Cell(60, 4, "Consignatario", 1, 0, 'C', 1);
	$pdf->Cell(17, 4, "Pto Embarque", 1, 0, 'C', 1);
	$pdf->Cell(17, 4, "Pto Descarga", 1, 0, 'C', 1);
	$pdf->Cell(12, 4, "Piezas", 1, 0, 'C', 1);
	$pdf->Cell(12, 4, "Embalaje", 1, 0, 'C', 1);
	$pdf->Cell(12, 4, "Peso", 1, 0, 'C', 1);
	$pdf->Cell(20, 4, "Ubicacion Final", 1, 1, 'C', 1);
	$pdf->Ln(1);

	$row4 = 5000;

	$alternate = "2";

	for ($row5 = 0; $row5 < mysqli_num_rows($result); $row5++) 
	{
		$row4++; 

		if ($alternate == "1") 
		{
			$color = "232, 232, 232";
			$alternate = "2";
		}
		else 
		{
			$color = "255, 255, 255";
			$alternate = "1";
		}	

		$values = mysqli_fetch_array($result, MYSQLI_BOTH);		

   		$hwb = $values['hawbnumber'];
   		$consignatario = $values['consignerdata'];	
		$consignatario2 = array();
   		$consignatario2 = explode("\n", $consignatario);
		$consignatario2[0];								
   		$piezas = $values['piezas'];		
   		$comodities = $values['natureqtygoods'];		
   		$peso = $values['peso'];		
		$puertoorigen = $values['puerto_origen'];
		$puertodestino = $values['puerto_destino'];
		$tipobultos = $values['bultos'];
		$bodega = $values['almacen'];
		$enteropiezas = (int) $piezas;  
		$enteropeso = (int) $peso;
		
		$pdf->SetFont('Arial', '', 7);
		$pdf->SetX(20);

		$pdf->SetFillColor($color);
		$pdf->Cell(8, 4, $row4, 0, 0, 'C', 1);
		
		$pdf->SetFillColor($color);
		$pdf->Cell(20, 4, $hwb, 0, 0, 'L', 1);

		$pdf->SetFillColor($color);
		$pdf->Cell(60, 4, $consignatario2[0], 0, 0, 'L', 1);
		
		$pdf->SetFillColor($color);
		$pdf->Cell(17, 4, $puertoorigen, 0, 0, 'C', 1);

		$pdf->SetFillColor($color);
		$pdf->Cell(17, 4, $puertodestino, 0, 0, 'C', 1);

		$pdf->SetFillColor($color);
		$pdf->Cell(12, 4, $piezas, 0, 0, 'R', 1);

		$pdf->SetFillColor($color);
		$pdf->Cell(12, 4, $tipobultos, 0, 0, 'C', 1);

		$pdf->SetFillColor($color);
		$pdf->Cell(12, 4, number_format($peso, 2), 0, 0, 'R', 1);

		$pdf->SetFillColor($color);
		$pdf->Cell(20, 4, $bodega, 0, 1, 'C', 1);
		
		$pdf->SetFont('Arial', 'B', 7);
		$pdf->SetX(20);

		$pdf->SetFillColor($color);
		$pdf->Cell(20, 4, "Commodities:", 0, 0, 'C', 1);

		$pdf->SetFont('Arial', '', 7);
		$pdf->SetFillColor($color);
		$pdf->Cell(158, 4, mysqli_result($result, $row5, "natureqtygoods"), 0, 1, 'L', 1);		
	}

	if ($row5 == mysqli_num_rows($result))
	{
		$pdf->SetFillColor(232, 232, 232);
		$pdf->SetFont('Arial', '', 7);
		$pdf->SetX(20);
		$pdf->Cell(122, 4, 'Totales Manifiesto Aereo', 1, 0, 'C', 0);
		$pdf->SetFont('Arial', '', 7);
		$pdf->Cell(12, 4, number_format($totalpiezas, 2), 1, 0, 'R', 1);
		$pdf->Cell(12, 4, '', 1, 0, 'R', 0);
		$pdf->Cell(12, 4, number_format($totalpeso, 2), 1, 0, 'R', 1);
		$pdf->Cell(20, 4, '', 1, 0, 'R', 0);	
	}

	$pdf->SetFont('Arial', '', 7);
	$pdf->SetY(-30);
	$pdf->SetX(180);
	$pdf->Cell(20, 4, "Pagina $x/$paginas", 0, 0, 'C', 0);
}
$pdf->Output();
?>