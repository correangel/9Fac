<?php



function DatosLocal($id){
	$id	= CleanID($id);
	$sql 		= "SELECT * FROM ges_locales WHERE IdLocal ='$id'";	
	return queryrow($sql);
}



switch($modo){

	case "JSONCargarLocal":
		$id 		= CleanID($_REQUEST["IdLocal"]);
		$sep = "#";
		if($row = DatosLocal($id)){
			$json = new Services_JSON();
			$output = $json->encode($row);	
			echo $output;					
		} else{
			echo "ERROR" . $_REQUEST["IdLocal"];		
		}			
		exit();				
		break;			
						
}



?>