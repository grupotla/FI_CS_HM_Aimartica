<?php include_once("seguridad.php");?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><link rel="shortcut icon" href="imagenes/<?=$_SESSION['empresa']?>.bmp">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	<link rel="stylesheet" href="css/customer.css" media="screen" type="text/css" title="estilo">
	<script type="text/javascript" language="javascript" src="js/HttpRequest.js"/></script>
	<script type="text/javascript" language="javascript" src="js/HttpRequestComplex.js"></script>
	<script src="js/jquery/jquery-1.1.3.1.js" type="text/javascript"></script>
  	<script src="SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
	<link href="SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css" />

	<script language="javascript" type="text/javascript">
		$(document).ready(function() {
			//$("#menu").load("menu_manifiestos.htm");
		});
	
    	function ventanaPrimaria(hwb) 
		{
   			window.open('air-buscar-puerto-origen.php?hawbnumber='+hwb, 'ventana1', 'height=300, width=450, menubar=0, resizable=1, scrollbars=1, toolbar=0');
		}		

    	function ventanaSecundaria(hwb) 
		{
   			window.open('air-buscar-puerto-destino.php?hawbnumber='+hwb, 'ventana1', 'height=300, width=450, menubar=0, resizable=1, scrollbars=1, toolbar=0');
		}		

    	function ventanaTercenaria(hwb) 
		{
   			window.open('air-buscar-embalaje.php?hawbnumber='+hwb, 'ventana1', 'height=300, width=450, menubar=0, resizable=1, scrollbars=1, toolbar=0');
		}		

    	function ventanaQuaternaria(hwb) 
		{
   			window.open('air-buscar-ubicacion-final.php?hawbnumber='+hwb, 'ventana1', 'height=300, width=450, menubar=0, resizable=1, scrollbars=1, toolbar=0');
		}		

		function valida_envia()
		{
    		if (document.fvalida.puerto_origen.value.length == 0)
			{
       			alert("Tienes que seleccionar un puerto embarque.")
       			document.fvalida.puerto_origen.focus()
       			return 0;
    		}

    		if (document.fvalida.puerto_destino.value.length == 0)
			{
       			alert("Tienes que seleccionar un puerto descarga.")
       			document.fvalida.puerto_destino.focus()
       			return 0;
    		}

    		if (document.fvalida.bultos.value.length == 0)
			{
       			alert("Tienes que seleccionar un embalaje.")
       			document.fvalida.bultos.focus()
       			return 0;
    		}

    		if (document.fvalida.almacen.value.length == 0)
			{
       			alert("Tienes que seleccionar una ubicacion final.")
       			document.fvalida.almacen.focus()
       			return 0;
    		}
			
    		document.fvalida.submit();
		} 
	</script>
       
<title><?=$_SESSION['empresa']?></title>
</head>
<body>
<div id="menu"><?php include("menu_manifiestos.php");?></div>
<?php
/*
   Fech@: 19/03/2010
   @utor: Pablo Contreras - GT
   Em@il: pablo-contreras@aimargroup.com
*/
include_once("conect_db.php");
$nombres = $_SESSION["nombres"];
$awb = $_GET["awb"];
$hwb = $_GET["hawbnumber"];
$consignerid = $_GET["consignerid"];

$link = conect_aereo_local();
/*
$query = mysqli_query($link,"SELECT awbnumber, consignerdata FROM {$_SESSION['air']} WHERE hawbnumber = '$hwb'");
while ($row = mysqli_fetch_array($query))
{
	$awb = $row["awbnumber"];
	$consigner = $row["consignerdata"];
}
$link1 = conect_localhost();
*/

/*
$ConsignatarioID = 0;	
$link = conect_aereo_local();
$sql = "SELECT AwbID, HAwbNumber, AwbNumber, ConsignerID FROM {$_SESSION['air']} where HAwbNumber = '$hwb'";
echo "({$sql})<br>";
$query_hwb = mysqli_query($link,$sql);
if ($query_hwb)
{		
	$values = mysqli_fetch_array($query_hwb, MYSQLI_BOTH);
	$awb = $values["AwbNumber"];	
	$ConsignatarioID = $values['ConsignerID'];	
}
*/

$link1 = conect_master_local();
$sql = "SELECT codigo_tributario, nombre_cliente FROM clientes WHERE id_cliente = $consignerid";
//echo "({$sql})<br>";
$sql_cliente = pg_exec($link1, $sql);
$query_cliente = pg_fetch_array($sql_cliente, 0); 
$cedula2 = trim($query_cliente[0]);
$cliente = $query_cliente[1];

$puerto_origen = "";
$puerto_destino = "";
$bultos = "";
$almacen = "";
$sql = "SELECT puerto_origen, puerto_destino, bultos, almacen FROM manifiestos_import WHERE awbnumber = '$awb' AND import_export = '{$_SESSION['ie']}' AND hawbnumber='$hwb'";
//echo "$sql<br>";
$sql_has_vuelo = mysqli_query($link,$sql);
if ($row = mysqli_fetch_array($sql_has_vuelo, MYSQLI_BOTH))
{
  $puertoorigen = $row["puerto_origen"];
  $puertodestino = $row["puerto_destino"];
  $bultos = $row["bultos"];
  $almacen = $row["almacen"];
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
<!--
<img src="imagenes/gt-flag.gif" width="26" height="17" />  
<img src="imagenes/sv-flag.gif" width="26" height="17" />
<img src="imagenes/hn-flag.gif" width="26" height="17" />
<img src="imagenes/pa-flag.gif" width="26" height="17" />    
-->

<h1 align="center">Completar Hawb - <?=$hwb; ?> -</h1>
<p>Por favor selecciona todos los campos.</p> 
<form name='fvalida' action="air-operador-completar-<?=$_SESSION['impex']?>-aereo.php" method="POST">
<table align="center" width="70%">
    <tr>
        <th>AWB:</td>
        <td><input type="hidden" name="awbnumber" value="<?=$awb ?>"><?=$awb ?></td>
        <th>HWB:</td>
        <td><input type="hidden" name="hawbnumber" value="<?=$hwb ?>"><?=$hwb ?></td>
    </tr>   
    <tr>
        <th>Puerto Embarque:</td>
        <td><input type="text" name="puerto_origen" size="25" maxlength="30" value="<?=$puertoorigen?>"><!--<a href=# onclick="javascript:ventanaPrimaria('$hwb');return false;"><img src='imagenes/buscar.jpg' alt='edit' align='center' border=0></a>-->
          <?=isset($_GET['puerto_origen']) ? "<font color=orange>*</font>"  : "";?>
          <a href="air-buscar-puerto-origen.php?hawbnumber=<?=$hwb?>">
          <img src='imagenes/search.gif' alt='Buscar' align='center' border=0>
          </a>
        </td>
        <th>Puerto Descarga:</td>
        <td><input type="text" name="puerto_destino" size="25" maxlength="30" value="<?=$puertodestino?>"><!--<a href=# onclick="javascript:ventanaSecundaria('$hwb');return false;"><img src='imagenes/buscar.jpg' alt='edit' align='center' border=0></a>-->
          <?=isset($_GET['puerto_destino']) ? "<font color=orange>*</font>"  : "";?>
          <a href="air-buscar-puerto-destino.php?hawbnumber=<?=$hwb?>">
          <img src='imagenes/search.gif' alt='Buscar' align='center' border=0>
          </a>
        </td>
    </tr>
    <tr>
        <th>Embalaje:</td>
        <td><input type="text" name="bultos" size="25" maxlength="30" value="<?=$bultos?>"><!--<a href=# onclick="javascript:ventanaTercenaria('$hwb');return false;"><img src='imagenes/buscar.jpg' alt='edit' align='center' border=0></a>-->
          <?=isset($_GET['bultos']) ? "<font color=orange>*</font>"  : "";?>
          <a href="air-buscar-embalaje.php?hawbnumber=<?=$hwb?>">
          <img src='imagenes/search.gif' alt='Buscar' align='center' border=0>
          </a>    
        </td>
        <th>Ubicacion Final:</td>
        <td><input type="text" name="almacen" size="25" maxlength="30" value="<?=$almacen?>"><!--<a href=# onclick="javascript:ventanaQuaternaria('$hwb');return false;"><img src='imagenes/buscar.jpg' alt='edit' align='center' border=0></a>-->
          <?=isset($_GET['almacen']) ? "<font color=orange>*</font>"  : "";?>
          <a href="air-buscar-ubicacion-final.php?hawbnumber=<?=$hwb?>">
          <img src='imagenes/search.gif' alt='Buscar' align='center' border=0>
        </td>
    </tr>
	
    <tr>
    	<th>Cedula Juridica:</td>
        <td><input type="text" name="cedula2" value="<?=$cedula2 ?>" size="25" maxlength="20"></td>
    	<th>Consignatario</td>
        <td><input type="hidden" name="ConsignatarioID" value="<?=$ConsignatarioID?>"> <?=$cliente ?></td>
    </tr>
</table>
<br>
<?php
//if (empty($puerto_origen) && empty($puerto_destino) && empty($bultos) && empty($almacen))

  echo '<input align="center" type="button"  name="accion" value="Grabar" onClick="javascript:valida_envia()">';
?>

</form>  
</center>


   <?php include("pie.php");?>
</body>
</html>