<?php include_once("seguridad.php");?>
<?php
/*
   Fech@: 03/02/2010
   @utor: Pablo Contreras - GT
   Em@il: pablo-contreras@aimargroup.com
*/
include_once("conect_db.php");
include("db-mysql-fecha.php");
$nombres = $_SESSION["nombres"];
$awb = $_GET["awb"];

$link = conect_aereo_local();
$tipced = "";
$sql = "SELECT b.awbnumber, b.hawbnumber, b.arrivaldate, m.vuelo, m.conocimiento, m.ubicacion, m.cedula2, b.ShipperID FROM {$_SESSION['air']} b, manifiestos_import m WHERE b.awbnumber = m.awbnumber AND b.hawbnumber = m.hawbnumber AND b.expired = 0 AND b.awbnumber = '$awb' AND import_export = '{$_SESSION['ie']}'";
$query = mysqli_query($link,$sql);

//echo "$sql<br><br>";

while ($row = mysqli_fetch_array($query))
{
	//$arrivaldate = cambia_a_normal($row["arrivaldate"]);
	$arrivaldate = $row["arrivaldate"];
	$vuelo = $row["vuelo"];
	$conocimiento = $row["conocimiento"];
	$ubicacion = $row["ubicacion"];
	if (strpos("**".$row['cedula2'],"||")) 
		list($tipced,$cedula2) = explode("||",$row['cedula2']);
	else
		$cedula2 = $row['cedula2']; //almacenada por usuario	
	$id_shipper = $row['ShipperID'];
}

$link2 = conect_localhost();

$sql = "SELECT b.awbid, b.hawbnumber, b.consignerdata, b.consignerid, b.natureqtygoods, b.totweight, b.totnoofpieces, m.puerto_origen, m.puerto_destino, m.bultos, m.almacen, m.fecha_endoso FROM {$_SESSION['air']} b, manifiestos_import m WHERE b.awbnumber = m.awbnumber AND b.hawbnumber = m.hawbnumber AND b.expired = 0 AND m.parcializado = 0 AND b.awbnumber = '$awb' AND import_export = '{$_SESSION['ie']}'";
$query_hwb = mysqli_query($link,$sql);
//echo "$sql<br>";

$link1 = conect_master_local();

$sql_cliente = pg_exec($link1, "SELECT nombre_cliente FROM clientes WHERE id_cliente = ".$id_shipper);
$query_result = pg_fetch_array($sql_cliente, 0);
//$id_shipper = str_replace("&", "Y", $query_result[0]);
//$id_shipper = "<![CDATA[".strtoupper(substr(trim($query_result[0]),0,100))."]]>";
$id_shipper = strtoupper(substr(trim(str_replace("&", "Y", $query_result[0])),0,100));

//echo "$id_shipper<br>";



$query_xml = "SELECT xml_enviado FROM manifiestos WHERE viaje = TRIM('$vuelo') AND import_export = '{$_SESSION['ie']}'";
$result3 = pg_query($link2, $query_xml);
$values3 = pg_fetch_array($result3, $row3, PGSQL_ASSOC);
$xml = $values3['xml_enviado'];

/*
$query_manifest = "SELECT van, declarante, tipo_emisor FROM manifiestos_van";
$manifest = pg_query($link2, $query_manifest);
$valuesmanifest = pg_fetch_array($manifest, $rowmanifest, PGSQL_ASSOC);
$casillero = $valuesmanifest['van'];
$declarante = $valuesmanifest['declarante'];
$tipodeclarante = $valuesmanifest['tipo_emisor'];
*/

$row_viajesmanifest = EmpresaParametros($_SESSION["pais"], "", "");

$casillero = $row_viajesmanifest['van'];
$declarante = $row_viajesmanifest['declarante'];
$tipodeclarante = $row_viajesmanifest['tipo_emisor'];


/* BEGIN TRANSACTION: Generar XML file. */
$sql = "BEGIN;";

$correlativo = pg_exec($link2, "SELECT correlativo FROM manifiestos_secuencia");
$query_correlativo = pg_fetch_array($correlativo, 0);
$correlativo_aplicar = $query_correlativo[0];

$sql = pg_exec($link2, "INSERT INTO manifiestos (sistema_id, correlativo, operacion_id, viaje, fecha_envio, enviado_por, import_export) VALUES (5, '".$correlativo_aplicar."', 1, '".$vuelo."', NOW(), '".$nombres."','{$_SESSION['ie']}')");
//$correlativo_aplicar = 6926;

$qry = "SELECT fecha_envio FROM manifiestos WHERE correlativo = $correlativo_aplicar AND import_export = '{$_SESSION['ie']}'";
//echo $qry . "<br>";
$fecha_envio = pg_exec($link2, $qry);


$query_envio = pg_fetch_array($fecha_envio, 0);
$envio = $query_envio[0];

$fecha_manifiesto = substr($envio, 0, 10);
$hora_manifiesto = substr($envio, 11, 8);
			
$buffer_bl = '<?xml version = "1.0"  encoding = "ISO-8859-1"?>
<ROOT VERSION = "2.01" xmlns = "http://www.hacienda.go.cr/TICA" xmlns:xsi = "http://www.w3.org/2001/XMLSche" >
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
                   <YCGFCHARR>'.$arrivaldate.'</YCGFCHARR>
                      <ROWSET_YCGCON>';
		$buffer_contenedor = '';
if ($query_hwb) 
{
	$row4 = 5000;
			
	for ($row2 = 0; $row2 < mysqli_num_rows($query_hwb); $row2++) 
	{
		$values = mysqli_fetch_array($query_hwb, MYSQLI_BOTH);

   		$hwb = $values['hawbnumber'];
   		$consignatario = $values['consignerdata'];	
		$consignatario2 = array();
   		$consignatario2 = explode("\n", $consignatario);
		//$consignatario2[0];	
		//$consignatario3[0] = str_replace("&", "Y", $consignatario2[0]);
		//$consignatario3 = "<![CDATA[".strtoupper(substr(trim($consignatario2[0]),0,60))."]]>";
		$consignatario3 = strtoupper(substr(trim(str_replace("&", "Y", $consignatario2[0])),0,60));
   		$piezas = $values['totnoofpieces'];		
   		//$comodities = "<![CDATA[".strtoupper(trim($values['natureqtygoods']))."]]>";
   		$comodities = strtoupper(trim(str_replace("&", "Y", $values['natureqtygoods'])));
   		$peso = $values['totweight'];		
		$puertoorigen = $values['puerto_origen'];
		$puertodestino = $values['puerto_destino'];
		$tipobultos = $values['bultos'];
		$bodega = $values['almacen'];
		$enteropiezas = (int) $piezas;  
		$enteropeso = (int) $peso;  
		$row3 = $row2 + 1;
		$row4++; 
			
$buffer_contenedor .= '
						<ROW_YCGCON NUM = "'.$row3.'" >
                              <YCGSECCON>'.$row4.'</YCGSECCON>
                              <YCGNROCON>'.$hwb.'</YCGNROCON>
                              <YCGPTOEMBA>'.$puertoorigen.'</YCGPTOEMBA>
                              <YCGTPORCON>A</YCGTPORCON>
                              <YCGTPOOPER>'.($_SESSION['ie'] == 'f' ? 'N' : 'D').'</YCGTPOOPER>
                              <YCGTDCAGEC>'.$tipodeclarante.'</YCGTDCAGEC>
                              <YCGRUCAGEC>'.$declarante.'</YCGRUCAGEC>
                              <YCGTPOCON>HWB</YCGTPOCON>
                              <YCGNROCONM>'.$conocimiento.'</YCGNROCONM>
                              <YCGPTODESC>'.$puertodestino.'</YCGPTODESC>
                              <YCGCODDOC>'.$tipced.'</YCGCODDOC>
                              <YCGCONCONS>'.$cedula2.'</YCGCONCONS>
                              <YCGNOMCONS>'.$consignatario3.'</YCGNOMCONS>
                              <YCGFCHEND></YCGFCHEND>
                              <YCGNOMEMBA>'.$id_shipper.'</YCGNOMEMBA>
                              <YCGCOURIER>N</YCGCOURIER>
                                 <ROWSET_YCGLIN>
                                     <ROW_YCGLIN NUM = "1" >
                                         <YCGNROLIN>001</YCGNROLIN>
                                         <YCGTPORLIN>A</YCGTPORLIN>
                                         <YCGLINPESB>'.$enteropeso.'.000</YCGLINPESB>
                                         <YCGLINTIPB>'.$tipobultos.'</YCGLINTIPB>
                                         <YCGLINTOTB>'.$enteropiezas.'.000</YCGLINTOTB>
                                         <YCGMARCLIN></YCGMARCLIN>
                                         <YCGDESC>'.$comodities.'</YCGDESC>
                                         <YCGLINDEP>'.$bodega.'</YCGLINDEP>
                                         <YCGCODSUSP>1</YCGCODSUSP>
                                         <YCGLITPOOP></YCGLITPOOP>
                                         <YCGTXTACTA></YCGTXTACTA>
                                     </ROW_YCGLIN>					
                                 </ROWSET_YCGLIN>
					    </ROW_YCGCON>
					      ';
   	}
}

$buffer_xml = '</ROWSET_YCGCON>
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

//echo "Finalizo!";	
//die();
	
if ($sql)
{
	$sql = "UPDATE manifiestos SET xml_enviado = '$name_file' WHERE correlativo = $correlativo_aplicar AND import_export = '{$_SESSION['ie']}'";
	$resultado = pg_exec($link2, $sql);
	$sql = "COMMIT";

	$sql = "UPDATE {$_SESSION['air']} SET transmited = 1 WHERE awbnumber = '$awb'";
	$resultado = mysqli_query($link,$sql);
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
echo "<div align='center'><br><a href='air-view-hwb-{$_SESSION['impex']}.php?awbnumber=$awb'><button><p>Regresar</p></button></a></div><br>";
/* END TRANSACTION: Generar XML file. */

/* BEGIN TRANSACTION: Mapear XML file por FTP. */
// define some variables
$file = $dir.$name_file;
$remote_file = $name_file;

/*
$ip = $_SERVER['REMOTE_ADDR'];
$ftp_server = $ip; // Address of FTP server.
$ftp_user_name = "aimartica"; // Username
$ftp_user_pass = "aimartica"; // Password
*/

$ip = empty($row_viajesmanifest['ftp_server']) ? $_SERVER['REMOTE_ADDR'] : $row_viajesmanifest['ftp_server'];

$ftp_server = $row_viajesmanifest['ftp_server']; // Server
$ftp_user_name = $row_viajesmanifest['ftp_user']; // Username
$ftp_user_pass = $row_viajesmanifest['ftp_pass']; // Password
$ftp_port = $row_viajesmanifest['ftp_port']; // Puerto
       
// establecer una conexión básica
$conn_id = ftp_connect($ftp_server, $ftp_port);

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