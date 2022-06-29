<?php include_once("seguridad.php");?>
<?php
/*
   Fech@: 11/07/2010
   @utor: Pablo Contreras - GT
   Em@il: pablo-contreras@aimargroup.com
*/
include_once("conect_db.php");
$link = conect_aereo_local();

$valor = $_GET["awbid"];

$sql = "SELECT awbnumber FROM {$_SESSION['air']} WHERE awbid = $valor";
$query_hwb = mysqli_query($link,$sql);
//$blnumber = mysqli_result($query_hwb, 0, "awbnumber");
$blnumber = mysqli_fetch_array($query_hwb)[0];

//echo $sql."<br>".$blnumber;

$query = mysqli_query($link,"SELECT statusmanif FROM {$_SESSION['air']} WHERE awbid = $valor");
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

$sql = "UPDATE {$_SESSION['air']} SET statusmanif = $val WHERE awbid = $valor";
$resultado = mysqli_query($link,$sql);

header("Location: air-view-hwb-{$_SESSION['impex']}.php?awbnumber=$blnumber");
?>
