<?php include_once("seguridad.php");?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><link rel="shortcut icon" href="imagenes/<?=$_SESSION['empresa']?>.bmp">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	<link rel="stylesheet" href="css/customer.css" media="screen" type="text/css" title="estilo">
	<script src="js/jquery/jquery-1.1.3.1.js" type="text/javascript"></script>
  	<script src="SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
	<link href="SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css" />

	<script language="javascript" type="text/javascript">
		$(document).ready(function() {
			//$("#menu").load("menu_manifiestos.htm");
		});
	</script>
   
<title><?=$_SESSION['empresa']?></title>
</head>
<body>
<div id="menu"><?php include("menu_manifiestos.php");?></div>
<?php
/*
   Fech@: 04/08/2010
   @utor: Pablo Contreras - GT
   Em@il: pablo-contreras@aimargroup.com
*/
include_once("conect_db.php");
$nombres = $_SESSION["nombres"];
$awb = $_GET["awbnumber"];

$link = conect_aereo_local();

$query = mysqli_query($link,"SELECT hawbnumber FROM manifiestos_import WHERE awbnumber = '$awb' AND parcializado = 1 AND import_export = '{$_SESSION['ie']}'");

if (mysqli_num_rows($query) == 0)
{
	echo"<link rel='stylesheet' href='css/customer.css' media='screen' type='text/css' title='estilo'>
		<style>
		.twoColElsLtHdr #container #header 
		{
			background-position: left right;
			text-align: left;
			vertical-align: top;
			width: 1250px;
			height: 113px;
		}
		</style>";
		echo "<div align='center'><br>La guia aerea: $awb no ha sido parcializada aun. En este link solo puedes ver guias aereas parcializadas.<br>
			<br><a href='air-view-awb-{$_SESSION['impex']}.php?awbnumber=$awb'><button><p>Regresar</p></button></a></div><br>";
		exit;
}
?>
<?php /* <table width="100%">
<tr bgcolor="#336699" height="30">
	<td align="center" width="20%" style="font:normal bold 10pt Verdana,Helvetica, Arial;color:#fff;">
		<a href=inicio.php><font color=white face="Verdana, Arial, Helvetica, sans-serif">Ir a Inicio</font></a>
    </td>
	<td align="center" width="60%" style="font:normal bold 10pt Verdana,Helvetica, Arial;color:#fff;">
		<span id="logged_user" style="cursor:pointer;"><?=$nombres; ?></span>
    </td>
	<td width="20%" align="center" style="font:normal bold 10pt Verdana,Helvetica, Arial;color:#fff;">
		<a href=logout.php><font color=white face="Verdana, Arial, Helvetica, sans-serif">Cerrar sesion</font></a>
    </td>
</tr>
</table>
<br> */?>
<center>


<h1 align="center">Detalle Awb: <?=$awb; ?></h1>   
<br>
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tbl-regs">
<thead>
<tr>
    <tr bgcolor="#336699" height="20">
	    <th align="center">NO.</th>
	    <th align="center">Hwb</th>
	    <th align="center">Puerto Embarque</th>
	    <th align="center">Puerto Descarga</th>
        <th align="center">No. Piezas</th>
        <th align="center">Embalaje</th>
        <th align="center">Peso</th>
        <th align="center">Ubicacion Final</th>
        <th align="center">No. Manifiesto</th>
        <th align="center">Sec. Tica</th>
        <th align="center">Ubicacion Tica</th>
        <th align="center">Fecha Arribo</th>
        <th align="center">Manifestar</th>
    </tr>
</thead>
<tbody>	
<?php
$query_hwb = mysqli_query($link,"SELECT id, hawbnumber, puerto_origen, puerto_destino, piezas, bultos, peso, almacen, vuelo, conocimiento, ubicacion, fecha_arribo_tica FROM manifiestos_import WHERE parcializado = 1 AND awbnumber = '$awb' AND import_export = '{$_SESSION['ie']}'");
	
if ($query_hwb) 
{
	$link1 = conect_master_local();
	
	for ($row2 = 0; $row2 < mysqli_num_rows($query_hwb); $row2++) 
	{
		$values = mysqli_fetch_array($query_hwb, MYSQLI_BOTH);

    	$id = $values['id'];
    	$hwb = $values['hawbnumber'];
		$origen = $values['puerto_origen'];
		$destino = $values['puerto_destino'];
    	$piezas = $values['piezas'];		
		$embalaje = $values['bultos'];
    	$peso = $values['peso'];		
		$bodega = $values['almacen'];
		$nomanifiesto = $values['vuelo'];
    	$secuenciatica = $values['conocimiento'];		
		$ubicaciontica = $values['ubicacion'];
    	$fechaarribotica = $values['fecha_arribo_tica'];		
										
        $color = "style='background-color: #FFFFFF; color: #336699;'";            	
					
		printf("<tr $color1><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td></tr>", $row2 + 1, $hwb, $origen, $destino, $piezas, $embalaje, $peso, $bodega, $nomanifiesto, $secuenciatica, $ubicaciontica, $fechaarribotica, "<a href='air-view-hwb2-{$_SESSION['impex']}.php?id=$id&awbnumber=$awb' class='activation'><img src='imagenes/pod.ico' alt='edit' align='center' border=0></a>");
    }

	$style = "";

	$sql_totales = mysqli_query($link,"SELECT SUM(piezas) AS total_piezas, SUM(peso) AS total_peso FROM manifiestos_import WHERE parcializado = 1 AND awbnumber = '$awb' AND import_export = '{$_SESSION['ie']}'");
	while ($rowbl = mysqli_fetch_array($sql_totales)) 
	{
		$total_piezas = $rowbl["total_piezas"];
		$total_peso = $rowbl["total_peso"];
		printf("<tr><td $style colspan='4'> &nbsp;%s </td><td $style> &nbsp;%s&nbsp; </td><td $style> &nbsp;%s&nbsp; </td><td $style> &nbsp;%s&nbsp; </td><td $style> &nbsp;%s&nbsp; </td><td $style> &nbsp;%s&nbsp; </td><td $style> &nbsp;%s&nbsp; </td><td $style> &nbsp;%s&nbsp; </td><td $style> &nbsp;%s&nbsp; </td><td $style> &nbsp;%s&nbsp; </td></tr>", "Totales", $total_piezas, "", number_format($total_peso, 2, '.', ','), "", "", "", "", "", "");
	}
}
?> 
</table>
</center>
<br>
<p align="center"><a href="air-clonar-hwb2-<?=$_SESSION['impex']?>.php?awbnumber=<?php echo $awb?>"><img src="imagenes/sobrante.jpg" title="Clonar Guia Parcializada" border="0"></a></p>
<p align="center"><a href="air-<?=$_SESSION['impex']?>-aereo.php"><img src="imagenes/home.png" alt="Inicio" border="0"></a></p>


   <?php include("pie.php");?>
</body>
</html>