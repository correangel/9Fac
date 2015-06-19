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

function DatosPresupuesto($IdPresupuesto){
	$IdPresupuesto = CleanID($IdPresupuesto);
	$sql 	= "SELECT * FROM ges_presupuestos WHERE (IdPresupuesto='$IdPresupuesto')";

	return queryrow($sql);
}

function DatosLineaPresupuesto($IdPresupuesto){
	$IdPresupuesto = CleanID($IdPresupuesto);
	$sql 	= "SELECT * FROM ges_presupuestos_det WHERE (IdPresupuesto='$IdPresupuesto')";

	$rows = array();

	$res = query($sql);

	while( $row = Row($res)){
		$IdLinea = $row["IdPresupuestoDet"];

		$rows[$IdLinea] = $row;
	}

	return $rows;
}

function DatosSiguientePresupuesto(){
		//devuelve datos utiles
        $datosutiles = array();

		$row = DatosNegocio();
		$serie = $row["SerieDefecto"];
        $IdFormato = $row["IdTipoNumeracionFactura"];

		$sql = "SELECT NPresupuesto,SeriePresupuesto FROM ges_presupuestos WHERE (SeriePresupuesto='$serie')  ORDER BY NPresupuesto DESC LIMIT 1";
		$row = queryrow($sql);

        $numero = $row["NPresupuesto"] +1;

		$datosutiles["NPresupuesto"] = $numero;
		$datosutiles["SerieDefecto"] = $serie;
        $datosutiles["Serie"] = $serie;
        $datosutiles["IdFormato"] = $IdFormato;
        $datosutiles["expresionNumeroPresupuesto"] = getPresupuestoFormateada($serie,$numero,$IdFormato);

        return $datosutiles;
}


function Recodifica($serie,$numero,$atomo){
	$atomo = str_replace("N","$numero",$atomo);
	$atomo = str_replace("Y",date("Y"),$atomo);
	$atomo = str_replace("S","$serie",$atomo);
	return $atomo;
}


function getPresupuestoFormateada($serie,$numero,$IdFormato){
 	$sql = "SELECT * FROM ges_factura_formatos WHERE (IdFormato='$IdFormato')";

        $row = queryrow($sql);

        $datos = Recodifica($serie,$numero,$row["Dato1"]) .
			Recodifica($serie,$numero,$row["Simbolo1"]) .
			Recodifica($serie,$numero,$row["Dato2"]) .
			Recodifica($serie,$numero,$row["Simbolo2"]) .
			Recodifica($serie,$numero,$row["Dato3"]) ;
        return $datos;
}


switch($modo){

	case "IniciarSiguientePresupuesto":
		$row = DatosSiguientePresupuesto();
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
	case "JSONCargarPresupuesto":
		$id 	= CleanID($_REQUEST["id"]);
		if($row = DatosPresupuesto($id)) {
			$json = new Services_JSON();
			$output = $json->encode($row);
			echo $output;
		} else {
			echo "ERROR" ;
		}
		exit();
		break;

	case "JSONCargarLineasPresupuesto":
		$id 	= CleanID($_REQUEST["id"]);
		if($row = DatosLineaPresupuesto($id)) {
			$json = new Services_JSON();
			$output = $json->encode($row);
			echo $output;
		} else {
			echo "ERROR";
		}
		exit();
		break;

	case "EnviarPresupuesto":
		include("con/procesarpresupuesto.con.php");
		break;

	case "ModificarPresupuesto":
		include("con/procesarpresupuesto.con.php");
		break;


	default:
		if ($modo) {
			echo "ERROR: '$modo'";
			exit();
		}

		break;
}




?>