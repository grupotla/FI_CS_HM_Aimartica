<?php include_once("seguridad.php");?>
<?php
/*
   Fech@: 02/09/2010
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
	$sql = "UPDATE {$_SESSION['air']} SET arrivaldate = '".cambia_a_mysql($_POST["arrivaldate"])."', expired = 0 WHERE hawbnumber = '$hwb'";	 
	$resultado = mysqli_query($link,$sql); 
}
else 
{
	$sql = "ROLLBACK";
	$resultado = mysqli_query($link,$sql);	
}

/* END TRANSACTION: Ingresar Datos HWB. */
//header("Location: air-{$_SESSION['impex']}-aereo.php");
header("Location: {$_POST['url_return']}&msg_ok=Datos grabados correctamente");
?>