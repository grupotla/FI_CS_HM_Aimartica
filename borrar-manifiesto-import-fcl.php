<?php include("seguridad.php");?>
<?php
/*
   Fech@: 09/03/2010
   @utor: Pablo Contreras - GT
   Em@il: pablo-contreras@aimargroup.com
*/
include("conect_db.php");
$nombres = $_SESSION["nombres"];
$bl = $_GET["bl"];

$link = conect_localhost();

$query_bl = "SELECT b.bl_id, b.no_viaje, b.id_cliente, b.id_puerto_origen, b.id_puerto_desembarque, b.fecha_arribo, b.tipo_conocimiento_id, b.tipo_identificacion_id, m.vuelo, m.conocimiento, m.ubicacion_tica, b.import_export FROM bl_completo b, manifiestos_import m WHERE b.activo AND b.no_viaje = m.viaje AND b.no_bl = m.mbl AND b.no_bl = '$bl' AND b.import_export = '{$_SESSION['ie']}' AND m.import_export = '{$_SESSION['ie']}'";
$result = pg_query($link, $query_bl);

$query_contenedor = "SELECT c.contenedor_id, c.no_contenedor, c.no_piezas, e.identificador AS tipo_bulto, c.comodity_id, c.volumen, c.peso, c.tipo_merc_p, u.identificador AS almacen, b.import_export "; //FROM bl_completo b, contenedor_completo c, embalajes_tica e, ubicaciones_tica u WHERE b.activo AND c.activo AND b.bl_id = c.bl_id AND c.embalaje_tica_id = e.embalaje_tica_id AND b.ubicacion_tica_id = u.ubicacion_tica_id AND b.no_bl = '$bl'";
$query_contenedor .= "FROM bl_completo b
inner join contenedor_completo c ON
		case when c.bl_id_ref = 0 then
		  c.bl_id
		else
		  c.bl_id_ref
		end = b.bl_id
inner join embalajes_tica e on (c.embalaje_tica_id = e.embalaje_tica_id)
left join ubicaciones_tica u on (b.ubicacion_tica_id = u.ubicacion_tica_id)
WHERE b.activo AND c.activo AND b.no_bl = '$bl' AND b.import_export = '{$_SESSION['ie']}'";

$result2 = pg_query($link, $query_contenedor);

$link1 = conect_master_local();

$values = pg_fetch_array($result, $row, PGSQL_ASSOC);

$sql_cliente = pg_exec($link1, "SELECT nombre_cliente FROM clientes WHERE id_cliente = ".$values['id_cliente']);
$query_result = pg_fetch_array($sql_cliente, 0);

$puerto_origen = pg_exec($link1, "SELECT codigo FROM unlocode WHERE unlocode_id = ".$values['id_puerto_origen']);
$query_origen = pg_fetch_array($puerto_origen, 0);

$puerto_desembarque = pg_exec($link1, "SELECT codigo FROM unlocode WHERE unlocode_id = ".$values['id_puerto_desembarque']);
$query_desembarque = pg_fetch_array($puerto_desembarque, 0);

$blid = $values['bl_id'];
$viaje = $values['no_viaje'];
$cliente = $query_result[0];
$puertoorigen = $query_origen[0];
$puertodestino = $query_desembarque[0];
$farribo = $values['fecha_arribo'];
$ubicacion = $values['ubicacion_tica'];
$tipobl = $values['tipo_conocimiento_id'];
$identificacion = $values['tipo_identificacion_id'];
$vuelo = $values['vuelo'];
$conocimiento = $values['conocimiento'];

$query_xml = "SELECT xml_enviado FROM manifiestos WHERE viaje = TRIM('$vuelo') AND operacion_id = 2 AND import_export = '{$_SESSION['ie']}'";
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

$sql = pg_exec($link, "INSERT INTO manifiestos (sistema_id, correlativo, operacion_id, viaje, fecha_envio, enviado_por, fecha_original, xml_original, original_por, import_export) VALUES (1, '".$correlativo_aplicar."', 2, '".$vuelo."', NOW(), '".$nombres."', '".$fecha_original."', '".$xml_original."', '".$enviado_original."','{$_SESSION['ie']}')");

$fecha_envio = pg_exec($link, "SELECT fecha_envio FROM manifiestos WHERE correlativo = $correlativo_aplicar AND import_export = '{$_SESSION['ie']}'");
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
                   <YCGTPOTRAN>1</YCGTPOTRAN>
                   <YCGTPOMIC>'.$_SESSION['YCGTPOMIC'].'</YCGTPOMIC>
                   <YCGNROMIC>'.$vuelo.'</YCGNROMIC>
                   <YRGDEPID>'.$ubicacion.'</YRGDEPID>
                   <YCGFCHARR>'.$farribo.'</YCGFCHARR>
                      <ROWSET_YCGCON>
                          <ROW_YCGCON NUM = "1" >
                              <YCGSECCON>2</YCGSECCON>
                              <YCGTPORCON>B</YCGTPORCON>
                                 <ROWSET_YCGLIN>';

if ($result2)
{
	for ($row2 = 0; $row2 < pg_num_rows($result2); $row2++)
	{
		$values2 = pg_fetch_array($result2, $row2, PGSQL_ASSOC);

		/*
		$sql_comodities = pg_exec($link1, "SELECT namees FROM commodities WHERE commodityid = ".$values2['comodity_id']);
		$query_comodities = pg_fetch_array($sql_comodities, 0);
		$contenedor =str_replace(array("-"," "),"",$values2['no_contenedor']);
		$piezas = $values2['no_piezas'];
		$bultos = $values2['tipo_bulto'];
		$comodities = $query_comodities[0];
		$volumen = $values2['volumen'];
		$peso = $values2['peso'];
		$mercaderia = $values2['tipo_merc_p'];
		$almacen = $values2['almacen'];
		*/
		$row3 = $row2 + 1;

		$buffer_contenedor = $buffer_contenedor.
'
                                     <ROW_YCGLIN NUM = "'.$row3.'" >
                                         <YCGNROLIN>'.$row3.'</YCGNROLIN>
                                         <YCGTPORLIN>B</YCGTPORLIN>
                                            <ROWSET_YCGCNTLI>
                                                <ROW_YCGCNTLI NUM = "1" >
                                                    <YCGNROCONL>'.str_replace(array("-"," "),"",$values2['no_contenedor']).'</YCGNROCONL>
                                                    <YCGTPORCL>B</YCGTPORCL>
                                                </ROW_YCGCNTLI>
                                            </ROWSET_YCGCNTLI>
                                     </ROW_YCGLIN>
';
	}
}

	$buffer_xml =
'                                 </ROWSET_YCGLIN>
                          </ROW_YCGCON>
                      </ROWSET_YCGCON>
               </ROW_YCGMIC>
           </ROWSET_YCGMIC>
</ROOT>';

$dir = $_SESSION['empresa']."_oceanmanifest/";
if (!file_exists($dir)) 
    mkdir($dir, 0777, true);

$name_file = '0001-'.$correlativo_aplicar.'.cg';
$file = fopen($dir.$name_file, "w+");
fwrite($file, $buffer_bl.$buffer_contenedor.$buffer_xml);
fclose($file);

if ($sql)
{
	$sql = "UPDATE manifiestos SET xml_enviado = '$name_file' WHERE correlativo = $correlativo_aplicar AND import_export = '{$_SESSION['ie']}'";
	$resultado = pg_exec($link, $sql);
	$sql = "COMMIT";

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
echo "<div align='center'><br><a href='{$_SESSION['impex']}-fcl.php'><button><p>Regresar</p></button></a></div><br>";
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
	echo "Se ha cargado el archivo XML $remote_file en el computador IP: $ip con Exito\n";
}
else
{
	echo "Hubo un problema durante la transferencia del archivo XML $remote_file al computador IP: $ip\n";
}

// cerrar la conexi�n ftp
ftp_close($conn_id);
/* END TRANSACTION: Mapear XML file por FTP. */
?>
