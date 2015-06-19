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

function DatosAlbaran($IdAlbaran){
	$IdAlbaran = CleanID($IdAlbaran);
	$sql 	= "SELECT * FROM ges_albarans WHERE (IdAlbaran='$IdAlbaran')";

	return queryrow($sql);
}

function DatosLineaAlbaran($IdAlbaran){
	$IdAlbaran = CleanID($IdAlbaran);
	$sql 	= "SELECT * FROM ges_albarans_det WHERE (IdAlbaran='$IdAlbaran')";

	$rows = array();

	$res = query($sql);

	while( $row = Row($res)){
		$IdLinea = $row["IdAlbaranDet"];

		$rows[$IdLinea] = $row;
	}

	return $rows;
}

function DatosSiguienteAlbaran(){
	//devuelve datos utiles
        $datosutiles = array();

	$row = DatosNegocio();
	$serie = $row["SerieDefecto"];
        $IdFormato = $row["IdTipoNumeracionFactura"];

	$sql = "SELECT NAlbaran,SerieAlbaran FROM ges_albarans WHERE (SerieAlbaran='$serie')  ORDER BY NAlbaran DESC LIMIT 1";
	$row = queryrow($sql);

    $numero = $row["NAlbaran"] +1;

	$datosutiles["NAlbaran"] = $numero;
	$datosutiles["SerieDefecto"] = $serie;
        $datosutiles["Serie"] = $serie;
        $datosutiles["IdFormato"] = $IdFormato;
        $datosutiles["expresionNumeroAlbaran"] = getAlbaranFormateada($serie,$numero,$IdFormato);

        return $datosutiles;
}


function Recodifica($serie,$numero,$atomo){
	$atomo = str_replace("N","$numero",$atomo);
	$atomo = str_replace("Y",date("Y"),$atomo);
	$atomo = str_replace("S","$serie",$atomo);
	return $atomo;
}


function getAlbaranFormateada($serie,$numero,$IdFormato){
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

	case "IniciarSiguienteAlbaran":
		$row = DatosSiguienteAlbaran();
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
	case "JSONCargarAlbaran":
		$id 	= CleanID($_REQUEST["id"]);
		if($row = DatosAlbaran($id)) {
			$json = new Services_JSON();
			$output = $json->encode($row);
			echo $output;
		} else {
			echo "ERROR" ;
		}
		exit();
		break;

	case "JSONCargarLineasAlbaran":
		$id 	= CleanID($_REQUEST["id"]);
		if($row = DatosLineaAlbaran($id)) {
			$json = new Services_JSON();
			$output = $json->encode($row);
			echo $output;
		} else {
			echo "ERROR";
		}
		exit();
		break;

	case "EnviarAlbaran":
		include("con/procesaralbarans.con.php");
		break;
	case "ModificarAlbaran":
		include("con/procesarmodalbarans.con.php");

		break;

}



?>
