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

function DatosPedido($IdPedido){
	$IdPedido = CleanID($IdPedido);
	$sql 	= "SELECT * FROM ges_pedidos WHERE (IdPedido='$IdPedido')";

	return queryrow($sql);
}

function DatosLineaPedido($IdPedido){
	$IdPedido = CleanID($IdPedido);
	$sql 	= "SELECT * FROM ges_pedidos_det WHERE (IdPedido='$IdPedido')";

	$rows = array();

	$res = query($sql);

	while( $row = Row($res)){
		$IdLinea = $row["IdPedidoDet"];

		$rows[$IdLinea] = $row;
	}

	return $rows;
}

function DatosSiguientePedido(){
	//devuelve datos utiles
        $datosutiles = array();

	$row = DatosNegocio();
	$serie = $row["SerieDefecto"];
        $IdFormato = $row["IdTipoNumeracionFactura"];

	$sql = "SELECT NPedido,SeriePedido FROM ges_pedidos WHERE (SeriePedido='$serie')  ORDER BY NPedido DESC LIMIT 1";
	$row = queryrow($sql);

    $numero = $row["NPedido"] + 1;

	$datosutiles["NPedido"] = $numero;
	$datosutiles["SerieDefecto"] = $serie;
    $datosutiles["Serie"] = $serie;
    $datosutiles["IdFormato"] = $IdFormato;
    $datosutiles["expresionNumeroPedido"] = getPedidoFormateada($serie,$numero,$IdFormato);

    return $datosutiles;
}


function Recodifica($serie,$numero,$atomo){
	$atomo = str_replace("N","$numero",$atomo);
	$atomo = str_replace("Y",date("Y"),$atomo);
	$atomo = str_replace("S","$serie",$atomo);
	return $atomo;
}


function getPedidoFormateada($serie,$numero,$IdFormato){
 	$sql = "SELECT * FROM ges_factura_formatos WHERE (IdFormato=$IdFormato)";

        $row = queryrow($sql);

        $datos = Recodifica($serie,$numero,$row["Dato1"]) .
			Recodifica($serie,$numero,$row["Simbolo1"]) .
			Recodifica($serie,$numero,$row["Dato2"]) .
			Recodifica($serie,$numero,$row["Simbolo2"]) .
			Recodifica($serie,$numero,$row["Dato3"]) ;
        return $datos;
}


switch($modo){

	case "IniciarSiguientePedido":


		$row = DatosSiguientePedido();
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
	case "JSONCargarPedido":
		$id 	= CleanID($_REQUEST["id"]);
		if($row = DatosPedido($id)) {
			$json = new Services_JSON();
			$output = $json->encode($row);
			echo $output;
		} else {
			echo "ERROR" ;
		}
		exit();
		break;

	case "JSONCargarLineasPedido":
		$id 	= CleanID($_REQUEST["id"]);
		if($row = DatosLineaPedido($id)) {
			$json = new Services_JSON();
			$output = $json->encode($row);
			echo $output;
		} else {
			echo "ERROR";
		}
		exit();
		break;

	case "EnviarPedido":
		include("con/procesarpedido.con.php");
		break;
	case "ModificarPedido":
		include("con/procesarmodpedidos.con.php");
		break;

}



?>
