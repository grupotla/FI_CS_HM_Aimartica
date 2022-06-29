<?php include("seguridad.php");?>
<?php
/*
   Fech@: 12/07/2010
   @utor: Pablo Contreras - GT
   Em@il: pablo-contreras@aimargroup.com
*/
include("conect_db.php");
$nombres = $_SESSION["nombres"];
$viaje = $_GET["no_viaje"];

$link = conect_localhost();

$query_viaje = "SELECT b.bl_id, b.no_bl, b.id_cliente, b.no_piezas, b.comodity_id, b.peso, e.identificador AS tipo_bulto, b.tipo_conocimiento_id, b.tipo_identificacion_id, b.tipo_merc_p, p.identificador AS almacen, u.identificador AS ubicacion_tica, vc.id_container_type, vc.viaje_contenedor_id, vc.mbl, vc.no_contenedor, v.id_puerto_origen, v.id_puerto_desembarque, v.fecha_arribo, v.no_viaje, m.vuelo, m.conocimiento, m.ubicacion_tica AS tica, b.dividido, b.activo, b.transmitido, b.justificacion_faltante, b.asociated, b.transmitido2, v.import_export FROM bill_of_lading b, viaje_contenedor vc, viajes v, embalajes_tica e, ubicaciones_tica u, ubicaciones_tica p, manifiestos_import m WHERE b.viaje_contenedor_id = vc.viaje_contenedor_id AND vc.viaje_id = v.viaje_id AND b.embalaje_tica_id = e.embalaje_tica_id AND b.ubicacion_tica_id = u.ubicacion_tica_id AND b.id_almacen = p.ubicacion_tica_id AND b.activo AND vc.activo AND v.activo AND v.no_viaje = m.viaje AND v.no_viaje = '$viaje' AND b.dividido = 'f' AND b.import_export = '{$_SESSION['ie']}' AND m.import_export = '{$_SESSION['ie']}' ORDER BY b.bl_id";
$result = pg_query($link, $query_viaje);
$tuplas_bls = pg_num_rows($result);

$query_hbl = "SELECT b.bl_id, b.no_bl, b.id_cliente, b.no_piezas, b.comodity_id, b.peso, e.identificador AS tipo_bulto, b.tipo_conocimiento_id, b.tipo_identificacion_id, b.tipo_merc_p, p.identificador AS almacen, u.identificador AS ubicacion_tica, vc.id_container_type, vc.viaje_contenedor_id, vc.mbl, vc.no_contenedor, v.id_puerto_origen, v.id_puerto_desembarque, v.fecha_arribo, v.no_viaje, m.vuelo, m.conocimiento, m.ubicacion_tica AS tica, d.division_id AS hdivision, d.no_bl AS hbl, d.cliente AS hcliente, d.no_bultos AS hpiezas, d.peso AS hpeso, d.descripcion AS hcommodities, b.dividido, d.activo, d.transmitido, d.asociated, d.transmitido2, v.import_export FROM bill_of_lading b, viaje_contenedor vc, viajes v, embalajes_tica e, ubicaciones_tica u, ubicaciones_tica p, manifiestos_import m, divisiones_bl d WHERE b.viaje_contenedor_id = vc.viaje_contenedor_id AND vc.viaje_id = v.viaje_id AND b.embalaje_tica_id = e.embalaje_tica_id AND b.ubicacion_tica_id = u.ubicacion_tica_id AND b.id_almacen = p.ubicacion_tica_id AND b.activo AND vc.activo AND v.activo AND v.no_viaje = m.viaje AND v.no_viaje = '$viaje' AND b.dividido = 't' AND d.activo AND b.bl_id = d.bl_asoc AND b.import_export = '{$_SESSION['ie']}' AND m.import_export = '{$_SESSION['ie']}' ORDER BY d.division_id";
$result2 = pg_query($link, $query_hbl);

$link1 = conect_master_local();

$values = pg_fetch_array($result, $row, PGSQL_ASSOC);
$values2 = pg_fetch_array($result2, $row2, PGSQL_ASSOC);

if (pg_num_rows($result) > 0)
{
	$farribo = $values['fecha_arribo'];
	$tica = $values['tica'];
	$identificacion = $values['tipo_identificacion_id'];
	$vuelo = $values['vuelo'];
	$conocimiento = $values['conocimiento'];
	$pesocontenedor = $values['peso'];

	$contenedornew = $values['no_contenedor'];
	//$contenedor2 = substr($contenedor, 0, 4);
	//$contenedor3 = substr($contenedor, 4, 8);
	//$contenedor4 = $contenedor2.'-'.$contenedor3;
	$contenedornew2 = str_replace("-","",$contenedornew);
	$contenedornew4 = str_replace(" ","",$contenedornew2);

	$query_counter1 = "SELECT sec_tica FROM manifiestos_counter WHERE contenedor = '$contenedornew'";
	$counter1 = pg_query($link, $query_counter1);
	$counter_values1 = pg_fetch_array($counter1, $row_counter1, PGSQL_ASSOC);
	$secuencia1 = $counter_values1['sec_tica'];

	$containertype = pg_exec($link1, "SELECT short_name FROM container_type WHERE id_container_type = ".$values['id_container_type']);
	$query_containertype = pg_fetch_array($containertype, 0);
	$tipocontenedor = $query_containertype[0];
	$tipocontenedor2 = substr($tipocontenedor, 0, 2);
}

if (pg_num_rows($result2) > 0)
{
	$farribo = $values2['fecha_arribo'];
	$tica = $values2['tica'];
	$identificacion = $values2['tipo_identificacion_id'];
	$vuelo = $values2['vuelo'];
	$conocimiento = $values2['conocimiento'];
	$pesocontenedor = $values2['peso'];

	$contenedornew = $values2['no_contenedor'];
	//$contenedor2 = substr($contenedor, 0, 4);
	//$contenedor3 = substr($contenedor, 4, 8);
	//$contenedor4 = $contenedor2.'-'.$contenedor3;
	$contenedornew2 = str_replace("-","",$contenedornew);
	$contenedornew4 = str_replace(" ","",$contenedornew2);

	$query_counter2 = "SELECT sec_tica FROM manifiestos_counter WHERE contenedor = '$contenedornew'";
	$counter2 = pg_query($link, $query_counter2);
	$counter_values2 = pg_fetch_array($counter2, $row_counter2, PGSQL_ASSOC);
	$secuencia2 = $counter_values2['sec_tica'];

	$containertype = pg_exec($link1, "SELECT short_name FROM container_type WHERE id_container_type = ".$values2['id_container_type']);
	$query_containertype = pg_fetch_array($containertype, 0);
	$tipocontenedor = $query_containertype[0];
	$tipocontenedor2 = substr($tipocontenedor, 0, 2);
}

$query_xml = "SELECT xml_enviado FROM manifiestos WHERE viaje = TRIM('$vuelo') AND operacion_id = 6 AND import_export = '{$_SESSION['ie']}'";
$result3 = pg_query($link, $query_xml);
$values3 = pg_fetch_array($result3, $row3, PGSQL_ASSOC);
$xml = $values3['xml_enviado'];

/* BEGIN TRANSACTION: Generar XML file. */
$sql = "BEGIN;";

$correlativo = pg_exec($link, "SELECT correlativo FROM manifiestos_secuencia");
$query_correlativo = pg_fetch_array($correlativo, 0);
$correlativo_aplicar = $query_correlativo[0];

$query_original = "SELECT fecha_envio, xml_enviado, enviado_por FROM manifiestos WHERE viaje = TRIM('$vuelo') AND import_export = '{$_SESSION['ie']}'";
$result4 = pg_query($link, $query_original);
$values4 = pg_fetch_array($result4, $row4, PGSQL_ASSOC);
$fecha_original = $values4['fecha_envio'];
$xml_original = $values4['xml_enviado'];
$enviado_original = $values4['enviado_por'];

$query_manifest = "SELECT van, declarante, tipo_emisor FROM manifiestos_van";
$manifest = pg_query($link, $query_manifest);
$valuesmanifest = pg_fetch_array($manifest, $rowmanifest, PGSQL_ASSOC);
$casillero = $valuesmanifest['van'];
$declarante = $valuesmanifest['declarante'];
$tipodeclarante = $valuesmanifest['tipo_emisor'];

$sql = pg_exec($link, "INSERT INTO manifiestos (sistema_id, correlativo, operacion_id, viaje, fecha_envio, enviado_por, fecha_original, xml_original, original_por, import_export) VALUES (2, '".$correlativo_aplicar."', 6, '".$vuelo."', NOW(), '".$nombres."', '".$fecha_original."', '".$xml_original."', '".$enviado_original."','{$_SESSION['ie']}')");

$fecha_envio = pg_exec($link, "SELECT fecha_envio FROM manifiestos WHERE correlativo = $correlativo_aplicar AND import_export = '{$_SESSION['ie']}'");
$query_envio = pg_fetch_array($fecha_envio, 0);
$envio = $query_envio[0];

$fecha_manifiesto = substr($envio, 0, 10);
$hora_manifiesto = substr($envio, 11, 8);

$buffer_viaje = '<?xml version = "1.0"?>
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
           <YCGTPOTRAN>1</YCGTPOTRAN>
           <YCGTPOMIC>'.$_SESSION['YCGTPOMIC'].'</YCGTPOMIC>
           <YCGNROMIC>'.$vuelo.'</YCGNROMIC>
           <YRGDEPID>'.$tica.'</YRGDEPID>
           <YCGFCHARR>'.$farribo.'</YCGFCHARR>
              <ROWSET_YCGCON>';

if ($result)
{
	$row3 = 1;

	for ($row = 1; $row <= pg_num_rows($result); $row++)
	{
		$sql_cliente = pg_exec($link1, "SELECT nombre_cliente FROM clientes WHERE id_cliente = ".$values['id_cliente']);
		$query_cliente = pg_fetch_array($sql_cliente, 0);

		$puerto_origen = pg_exec($link1, "SELECT codigo FROM unlocode WHERE unlocode_id = ".$values['id_puerto_origen']);
		$query_origen = pg_fetch_array($puerto_origen, 0);

		$puerto_desembarque = pg_exec($link1, "SELECT codigo FROM unlocode WHERE unlocode_id = ".$values['id_puerto_desembarque']);
		$query_desembarque = pg_fetch_array($puerto_desembarque, 0);

		$sql_comodities = pg_exec($link1, "SELECT namees FROM commodities WHERE commodityid = ".$values['comodity_id']);
		$query_comodities = pg_fetch_array($sql_comodities, 0);

		$blid = $values['bl_id'];
		$bl = $values['no_bl'];
		$cliente = $query_cliente[0];
		$tipobl = $values['tipo_conocimiento_id'];
		$identificacion = $values['tipo_identificacion_id'];
		$puertoorigen = $query_origen[0];
		$puertodestino = $query_desembarque[0];
		$idviaje = $values['viaje_id'];
	    $contenedor = $values['no_contenedor'];
		//$contenedor2 = substr($contenedor, 0, 4);
		//$contenedor3 = substr($contenedor, 4, 8);
		//$contenedor4 = $contenedor2.'-'.$contenedor3;
		$contenedor2 = str_replace("-","",$contenedor);
		$contenedor4 = str_replace(" ","",$contenedor2);
   		$piezas = $values['no_piezas'];
   		$bultos = $values['tipo_bulto'];
		$comodities = $query_comodities[0];
   		$peso = $values['peso'];
   		$mercaderia = $values['tipo_merc_p'];
   		$ubicacion = $values['ubicacion_tica'];
   		$activo = $values['activo'];
   		$transmitido = $values['transmitido'];
   		$faltante = $values['justificacion_faltante'];
   		$asociated = $values['asociated'];
   		$transmitido2 = $values['transmitido2'];
		$row3++;

//		if ($transmitido == 'f' and $activo == 't' and $asociated == 't' and $transmitido2 == 'f')
		if ($transmitido == 't' and $activo == 't' and $faltante != "" and $asociated == 'f' and $transmitido2 == 'f')
		{

$buffer_bl = $buffer_bl.
'
	              <ROW_YCGCON NUM = "'.$row.'" >
	                  <YCGSECCON>'.$row3.'</YCGSECCON>
	                     <ROWSET_YCGLIN>
	                         <ROW_YCGLIN NUM = "1" >
	                             <YCGNROLIN>1</YCGNROLIN>
	                             <YCGTPORLIN>M</YCGTPORLIN>
	                             <YCGLINPESB>'.$peso.'0</YCGLINPESB>
	                             <YCGLINTIPB>'.$bultos.'</YCGLINTIPB>
	                             <YCGLINTOTB>'.$piezas.'.000</YCGLINTOTB>
	                             <YCGMARCLIN></YCGMARCLIN>
	                             <YCGDESC>'.strtoupper(trim($comodities)).'</YCGDESC>
	                             <YCGLINDEP>'.$ubicacion.'</YCGLINDEP>
	                             <YCGCODSUSP>'.$mercaderia.'</YCGCODSUSP>
	                             <YCGLITPOOP>G</YCGLITPOOP>
	                             <YCGTXTACTA>REGISTRO DE FALTANTE EN CONOCIMIENTO EMBARQUE</YCGTXTACTA>
	                                <ROWSET_YCGCNTLI>
	                                    <ROW_YCGCNTLI NUM = "1" >
	                                        <YCGNROCONL>'.$contenedor4.'</YCGNROCONL>
	                                        <YCGTPORCL>M</YCGTPORCL>
	                                        <YCGBULTSCO>'.$piezas.'.000</YCGBULTSCO>
	                                    </ROW_YCGCNTLI>
	                                </ROWSET_YCGCNTLI>
	                         </ROW_YCGLIN>
	                     </ROWSET_YCGLIN>
			      </ROW_YCGCON>
';
		}

		if ($row  < pg_num_rows($result))
		{
			$values = pg_fetch_array($result, $row, PGSQL_ASSOC);
		}

    } // fin de for ($row = 1; $row <= pg_num_rows($result); $row++)
} // fin de if ($result).

if ($result2)
{
	$row7 = 1 + $tuplas_bls;
	$row5 = $tuplas_bls;

	for ($row4 = 1; $row4 <= pg_num_rows($result2); $row4++)
	{
		$sql_cliente = pg_exec($link1, "SELECT nombre_cliente FROM clientes WHERE id_cliente = ".$values2['id_cliente']);
		$query_cliente = pg_fetch_array($sql_cliente, 0);

		$puerto_origen = pg_exec($link1, "SELECT codigo FROM unlocode WHERE unlocode_id = ".$values2['id_puerto_origen']);
		$query_origen = pg_fetch_array($puerto_origen, 0);

		$puerto_desembarque = pg_exec($link1, "SELECT codigo FROM unlocode WHERE unlocode_id = ".$values2['id_puerto_desembarque']);
		$query_desembarque = pg_fetch_array($puerto_desembarque, 0);

		$sql_comodities = pg_exec($link1, "SELECT namees FROM commodities WHERE commodityid = ".$values2['comodity_id']);
		$query_comodities = pg_fetch_array($sql_comodities, 0);

		$hblid = $values2['bl_id'];
		$bl = $values2['no_bl'];
	    $hdivision = $values2['hdivision'];
	    $hbl = $values2['hbl'];
		$hcliente = $values2['hcliente'];
		$tipobl = $values2['tipo_conocimiento_id'];
		$identificacion = $values2['tipo_identificacion_id'];
		$puertoorigen = $query_origen[0];
		$puertodestino = $query_desembarque[0];
		$idviaje = $values2['viaje_id'];
    	$contenedor = $values2['no_contenedor'];
		//$contenedor2 = substr($contenedor, 0, 4);
		//$contenedor3 = substr($contenedor, 4, 8);
		//$contenedor4 = $contenedor2.'-'.$contenedor3;
		$contenedor2 = str_replace("-","",$contenedor);
		$contenedor4 = str_replace(" ","",$contenedor2);
	    $hpiezas = $values2['hpiezas'];
   		$bultos = $values2['tipo_bulto'];
	    $hcommodities = $values2['hcommodities'];
	    $hpeso = $values2['hpeso'];
  		$mercaderia = $values2['tipo_merc_p'];
		$ubicacion = $values2['ubicacion_tica'];
   		$activo = $values2['activo'];
   		$transmitido = $values2['transmitido'];
   		$asociated = $values2['asociated'];
		$transmitido2 = $values2['transmitido'];
		$row4++;

		if ($transmitido == 't' and $activo == 't' and $asociated == 'f' and $transmitido2 == 'f')
		{

$buffer_bl = $buffer_bl.
'
                  <ROW_YCGCON NUM = "'.$row5.'" >
                      <YCGSECCON>'.$row7.'</YCGSECCON>
                         <ROWSET_YCGLIN>
                             <ROW_YCGLIN NUM = "1" >
                                 <YCGNROLIN>1</YCGNROLIN>
                                 <YCGTPORLIN>M</YCGTPORLIN>
                                 <YCGLINPESB>'.$hpeso.'</YCGLINPESB>
                                 <YCGLINTIPB>'.$bultos.'</YCGLINTIPB>
                                 <YCGLINTOTB>'.$hpiezas.'.000</YCGLINTOTB>
                                 <YCGMARCLIN></YCGMARCLIN>
                                 <YCGDESC>'.strtoupper(trim($comodities)).'</YCGDESC>
                                 <YCGLINDEP>'.$ubicacion.'</YCGLINDEP>
                                 <YCGCODSUSP>'.$mercaderia.'</YCGCODSUSP>
                                 <YCGLITPOOP>G</YCGLITPOOP>
                                 <YCGTXTACTA>REGISTRO DE FALTANTE EN CONOCIMIENTO EMBARQUE</YCGTXTACTA>
                                    <ROWSET_YCGCNTLI>
                                        <ROW_YCGCNTLI NUM = "1" >
                                            <YCGNROCONL>'.$contenedor4.'</YCGNROCONL>
                                            <YCGTPORCL>M</YCGTPORCL>
                                            <YCGBULTSCO>'.$hpiezas.'.000</YCGBULTSCO>
                                        </ROW_YCGCNTLI>
                                    </ROWSET_YCGCNTLI>
                             </ROW_YCGLIN>
                         </ROWSET_YCGLIN>
			      </ROW_YCGCON>
';
		}

		$row7++;
		$row5++;

		if ($row4  < pg_num_rows($result2))
		{
			$values2 = pg_fetch_array($result2, $row4, PGSQL_ASSOC);
		}

    } // fin de for ($row4 = 1; $row4 <= pg_num_rows($result2); $row4++)
} // fin de if ($result2).

$buffer_xml =
'                      
			</ROWSET_YCGCON>
       </ROW_YCGMIC>
   </ROWSET_YCGMIC>
</ROOT>';

$dir = $_SESSION['empresa']."_oceanmanifest/";
if (!file_exists($dir)) 
    mkdir($dir, 0777, true);

$name_file = '0001-'.$correlativo_aplicar.'.cg';
$file = fopen($dir.$name_file, "w+");
fwrite($file, $buffer_viaje.$buffer_bl.$buffer_xml);
fclose($file);

if ($sql)
{
	$sql = "UPDATE manifiestos SET xml_enviado = '$name_file' WHERE correlativo = $correlativo_aplicar AND import_export = '{$_SESSION['ie']}'";
	$resultado = pg_exec($link, $sql);
	$sql = "COMMIT";

	$faltante_bl = "SELECT b.bl_id FROM bill_of_lading b, viaje_contenedor vc, viajes v WHERE b.viaje_contenedor_id = vc.viaje_contenedor_id AND vc.viaje_id = v.viaje_id AND v.no_viaje = '$viaje' AND b.transmitido = 't' AND justificacion_faltante IS NOT NULL AND b.import_export = '{$_SESSION['ie']}'";
	$result3 = pg_query($link, $faltante_bl);
	$values3 = pg_fetch_array($result3, $row_faltante, PGSQL_ASSOC);
	$bl_id = $values3['bl_id'];

	if (pg_num_rows($result3) > 0)
	{
		$sql = "UPDATE bill_of_lading SET transmitido2 = true WHERE bl_id = $bl_id AND import_export = '{$_SESSION['ie']}'";
		$resultado = pg_exec($link, $sql);
		$sql = "COMMIT";
	}

	if (pg_num_rows($result2) > 0)
	{
		$sql = "UPDATE divisiones_bl SET transmitido2 = true WHERE division_id = $hdivision";
		$resultado = pg_exec($link, $sql);
		$sql = "COMMIT";
	}

	$sql = "UPDATE manifiestos_secuencia SET correlativo = correlativo + 1";
	$resultado = pg_exec($link, $sql);
	$sql = "COMMIT";
	$resultado = pg_exec($link, $sql);
}
else
{
	$sql = "ROLLBACK";
	$resultado = pg_exec($link, $sql);
}
echo "<div align='center'>Manifiesto $name_file generado exitosamente!!!\n";
echo "<div align='center'><br><a href='view-viaje.php?no_viaje=$viaje'><button><p>Regresar</p></button></a></div><br>";
/* END TRANSACTION: Generar XML file. */

/* BEGIN TRANSACTION: Mapear XML file por FTP. */
// define some variables
$file = $dir.$name_file;
$remote_file = $name_file;
$ip = $_SERVER['REMOTE_ADDR'];

$ftp_server = $ip; // Address of FTP server.
$ftp_user_name = "aimartica"; // Username
$ftp_user_pass = "aimartica"; // Password

// establecer una conexi�n b�sica
$conn_id = ftp_connect($ftp_server);

// iniciar sesi�n con nombre de usuario y contrase�a
$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);

ftp_pasv($conn_id, true);

// cargar un archivo
if (ftp_put($conn_id,  $remote_file, $file, FTP_ASCII))
{
	echo "Se ha cargado el archivo XML $remote_file en el computador IP: $ip con exito\n";
}
else
{
	echo "Hubo un problema durante la transferencia del archivo XML $remote_file al computador IP: $ip\n";
}

// cerrar la conexi�n ftp
ftp_close($conn_id);
/* END TRANSACTION: Mapear XML file por FTP. */
?>