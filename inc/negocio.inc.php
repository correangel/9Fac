<?php




//IdTipoNumeracionFactura




function DatosNegocio(){
	//Devuelve el primer local, que es en el que se guardan los datos

	$sql  = "SELECT * FROM ges_locales ";
	$res = query($sql);
	if (!$res) return false;

	return Row($res);
}






?>