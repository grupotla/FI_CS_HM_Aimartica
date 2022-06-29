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
   
<title><?=$_SESSION['empresa']?></title>
</head>
<body>
<?php
/*
   Fech@: 27/01/2010
   @utor: Pablo Contreras - GT
   Em@il: pablo-contreras@aimargroup.com
*/
include_once("conect_db.php");
$link = conect_localhost();
$nombres = $_SESSION["nombres"];

		unset($_SESSION['air']);
		unset($_SESSION['impex']);
		$_POST['iReturn'] = "inicio.php";

		if (isset($_SESSION['url']))
			foreach ($_SESSION['url'] as $key => $url) {			
				//echo "($key)({$_SERVER["PHP_SELF"]})<br>";
				//if ($key != $_SERVER["PHP_SELF"])
				unset($_SESSION['url'][$key]);
			}

		$_SESSION['area'] = "Aereo";
?>

<div id="menu"><?php include("menu_manifiestos.php");?></div>

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


<div class="sub_menu_titulo smt_aereo">MENU AEREO</div>
<a class="sub_menu_opciones smo_aereo" href='air-import-aereo.php'>Import</a>
<a class="sub_menu_opciones smo_aereo" href='air-export-aereo.php'>Export</a>

<!--
<center>
<table border=0 width=50%>
<tr valign=top>
	<td align=center width=50%>
	<table border=0 width=100%>
		<tr bgcolor=#0066FF>
        	<td align=center><font color=white face="Verdana, Arial, Helvetica, sans-serif">MENU AEREO</font>
            </td>
        </tr>
		<tr bgcolor=#0099FF>
        	<td><a href="air-import-aereo.php"><font color=white face="Verdana, Arial, Helvetica, sans-serif">Import</font></a>
            </td>
       	</tr>
		<tr bgcolor=#0099FF>
        	<td><a href="export-aereo.php" onclick="return en_desarrollo(this)"><font color=white face="Verdana, Arial, Helvetica, sans-serif">Export</font></a>
            </td>
       	</tr>
	</table>
	</td>
</table>

</center>    
-->
<p align="center"><a href="inicio.php"><img src="imagenes/home.png" alt="Inicio" border="0"></a></p>


   <?php include("pie.php");?>
</body>
</html>