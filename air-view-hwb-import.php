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

	
	
	
	
	
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/interface.js"></script>
	<link href="style.css" rel="stylesheet" type="text/css" />

<title><?=$_SESSION['empresa']?></title>
</head>

<body>
<div id="menu"><?php include("menu_manifiestos.php");?></div>
<?php
/*
   Fech@: 20/03/2010
   @utor: Pablo Contreras - GT
   Em@il: pablo-contreras@aimargroup.com
*/
include_once("conect_db.php");
include("db-mysql-fecha.php");

$nombres = $_SESSION["nombres"];
$awb = $_GET["awbnumber"];

$link = conect_aereo_local();

$query = mysqli_query($link,"SELECT hawbnumber FROM manifiestos_import WHERE awbnumber = '$awb' AND parcializado = 1 AND import_export = '{$_SESSION['ie']}'");

if (mysqli_num_rows($query) > 0)
{
	echo"<link rel='stylesheet' href='css/customer.css' media='screen' type='text/css' title='estilo'>
		<style>
		.twoColElsLtHdr #container #header 
		{
			background-position: left right;
			text-align: left;
			vertical-align: top;
			width: 1250px;
			height: 113px;
		}
		</style>";
		echo "<div align='center'><br>La guia aerea: $awb ya fue parcializada. En este link solo puedes ver guias aereas completas.<br>
			<br><a href='air-view-awb-{$_SESSION['impex']}.php?awbnumber=$awb'><button><p>Regresar</p></button></a></div><br>";
		exit;
}

$query = mysqli_query($link,"SELECT b.awbnumber, b.hawbnumber, b.arrivaldate, m.vuelo, m.conocimiento, m.ubicacion FROM {$_SESSION['air']} b, manifiestos_import m WHERE b.awbnumber = m.awbnumber AND b.hawbnumber = m.hawbnumber AND b.expired = 0 AND b.awbnumber = '$awb' AND import_export = '{$_SESSION['ie']}'");
while ($row = mysqli_fetch_array($query))
{
	$arrivaldate = cambia_a_normal($row["arrivaldate"]);
	$vuelo = $row["vuelo"];
	$conocimiento = $row["conocimiento"];
	$idubicacion = $row["ubicacion"];
}

$sql_cambios = "SELECT id_change, modificacion FROM manifiestos_cambios ORDER BY id_change";
$sql_view_cambios = mysqli_query($link,$sql_cambios);
while ($row = mysqli_fetch_array($sql_view_cambios)) 
{
	$idchange = $row["id_change"];
	$modificacion = $row["modificacion"];
}

$cambio = "<select id='id_change' tabindex='1' name='id_change' value='$idchange'>
		<option value='0'>Selecciona un Cambio</option>
		<option value='1'>Puerto Embarque</option>
		<option value='2'>Puerto Descarga</option>
		<option value='3'>Peso</option>
		<option value='4'>Embalaje</option>
		<option value='5'>Cantidad Piezas</option>
		<option value='6'>Commodities</option>		
		<option value='7'>Ubicacion Final</option>		
		</select>";		
				
$link2 = conect_localhost();

$query_xml = "SELECT xml_enviado FROM manifiestos WHERE viaje = '$vuelo' AND import_export = '{$_SESSION['ie']}'";
$result3 = pg_query($link2, $query_xml);
$values3 = pg_fetch_array($result3, $row3, PGSQL_ASSOC);
$xml = $values3['xml_enviado'];
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
<h1 align="center">Detalle del AWB: <?=$awb; ?></h1>   
<table align="center" width="80%">
	<tr>
    	<th>No. Manifiesto:</td>
        <td><?=$vuelo; ?></td>
	   	<th>Fecha Arribo:</td>
        <td><?=$arrivaldate; ?></td>
    	<th>Ubicacion Tica:</td>
        <td><?=$idubicacion; ?></td>
    	<th>Sec. Interna:</td>
        <td>1</td>
    	<th>Sec. Tica:</td>
        <td><?=$conocimiento; ?></td>
	</tr>
</table>

<br>
<table>
	<td><a href="javascript:void()" onclick=window.open("air-documento-aereo-<?=$_SESSION['impex']?>.php?awb=<?php echo $awb?>"); target="_blank"><img src="imagenes/pdf.gif" title="Generar Documento" border="0"></a></td>
	<td><a href="air-editar-manifiesto-aereo-<?=$_SESSION['impex']?>.php?awb=<?php echo $awb?>&vuelo=<?php echo $vuelo?>" onclick="if (!confirm('Confirme Editar Llave Manifiesto')) { return false;}"><img src="imagenes/aimartica.png" title="Editar Llave Manifiesto" border="0"></a></td>
	<td><a href="air-generar-manifiesto-<?=$_SESSION['impex']?>-aereo.php?awb=<?php echo $awb?>" onclick="if (!confirm('Confirme Generar Manifiesto')) { return false;}"><img src="imagenes/manifiesto.jpg" title="Generar Manifiesto" border="0"></a></td>
	<td><a href="endosar-manifiesto-<?=$_SESSION['impex']?>-aereo.php?awb=<?php echo $awb?>" onclick="if (!confirm('Confirme Endosar')) { return false;}"><img src="imagenes/calendar.png" title="Endosar" border="0"></a></td>
	<td><a href="air-borrar-manifiesto-<?=$_SESSION['impex']?>-aereo.php?awb=<?php echo $awb?>" onclick="if (!confirm('Confirme Mensaje de Anulacion')) { return false;}"><img src="imagenes/delete.gif" title="Mensaje de Anulacion" border="0"></a></td>
	<td><a href="air-clonar-hwb-<?=$_SESSION['impex']?>.php?awbnumber=<?php echo $awb?>" onclick="if (!confirm('Confirme Clonar Guia Aerea')) { return false;}"><img src="imagenes/faltante.jpg" title="Clonar Guia Aerea" border="0"></a></td>
</table>
<br>
<div class="frame_tbl">
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tbl-regs">
<thead>
<tr>
<td></td><td></td><td>
	<form method="POST" action="air-modificar-manifiesto-<?=$_SESSION['impex']?>-aereo.php?awb=<?php echo $awb?>">
			
	<label for="id_change"></label><?= $cambio; ?>
	<label>
		<td colspan="3">
			<input type="submit" name="Modificar" id="Modificar" value="Modificar" onclick="if (document.getElementById('id_change').value == 0) {alert('Seleccione una opción por favor'); return false; }"/>
	</label></form>

	</td>
    <tr bgcolor="#336699" height="20">
	    <th align="center">NO.</th>
	    <th align="center">Hawb</th>
	    <th align="center">Consignatario</th>
	    <th align="center">Tipo BL</th>
	    <th align="center">Identifier</th>
	    <th align="center">Puerto Embarque</th>
	    <th align="center">Puerto Descarga</th>
        <th align="center">No. Piezas</th>
        <th align="center">Embalaje</th>
        <th align="center">Commodities</th>
        <th align="center">Peso</th>
        <th align="center">Tipo Merc</th>
        <th align="center">Ubicacion Final</th>
        <th align="center">Endoso</th>
        <th align="center">Borrar</th>
    </tr>
</thead>
<tbody>	
<?php
$query_hwb = mysqli_query($link,"SELECT b.awbid, b.hawbnumber, b.consignerdata, b.consignerid, b.natureqtygoods, b.totweight, b.totnoofpieces, m.puerto_origen, m.puerto_destino, m.bultos, m.almacen, m.fecha_endoso, b.statusmanif FROM {$_SESSION['air']} b, manifiestos_import m WHERE b.awbnumber = m.awbnumber AND b.hawbnumber = m.hawbnumber AND b.expired = 0 AND m.parcializado = 0 AND b.awbnumber = '$awb' AND import_export = '{$_SESSION['ie']}'");
	
if ($query_hwb) 
{
	$link1 = conect_master_local();
	
	for ($row2 = 0; $row2 < mysqli_num_rows($query_hwb); $row2++) 
	{
		$values = mysqli_fetch_array($query_hwb, MYSQLI_BOTH);

    	$id = $values['awbid'];
    	$hwb = $values['hawbnumber'];
    	$consignatario = $values['consignerdata'];
		$consignatario2 = array();
   		$consignatario2 = explode("\n", $consignatario);
		$consignatario2[0];					
    	$piezas = $values['totnoofpieces'];		
    	$comodities = $values['natureqtygoods'];		
    	$peso = $values['totweight'];		
		$origen = $values['puerto_origen'];
		$destino = $values['puerto_destino'];
		$tipobultos = $values['bultos'];
		$bodega = $values['almacen'];
		$fendoso = $values['fecha_endoso'];
    	$status = $values['statusmanif'];		
										
		if ($status == 1)
        {
        	$color = "style='background-color: #8FCAED; color: #336699;'";                
        } 
		else
        {
        	$color = "style='background-color: #FFFFFF; color: #336699;'";            
        }
			
		printf("<tr $color1><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td></tr>", $row2 + 1, "<a href='air-editar-hwb-{$_SESSION['impex']}.php?hawbnumber=$hwb'>$hwb</a>", $consignatario2[0], "HWB", "J", $origen, $destino, $piezas, $tipobultos, $comodities, $peso, "1", $bodega, $fendoso, "<a href='air-borrar-hwb-{$_SESSION['impex']}-aereo.php?awbid=$id' class='activation'><img src='imagenes/delete.gif' alt='edit' align='center' border=0></a>");
    }
}
?> 
</tbody>
</table>
</div>
</center>
<br>
<p align="center"><a href="air-<?=$_SESSION['impex']?>-aereo.php"><img src="imagenes/home.png" alt="Inicio" border="0"></a></p>


   <?php include("pie.php");?>
</body>
</html>
