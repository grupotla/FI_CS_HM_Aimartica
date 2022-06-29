<?php include_once("seguridad.php");?>
<?php
/*
   Fech@: 03/08/2010
   @utor: Pablo Contreras - GT
   Em@il: pablo-contreras@aimargroup.com
*/
include_once("conect_db.php");
include("db-mysql-fecha.php");

$user = $_SESSION["user"];
$awb = $_POST["awbnumber"];
$hwb = $_POST["hawbnumber"];

$link = conect_aereo_local();

if (empty($_POST["piezas"]))
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
		echo "<div align='center'><br>Debes ingresar una cantidad de piezas.<br>
			<br><a href='air-clonar-hwb-{$_SESSION['impex']}.php?awbnumber=$awb'><button><p>Regresar</p></button></a></div><br>";
		exit;
}

if (empty($_POST["peso"]))
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
		echo "<div align='center'><br>Debes ingresar el peso de la guia aerea.<br>
			<br><a href='air-clonar-hwb-{$_SESSION['impex']}.php?awbnumber=$awb'><button><p>Regresar</p></button></a></div><br>";
		exit;
}

/* BEGIN TRANSACTION: Ingresar Datos Hwb. */
$sql = "BEGIN;";

$campos = "awbnumber, hawbnumber, puerto_origen, puerto_destino, bultos, almacen, vuelo, conocimiento, ubicacion, fecha_arribo_tica, piezas, peso, creado_por, parcializado, modificado, borrado, import_export";

$values = "'".$_POST["awbnumber"]."', '".$_POST["hawbnumber"]."', '".$_POST["puerto_origen"]."', '".$_POST["puerto_destino"]."', '".$_POST["bultos"]."', '".$_POST["almacen"]."', '".$_POST["vuelo"]."', '".$_POST["conocimiento"]."', '".$_POST["ubicacion"]."', '".cambia_a_mysql($_POST["arrivaldate"])."', '".$_POST["piezas"]."', '".$_POST["peso"]."', '$user', 1, 0, 0, '{$_SESSION['ie']}'";

if ($sql)
{
	$sql = "INSERT INTO manifiestos_import (".$campos.") VALUES (".$values.")";  
	$resultado = mysqli_query($link,$sql);
}
else 
{
	$sql = "ROLLBACK";
	$resultado = mysqli_query($link,$sql);	
}

$air = $_POST["awbnumber"];
/* END TRANSACTION: Ingresar Datos Hwb. */
header("Location: air-view-hwb-{$_SESSION['impex']}.php?awbnumber=$air");
?>