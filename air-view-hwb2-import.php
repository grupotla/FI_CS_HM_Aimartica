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
		
		function ventanaSecundaria(hwb,id,parcial) 
		{
   			window.open('consigner-hbl-aereo.php?hwb='+hwb+'&id='+id+'&parcial='+parcial, 'ventana2', 'width=700, height=400, scrollbars=YES');
		}
				
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
   Fech@: 05/08/2010
   @utor: Pablo Contreras - GT
   Em@il: pablo-contreras@aimargroup.com
*/
include_once("conect_db.php");
$nombres = $_SESSION["nombres"];
$id = $_GET["id"];
$awb = $_GET["awbnumber"];

$link = conect_aereo_local();

$query = mysqli_query($link,"SELECT vuelo, conocimiento, ubicacion, fecha_arribo_tica FROM manifiestos_import WHERE parcializado = 1 AND id = $id AND awbnumber = '$awb' AND import_export = '{$_SESSION['ie']}'");
while ($row = mysqli_fetch_array($query))
{
	$vuelo = $row["vuelo"];
	$farribo = $row["fecha_arribo_tica"];
	$idubicacion = $row["ubicacion"];
	$conocimiento = $row["conocimiento"];
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

<h1 align="center">Detalle AWB: <?=$awb; ?></h1>
<table align="center" width="80%">
	<tr>
    	<th>No. Manifiesto:</td>
        <td><?=$vuelo; ?></td>
	   	<th>Fecha Arribo:</td>
        <td><?=$farribo; ?></td>
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
	<td><a href="javascript:void()" target="_blank" onclick=window.open("air-documento-aereo2-<?=$_SESSION['impex']?>.php?awbnumber=<?php echo $awb?>&id=<?php echo $id?>")><img src="imagenes/pdf.gif" title="Generar Documento" border="0"></a></td>
	<td><a href="air-editar-manifiesto-aereo2-<?=$_SESSION['impex']?>.php?awbnumber=<?php echo $awb?>&id=<?php echo $id?>&vuelo=<?php echo $vuelo?>" onclick="if (!confirm('Confirme Editar Llave Manifiesto')) { return false;}"><img src="imagenes/aimartica.png" title="Editar Llave Manifiesto" border="0"></a></td>
	<td><a href="air-generar-manifiesto-<?=$_SESSION['impex']?>-aereo2.php?awbnumber=<?php echo $awb?>&id=<?php echo $id?>" onclick="if (!confirm('Confirme Generar Manifiesto')) { return false;}"><img src="imagenes/manifiesto.jpg" title="Generar Manifiesto" border="0"></a></td>
	<td><a href="air-endosar-manifiesto-<?=$_SESSION['impex']?>-aereo2.php?awbnumber=<?php echo $awb?>&id=<?php echo $id?>" onclick="if (!confirm('Confirme Endosar')) { return false;}"><img src="imagenes/calendar.png" title="Endosar" border="0"></a></td>
	<td><a href="air-borrar-manifiesto-<?=$_SESSION['impex']?>-aereo2.php?awbnumber=<?php echo $awb?>&id=<?php echo $id?>" onclick="if (!confirm('Confirme Mensaje de Anulacion')) { return false;}"><img src="imagenes/delete.gif" title="Mensaje de Anulacion" border="0"></a></td>
</table>
<br>
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tbl-regs">
<thead>
<tr>
<td></td><td></td><td><form method="POST" action="air-modificar-manifiesto-<?=$_SESSION['impex']?>-aereo2.php?awbnumber=<?php echo $awb?>&id=<?php echo $id?>"><label for="id_change"></label><?= $cambio; ?><td colspan="2"><label><input type="submit" name="Modificar" id="Modificar" value="Modificar" onclick="if (document.getElementById('id_change').value == 0) {alert('Seleccione una opción por favor'); return false;}" /></label></form></td>
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
		<th align="center">Reg Consig</th>
        <th align="center">Ver Consig</th>		
    </tr>
</thead>
<tbody>	
<?php
$query_hwb = mysqli_query($link,"SELECT m.hawbnumber, b.consignerdata, b.consignerid, b.natureqtygoods, m.peso, m.piezas, m.puerto_origen, m.puerto_destino, m.bultos, m.almacen, m.fecha_endoso, m.statusmanif, m.id FROM {$_SESSION['air']} b, manifiestos_import m WHERE b.awbnumber = m.awbnumber AND b.hawbnumber = m.hawbnumber AND b.expired = 0 AND m.parcializado = 1 AND m.awbnumber = '$awb' AND m.id = $id AND import_export = '{$_SESSION['ie']}'");

if ($query_hwb)
{
	$link1 = conect_master_local();

	for ($row2 = 0; $row2 < mysqli_num_rows($query_hwb); $row2++)
	{
		$values = mysqli_fetch_array($query_hwb, MYSQLI_BOTH);

    	$hwb = $values['hawbnumber'];
    	$hwb = str_replace("-","",$hwb);
   		$hwb = str_replace(" ","",$hwb);
    	$consignatario = $values['consignerdata'];
		$consignatario2 = array();
   		$consignatario2 = explode("\n", $consignatario);
		$consignatario2[0];
    	$piezas = $values['piezas'];
    	$comodities = $values['natureqtygoods'];
    	$peso = $values['peso'];
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

		printf("<tr $color1><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td></tr>", 
		$row2 + 1, "<a href='air-editar-hwb2-{$_SESSION['impex']}.php?hawbnumber=$hwb&id=$id'>$hwb</a>", 
		$consignatario2[0], "HWB", "J", $origen, $destino, $piezas, $tipobultos, $comodities, $peso, "1", $bodega, $fendoso, 
		"<a href='air-borrar-hwb2-{$_SESSION['impex']}-aereo.php?hawbnumber=$hwb&id=$id&awbnumber=$awb' class='activation'><img src='imagenes/delete.gif' alt='edit' align='center' border=0></a>"
,"<a href='air-reg-consigner-{$_SESSION['impex']}-aereo.php?hwb=$hwb&id={$values['id']}&parcial=1' class='activation'><img src='imagenes/chat.jpg' alt='edit' align='center' border=0></a>" 
,"<a href=# onclick=javascript:ventanaSecundaria('$hwb','{$values['id']}','1');><img src='imagenes/chat.jpg' alt='edit' align='center' border=0></a>" 		
		);
    }
}
?>
</table>
</center>
<br>
<p align="center"><a href="air-view-hwb-parcializados-<?=$_SESSION['impex']?>.php?awbnumber=<?php echo $awb?>"><img src="imagenes/home.png" alt="Inicio" border="0"></a></p>


   <?php include("pie.php");?>
</body>
</html>