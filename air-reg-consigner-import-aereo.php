<?php include_once("seguridad.php");?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><link rel="shortcut icon" href="imagenes/<?=$_SESSION['empresa']?>.bmp">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	<link rel="stylesheet" href="css/customer.css" media="screen" type="text/css" title="estilo">
	<!--
	<script type="text/javascript" language="javascript" src="js/HttpRequest.js"/></script>
	<script type="text/javascript" language="javascript" src="js/HttpRequestComplex.js"></script>
	<script src="js/jquery/jquery-1.1.3.1.js" type="text/javascript"></script>
  	<script src="SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
	<link href="SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css" />
	-->
	<script language="javascript" type="text/javascript">
		//$(document).ready(function() {
			//$("#menu").load("menu_manifiestos.htm");
		//});
	</script>

	<script language="javascript" type="text/javascript">
		function valida_envia()
		{
			/*
    		if (document.fvalida.cedula2.value.length == 0)
			{
       			//alert("Tienes que ingresar la cedula del Consignatario.")
				alert("Debe estar presente la cedula del Consignatario en el maestro de clientes.")
       			document.fvalida.cedula2.focus()
       			return 0;
    		}
			*/
    		document.fvalida.submit();
		}
	</script>

	<style>
		td {
			text-align:left;
		}
		th {
			width:80px;;
			text-align:right;
		}
	</style>

<title><?=$_SESSION['empresa']?></title>
</head>
<body>
<div id="menu"><?php include("menu_manifiestos.php");?></div>
<?php
/*
   Fech@: 13/07/2010
   @utor: Pablo Contreras - GT
   Em@il: pablo-contreras@aimargroup.com
*/
include_once("conect_db.php");
$nombres = $_SESSION["nombres"];
$hwb = $_GET["hwb"];
$id = $_GET["id"];
$parcial = $_GET["parcial"];

$link = conect_aereo_local();
$ConsignatarioID = 0;
$sql = "SELECT AwbID, HAwbNumber, AwbNumber, ConsignerID FROM {$_SESSION['air']} where HAwbNumber = '$hwb'";
//echo "$sql<br>";
$query_hwb = mysqli_query($link,$sql);
//print_r($query_hwb);
$values = mysqli_fetch_array($query_hwb, MYSQLI_BOTH);
if ($values) {
	//print_r($values);
	$hwb_id = $values["AwbNumber"];
	$identificacion = "J";
	$ConsignatarioID = $values['ConsignerID'];
	$viaje = "HWB";
}

if (empty($hwb_id))	$hwb_id = $_GET["awbnumber"];

$identificacion_reg = "";

$sql = "SELECT cedula2 FROM manifiestos_import WHERE id = $id AND import_export = '{$_SESSION['ie']}'";
//echo "$sql<br>";
$query_cedula = mysqli_query($link,$sql);
if ($query_cedula) {
	$values = mysqli_fetch_array($query_cedula, MYSQLI_BOTH);
	if (strpos("**".$values['cedula2'],"||"))
		list($identificacion_reg,$cedula2_reg) = explode ("||",$values['cedula2']);
	else
		$cedula2_reg = $values['cedula2']; //almacenada por usuario
}

$link1 = conect_master_local();

$sql = "SELECT codigo_tributario, nombre_cliente, id_tipo_identificacion_tributaria FROM clientes WHERE id_cliente = ".intval($ConsignatarioID);
//echo "{$sql}<br>";
$sql_cliente = pg_exec($link1, $sql);
$query_cliente = pg_fetch_assoc($sql_cliente, 0);
$cliente = $query_cliente['nombre_cliente'];
if (empty($cedula2)) $cedula2 = trim($query_cliente['codigo_tributario']); //si usuario no ha almacenado
$identificacion1 = $query_cliente['id_tipo_identificacion_tributaria'];
switch ($identificacion1) {
	case 1:$identificacion = 'F';break;
	case 2:$identificacion = 'J';break;
	case 3:$identificacion = 'E';break;
}
?>

<?php /* <table width="100%">
<tr bgcolor="#336699" height="30">
	<td align="center" width="20%" style="font:normal bold 10pt Verdana,Helvetica, Arial;color:#fff;">
		<a href=inicio.php><font color=white face="Verdana, Arial, Helvetica, sans-serif">Ir a Inicio</font></a>
    </td>
    <td align="center" width="60%" style="font:normal bold 10pt Verdana,Helvetica, Arial;color:#fff;">
        <span id="logged_user" style="cursor:pointer;"><?=$nombres;?></span>
    </td>
    <td width="20%" align="center" style="font:normal bold 10pt Verdana,Helvetica, Arial;color:#fff;">
        <a href=logout.php><font color=white face="Verdana, Arial, Helvetica, sans-serif">Cerrar sesion</font></a>
    </td>
</tr>
</table>
<br> */?>
<center>

<h1 align="center">Registrar Consignatario - HWB - <?=$hwb;?> -</h1>
<form name='fvalida' action="air-operador-reg-consigner-<?=$_SESSION['impex']?>-aereo.php" method="POST">

<input type="hidden" name="id" value="<?=$id?>">
<input type="hidden" name="parcial" value="<?=$parcial?>">
<input type="hidden" name="hwb_id" value="<?=$hwb_id?>">
<input type="hidden" name="hwb" value="<?=$hwb?>">
<input type="hidden" name="nombre_cliente" value="<?=$cliente?>">
<input type="hidden" name="ConsignatarioID" value="<?=$ConsignatarioID?>">
<input type="hidden" name="no_viaje" value="<?=$viaje?>">

<table align="center" width="50%" BORDER=0>
	<tr>
    	<th>Awb Id:</th>
      <td><?=$hwb_id?></td>
			<td></td>
    	<th>Hwb:</th>
      <td><?=$hwb?></td>
	</tr>
	<tr>
			<th>Consignatario:</th>
			<td colspan=4><?=$cliente?></td>
	</tr>
	<tr>
			<th>Cedula Juridica:</th>
			<td><?=$cedula2_reg?></td>
			<td colspan=2><input type="text" name="cedula2" value="<?=$cedula2?>" size="25" maxlength="20"></td>
			<td></td>
	</tr>
	<tr>
    	<th>Tipo Cedula:</th>
			<td><?=$identificacion_reg?></td>
      <td><input type="text" name="tipo_identificacion_id" value="<?=$identificacion?>"  size="15" maxlength="20"></td>
    	<th>Tipo BL:</th>
      <td><?=$viaje?></td>
	</tr>
</table>
<br>
<input align="center" type="button"  name="accion" value="Grabar" onClick="javascript:valida_envia()">
</form>
</center>


   <?php include("pie.php");?>
</body>
</html>
