<?php


function isUsuarioAdministradorWeb(){
	return false;
}

function PageStart( $titulo = "9Gestion",$cache=false,$fondoblanco=false) {
	global $esPruebas;
	

	
	header("Content-languaje: es");
	header("Content-Type: text/html; charset=UTF-8");

	if (!$cache)
  		$cache = "";
	else {
		$cache= '<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE"><META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE"><META NAME="ROBOTS" CONTENT="NOINDEX,NOFOLLOW">\n<META NAME="GOOGLEBOT" CONTENT="NOARCHIVE">';
		header("Pragma: no-cache");
		header("Cache-control: no-cache");
	}

	if (0) {
		echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n";
	} else {
		echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/DTD/loose.dtd\">\n";	
	}
	

	
	echo "<html><head><title>$titulo</title>";
		
	if ($esPruebas)
		echo "<link rel='stylesheet' type='text/css' href='css/basecss.php'>";
	else
		echo "<link rel='stylesheet' type='text/css' href='css/base.css'>";		
		
	echo "<link href='css/printcss.css' rel='stylesheet' type='text/css' media='print'>";			
		
	echo "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'><META HTTP-EQUIV='CONTENT-LANGUAGE' CONTENT='es'>$cache";	
	echo "<script language='JavaScript' src='js/basejs.php' type='text/JavaScript'></script>";
		 
	echo "</head>";
	
	$uglybody = "topmargin='0' marginheight='0'";
	
	$fondo = "";
	if ($fondoblanco) {
		$uglybody = "leftmargin='0' topmargin='0' marginwidth='0' marginheight='0'";//REPRUEBAS
		$fondo = "style='background-color: white!important;background-image: none !important;margin: 0px;padding: 0px' ";
	}
		
	echo "<body $uglybody $fondo>";
}


function PageEnd($debug=true){
	global $link,$action,$modo,$esPruebas,$sqlTimeSuma,$sumaTiemposTotal,$esVuelcaTiming;
	
	if ($debug and isUsuarioAdministradorWeb()){
		
		
		if(isset($_GET["cargarmodoget"]))
			$add = " (get )modo = '$modo'";
		else
			$add = " (post)modo = '$modo'";
		
		echo "<p>";
		echo gColor("gray",glink($action,$action).  serialize($_GET) . $add);
		
	}	
	
	echo "<p>";
	
	if ($esVuelcaTiming ) {		
		VuelcaMomentos();
		echo "[Suma Tiempos SQL] $sqlTimeSuma<br>";
		echo "<script>timingTerminaGeneracionPagina($sumaTiemposTotal)</script>";					
	}

	$usuario = $_SESSION["NombreUsuario"];
		
	//if ($usuario and $debug)
	//	echo "<div class=piedepagina style='color:gray'>Operador: $usuario</div>";
	
	echo "</body></html>";	
	
	if ($link){
		mysql_close ($link);	
	}
	die();//Termina la ejecucion
} 
 
  
function g($tag="br",$txt ="", $clas="") {
	if($clas!="")
		$clas = " class=\"$clas\" ";
	
	return "<$tag $clas>$txt</$tag>";
}
 
 
function gColor($color,$txt,$bold=false){
	if(!$bold)
		return "<font color='$color'>$txt</font>";
	return "<font color='$color'><b>$txt</b></font>";
			
}
 

?>
