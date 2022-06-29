<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$_SESSION['empresa']?></title>

<link rel="shortcut icon" href="imagenes/<?=$_SESSION['empresa']?>.bmp">
<style>
	body { font-family:arial;	}
</style>
</head>

<body>
	
	<table align=center border=0 cellpadding=10 cellspacing=10>
	<tr>
		<td align=center>
			<h1>Sistema de Manifiestos Costa Rica</h1>
			<h1>Seleccione Empresa</h1>
		</td>
	</tr>
	<tr>
		<td align=center>
			<form method=post action="login.php">	
			<button id="empresa" name="empresa" value="AIMARTICA">
				<img src="imagenes/AIMARTICA.jpg" alt="AIMARTICA"/>
			</button>
			<br>
			Aimartica
			<input type=hidden id=pais name=pais value="CR">
			<input type=hidden id=bg1 name=bg1 value="rgb(47,87,156)">
			<input type=hidden id=bg2 name=bg2 value="rgb(8,144,62)">			</form>
		</td>
	</tr>

	<tr>
		<td align=center>
			<form method=post action="login.php">	
			<button id="empresa" name="empresa" value="LATINTICA">
				<img src="imagenes/LATINTICA.jpg" alt="LATINTICA"/>
			</button>
			<br>
			Latintica
			<input type=hidden id=pais name=pais value="CRLTF">
			<input type=hidden id=bg1 name=bg1 value="rgb(0,98,152)">
			<input type=hidden id=bg2 name=bg2 value="rgb(120,120,123)">
			</form>
		</td>			
	</tr>

</body>