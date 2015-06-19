<?php
/*
 * Created on 30-abr-2008
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

$json = new Services_JSON();


/*
array (
  'numProductos' => '2',
  'prod_0__codigo' => '65',
  'prod_0__descripcion' => 'afproducto',
  'prod_0__unid' => '1',
  'prod_0__precio' => '0',
  'prod_0__dto' => '0',
  'prod_0__impuesto' => '0',
  'prod_1__codigo' => '65',
  'prod_1__descripcion' => 'afproducto',
  'prod_1__unid' => '1',
  'prod_1__precio' => '32',
  'prod_1__dto' => '0',
  'prod_1__impuesto' => '0',
  'NombreComercial' => 'Ana2',
  'Direccion' => 'false',
  'Poblacion' => 'false',
  'CIF' => 'false',
  'CodigoPostal' => 'FALSE',
  'datoNumalbaran' => '',
  'datoFecha' => '',
)array (
  0 =>
  array (
    'IdAlbaran' => NULL,
    'IdProducto' => 65,
    'Referencia' => 'K1065',
    'CodigoBarras' => '90001066',
    'Concepto' => 'afproducto',
    'Cantidad' => 1,
    'Iva' => 0,
    'Precio' => 0,
    'Descuento' => 0,
    'Importe' => 0,
  ),
  1 =>
  array (
    'IdAlbaran' => NULL,
    'IdProducto' => 65,
    'Referencia' => 'K1065',
    'CodigoBarras' => '90001066',
    'Concepto' => 'afproducto',
    'Cantidad' => 1,
    'Iva' => 0,
    'Precio' => 32,
    'Descuento' => 0,
    'Importe' => 32,
  ),
)
*/





//var_export($_POST);

$datosAlbaran = array();

//IdAlbaran
//IdLocal
//IdUsuario
$datosAlbaran["SerieAlbaran"] 		= $_POST["SerieAlbaran"];
$datosAlbaran["NAlbaran"] 			= $_POST["NAlbaran"];
$datosAlbaran["SerieNumeroAlbaran"] = $_POST["SerieNumeroAlbaran"];

$datosAlbaran["FechaAlbaran"] 		= CleanFechaES($_POST["FechaAlbaran"]);
//IdCliente
//totalalbaran,importeiva,baseimponible
$datosAlbaran["ImporteNeto"] 		= $_POST["baseimponible"];
$datosAlbaran["IvaImporte"] 		= $_POST["importeiva"];
$datosAlbaran["TotalImporte"] 		= $_POST["totalalbaran"];
//ImportePendiente
$datosAlbaran["Status"] 			= $_POST["Status"]; //TODO
//Observaciones
$datosAlbaran["ObraRealizada"] 		= $_POST["obrarealizada"];


$datosAlbaran["IdCliente"] 			= $_POST["IdCliente"];

//NombreComercial,Direccion,Poblacion,CIF,CodigoPostal
$datosAlbaran["NombreComercial"] 	= $_POST["NombreComercial"];
$datosAlbaran["Direccion"] 			= $_POST["Direccion"];
$datosAlbaran["Poblacion"] 			= $_POST["Poblacion"];
$datosAlbaran["CIF"] 				= $_POST["CIF"];
$datosAlbaran["CodigoPostal"] 		= $_POST["CodigoPostal"];


$datosAlbaran["IdAlbaran"] = $_POST["IdAlbaran"];

$sql = "INSERT INTO ges_albarans (
			SerieAlbaran,NAlbaran,FechaAlbaran,IdCliente,
		    ImporteNeto,IvaImporte,TotalImporte,
		     ObraRealizada,SerieNumeroAlbaran,
			NombreComercial,Direccion,Poblacion,CIF,CodigoPostal
		) VALUES (
			%SerieAlbaran%,%NAlbaran%,%FechaAlbaran%,%IdCliente%,
		    %ImporteNeto%,%IvaImporte%,%TotalImporte%,
		     %ObraRealizada%,%SerieNumeroAlbaran%,
			%NombreComercial%,%Direccion%,%Poblacion%,%CIF%,%CodigoPostal%
		)";

$sql = "UPDATE ges_albarans SET SerieAlbaran=%SerieAlbaran%,".
	"NAlbaran=%NAlbaran%,FechaAlbaran=%FechaAlbaran%,IdCliente=%IdCliente%,
		    ImporteNeto=%ImporteNeto%,IvaImporte=%IvaImporte%,TotalImporte=%TotalImporte%,
		     SerieNumeroAlbaran=%SerieNumeroAlbaran%,
			NombreComercial=%NombreComercial%,Direccion=%Direccion%,Poblacion=%Poblacion%,CIF=%CIF%,CodigoPostal=%CodigoPostal%, ".
	" ObraRealizada=%ObraRealizada% WHERE IdAlbaran=%IdAlbaran%  ";



foreach($datosAlbaran as $key=>$value){
	$sql = str_replace( "%".$key."%","'". CleanRealMysql($value) . "'",$sql);
}

///var_export($sql);

/*  Vamos a dar de alta la albaran */

$res = query($sql);

if (!$res){
	$output 			= $json->encode(array("ERROR"=>"1") );
	echo $output;
	exit();
}

//$IdAlbaran = $UltimaInsercion;
$IdAlbaran = CleanID($_POST["IdAlbaran"]);



$lineasProducto 		= array();
$numLineas 				= $_POST["numProductos"];


$AporteImpuestosVenta 	= 0;
$TotalPagoVenta 		= 0;
$AporteNetoVenta 		= 0;


for($t=0;$t<$numLineas;$t++){
	$producto = array();

	$firma = "prod_". $t . "__";

	$producto["IdAlbaran"] = $IdAlbaran;

	$IdProducto = intval($_POST[$firma ."codigo"]);
	$producto["IdProducto"] = $IdProducto;

	if ($IdProducto)
		$row = getProductoDatos($IdProducto);

	$producto["Referencia"] = $row["Referencia"];//TODO
	$producto["CodigoBarras"] = $row["CodigoBarras"];//TODO
	$producto["Concepto"] = $_POST[$firma . "descripcion"];
	//Talla
	//Color
	$producto["Cantidad"] = intval($_POST[$firma . "unid"]);
	$producto["Iva"] = intval($_POST[$firma . "impuesto"]);

	$precioSinIVA =  $_POST[$firma . "precio"];

        $aporteIva = $precioSinIVA * ($producto["Iva"]/100);

	$producto["Precio"] = $precioSinIVA + $aporteIva;

	$producto["Descuento"] = intval($_POST[$firma . "dto"]);//importe o %?

	{
		$aporteNeto = $producto["Precio"] * $producto["Cantidad"];
		$producto["Importe"] = $aporteNeto -  $aporteNeto * ($producto["Descuento"]/100);
	}


	$producto["TEST-Importe(neto)"] = $aporteNeto;


	$lineasProducto[] = $producto;
}

/*
	DATOS TENEMOS:
	0 => array ( 'IdAlbaran' => 4, 'IdProducto' => 14, 'Referencia' =>
	'R1003', 'CodigoBarras' => '', 'Concepto' => 'Moral', 'Cantidad' => 1,
	 'Iva' => 0, 'Precio' => 30, 'Descuento' => 0, 'Importe' => 30,
	 'TEST-Importe(neto)' => 30, ), )

  	k IdAlbaranDet
	k IdAlbaran
	k IdProducto
	k Referencia
	k CodigoBarras
	k Concepto
	- Talla
	- Color
	k Cantidad
	k Precio
	k Descuento
	k Importe
	k Iva
	Eliminado

*/


query("START TRANSACTION");

query("DELETE FROM ges_albarans_det WHERE IdAlbaran='$IdAlbaran'");

foreach ($lineasProducto as $linea){

	if (!$linea["IdProducto"] && !$linea["Cantidad"] && !$linea["Concepto"])		continue;//se salta lineas en blanco

	$sql = "INSERT INTO ges_albarans_det
			(IdAlbaran,IdProducto,Referencia,CodigoBarras,Concepto,Cantidad,Precio,Descuento,Importe,Iva )
			VALUES
			('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s' )
	";

	$sql = sprintf($sql,
		CleanID($linea["IdAlbaran"]),
		CleanRealMysql($linea["IdProducto"]),
		CleanRealMysql($linea["Referencia"]),
		CleanRealMysql($linea["CodigoBarras"]),
		CleanRealMysql($linea["Concepto"]),
		CleanRealMysql($linea["Cantidad"]),
		CleanRealMysql($linea["Precio"]),
		CleanRealMysql($linea["Descuento"]),
		CleanRealMysql($linea["Importe"]),
		CleanRealMysql($linea["Iva"])	);

	query($sql);
}

query("COMMIT");


$salida["IdAlbaran"] = $IdAlbaran;

$respuesta = $json->encode($salida);
echo $respuesta;




function getProductoDatos($IdProducto){
	$sql = sprintf("SELECT * FROM ges_productos WHERE IdProducto =%d",$IdProducto);

	return queryrow($sql);
}




exit();


?>