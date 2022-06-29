<?php include("seguridad.php");?>
<?php
/*
   Fech@: 20/03/2010
   @utor: Pablo Contreras - GT
   Em@il: pablo-contreras@aimargroup.com
*/
include("conect_db.php");
$link = conect_aereo_local();
$user = $_SESSION["user"];

$awb = $_POST["awbnumber"];

if (empty($_POST["vuelo"]))
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
		echo "<div align='center'><br>Debes seleccionar un numero de vuelo o aerolinea.<br>
			<br><a href='view-awb-aereo.php?awbnumber=$awb'><button><p>Regresar</p></button></a></div><br>";
		exit;
}

if (empty($_POST["conocimiento"]))
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
		echo "<div align='center'><br>Debes seleccionar un numero de conocimiento tica.<br>
			<br><a href='view-awb-aereo.php?awbnumber=$awb'><button><p>Regresar</p></button></a></div><br>";
		exit;
}

if (empty($_POST["ubicacion"]))
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
		echo "<div align='center'><br>Debes seleccionar una ubicacion.<br>
			<br><a href='view-awb-aereo.php?awbnumber=$awb'><button><p>Regresar</p></button></a></div><br>";
		exit;
}

/* BEGIN TRANSACTION: Ingresar Datos HWB. */
$sql = "BEGIN;";

if ($sql)
{
	$sql = "UPDATE manifiestos_import SET vuelo = '".$_POST["vuelo"]."', conocimiento = '".$_POST["conocimiento"]."', ubicacion = '".$_POST["ubicacion"]."' WHERE awbnumber = '$awb' AND import_export = '{$_SESSION['ie']}'"; 
	$resultado = mysqli_query($link,$sql);

	echo $sql;
}
else 
{
	$sql = "ROLLBACK";
	$resultado = mysqli_query($link,$sql);	
}

$air = $_POST["awbnumber"];
/* END TRANSACTION: Ingresar Datos HWB. */
header("Location: air-view-awb-{$_SESSION['impex']}.php?awbnumber=$air");
?>