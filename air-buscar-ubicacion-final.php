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
   Fech@: 18/11/2010
   @utor: Pablo Contreras - GT
   Em@il: pablo-contreras@aimargroup.com
*/
include_once("conect_db.php");
$link2 = conect_localhost();
/*
$sql_embalaje = "SELECT identificador AS almacen, descripcion FROM ubicaciones_tica ORDER BY almacen";
$sql_view_embalaje = pg_exec($link2, $sql_embalaje);
$options_embalaje = "";
while ($row = pg_fetch_array($sql_view_embalaje)) 
{
	$almacen = $row["almacen"];
	$nombre_descripcion = $row["descripcion"];
}*/
?>
<br>
<center>
<p><h1>- Buscar Ubicacion Final -</h1>
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
	
$sql = "SELECT identificador AS almacen, descripcion FROM ubicaciones_tica WHERE identificador ILIKE '%$keyword2%' AND descripcion <> '' ORDER BY descripcion";

$rs = pagination($sql,$link2,"postgres",20,"{$_SERVER["PHP_SELF"]}?hawbnumber={$_GET['hawbnumber']}");
?>
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tbl-regs">
    <tr>
      <th "style='background-color: #3399FF; color: #FFFFFF;'" align="center" width="1%">No.</th>
	    <th "style='background-color: #3399FF; color: #FFFFFF;'" align="center" width="1%">Codigo</th>
      <th "style='background-color: #3399FF; color: #FFFFFF;'" align="center" width="7%">Ubicacion</th>
    </tr>
<?php
if ($rs['recordset']) 
{
    $div = 0;
	
	for ($row = 0; $row < pg_num_rows($rs['recordset']); $row++) 
	{
    	$values = pg_fetch_array($rs['recordset'], $row, PGSQL_ASSOC);

    	$codigo = $values['almacen'];
    	$nombre = $values['descripcion'];

		if ($div % 2 == 0)
        {
        	$color = "style='background-color: #8FCAED; color: #336699;'";                
        } 
		else
        {
        	$color = "style='background-color: #FFFFFF; color: #336699;'";            
        }
        $div++;
			
		printf('<tr $color1><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td></tr>', 
      //"<a class=labellist href=# onclick=\"top.opener.document.forms[0].almacen.value = '$codigo'; top.close();\">$codigo</a>", 
      $row + 1 + (($rs['curPage']-1)*$rs['lineasxpagina']),
      "<a class=labellist href='$return1&almacen=$codigo'>$codigo</a>",
      $nombre);
    }
}
?>       
</table>
</div>
</center>
</body>
</html>