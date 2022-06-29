<?php include_once("seguridad.php");?>
<?php
/*
   Fech@: 05/08/2010
   @utor: Pablo Contreras - GT
   Em@il: pablo-contreras@aimargroup.com
*/
include_once("conect_db.php");
$link = conect_aereo_local();

$valor = $_GET["id"];
$blnumber = $_GET["awbnumber"];

$query = mysqli_query($link,"SELECT statusmanif FROM manifiestos_import WHERE id = $valor AND import_export = '{$_SESSION['ie']}'");
//$status = mysqli_result($query, 0, "statusmanif");
$status = mysqli_fetch_array($query)[0];

if ($status == 1)
{
	$val = 0;
}
elseif ($status == 0) 
{
	$val = 1;
}

$sql = "UPDATE manifiestos_import SET statusmanif = $val WHERE id = $valor AND import_export = '{$_SESSION['ie']}'";
$resultado = mysqli_query($link,$sql);

header("Location: air-view-hwb2-{$_SESSION['impex']}.php?awbnumber=$blnumber&id=$valor");
?>