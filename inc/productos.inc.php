<?php

require_once("clases/producto.class.php");

$o = new producto;

switch($modo){
	case	"JSONCargar":
		$id  = CleanID($_REQUEST["id"]);		
		$prod = new producto;
			
		if( $prod->Load($id) ){
			$row = $prod->export();		
		
			$json = new Services_JSON();
			$output = $json->encode($row);	
			echo $output;			
		} else{
			echo "ERROR";		
		}			
		exit();	
		break;
		
	case "borrar":			
	
		$id = CleanID($_GET["id"]);
		
		$sql = "UPDATE ges_productos SET Eliminado=1 WHERE (IdProducto=$id)";
		
		if (query($sql)){
			echo "OK=$id";
		} else {
			echo "ERROR";	
		}
		exit();
		
				
		break;
			
	case "alta":	

		$o->Crea();

		$o->setLang(getSesionDato("IdLenguajeDefecto"));
	
		$o->set("Referencia",($_POST["Referencia"]),FORCE);
		$o->set("RefProvHab",($_POST["RefProvHab"]),FORCE);
		$o->set("Nombre",($_POST["Nombre"]),FORCE);
		$o->set("Descripcion",($_POST["Descripcion"]),FORCE);
		$o->set("CosteSinIVA",($_POST["CosteSinIVA"]),FORCE);
		$o->set("PrecioVenta",($_POST["PrecioVenta"]),FORCE);
		
		if ( $o->Alta() ){
			$id = $o->getId();
			echo "OK=$id";		
		}	else {
			var_export($o);	
			echo 0;		
		}		
		
		exit();
		break;
	case "modificacion":	
	
		$id = intval($_POST["id"]);	
	
		if (!$o->Load($id)){
			echo "no existe $id";
			exit();
		}				

		$o->set("Referencia",($_POST["Referencia"]),FORCE);
		$o->set("RefProvHab",($_POST["RefProvHab"]),FORCE);		
		$o->set("Nombre",($_POST["Nombre"]),FORCE);
		$o->set("Descripcion",($_POST["Descripcion"]),FORCE);
		$o->set("CosteSinIVA",($_POST["CosteSinIVA"]),FORCE);
		$o->set("PrecioVenta",($_POST["PrecioVenta"]),FORCE);
		
		if ( $o->Modificacion() ){
			echo "OK=OK";
			var_export($o);		
		}	else {

			echo 0;		
		}
		exit();
		break;
}

	







?>