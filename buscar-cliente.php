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
$keyword = $_POST["clave"];
$keyword2 = strtoupper($keyword);
?>
<br>
<center>
<p><h1>- Buscar Shipper -</h1>
<form action="<?php echo $PHP_SELF?>" method="POST">
<fieldset>
<legend><b>Ingrese una palabra clave</b></legend>
Clave:<input type="text" name="clave" size="20" maxlength="30" value="<?=$keyword?>">
<input align="center" type="submit" name="accion" value="Buscar">
</fieldset>
</form>
<br>
<?php
include_once("conect_db.php");
$link1 = conect_master_local();

$sql = "SELECT id_cliente, codigo_tributario, nombre_cliente FROM clientes WHERE es_shipper = 't' and id_estatus = '1' and nombre_cliente ILIKE '%$keyword2%' and nombre_cliente <> '' ORDER BY nombre_cliente";
//echo $sql;
$rs = pagination($sql,$link1,"postgres",20,"{$_SERVER["PHP_SELF"]}?codigo={$_GET['codigo']}");
?>
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tbl-regs">
    <tr>
      <th width="10%">No.</th>
	    <th width="10%">Codigo</th>
      <th width="10%">Codigo Tributario</th>
      <th style="width:60%">Shipper</th>
    </tr>
<?php
if ($rs['recordset']) 
{
    $div = 0;
	
	for ($row = 0; $row < pg_num_rows($rs['recordset']); $row++) 
	{
    	$values = pg_fetch_array($rs['recordset'], $row, PGSQL_ASSOC);

    	$codigo = $values['id_cliente'];
    	$nombre = $values['nombre_cliente'];

		if ($div % 2 == 0)
        {
        	$color = "style='background-color: #8FCAED; color: #336699;'";                
        } 
		else
        {
        	$color = "style='background-color: #FFFFFF; color: #336699;'";            
        }
        $div++;
			
		printf('<tr $color1><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td style="text-align:left"> &nbsp;%s&nbsp; </td></tr>',
      //"<a class=labellist href=# onclick=\"top.opener.document.forms[0].bultos.value = '$codigo'; top.close();\">$codigo</a>", 
      $row + 1 + (($rs['curPage']-1)*$rs['lineasxpagina']),
      "<a class=labellist href='$return1&id_shipper=$codigo'>$codigo</a>",
      $values['codigo_tributario'],
      $nombre);
    }
}
?>       
</table>
</div>
</center>
</body>
</html>