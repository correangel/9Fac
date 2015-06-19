<?php

function DatosProducto($id){
	$id	= CleanID($id);
	$sql = "SELECT ges_productos.*, ges_productos_idioma.Nombre FROM 
		ges_productos INNER JOIN ges_productos_idioma ON  ges_productos.IdProdBase = ges_productos_idioma.IdProdBase
		WHERE ges_productos_idioma.IdIdioma = 1 AND 
		(ges_productos.IdProducto = $id)
		AND
		ges_productos.Eliminado = 0
		AND
		ges_productos_idioma.Eliminado = 0";
			
	return queryrow($sql);
}

function DatosCliente($IdCliente){
	$IdCliente	= CleanID($IdCliente);
	$sql 		= "SELECT * FROM ges_clientes WHERE IdCliente ='$IdCliente'";	
	return queryrow($sql);
}

function DatosFactura($IdFactura){
	$IdFactura = CleanID($IdFactura);
	$sql 	= "SELECT * FROM ges_facturas WHERE (IdFactura='$IdFactura')";

	return queryrow($sql);
}

function DatosLineaFactura($IdFactura){
	$IdFactura = CleanID($IdFactura);
	$sql 	= "SELECT * FROM ges_facturas_det WHERE (IdFactura='$IdFactura')";

	$rows = array();

	$res = query($sql);

	while( $row = Row($res)){
		$IdLinea = $row["IdFacturaDet"];
	
		$rows[$IdLinea] = $row;
	}

	return $rows;
}

function DatosSiguienteFactura(){
	//devuelve datos utiles 
	$datosutiles = array();

	$row = DatosNegocio();
	$serie = $row["SerieDefecto"];
	$IdFormato = $row["IdTipoNumeracionFactura"];

	$sql = "SELECT NFactura,SerieFactura FROM ges_facturas WHERE (SerieFactura='$serie')  ORDER BY NFactura DESC LIMIT 1";
	$row = queryrow($sql);

	$numero = $row["NFactura"] +1 ;

	$datosutiles["NFactura"] = $numero;
	$datosutiles["SerieDefecto"] = $serie;
	$datosutiles["Serie"] = $serie;
	$datosutiles["IdFormato"] = $IdFormato;
	$datosutiles["expresionNumeroFactura"] = getFacturaFormateada($serie,$numero,$IdFormato);

	return $datosutiles;
}


function Recodifica($serie,$numero,$atomo){		
	$atomo = str_replace("N","$numero",$atomo);
	$atomo = str_replace("Y",date("Y"),$atomo);
	$atomo = str_replace("S","$serie",$atomo);
	return $atomo;			
}


function getFacturaFormateada($serie,$numero,$IdFormato){
 	$sql = "SELECT * FROM ges_factura_formatos WHERE (IdFormato=$IdFormato)";
        
        $row = queryrow($sql);
    
        $datos = Recodifica($serie,$numero,$row["Dato1"]) .
			Recodifica($serie,$numero,$row["Simbolo1"]) .
			Recodifica($serie,$numero,$row["Dato2"]) .
			Recodifica($serie,$numero,$row["Simbolo2"]) .
			Recodifica($serie,$numero,$row["Dato3"]) ;
        return $datos;
}


//die("hola mundo ($modo)");

switch($modo){

	case "IniciarSiguienteFactura":		
		$row = DatosSiguienteFactura();
		if ($row){
			$json = new Services_JSON();
			$output = $json->encode($row);	
			echo $output;		
		} else {
			return "ERROR";
		}
	
		exit();	

		break;
	case "JSONCargarCliente":
		$id 		= CleanID($_REQUEST["IdCliente"]);
		if($row = DatosCliente($id)){
			$json = new Services_JSON();
			$output = $json->encode($row);	
			echo $output;					
		} else{
			echo "ERROR";		
		}			
		exit();				
		break;			
	case "JSONCargarProducto":
		$id 		= CleanID($_REQUEST["IdProducto"]);
		if($row = DatosProducto($id)) {
			$json = new Services_JSON();
			$output = $json->encode($row);	
			echo $output;					
		} else {
			echo "ERROR";		
		}			
		exit();
		break;
	case "JSONCargarFactura":
		$id 	= CleanID($_REQUEST["id"]);
		if($row = DatosFactura($id)) {
			$json = new Services_JSON();
			$output = $json->encode($row);	
			echo $output;					
		} else {
			echo "ERROR" ;		
		}			
		exit();		
		break;

	case "JSONCargarLineasFactura":
		$id 	= CleanID($_REQUEST["id"]);
		if($row = DatosLineaFactura($id)) {
			$json = new Services_JSON();
			$output = $json->encode($row);	
			echo $output;					
		} else {
			echo "ERROR";		
		}			
		exit();		
		break;

	case "EnviarFactura":	
		include("con/procesarfactura.con.php");
		break;
	case "ModificarFactura":
		include("con/procesarmodfactura.con.php");
		break;	
		
}



?>