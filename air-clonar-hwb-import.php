<?php include_once("seguridad.php");?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><link rel="shortcut icon" href="imagenes/<?=$_SESSION['empresa']?>.bmp">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	<link rel="stylesheet" href="css/customer.css" media="screen" type="text/css" title="estilo">
	<script src="js/jquery/jquery-1.1.3.1.js" type="text/javascript"></script>
  	<script src="SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
	<link href="SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css" />

	<script language="javascript" type="text/javascript">
		$(document).ready(function() {
			//$("#menu").load("menu_manifiestos.htm");
		});

		function valida_envia()
		{
	    	alert("Desea grabar estos datos?");
    		document.fvalida.submit();
		} 
	</script>
       
<title><?=$_SESSION['empresa']?></title>
</head>
<body>
<div id="menu"><?php include("menu_manifiestos.php");?></div>
<?php
/*
   Fech@: 03/08/2010
   @utor: Pablo Contreras - GT
   Em@il: pablo-contreras@aimargroup.com
*/
include_once("conect_db.php");
include("db-mysql-fecha.php");

$nombres = $_SESSION["nombres"];
$awb = $_GET["awbnumber"];

$link = conect_aereo_local();

$query = mysqli_query($link,"SELECT hawbnumber, arrivaldate FROM {$_SESSION['air']} WHERE awbnumber = '$awb'");
while ($row = mysqli_fetch_array($query))
{
	$hwb = $row["hawbnumber"];
	$arrivaldate = cambia_a_normal($row["arrivaldate"]);
}

$query_hwb = mysqli_query($link,"SELECT puerto_origen, puerto_destino, bultos, almacen, vuelo, conocimiento, ubicacion FROM manifiestos_import WHERE hawbnumber = '$hwb' AND import_export = '{$_SESSION['ie']}'");
while ($row = mysqli_fetch_array($query_hwb))
{
	$puertoorigen = $row["puerto_origen"];
	$puertodestino = $row["puerto_destino"]; 
	$bultos = $row["bultos"];
	$ubicacionfinal = $row["almacen"]; 
	$nomanifiestotica = $row["vuelo"];
	$secuenciatica = $row["conocimiento"];
	$ubicaciontica = $row["ubicacion"];
}
?>
<?php /* <table width="100%">
<tr bgcolor="#336699" height="30">
	<td align="center" width="20%" style="font:normal bold 10pt Verdana,Helvetica, Arial;color:#fff;">
		<a href=inicio.php><font color=white face="Verdana, Arial, Helvetica, sans-serif">Ir a Inicio</font></a>
    </td>
    <td align="center" width="60%" style="font:normal bold 10pt Verdana,Helvetica, Arial;color:#fff;">
        <span id="logged_user" style="cursor:pointer;"><?=$nombres; ?></span>
    </td>
    <td width="20%" align="center" style="font:normal bold 10pt Verdana,Helvetica, Arial;color:#fff;">
        <a href=logout.php><font color=white face="Verdana, Arial, Helvetica, sans-serif">Cerrar sesion</font></a>
    </td>
</tr>
</table>
<br> */?>
<center>

<h1 align="center">Clonar Hwb - <?=$hwb; ?> -</h1>
<form name='fvalida' action="air-operador-clonar-hwb-<?=$_SESSION['impex']?>.php" method="POST">
<table align="center" width="70%">
    <tr>
        <th>HWB:</td>
        <td><input type="hidden" name="hawbnumber" value="<?=$hwb ?>"><?=$hwb ?></td>
        <th>AWB:</td>
        <td><input type="hidden" name="awbnumber" value="<?=$awb ?>"><?=$awb ?></td>
    </tr>
    <tr>
        <th>Puerto Embarque:</td>
        <td><input type="hidden" name="puerto_origen" value="<?=$puertoorigen ?>"><?=$puertoorigen ?></td>
        <th>Puerto Descarga:</td>
        <td><input type="hidden" name="puerto_destino" value="<?=$puertodestino ?>"><?=$puertodestino ?></td>
    </tr>
    <tr>
        <th>Embalaje:</td>
        <td><input type="hidden" name="bultos" value="<?=$bultos ?>"><?=$bultos ?></td>
        <th>Ubicacion Final:</td>
        <td><input type="hidden" name="almacen" value="<?=$ubicacionfinal ?>"><?=$ubicacionfinal ?></td>
    </tr>
    <tr>
        <th>No. Manifiesto:</td>
        <td><input type="hidden" name="vuelo" value="<?=$nomanifiestotica ?>"><?=$nomanifiestotica ?></td>
        <th>Fecha Arribo:</td>
        <td><input type="hidden" name="arrivaldate" value="<?=$arrivaldate ?>"><?=$arrivaldate ?></td>
    </tr>
    <tr>
        <th>Ubicacion Tica:</td>
        <td><input type="hidden" name="ubicacion" value="<?=$ubicaciontica ?>"><?=$ubicaciontica ?></td>
        <th>Secuencia Tica:</td>
        <td><input type="hidden" name="conocimiento" value="<?=$secuenciatica ?>"><?=$secuenciatica ?></td>
    </tr>
    <tr>
        <th>No Piezas:</td>
        <td><input type="text" name="piezas" size="25" maxlength="30"></td>
        <th>Peso:</td>
        <td><input type="text" name="peso" size="25" maxlength="30"></td>
    </tr>
</table>
<br>
<input align="center" type="button"  name="accion" value="Grabar" onClick="javascript:valida_envia()">
</form>  
<br>
</center>


   <?php include("pie.php");?>
</body>
</html>