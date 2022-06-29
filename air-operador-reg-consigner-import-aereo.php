<?php include_once("seguridad.php");?>
<?php
/*
   Fech@: 13/07/2010
   @utor: Pablo Contreras - GT
   Em@il: pablo-contreras@aimargroup.com
*/
include_once("conect_db.php");
$link = conect_aereo_local();
$user = $_SESSION["user"];

$hwb = $_POST["hwb"];
$hwb_id = $_POST["hwb_id"];
$id = $_POST['id'];
//$viaje = $_POST["no_viaje"];
/*
if (empty($_POST["cedula2"]))
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
		echo "<div align='center'><br>Debes ingresar la cedula juridica.<br>
			<br><a href='air-reg-consigner-imp ort-aereo.php?hwb=$hwb'><button><p>Regresar</p></button></a></div><br>";									
		exit;
}
*/
/* BEGIN TRANSACTION: Ingresar Datos MBL. */
$sql = "BEGIN;";

if ($sql)
{
	$sql = "UPDATE manifiestos_import SET cedula2 = '{$_POST["tipo_identificacion_id"]}||{$_POST["cedula2"]}' WHERE id = $id AND import_export = '{$_SESSION['ie']}'";
	$resultado = mysqli_query($link,$sql);
}
else 
{
	$sql = "ROLLBACK";
	$resultado = mysqli_query($link,$sql);
}

//$ocean = $_POST["no_bl"];
/* END TRANSACTION: Ingresar Datos MBL. */
if ($_POST["parcial"] == '1')
header("Location: air-view-hwb2-{$_SESSION['impex']}.php?id=$id&awbnumber=$hwb_id");
else
header("Location: air-view-awb-{$_SESSION['impex']}.php?awbnumber=$hwb_id");




?>