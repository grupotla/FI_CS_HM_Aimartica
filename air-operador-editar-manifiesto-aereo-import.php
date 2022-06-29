<?php include_once("seguridad.php");?>
<?php
/*
   Fech@: 23/06/2010
   @utor: Pablo Contreras - GT
   Em@il: pablo-contreras@aimargroup.com
*/
include_once("conect_db.php");
include("db-mysql-fecha.php");

$nombres = $_SESSION["nombres"];
$awb = $_POST["awbnumber"];
$vuelo = $_POST["vuelo"];

$link = conect_aereo_local();

/* BEGIN TRANSACTION: Modificar Datos BL. */
$sql = "BEGIN;";

if ($sql)
{
	$sql1 = "UPDATE manifiestos_import SET vuelo = '".$_POST["vuelo"]."', conocimiento = '".$_POST["conocimiento"]."', ubicacion = '".$_POST["ubicacion"]."' WHERE awbnumber = '$awb' AND import_export = '{$_SESSION['ie']}'";  
	$resultado = mysqli_query($link,$sql1);
}
else 
{
	$sql = "ROLLBACK";
	$resultado = mysqli_query($link,$sql);
}

if ($resultado)
{
	$sql1 = "UPDATE {$_SESSION['air']} SET arrivaldate = '".cambia_a_mysql($_POST["arrivaldate"])."' WHERE awbnumber = '$awb' AND expired = 0";  
	$resultado = mysqli_query($link,$sql1);
}
else 
{
	$sql = "ROLLBACK";
	$resultado = mysqli_query($link,$sql);
}

$air = $_POST["awbnumber"];
/* END TRANSACTION: Modificar Datos BL. */
header("Location: air-view-hwb-{$_SESSION['impex']}.php?awbnumber=$air");
?>