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

    	function ventanaPrimaria(hwb) 
		{
   			window.open('buscar-puerto-origen.php?hawbnumber='+hwb, 'ventana1', 'height=300, width=450, menubar=0, resizable=1, scrollbars=1, toolbar=0');
		}		

    	function ventanaSecundaria(hwb) 
		{
   			window.open('buscar-puerto-destino.php?hawbnumber='+hwb, 'ventana1', 'height=300, width=450, menubar=0, resizable=1, scrollbars=1, toolbar=0');
		}		

    	function ventanaTercenaria(hwb) 
		{
   			window.open('buscar-embalaje.php?hawbnumber='+hwb, 'ventana1', 'height=300, width=450, menubar=0, resizable=1, scrollbars=1, toolbar=0');
		}		

    	function ventanaQuaternaria(hwb) 
		{
   			window.open('buscar-ubicacion-final.php?hawbnumber='+hwb, 'ventana1', 'height=300, width=450, menubar=0, resizable=1, scrollbars=1, toolbar=0');
		}		

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
$nombres = $_SESSION["nombres"];
$id = $_GET["id"];
$hwb = $_GET["hawbnumber"];

$link = conect_aereo_local();

$query_hwb = mysqli_query($link,"SELECT awbnumber, puerto_origen, puerto_destino, piezas, bultos, peso, almacen, vuelo, conocimiento, ubicacion, fecha_arribo_tica FROM manifiestos_import WHERE parcializado = 1 AND hawbnumber = '$hwb' AND import_export = '{$_SESSION['ie']}'");
while ($row = mysqli_fetch_array($query_hwb))
{
	$awb = $row["awbnumber"];
	$puertoorigen = $row["puerto_origen"];
	$puertodestino = $row["puerto_destino"]; 
	$piezas = $row["piezas"];
	$bultos = $row["bultos"];
	$peso = $row["peso"];
	$almacen = $row["almacen"]; 
	$nomanifiestotica = $row["vuelo"];
	$secuenciatica = $row["conocimiento"];
	$ubicaciontica = $row["ubicacion"];
}

if (isset($_GET['puerto_origen'])) {
  $puertoorigen = $_GET['puerto_origen'];
}

if (isset($_GET['puerto_destino'])) {
  $puertodestino = $_GET['puerto_destino'];
}

if (isset($_GET['bultos'])) {
  $bultos = $_GET['bultos'];
}

if (isset($_GET['almacen'])) {
  $almacen = $_GET['almacen'];
}

$link1 = conect_localhost();
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

<h1 align="center">Editar Hwb - <?=$hwb; ?> -</h1>
<form name='fvalida' action="air-operador-editar-hwb2-<?=$_SESSION['impex']?>.php" method="POST">
<table align="center" width="70%">
    <tr>
        <th>HWB:</td>
        <td><input type="hidden" name="hawbnumber" value="<?=$hwb ?>"><?=$hwb ?></td>
        <th>AWB:</td>
        <td><input type="hidden" name="awbnumber" value="<?=$awb ?>"><?=$awb ?></td>
    </tr>  
    <tr>
        <th>Puerto Embarque:</td>
        <td><input type="text" name="puerto_origen" value="<?=$puertoorigen ?>"size="25" maxlength="30"><!--<a href=# onclick="javascript:ventanaPrimaria('$hwb');return false;"><img src='imagenes/buscar.jpg' alt='edit' align='center' border=0></a>-->
          <?=isset($_GET['puerto_origen']) ? "<font color=orange>*</font>"  : "";?>
          <a href="air-buscar-puerto-origen.php?hawbnumber=<?=$hwb?>">
          <img src='imagenes/search.gif' alt='Buscar' align='center' border=0>
          </a>          
        </td>
        <th>Puerto Descarga:</td>
        <td><input type="text" name="puerto_destino" value="<?=$puertodestino ?>"size="25" maxlength="30"><!--<a href=# onclick="javascript:ventanaSecundaria('$hwb');return false;"><img src='imagenes/buscar.jpg' alt='edit' align='center' border=0></a>-->
          <?=isset($_GET['puerto_destino']) ? "<font color=orange>*</font>"  : "";?>
          <a href="air-buscar-puerto-destino.php?hawbnumber=<?=$hwb?>">
          <img src='imagenes/search.gif' alt='Buscar' align='center' border=0>
          </a>  
        </td>
    </tr>
    <tr>
        <th>Embalaje:</td>
        <td><input type="text" name="bultos" value="<?=$bultos ?>"size="25" maxlength="30"><!--<a href=# onclick="javascript:ventanaTercenaria('$hwb');return false;"><img src='imagenes/buscar.jpg' alt='edit' align='center' border=0></a>-->
          <?=isset($_GET['bultos']) ? "<font color=orange>*</font>"  : "";?>
          <a href="air-buscar-embalaje.php?hawbnumber=<?=$hwb?>">
          <img src='imagenes/search.gif' alt='Buscar' align='center' border=0>
          </a>  
        </td>
        <th>Ubicacion Final:</td>
        <td><input type="text" name="almacen" value="<?=$almacen ?>"size="25" maxlength="30"><!--<a href=# onclick="javascript:ventanaQuaternaria('$hwb');return false;"><img src='imagenes/buscar.jpg' alt='edit' align='center' border=0></a>-->
          <?=isset($_GET['almacen']) ? "<font color=orange>*</font>"  : "";?>
          <a href="air-buscar-ubicacion-final.php?hawbnumber=<?=$hwb?>">
          <img src='imagenes/search.gif' alt='Buscar' align='center' border=0>
          </a>          
        </td>
    </tr>
    <tr>
        <th>No. Piezas:</td>
        <td><input type="text" name="piezas" value="<?=$piezas ?>"size="25" maxlength="30"></td>
        <th>Peso:</td>
        <td><input type="text" name="peso" value="<?=$peso ?>"size="25" maxlength="30"></td>
    </tr>
    <tr>
        <th>Id Guia Parcializada:</td>
        <td><input type="hidden" name="id" value="<?=$id ?>"><?=$id ?></td>
        <th></td>
        <td></td>
    </tr>
</table>
<br>
<input align="center" type="button"  name="accion" value="Grabar" onClick="javascript:valida_envia()">
</form>  
<br>
<!--
<form action="air-endoso-hwb2-<?=$_SESSION['impex']?>.php?hawbnumber=<?php echo $hwb?>&id=<?php echo $id?>" method="POST"><td><input type="hidden" name="hawbnumber" value="<?=$hwb ?>"></td><input type="submit" name="action" value="Agregar Endoso"></form>
-->
<a href="air-endoso-hwb2-<?=$_SESSION['impex']?>.php?hawbnumber=<?=$hwb?>">
  <button>
  Agregar Endoso
  </button>
</a>

</center>
<br>


   <?php include("pie.php");?>
</body>
</html>