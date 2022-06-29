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
	</script>
       
<title><?=$_SESSION['empresa']?></title>
</head>
<body>
<div id="menu"><?php include("menu_manifiestos.php");?></div>
<?php
/*
   Fech@: 14/07/2010
   @utor: Pablo Contreras - GT
   Em@il: pablo-contreras@aimargroup.com
*/
include_once("conect_db.php");

$username = $_SESSION["user"];
$nombres = $_SESSION["nombres"];
$hwb = $_GET["hwb"];
$id = $_GET["id"];

$link = conect_aereo_local();
$sql = "SELECT AwbID, HAwbNumber, AwbNumber, consignerdata FROM {$_SESSION['air']} where HAwbNumber = '$hwb'";
//echo $sql;
$query_hwb = mysqli_query($link,$sql);
if ($query_hwb)
{		
	$values = mysqli_fetch_array($query_hwb, MYSQLI_BOTH);
	$hwb_id = $values["AwbNumber"];
	$tipced = "J";
	$consignatario = $values['consignerdata'];
	$consignatario2 = array();
	$consignatario2 = explode("\n", $consignatario);
	$cliente = $consignatario2[0];
	$viaje = "HWB";
}

$sql = "SELECT cedula2 FROM manifiestos_import WHERE id = $id AND import_export = '{$_SESSION['ie']}'"; 	
//echo "$sql<br>";
$query_cedula = mysqli_query($link,$sql);
if ($query_cedula) {
	$values = mysqli_fetch_array($query_cedula, MYSQLI_BOTH);	
	if (strpos("**".$values['cedula2'],"||")) 
		list($tipced,$cedula2) = explode ("||",$values['cedula2']);
	else
		$cedula2 = $values['cedula2']; //almacenada por usuario
}

/*
$link = conect_localhost();
$sql = pg_query($link, "SELECT b.bl_id, b.tipo_identificacion_id, b.id_cliente, v.no_viaje, b.cedula2 FROM bill_of_lading b, viaje_contenedor vc, viajes v WHERE b.viaje_contenedor_id = vc.viaje_contenedor_id AND vc.viaje_id = v.viaje_id AND b.no_bl = '$bl'");
while ($row = pg_fetch_array($sql)) 
{
	$bl_id = $row["bl_id"];
	$identificacion = $row["tipo_identificacion_id"];
	$cedula2 = $row["cedula2"];
	$viaje = $row["no_viaje"];
}
$link1 = conect_master_local();
$values = pg_fetch_array($sql, $row, PGSQL_ASSOC);
$sql_cliente = pg_exec($link1, "SELECT nombre_cliente FROM clientes WHERE id_cliente = ".$values['id_cliente']);
$query_cliente = pg_fetch_array($sql_cliente, 0);
$cliente = $query_cliente[0];
*/
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

<h1 align="center">HWB - <?=$hwb; ?> -</h1>
<br>
<table align="center" width="50%">
	<tr>
    	<th>Cedula Consignatario:</td>
        <td><?=$cedula2; ?></td>
	</tr>
	<tr>
    	<th>Consignatario:</td>
        <td><?=$cliente; ?></td>
	</tr>
	<tr>
    	<th>Tipo Cedula:</td>
        <td><?=$tipced; ?></td>
	</tr>
</table>
<br>
</center>


   <?php include("pie.php");?> 
</body>
</html>