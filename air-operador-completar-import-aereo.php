<?php include_once("seguridad.php");?>
<?php
/*
   Fech@: 19/03/2010
   @utor: Pablo Contreras - GT
   Em@il: pablo-contreras@aimargroup.com
*/
include_once("conect_db.php");



$user = $_SESSION["user"];

$awb = $_POST["awbnumber"];
$hwb = $_POST["hawbnumber"];

$link = conect_aereo_local();


if (empty($_POST["puerto_origen"]))
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
		echo "<div align='center'><br>Debes seleccionar un puerto embarque.<br>
			<br><a href='air-completar-{$_SESSION['impex']}-aereo.php?hawbnumber=$hwb'><button><p>Regresar</p></button></a></div><br>";
		exit;
}

if (empty($_POST["puerto_destino"]))
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
		echo "<div align='center'><br>Debes seleccionar un puerto descarga.<br>
			<br><a href='air-completar-{$_SESSION['impex']}-aereo.php?hawbnumber=$hwb'><button><p>Regresar</p></button></a></div><br>";
		exit;
}

if (empty($_POST["bultos"]))
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
		echo "<div align='center'><br>Debes seleccionar un embalaje.<br>
			<br><a href='air-completar-{$_SESSION['impex']}-aereo.php?hawbnumber=$hwb'><button><p>Regresar</p></button></a></div><br>";
		exit;
}

if (empty($_POST["almacen"]))
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
		echo "<div align='center'><br>Debes seleccionar una ubicacion final.<br>
			<br><a href='air-completar-{$_SESSION['impex']}-aereo.php?hawbnumber=$hwb'><button><p>Regresar</p></button></a></div><br>";
		exit;
}

/* BEGIN TRANSACTION: Ingresar Datos HWB. */
$sql = "BEGIN;";

$campos = "awbnumber, hawbnumber, puerto_origen, puerto_destino, bultos, almacen, creado_por, modificado, borrado, parcializado, cedula2, import_export";

$values = "'".$_POST["awbnumber"]."', '".$_POST["hawbnumber"]."', '".$_POST["puerto_origen"]."', '".$_POST["puerto_destino"]."', '".$_POST["bultos"]."', '".$_POST["almacen"]."', '$user', 0, 0, 0, '{$_POST["cedula2"]}', '{$_SESSION['ie']}'";

if ($sql)
{
	$sql = "INSERT INTO manifiestos_import (".$campos.") VALUES (".$values.")";

	//echo $sql;

	$resultado = mysqli_query($link,$sql);
}
else 
{
	$sql = "ROLLBACK";
	$resultado = mysqli_query($link,$sql);	
}


/* END TRANSACTION: Ingresar Datos HWB. */
header("Location: air-view-awb-{$_SESSION['impex']}.php?awbnumber=$awb");
?>