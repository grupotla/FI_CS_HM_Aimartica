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
       
<title><?=$_SESSION['empresa']?></title>

</head>
<body>
<div id="menu"><?php include("menu_manifiestos.php");?></div>
<?php
/*
   Fech@: 21/07/2010
   @utor: Pablo Contreras - GT
   Em@il: pablo-contreras@aimargroup.com
*/
include_once("conect_db.php");
$link2 = conect_master_local();
/*
$sql_puerto_origen = "SELECT unlocode_id AS uno, codigo AS puerto_origen, nombre AS nombre_origen FROM unlocode ORDER BY nombre_origen";
$sql_view_puerto_origen = pg_exec($link2, $sql_puerto_origen);
$options_puerto_origen = "";
while ($row = pg_fetch_array($sql_view_puerto_origen)) 
{
	$uno = $row["uno"];
	$idpuerto_origen = $row["puerto_origen"];
	$nombre_origen = $row["nombre_origen"];
}*/
?>
<br>
<center>
<p><h1>- Buscar Puerto Embarque -</h1>
<form action="<?php echo $PHP_SELF?>" method="POST">
<fieldset>
<legend><b>Ingrese una palabra clave</b></legend>
Clave:<input type="text" name="clave" size="20" maxlength="30">
<input align="center" type="submit" name="accion" value="Buscar">
</fieldset>
</form>
<br>
<?php
$keyword = $_POST["clave"];
$keyword2 = strtoupper($keyword);
	
//$result = pg_query($link2, $sql);

$sql = "SELECT unlocode_id, codigo, nombre, pais FROM unlocode WHERE nombre ILIKE '%$keyword2%' ORDER BY nombre";

$rs = pagination($sql,$link2,"postgres",20,"{$_SERVER["PHP_SELF"]}?hawbnumber={$_GET['hawbnumber']}");
?>
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tbl-regs">
    <tr>
        <th "style='background-color: #3399FF; color: #FFFFFF;'" align="center" width="1%">No.</th>
	    <th "style='background-color: #3399FF; color: #FFFFFF;'" align="center" width="1%">Codigo</th>
        <th "style='background-color: #3399FF; color: #FFFFFF;'" align="center" width="7%">Puerto Embarque</th>
	    <th "style='background-color: #3399FF; color: #FFFFFF;'" align="center" width="1%">Pais</th>
    </tr>
<?php
if ($rs['recordset']) 
{
    $div = 0;
	
	for ($row = 0; $row < pg_num_rows($rs['recordset']); $row++) 
	{
    	$values = pg_fetch_array($rs['recordset'], $row, PGSQL_ASSOC);

    	$codigo = $values['codigo'];
    	$nombre = $values['nombre'];
    	$pais = $values['pais'];

		if ($div % 2 == 0)
        {
        	$color = "style='background-color: #8FCAED; color: #336699;'";                
        } 
		else
        {
        	$color = "style='background-color: #FFFFFF; color: #336699;'";            
        }
        $div++;
			
		printf('<tr $color1><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td></tr>', 
            //"<a class=labellist href=javascript:void() onclick=alert(window.parent.getElementById('puerto_origen').value);>$codigo</a>", 

            $row + 1 + (($rs['curPage']-1)*$rs['lineasxpagina']),

            "<a class=labellist href='$return1&puerto_origen=$codigo'>$codigo</a>", 

            $nombre, $pais);
    }
}
?>       
</table>
</div>
</center>
</body>
</html>