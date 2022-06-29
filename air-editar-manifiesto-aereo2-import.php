<?php include_once("seguridad.php");?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><link rel="shortcut icon" href="imagenes/<?=$_SESSION['empresa']?>.bmp">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	<link rel="stylesheet" href="css/customer.css" media="screen" type="text/css" title="estilo">
   	<script type="text/javascript" src="calendar/calendar.js"></script>
	<script type="text/javascript" src="calendar/lang/calendar-en.js"></script>
	<script type="text/javascript" src="calendar/calendar-setup.js"></script>
	<script type="text/javascript" language="javascript" src="js/HttpRequest.js"/></script>
	<script type="text/javascript" language="javascript" src="js/HttpRequestComplex.js"></script>
	<script src="js/jquery/jquery-1.1.3.1.js" type="text/javascript"></script>
	<script src="SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
	<link href="SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css" />
	<link href="calendar/calendar-blue.css" rel="stylesheet" type="text/css" />
    
	<script language="javascript" type="text/javascript">
		$(document).ready(function() {
			//$("#menu").load("menu_manifiestos.htm");
		});
	</script>

	<script language="javascript" type="text/javascript">
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
   Fech@: 05/08/2010
   @utor: Pablo Contreras - GT
   Em@il: pablo-contreras@aimargroup.com
*/
include_once("conect_db.php");
include("db-mysql-fecha.php");
$nombres = $_SESSION["nombres"];
$vuelo = $_GET["vuelo"];
$id = $_GET["id"];
$awb = $_GET["awbnumber"];

$link = conect_aereo_local();

$query = mysqli_query($link,"SELECT hawbnumber, conocimiento, ubicacion, DATE_FORMAT(fecha_arribo_tica, '%d/%m/%Y') AS fecha_arribo_tica FROM manifiestos_import WHERE vuelo = '$vuelo' AND id = $id" AND import_export = '{$_SESSION['ie']}');
while ($row = mysqli_fetch_array($query))
{
	$hawb = $row["hawbnumber"];
	$conocimiento = $row["conocimiento"];
	$idubicacion = $row["ubicacion"];
	$farribo = $row["fecha_arribo_tica"];
}

$hwb = $hawb;
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

<h1 align="center">Awb - <?=$awb; ?> -</h1>
<form name='fvalida' action="air-operador-editar-manifiesto-aereo2-<?=$_SESSION['impex']?>.php" method="POST">
<table align="center" width="50%">
    <tr>
        <th>AWB:</td>
        <td><input type="hidden" name="awbnumber" value="<?=$awb ?>"><?=$awb ?></td>
        <th>No. Manifiesto:</td>
        <td><input type="text" name="vuelo" value="<?=$vuelo ?>"size="25" maxlength="30"></td>
    </tr>
    <tr>
        <th>Ubicacion Tica:</td>
        <td><input type="text" name="ubicacion" value="<?=$idubicacion ?>"size="25" maxlength="30"></td>
        <th>Secuencia Tica:</td>
        <td><input type="text" name="conocimiento" value="<?=$conocimiento ?>"size="25" maxlength="30"></td>
    </tr>
    <tr>
        <th>Fecha Arribo:</td>
        <td><input type="text" id="fecha1" name="fecha_arribo_tica" size="22" maxlength="30" value="<?=$farribo ?>" onFocus="blur()"><img src="imagenes/calendar.png" id="trigger1"></td>
        <th>Id Guia Parcializada</td>
        <td><input type="hidden" name="id" value="<?=$id ?>"><?=$id ?></td>
    </tr>
</table>
<br>
<input align="center" type="button"  name="accion" value="Grabar" onClick="javascript:valida_envia()">
</form>  

	<script type="text/javascript">
	Calendar.setup(
	{
		inputField : "fecha1", 
		ifFormat : "%d/%m/%Y", 
		button : "trigger1" 
	}
	);
	</script>

</center>


   <?php include("pie.php");?>
</body>
</html>