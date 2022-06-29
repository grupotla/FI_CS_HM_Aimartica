<?php include_once("seguridad.php");?>
<?php
/*
   Fech@: 05/08/2010
   @utor: Pablo Contreras - GT
   Em@il: pablo-contreras@aimargroup.com
*/
include_once("conect_db.php");
$link = conect_aereo_local();
$user = $_SESSION["user"];

$awb = $_POST["awbnumber"];
$hwb = $_POST["hawbnumber"];
$id = $_POST["id"];

$query_hwb = mysqli_query($link,"SELECT puerto_origen, puerto_destino, bultos, almacen, ubicacion, piezas, peso FROM manifiestos_import WHERE hawbnumber = '$hwb' AND id = $id AND import_export = '{$_SESSION['ie']}'");
while ($row = mysqli_fetch_array($query_hwb))
{
	$puertoorigen = $row["puerto_origen"];
	$puertodestino = $row["puerto_destino"]; 
	$bultos = $row["bultos"];
	$almacen = $row["almacen"]; 
	$piezas = $row["piezas"];
	$peso = $row["peso"]; 
}

/* BEGIN TRANSACTION: Modificar Datos HWB. */
$sql = "BEGIN;";

if ($sql)
{
	if ($puertoorigen != $_POST["puerto_origen"])
	{
		$sql = "UPDATE manifiestos_import SET puerto_origen = '".$_POST["puerto_origen"]."', cambioptoorigen = 1, modificado = 1, modificado_por = '$user' WHERE hawbnumber = '$hwb' AND id = $id AND import_export = '{$_SESSION['ie']}'";  
		$resultado = mysqli_query($link,$sql);
	}

	if ($puertodestino != $_POST["puerto_destino"])
	{
		$sql = "UPDATE manifiestos_import SET puerto_destino = '".$_POST["puerto_destino"]."', cambioptodestino = 1, modificado = 1, modificado_por = '$user' WHERE hawbnumber = '$hwb' AND id = $id AND import_export = '{$_SESSION['ie']}'";  
		$resultado = mysqli_query($link,$sql);
	}

	if ($bultos != $_POST["bultos"])
	{
		$sql = "UPDATE manifiestos_import SET bultos = '".$_POST["bultos"]."', cambiobultos = 1, modificado = 1, modificado_por = '$user' WHERE hawbnumber = '$hwb' AND id = $id AND import_export = '{$_SESSION['ie']}'";  
		$resultado = mysqli_query($link,$sql);
	}

	if ($almacen != $_POST["almacen"])
	{
		$sql = "UPDATE manifiestos_import SET almacen = '".$_POST["almacen"]."', cambioalmacen = 1, modificado = 1, modificado_por = '$user' WHERE hawbnumber = '$hwb' AND id = $id AND import_export = '{$_SESSION['ie']}'";  
		$resultado = mysqli_query($link,$sql);
	}

	if ($piezas != $_POST["piezas"])
	{
		$sql = "UPDATE manifiestos_import SET piezas = '".$_POST["piezas"]."', cambiopiezas = 1, modificado = 1, modificado_por = '$user' WHERE hawbnumber = '$hwb' AND id = $id AND import_export = '{$_SESSION['ie']}'";  
		$resultado = mysqli_query($link,$sql);
	}

	if ($peso != $_POST["peso"])
	{
		$sql = "UPDATE manifiestos_import SET peso = '".$_POST["peso"]."', cambiopeso = 1, modificado = 1, modificado_por = '$user' WHERE hawbnumber = '$hwb' AND id = $id AND import_export = '{$_SESSION['ie']}'";  
		$resultado = mysqli_query($link,$sql);
	}
}
else 
{
	$sql = "ROLLBACK";
	$resultado = mysqli_query($link,$sql);	
}

/* END TRANSACTION: Modificar Datos HWB. */
header("Location: air-view-hwb2-{$_SESSION['impex']}.php?awbnumber=$awb&id=$id");
?>