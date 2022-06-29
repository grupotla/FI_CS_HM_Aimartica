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

		function valida_envia()
		{
	    	if (confirm("Desea grabar estos datos?")){
				document.fvalida.submit();
			} else {
				return false;
			}

		}
	</script>

<title><?=$_SESSION['empresa']?></title>
</head>
<body>
<div id="menu"><?php include("menu_manifiestos.php");?></div>
<?php
/*
   Fech@: 29/01/2010
   @utor: Pablo Contreras - GT
   Em@il: pablo-contreras@aimargroup.com
*/
include_once("conect_db.php");
include("db-mysql-fecha.php");

$nombres = $_SESSION["nombres"];
$awb = $_GET["awbnumber"];

$link = conect_aereo_local();

$sql_has_vuelo = mysqli_query($link,"SELECT vuelo FROM manifiestos_import WHERE awbnumber = '$awb' AND import_export = '{$_SESSION['ie']}'");
while ($row = mysqli_fetch_array($sql_has_vuelo, MYSQLI_BOTH))
{
	$vuelo = $row["vuelo"];
}

if ($vuelo != "")
{
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

<br>
<div class="frame_tbl">
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tbl-regs">
<thead>
<tr>
<td></td>
<td><a href="air-view-hwb-<?=$_SESSION['impex']?>.php?awbnumber=<?php echo $awb?>"><img src="imagenes/manifiesto.jpg" title="Guia Normal" border="0"></a></td>
<td><p>Vuelo Asignado <img src="imagenes/AirTransport.ico" title="SI" border="0"></p></td>
<td><a href="air-view-hwb-parcializados-<?=$_SESSION['impex']?>.php?awbnumber=<?php echo $awb?>"><img src="imagenes/faltante.jpg" title="Guias Parcializadas" width="30" height="32" border="0"></a></td>
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
		<th align="center">Reg Consig</th>
        <!-- <th align="center">Ver Consig</th> -->
    </tr>
</thead>
<tbody>
<?php
	$sql = "SELECT b.awbnumber, b.awbid, b.hawbnumber, b.consignerdata, b.consignerid, b.natureqtygoods, b.totweight, b.totnoofpieces, m.puerto_origen, m.puerto_destino, ".($_SESSION['air'] == "Awb" ? "0" : "b.statusmanif")." as statusmanif, m.bultos, m.almacen, m.id FROM {$_SESSION['air']} b, manifiestos_import m WHERE b.awbnumber = m.awbnumber AND b.hawbnumber = m.hawbnumber AND b.expired = 0 AND m.parcializado = 0 AND b.awbnumber = '$awb' AND import_export = '{$_SESSION['ie']}'";
	//echo "$sql<br>";
	$query_hwb = mysqli_query($link,$sql);

	if ($query_hwb)
	{
		$link1 = conect_master_local();

		for ($row2 = 0; $row2 < mysqli_num_rows($query_hwb); $row2++)
		{
			$values = mysqli_fetch_array($query_hwb, MYSQLI_BOTH);

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
	    	$status = $values['statusmanif'];
		$embalage = $values['bultos'];
		$ubicacionfin = $values['almacen'];

			if ($status == 1)
        	{
        		$color = "style='background-color: #8FCAED; color: #336699;'";
        	}
			else
        	{
        		$color = "style='background-color: #FFFFFF; color: #336699;'";
        	}

			/*printf("<tr $color1><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td></tr>", $row2 + 1, $hwb, $consignatario2[0], "HWB", "J", "", "", $piezas, "", $comodities, $peso, "1", "");*/
printf("<tr $color1><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td></tr>", //<td> &nbsp;%s&nbsp; </td></tr>",
$row2 + 1, $hwb, $consignatario2[0], "HWB", "J", $origen, $destino, $piezas, $embalage, $comodities, $peso, "1", $ubicacionfin
,"<a href='air-reg-consigner-{$_SESSION['impex']}-aereo.php?hwb=$hwb&id={$values['id']}&parcial=0&awbnumber=$awb' class='activation'><img src='imagenes/chat.jpg' alt='edit' align='center' border=0></a>"
//,"<a href=# onclick=javascript:ventanaSecundaria('$hwb','{$values['id']}','0');><img src='imagenes/chat.jpg' alt='edit' align='center' border=0></a>" 2014-04-24
);

    	}
	}
}
else
{
	$sql = "SELECT awbnumber, iatano, arrivaldate FROM {$_SESSION['air']} WHERE expired = 0 AND awbnumber = '$awb'";
	//echo "$sql<br>";
	$query = mysqli_query($link,$sql);

	if (mysqli_num_rows($query) > 0) {
		while ($row = mysqli_fetch_array($query, MYSQLI_BOTH))
		{
			$iatano = $row["iatano"];
			$arrivaldate = cambia_a_normal($row["arrivaldate"]);
		}
	}

	$link1 = conect_localhost();

	$sql_ubicacion = "SELECT identificador AS ubicacion FROM ubicaciones_tica";
	$sql_view_ubicacion = pg_exec($link1, $sql_ubicacion);
	$options_ubicacion = "";
	while ($row = pg_fetch_array($sql_view_ubicacion))
	{
		$idubicacion = $row["ubicacion"];
		$options_ubicacion.="<OPTION VALUE=\"$idubicacion\">".$idubicacion.'</option>';
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

<p>Despues de completar datos de Hwb, llena ahora los datos de esta tabla.</p>
<form name='fvalida' action="air-operador-ingresar-vuelo.php" method="POST">
<table align="center" width="70%">
	<tr>
    	<th>AWB:</td>
        <td><input type="hidden" name="awbnumber" value="<?=$awb ?>"><?=$awb ?></td>
    	<th>AWB:</td>
        <td><input type="hidden" name="awbnumber1" value="<?=$awb ?>"><?=$awb ?></td>
	</tr>
	<tr>
    	<th>No. Manifiesto:</td>
        <td><input type="text" name="vuelo" size="25" maxlength="30"></td>
    	<th>Secuencia Tica:</td>
        <td><input type="text" name="conocimiento" size="25" maxlength="30"></td>
	</tr>
	<tr>
	   	<th>Fecha Arribo:</td>
        <td><input type="hidden" name="arrivaldate" value="<?=$arrivaldate ?>"><?=$arrivaldate ?></td>
    	<th>Ubicacion Tica:</td>
        <td><input type="text" name="ubicacion" size="25" maxlength="30"></td>
	</tr>
</table>
<br>
<input align="center" type="button"  name="accion" value="Grabar" onClick="javascript:valida_envia()">
</form>
<br>
<div class="frame_tbl">
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tbl-regs">
<thead>
<tr>
<td></td><td></td><td colspan="5"><p>Primero completa los Hwb con los datos que faltan.</p></td>
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
    </tr>
</thead>
<tbody>
<?php


	$sql_awb ="SELECT b.awbid, b.hawbnumber, b.consignerdata, b.consignerid, b.natureqtygoods, b.totweight, b.totnoofpieces, a.airportcode AS origen, c.airportcode AS desembarque, a.country AS paisorigen, c.country AS paisdestino, ".($_SESSION['ie'] == 'f' ? '0' : "b.statusmanif") . " as statusmanif FROM {$_SESSION['air']} b, Airports a, Airports c WHERE b.airportdepid = a.airportid AND b.airportdesid = c.airportid AND b.expired = 0 AND b.awbnumber = '$awb'";
	
	//echo "$sql_awb<br>";
	
	$query_hwb = mysqli_query($link,$sql_awb);

	if (mysqli_num_rows($query_hwb) > 0)
	{
		$link1 = conect_master_local();

		for ($row2 = 0; $row2 < mysqli_num_rows($query_hwb); $row2++)
		{
			$values = mysqli_fetch_array($query_hwb, MYSQLI_BOTH);

			$puerto_origen = pg_exec($link1, "SELECT codigo FROM unlocode WHERE locode = '".$values['origen']."' AND pais = '".$values['paisorigen']."'");
			$query_origen = pg_fetch_array($puerto_origen, null);


			if (!empty($values['paisdestino'])){
			$sql_desem =  "SELECT codigo FROM unlocode WHERE locode = '".$values['desembarque']."' AND pais = '".$values['paisdestino']."'";
			//echo  $sql_desem;
			$puerto_desembarque = pg_exec($link1, $sql_desem);
			//print_r($puerto_desembarque);
			$query_desembarque = pg_fetch_array($puerto_desembarque, 0);
			}

	    	$hwb = $values['hawbnumber'];
    		$consignatario = $values['consignerdata'];
			$consignatario2 = array();
    		$consignatario2 = explode("\n", $consignatario);
			$consignatario2[0];
    		$piezas = $values['totnoofpieces'];
    		$comodities = $values['natureqtygoods'];
    		$peso = $values['totweight'];
			$origen = $query_origen[0];
			$destino = $query_desembarque[0];

			if ($status == 1)
        	{
        		$color = "style='background-color: #8FCAED; color: #336699;'";
        	}
			else
        	{
        		$color = "style='background-color: #FFFFFF; color: #336699;'";
        	}

			printf("<tr $color1><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td></tr>",
			$row2 + 1, "<a href='air-completar-{$_SESSION['impex']}-aereo.php?hawbnumber=$hwb&consignerid={$values['consignerid']}&awb=$awb'>$hwb</a>", $consignatario2[0], "HWB", "J", "", "", $piezas, "", $comodities, $peso, "1", "");
    	}
	} else echo "<tr><td colspan=13>No se encontraron Houses</td></tr>";
}
?>
</table>
</div>
</center>
<br>
<p align="center"><a href="air-<?=$_SESSION['impex']?>-aereo.php"><img src="imagenes/home.png" alt="Inicio" border="0"></a></p>


   
<?php include("pie.php");?>

</body>
</html>
