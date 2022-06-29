<?php 
if (!isset($_GET['impex'])) $_GET['impex'] = 't'; ?>
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
	</script>

<title><?=$_SESSION['empresa']?></title>
</head>
<body>

<?php
/*
   Fech@: 29/01/2010
   @utor: Pablo Contreras - GT
   Em@il: pablo-contreras@aimargroup.com
*/
include_once("conect_db.php");
$nombres = $_SESSION["nombres"];
$link = conect_aereo_local();
//echo "<pre>";
//print_r($_GET);
//echo "</pre>";
	$_SESSION['air'] = $_GET['impex'] == "f" ? "Awb" : "Awbi"; //nombres de tablas del aereo
	$_SESSION['impex'] = $_GET['impex'] == "f" ? "export" : "import"; //usado para nombres de archivos 
	$_SESSION['YCGTPOMIC'] = $_GET['impex'] == "f" ? "1" : "0"; //usado en xml tipo operacion 
	$_SESSION['ie'] = $_GET['impex']; //solo para queryes a manifiestos postgres boolean / mysql char 't' 'f'
	//echo "<pre>";	
	//print_r($_SESSION);
	//echo "</pre>";

	//echo "<br><br>";
?>

<div id="menu"><?php include("menu_manifiestos.php");?></div>

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


<form action="<?php echo $PHP_SELF?>" method="POST" name="busqueda">
<fieldset>
<legend>Buscar por AWB</legend>
<label for="clave">AWB:</label>
<input type="text" name="clave" size="20" maxlength="30">
<input align="center" type="submit" name="accion" value="Buscar">
</form>

	<script type="text/javascript" language="javascript">
	var clave = document.forms['busqueda'].elements['clave'];
	if (clave.value == '')
	{
    	clave.focus();
	}
	</script>

</fieldset>
<br>
<?php
$keyword = $_POST["clave"];

$sql = "SELECT awbnumber, hawbnumber, arrivaldate, createddate, expired FROM {$_SESSION['air']} WHERE awbnumber LIKE '%$keyword%' AND countries = '{$_SESSION['pais']}' ORDER BY createddate DESC";
//$air = mysqli_query($link,$sql);
//$tuplas = mysqli_num_rows($air);
//print_r($tuplas);
?>

<?php $rs = pagination($sql,$link,"mysql",20,"air-{$_SESSION['impex']}-aereo.php");?>
<div class="frame_tbl">
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tbl-regs">
    <tr>
	    <th align="center" width="10%">NO.</th>
        <th align="center" width="30%">AWB</th>
        <th align="center" width="40%">HWB</th>
        <th align="center" width="40%">Fecha Arribo</th>
        <th align="center" width="40%">Agregar</th>
    </tr>
<?php
if ($rs['recordset'])
{
	for ($row = 0; $row < mysqli_num_rows($rs['recordset']); $row++)
	{
		$values = mysqli_fetch_array($rs['recordset'], MYSQLI_BOTH);
    	$awb = $values['awbnumber'];
    	$hwb = $values['hawbnumber'];
    	$farribo = $values['arrivaldate'];
    	$expired = $values['expired'];

		if ($expired == 1)
        {
        	$color = "style='background-color: #8FCAED; color: #336699;'";
        }
		else
        {
        	$color = "style='background-color: #FFFFFF; color: #336699;'";
        }

		printf("<tr $color1><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td></tr>", $row + 1 + (($rs['curPage']-1)*$rs['lineasxpagina']), "<a href='air-view-awb-{$_SESSION['impex']}.php?awbnumber=$awb'>$awb</a>", $hwb, $farribo, "<a href='air-agregar-farribo-hwb-{$_SESSION['impex']}.php?hawbnumber=$hwb' class='activation'><img src='imagenes/calendar.png' alt='edit' align='center' border=0></a>");
    }
}
?>
</table>
</div>
</center>
<p align="center"><a href="air-air.php"><img src="imagenes/home.png" alt="Inicio" border="0"></a></p>


   <?php include("pie.php");?>
</body>
</html>
