<?php include_once("seguridad.php");?>
<?php
/*
   Fech@: 02/06/2010
   @utor: Pablo Contreras - GT
   Em@il: pablo-contreras@aimargroup.com
*/
include_once("conect_db.php");
include("db-mysql-fecha.php");

$link = conect_aereo_local();
$user = $_SESSION["user"];

$awb = $_POST["awbnumber"];
$hwb = $_POST["hawbnumber"];

/* BEGIN TRANSACTION: Ingresar Datos HWB. */
$sql = "BEGIN;";

if ($sql)
{
	$sql = "UPDATE manifiestos_import SET fecha_endoso = '".cambia_a_mysql($_POST["fecha_endoso"])."' WHERE hawbnumber = '$hwb' AND import_export = '{$_SESSION['ie']}'";	 
	echo $sql;
	$resultado = mysqli_query($link,$sql); 
}
else 
{
	$sql = "ROLLBACK";
	$resultado = mysqli_query($link,$sql);	
}

$air = $_POST["awbnumber"];
/* END TRANSACTION: Ingresar Datos HWB. */
//header("Location: air-view-hwb-{$_SESSION['impex']}.php?awbnumber=$air");
?>