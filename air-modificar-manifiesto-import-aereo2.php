<?php include_once("seguridad.php");?>
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
$idchange = $_POST["id_change"];

$link = conect_aereo_local();

$query = mysqli_query($link,"SELECT vuelo, conocimiento, ubicacion, fecha_arribo_tica FROM manifiestos_import WHERE parcializado = 1 AND id = $id AND awbnumber = '$awb' AND import_export = '{$_SESSION['ie']}'");
while ($row = mysqli_fetch_array($query))
{
	$vuelo = $row["vuelo"];
	$farribo = $row["fecha_arribo_tica"];
	$ubicacion = $row["ubicacion"];
	$conocimiento = $row["conocimiento"];
}

$link2 = conect_localhost();

$query_hwb = mysqli_query($link,"SELECT b.awbid, b.hawbnumber, b.consignerdata, b.consignerid, b.natureqtygoods, b.totweight, b.totnoofpieces, m.puerto_origen, m.puerto_destino, m.bultos, m.almacen, m.courier_id, m.fecha_endoso, m.modificado, m.cambioptoorigen, m.cambioptodestino, m.cambiobultos, m.cambioalmacen, m.cambiopiezas, m.cambiopeso FROM {$_SESSION['air']} b, manifiestos_import m WHERE b.awbnumber = m.awbnumber AND b.hawbnumber = m.hawbnumber AND b.expired = 0 AND m.parcializado = 1 AND b.awbnumber = '$awb' AND m.id = $id AND import_export = '{$_SESSION['ie']}'");

$link1 = conect_master_local();

$query_xml = "SELECT xml_enviado FROM manifiestos WHERE viaje = TRIM('$vuelo') AND operacion_id = 3 AND import_export = '{$_SESSION['ie']}'";
$result3 = pg_query($link2, $query_xml);
$values3 = pg_fetch_array($result3, $row3, PGSQL_ASSOC);
$xml = $values3['xml_enviado'];

/* BEGIN TRANSACTION: Generar XML file. */
$sql = "BEGIN;";

$correlativo = pg_exec($link2, "SELECT correlativo FROM manifiestos_secuencia");
$query_correlativo = pg_fetch_array($correlativo, 0);
$correlativo_aplicar = $query_correlativo[0];

$query_original = "SELECT fecha_envio, xml_enviado, enviado_por FROM manifiestos WHERE viaje = TRIM('$vuelo') AND import_export = '{$_SESSION['ie']}'";
$result4 = pg_query($link2, $query_original);
$values4 = pg_fetch_array($result4, $row4, PGSQL_ASSOC);
$fecha_original = $values4['fecha_envio'];
$xml_original = $values4['xml_enviado'];
$enviado_original = $values4['enviado_por'];

$query_manifest = "SELECT van, declarante, tipo_emisor FROM manifiestos_van";
$manifest = pg_query($link2, $query_manifest);
$valuesmanifest = pg_fetch_array($manifest, $rowmanifest, PGSQL_ASSOC);
$casillero = $valuesmanifest['van'];
$declarante = $valuesmanifest['declarante'];
$tipodeclarante = $valuesmanifest['tipo_emisor'];

$sql = pg_exec($link2, "INSERT INTO manifiestos (sistema_id, correlativo, operacion_id, viaje, fecha_envio, enviado_por, fecha_original, xml_original, original_por, import_export) VALUES (5, '".$correlativo_aplicar."', 3, '".$vuelo."', NOW(), '".$nombres."', '".$fecha_original."', '".$xml_original."', '".$enviado_original."','{$_SESSION['ie']}')");

$fecha_envio = pg_exec($link2, "SELECT fecha_envio FROM manifiestos WHERE correlativo = $correlativo_aplicar AND import_export = '{$_SESSION['ie']}'");
$query_envio = pg_fetch_array($fecha_envio, 0);
$envio = $query_envio[0];

$fecha_manifiesto = substr($envio, 0, 10);
$hora_manifiesto = substr($envio, 11, 8);

$buffer_bl = '<?xml version = "1.0"?>
<ROOT>
		   <ROWSET_YCGDRES>
               <ROW_YCGDRES NUM = "1" >
                   <YCGCASRES>'.$casillero.'</YCGCASRES>
                   <YCGNROMSG>'.$correlativo_aplicar.'</YCGNROMSG>
                   <YCGFCHENVM>'.$fecha_manifiesto.'</YCGFCHENVM>
                   <YCGHRENVMS>'.$hora_manifiesto.'</YCGHRENVMS>
                   <YCGCODVAN>RA</YCGCODVAN>
                   <YCGTDGARA>'.$tipodeclarante.'</YCGTDGARA>
                   <YCGGARANTE>'.$declarante.'</YCGGARANTE>
               </ROW_YCGDRES>
           </ROWSET_YCGDRES>
           <ROWSET_YCGMIC>
               <ROW_YCGMIC NUM = "1" >
                   <YCGTPOTRAN>4</YCGTPOTRAN>
                   <YCGTPOMIC>'.$_SESSION['YCGTPOMIC'].'</YCGTPOMIC>
                   <YCGNROMIC>'.$vuelo.'</YCGNROMIC>
                   <YRGDEPID>'.$ubicacion.'</YRGDEPID>
                   <YCGFCHARR>'.$farribo.'</YCGFCHARR>
                      <ROWSET_YCGCON>';

if ($query_hwb)
{
	$row4 = 1;

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
    	$courier = $values['courier_id'];
		$puertoorigen = $values['puerto_origen'];
		$puertodestino = $values['puerto_destino'];
		$tipobultos = $values['bultos'];
		$bodega = $values['almacen'];
		$modificado = $values['modificado'];
		$ptoorigen = $values['cambioptoorigen'];
		$ptodestino = $values['cambioptodestino'];
		$cambiobultos = $values['cambiobultos'];
		$cambioalmacen = $values['cambioalmacen'];
		$cambiopiezas = $values['cambiopiezas'];
		$cambiopeso = $values['cambiopeso'];
		$enteropiezas = (int) $piezas;
		$enteropeso = (int) $peso;
		$row3 = $row2 + 1;
		$row4++;

		if ($modificado == 1)
		{
			if ($idchange == "1" and $ptoorigen == 1)
			{
$buffer_contenedor = $buffer_contenedor.
'
                          <ROW_YCGCON NUM = "1" >
                              <YCGSECCON>'.$row4.'</YCGSECCON>
                              <YCGPTOEMBA>'.$puertoorigen.'</YCGPTOEMBA>
                              <YCGTPORCON>M</YCGTPORCON>
					      </ROW_YCGCON>
';
			}

			if ($idchange == "2" and $ptodestino == 1)
			{
$buffer_contenedor = $buffer_contenedor.
'
                          <ROW_YCGCON NUM = "1" >
                              <YCGSECCON>'.$row4.'</YCGSECCON>
                              <YCGTPORCON>M</YCGTPORCON>
                              <YCGPTODESC>'.$puertodestino.'</YCGPTODESC>
					      </ROW_YCGCON>
';
			}

			if ($idchange == "3" and $cambiopeso == 1)
			{
$buffer_contenedor = $buffer_contenedor.
'
                          <ROW_YCGCON NUM = "1" >
                              <YCGSECCON>'.$row4.'</YCGSECCON>
                                 <ROWSET_YCGLIN>
                                     <ROW_YCGLIN NUM = "1" >
                                         <YCGNROLIN>1</YCGNROLIN>
                                         <YCGTPORLIN>M</YCGTPORLIN>
                                         <YCGLINPESB>'.$enteropeso.'.000</YCGLINPESB>
                                         <YCGTXTACTA>MODIFICACION PESO</YCGTXTACTA>
                                     </ROW_YCGLIN>
                                 </ROWSET_YCGLIN>
					      </ROW_YCGCON>
';
			}

			if ($idchange == "4" and $cambiobultos == 1)
			{
$buffer_contenedor = $buffer_contenedor.
'
                          <ROW_YCGCON NUM = "1" >
                              <YCGSECCON>'.$row4.'</YCGSECCON>
                                 <ROWSET_YCGLIN>
                                     <ROW_YCGLIN NUM = "1" >
                                         <YCGNROLIN>1</YCGNROLIN>
                                         <YCGTPORLIN>M</YCGTPORLIN>
                                         <YCGLINTIPB>'.$tipobultos.'</YCGLINTIPB>
                                         <YCGTXTACTA>MODIFICACION BULTOS</YCGTXTACTA>
                                     </ROW_YCGLIN>
                                 </ROWSET_YCGLIN>
					      </ROW_YCGCON>
';
			}

			if ($idchange == "5" and $cambiopiezas == 1)
			{
$buffer_contenedor = $buffer_contenedor.
'
                          <ROW_YCGCON NUM = "1" >
                              <YCGSECCON>'.$row4.'</YCGSECCON>
                                 <ROWSET_YCGLIN>
                                     <ROW_YCGLIN NUM = "1" >
                                         <YCGNROLIN>1</YCGNROLIN>
                                         <YCGTPORLIN>M</YCGTPORLIN>
                                         <YCGLINTOTB>'.$enteropiezas.'.000</YCGLINTOTB>
                                         <YCGTXTACTA>MODIFICACION CANTIDAD DE PIEZAS</YCGTXTACTA>
                                     </ROW_YCGLIN>
                                 </ROWSET_YCGLIN>
					      </ROW_YCGCON>
';
			}

			if ($idchange == "6")
			{
$buffer_contenedor = $buffer_contenedor.
'
                          <ROW_YCGCON NUM = "1" >
                              <YCGSECCON>'.$row4.'</YCGSECCON>
                                 <ROWSET_YCGLIN>
                                     <ROW_YCGLIN NUM = "1" >
                                         <YCGNROLIN>1</YCGNROLIN>
                                         <YCGTPORLIN>M</YCGTPORLIN>
                                         <YCGDESC>'.strtoupper(trim($comodities)).'</YCGDESC>
                                         <YCGTXTACTA>MODIFICACION COMMODITIES</YCGTXTACTA>
                                     </ROW_YCGLIN>
                                 </ROWSET_YCGLIN>
					      </ROW_YCGCON>
';
			}

			if ($idchange == "7" and $cambioalmacen == 1)
			{
$buffer_contenedor = $buffer_contenedor.
'
                          <ROW_YCGCON NUM = "1" >
                              <YCGSECCON>'.$row4.'</YCGSECCON>
                                 <ROWSET_YCGLIN>
                                     <ROW_YCGLIN NUM = "1" >
                                         <YCGNROLIN>1</YCGNROLIN>
                                         <YCGTPORLIN>M</YCGTPORLIN>
                                         <YCGLINDEP>'.$bodega.'</YCGLINDEP>
                                         <YCGTXTACTA>MODIFICACION UBICACION ALMACEN</YCGTXTACTA>
                                     </ROW_YCGLIN>
                                 </ROWSET_YCGLIN>
					      </ROW_YCGCON>
';
			}
		}
    }
}

$buffer_xml =
'                      </ROWSET_YCGCON>
               </ROW_YCGMIC>
           </ROWSET_YCGMIC>
</ROOT>';

$dir = $_SESSION['empresa']."_airmanifest/";
if (!file_exists($dir)) 
    mkdir($dir, 0777, true);

$name_file = '0001-'.$correlativo_aplicar.'.cg';
$file = fopen($dir.$name_file, "w+");
fwrite($file, $buffer_bl.$buffer_contenedor.$buffer_xml);
fclose($file);

if ($sql)
{
	$sql = "UPDATE manifiestos SET xml_enviado = '$name_file' WHERE correlativo = $correlativo_aplicar AND import_export = '{$_SESSION['ie']}'";
	$resultado = pg_exec($link2, $sql);
	$sql = "COMMIT";

	$sql = "UPDATE manifiestos_secuencia SET correlativo = correlativo + 1";
	$resultado = pg_exec($link2, $sql);
	$sql = "COMMIT";
	$resultado = pg_exec($link2, $sql);
}
else
{
	$sql = "ROLLBACK";
	$resultado = pg_exec($link2, $sql);
}
echo "<div align='center'>Manifiesto $name_file generado exitosamente!!!\n";
echo "<div align='center'><br><a href='air-view-hwb2-{$_SESSION['impex']}.php?awbnumber=$awb&id=$id'><button><p>Regresar</p></button></a></div><br>";
/* END TRANSACTION: Generar XML file. */

/* BEGIN TRANSACTION: Mapear XML file por FTP. */
// define some variables
$file = $dir.$name_file;
$remote_file = $name_file;
$ip = $_SERVER['REMOTE_ADDR'];

$ftp_server = $ip; // Address of FTP server.
$ftp_user_name = "aimartica"; // Username
$ftp_user_pass = "aimartica"; // Password

// establecer una conexión básica
$conn_id = ftp_connect($ftp_server);

// iniciar sesión con nombre de usuario y contraseña
$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);

ftp_pasv($conn_id, true);

// cargar un archivo
if (ftp_put($conn_id,  $remote_file, $file, FTP_ASCII))
{
	echo "Se ha cargado el archivo XML $remote_file en el computador IP: $ip con éxito\n";
}
else
{
	echo "Hubo un problema durante la transferencia del archivo XML $remote_file al computador IP: $ip\n";
}

// cerrar la conexión ftp
ftp_close($conn_id);
/* END TRANSACTION: Mapear XML file por FTP. */
?>