<?php

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







?>