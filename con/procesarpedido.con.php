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
  'datoNumpedido' => '',
  'datoFecha' => '',
)array (
  0 =>
  array (
    'IdPedido' => NULL,
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
    'IdPedido' => NULL,
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

$datosPedido = array();

//IdPedido
//IdLocal
//IdUsuario
$datosPedido["SeriePedido"] 		= $_POST["SeriePedido"];
$datosPedido["NPedido"] 			= $_POST["NPedido"];
$datosPedido["SerieNumeroPedido"] = $_POST["SerieNumeroPedido"];

$datosPedido["FechaPedido"] 		= CleanFechaES($_POST["FechaPedido"]);
//IdCliente
//totalpedido,importeiva,baseimponible
$datosPedido["ImporteNeto"] 		= $_POST["baseimponible"];
$datosPedido["IvaImporte"] 		= $_POST["importeiva"];
$datosPedido["TotalImporte"] 		= $_POST["totalpedido"];
//ImportePendiente
$datosPedido["Status"] 			= $_POST["Status"]; //TODO
//Observaciones
$datosPedido["ObraRealizada"] 		= $_POST["obrarealizada"];


$datosPedido["IdCliente"] 			= $_POST["IdCliente"];

//NombreComercial,Direccion,Poblacion,CIF,CodigoPostal
$datosPedido["NombreComercial"] 	= $_POST["NombreComercial"];
$datosPedido["Direccion"] 			= $_POST["Direccion"];
$datosPedido["Poblacion"] 			= $_POST["Poblacion"];
$datosPedido["CIF"] 				= $_POST["CIF"];
$datosPedido["CodigoPostal"] 		= $_POST["CodigoPostal"];

$sql = "INSERT INTO ges_pedidos (
			SeriePedido,NPedido,FechaPedido,IdCliente,
		    ImporteNeto,IvaImporte,TotalImporte,
		     ObraRealizada,SerieNumeroPedido,
			NombreComercial,Direccion,Poblacion,CIF,CodigoPostal
		) VALUES (
			%SeriePedido%,%NPedido%,%FechaPedido%,%IdCliente%,
		    %ImporteNeto%,%IvaImporte%,%TotalImporte%,
		     %ObraRealizada%,%SerieNumeroPedido%,
			%NombreComercial%,%Direccion%,%Poblacion%,%CIF%,%CodigoPostal%
		)";

foreach($datosPedido as $key=>$value){
	$sql = str_replace( "%".$key."%","'". CleanRealMysql($value) . "'",$sql);
}

///var_export($sql);

/*  Vamos a dar de alta la pedido */

$res = query($sql);

if (!$res){
	$output 			= $json->encode(array("ERROR"=>"1") );
	echo $output;
	exit();
}

$IdPedido = $UltimaInsercion;



$lineasProducto 		= array();
$numLineas 				= $_POST["numProductos"];


$AporteImpuestosVenta 	= 0;
$TotalPagoVenta 		= 0;
$AporteNetoVenta 		= 0;


for($t=0;$t<$numLineas;$t++){
	$producto = array();

	$firma = "prod_". $t . "__";

	$producto["IdPedido"] = $IdPedido;

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
	0 => array ( 'IdPedido' => 4, 'IdProducto' => 14, 'Referencia' =>
	'R1003', 'CodigoBarras' => '', 'Concepto' => 'Moral', 'Cantidad' => 1,
	 'Iva' => 0, 'Precio' => 30, 'Descuento' => 0, 'Importe' => 30,
	 'TEST-Importe(neto)' => 30, ), )

  	k IdPedidoDet
	k IdPedido
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

foreach ($lineasProducto as $linea){

	$sql = "INSERT INTO ges_pedidos_det
			(IdPedido,IdProducto,Referencia,CodigoBarras,Concepto,Cantidad,Precio,Descuento,Importe,Iva )
			VALUES
			('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s' )
	";

	$sql = sprintf($sql,
		CleanID($linea["IdPedido"]),
		CleanRealMysql($linea["IdProducto"]),
		CleanRealMysql($linea["Referencia"]),
		CleanRealMysql($linea["CodigoBarras"]),
		CleanRealMysql($linea["Concepto"]),
		CleanRealMysql($linea["Cantidad"]),
		CleanRealMysql($linea["Precio"]),
		CleanRealMysql($linea["Descuento"]),
		CleanRealMysql($linea["Importe"]),
		CleanRealMysql($linea["Iva"])	);

	//echo $sql. "\n";
	query($sql);

}


$salida["IdPedido"] = $IdPedido;

$respuesta = $json->encode($salida);
echo $respuesta;




function getProductoDatos($IdProducto){
	$sql = sprintf("SELECT * FROM ges_productos WHERE IdProducto =%d",$IdProducto);

	return queryrow($sql);
}




exit();



?>