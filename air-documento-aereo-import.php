<?php include_once("seguridad.php");?>
<?php
/*
   Fech@: 15/07/2010
   @utor: Pablo Contreras - GT
   Em@il: pablo-contreras@aimargroup.com
*/
include_once("conect_db.php");
$nombres = $_SESSION["nombres"];
$awb = $_GET["awb"];

$link = conect_aereo_local();
include("db-mysql-fecha.php");

$query = mysqli_query($link,"SELECT b.awbnumber, b.hawbnumber, b.arrivaldate, m.vuelo, m.conocimiento, m.ubicacion FROM {$_SESSION['air']} b, manifiestos_import m WHERE b.awbnumber = m.awbnumber AND b.hawbnumber = m.hawbnumber AND b.expired = 0 AND b.awbnumber = '$awb' AND import_export = '{$_SESSION['ie']}'");
while ($row = mysqli_fetch_array($query))
{
	$arrivaldate = cambia_a_normal($row["arrivaldate"]);
	$vuelo = $row["vuelo"];
	$conocimiento = $row["conocimiento"];
	$ubicacion = $row["ubicacion"];
}

$link2 = conect_localhost();

$query_hwb = "SELECT b.awbid, b.hawbnumber, b.consignerdata, b.consignerid, b.natureqtygoods, b.totweight, b.totnoofpieces, m.puerto_origen, m.puerto_destino, m.bultos, m.almacen, m.fecha_endoso FROM {$_SESSION['air']} b, manifiestos_import m WHERE b.awbnumber = m.awbnumber AND b.hawbnumber = m.hawbnumber AND b.expired = 0 AND m.parcializado = 0 AND b.awbnumber = '$awb' AND import_export = '{$_SESSION['ie']}'";
$result = mysqli_query($link,$query_hwb);
$row = mysqli_num_rows($result);

$query_hwb2 = "SELECT SUM(totweight) AS totalpeso, SUM(totnoofpieces) AS totalpiezas FROM {$_SESSION['air']} WHERE expired = 0 AND awbnumber = '$awb' AND HAwbNumber <> ''";
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
/*
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

	//Recuadro.
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
		$pdf->Cell(30, 6, cambiaf_a_mysql($arrivaldate), 1, 0, 'C', 1);
		$pdf->Cell(32, 6, $ubicacion, 1, 0, 'C', 1);
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
   		$piezas = $values['totnoofpieces'];
   		$comodities = $values['natureqtygoods'];
   		$peso = $values['totweight'];
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
		$pdf->Cell(20, 4, mysqli_result($result, $row5, "hawbnumber"), 0, 0, 'L', 1);

		$pdf->SetFillColor($color);
		$pdf->Cell(60, 4, $consignatario2[0], 0, 0, 'L', 1);

		$pdf->SetFillColor($color);
		$pdf->Cell(17, 4, mysqli_result($result, $row5, "puerto_origen"), 0, 0, 'C', 1);

		$pdf->SetFillColor($color);
		$pdf->Cell(17, 4, mysqli_result($result, $row5, "puerto_destino"), 0, 0, 'C', 1);

		$pdf->SetFillColor($color);
		$pdf->Cell(12, 4, mysqli_result($result, $row5, "totnoofpieces"), 0, 0, 'R', 1);

		$pdf->SetFillColor($color);
		$pdf->Cell(12, 4, mysqli_result($result, $row5, "bultos"), 0, 0, 'C', 1);

		$pdf->SetFillColor($color);
		$pdf->Cell(12, 4, number_format(mysqli_result($result, $row5, "totweight"), 2), 0, 0, 'R', 1);

		$pdf->SetFillColor($color);
		$pdf->Cell(20, 4, mysqli_result($result, $row5, "almacen"), 0, 1, 'C', 1);

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

		//$pdf->Cell(10, 10, $query_hwb2, 1, 0, 'R', 0);para test de query


	}

	$pdf->SetFont('Arial', '', 7);
	$pdf->SetY(-30);
	$pdf->SetX(180);
	$pdf->Cell(20, 4, "Pagina $x/$paginas", 0, 0, 'C', 0);
}
$pdf->Output();
*/



$page = '

<style>
th, td { font-family:Arial; font-size:14px; }
th { padding-top:3px;padding-bottom:3px; }
	.box { border:1px solid silver; margin-bottom:10px;}
	.grid th { background-color:silver }
</style>

		<page backtop="30mm" backbottom="10mm" backleft="0mm" backright="0mm">
		    <page_header>
		        <table style="width: 100%; border: solid 1px black;">
		            <tr>
		                <td style="text-align: left;    width: 33%"><img src='.$imagen.'></td>
		                <td style="text-align: center;    width: 34%"><h2>MANIFIESTO AEREO</h2></td>
		                <td style="text-align: right;    width: 33%">'.$nombres.'</td>
		            </tr>
		        </table>
		    </page_header>
		    <page_footer>
		        <table style="width: 100%; border: solid 1px black;">
		            <tr>
		                <td style="text-align: left;    width: 50%">'.$now.'</td>
		                <td style="text-align: right;    width: 50%">page [[page_cu]]/[[page_nb]]</td>
		            </tr>
		        </table>
		    </page_footer>

				<table border=0 style="width: 100%;border: solid 1px #5544DD; border-collapse: collapse" align="center" class="box grid">
				<tr><th style="width:20%;">AWB</th><th style="width:20%;">Fecha Arribo</th><th style="width:20%;">Ubicacion Tica</th><th style="width:20%;">Secuencia Interna</th><th style="width:20%;">Secuencia Tica</th></tr>
				'.sprintf("<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr></table>",$awb,$arrivaldate,$ubicacion,"1",$conocimiento).'
<br>
		    <table border=0 style="width: 97%;border: solid 1px #5544DD; border-collapse: collapse" align="center" class="box grid">
		        <thead>
						<tr><th style="width:5%;">Sec&nbsp;</th><th style="width:10%;">Hawb&nbsp;</th><th style="width:31%;">Consignatario&nbsp;</th><th style="width:10%;">Pto<br>Embarque&nbsp;</th><th style="width:10%;">Pto<br>Descarga&nbsp;</th><th style="width:7%;">Piezas&nbsp;</th><th style="width:10%;">Embalaje&nbsp;</th><th style="width:7%;">Peso&nbsp;</th><th style="width:10%;">Ubicacion<br>Final&nbsp;</th></tr>
		        </thead>
		        <tbody>';

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


			//for ($i=0;$i<70;$i++) {

			$hwb = $values['hawbnumber'];
			$consignatario = $values['consignerdata'];
			$consignatario2 = array();
			$consignatario2 = explode("\n", $consignatario);
			$consignatario2[0];
			$piezas = $values['totnoofpieces'];
			$comodities = $values['natureqtygoods'];
			$peso = $values['totweight'];
			$puertoorigen = $values['puerto_origen'];
			$puertodestino = $values['puerto_destino'];
			$tipobultos = $values['bultos'];
			$bodega = $values['almacen'];
			$enteropiezas = (int) $piezas;
			$enteropeso = (int) $peso;

			$page .=  sprintf("<tr><td valign=top>%s</td><td valign=top>%s</td><td style='width:100px;'>%s</td><td valign=top>%s</td><td valign=top>%s</td><td valign=top align=right>%s</td><td valign=top align=center>%s</td><td valign=top align=right>%s</td><td valign=top align=center>%s</td></tr>",
			$row4,
			$hwb,
			trim($consignatario2[0]),
			$puertoorigen,
			$puertodestino,
			number_format($piezas, 2),
			$tipobultos,
			number_format($peso, 2),
			$bodega);

			$page .=  "<tr><th style='border-bottom:1px solid silver' colspan=2>Commodities</th><td style='width:65%;border-bottom:1px solid silver' colspan=7>$comodities</td></tr>";

			//}
		}


        $page .= '</tbody>
        <tfoot>'.sprintf("<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><th align=right>%s</th><td>%s</td><th align=right>%s</th><td>%s</td></tr>",
				"",
				"",
				"",
				"",
				"Total",
				number_format($totalpiezas, 2),
				"",
				number_format($totalpeso, 2),
				"").'</tfoot>
    </table>
</page>
';


    // convert to PDF
    require_once(dirname(__FILE__).'/html2pdf/html2pdf.class.php');
    try
    {
        $html2pdf = new HTML2PDF('P', 'A4', 'fr', true, 'UTF-8', 6);
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->writeHTML($page, isset($_GET['vuehtml']));
        $html2pdf->Output('exemple03.pdf');
    }
    catch(HTML2PDF_exception $e) {
        echo $e;
        exit;
    }



?>