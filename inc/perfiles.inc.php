<?php

function GenerarJSUsers(){
	$sql = "SELECT IdPerfil,NombrePerfil FROM ges_perfiles WHERE Eliminado=0 ORDER BY NombrePerfil ASC";
	$res = query($sql);
	if ($res) {
		while($row = Row($res)){
			$id = CleanID($row["IdPerfil"]);
			$nombre = addslashes(CleanParaXul(CleanNombre($row["NombrePerfil"])));
			echo "xAddUser($id,'$nombre');\n";
		}
	}
}	

function ModificarPerfil($IdPerfil, $NuevoNombre, $perfil, $pass,$esActivo,$esAdmin){	
	//OBSOLETO
}



function DatosPerfil($IdPerfil){
	$IdPerfil	= CleanID($IdPerfil);
	$sql 		= "SELECT * FROM ges_perfiles WHERE IdPerfil ='$IdPerfil'";	
	return queryrow($sql);
}

function BorrarPerfil($id){
	$id = CleanID($id);
	$sql = "UPDATE ges_perfiles SET Eliminado=1 WHERE (IdPerfil='$id')";
	return query($sql);
}



switch($modo){
	
	case "modificarperfil":	
 
		$idperfil = CleanID($_POST["IdPerfil"]);		
		$nombre = $_POST["NombrePerfil"];
		
		//NombrePerfil,Administracion,Informes,Productos,Proveedores,Compras,Clientes
				
		
		$oPerfil = new perfil();
		if(!$oPerfil->Load($idperfil)) {
			echo "ERROR: fallo al cargar el perfil($idperfil)";
			exit();	
		}
		
		$oPerfil->setIfData("NombrePerfil", $nombre, FORCE);		
		$oPerfil->set("Administracion",intval($_POST["Administracion"]),FORCE);
		$oPerfil->set("Informes",intval($_POST["Informes"]),FORCE);
		$oPerfil->set("Productos",intval($_POST["Productos"]),FORCE);
		$oPerfil->set("Proveedores",intval($_POST["Proveedores"]),FORCE);
		$oPerfil->set("Compras",intval($_POST["Compras"]),FORCE);
		$oPerfil->set("Clientes",intval($_POST["Clientes"]),FORCE);

		
		
		if( $oPerfil->Save()){
			echo "OK=$idperfil";
		} else {
			echo "ERROR, fallo al salvar el perfil($idperfil)";
		}
		
		break;	
		
	
	
	case "borrar":
		$id = CleanID($_GET["id"]);
		
		if (!$id)
			echo "ERROR";
		else {
			BorrarPerfil($id);
			echo "OK=ok";
		}		
		exit();
		break;
	case "alta":
		$nombre = CleanNombre($_POST["NombrePerfil"]);

		if ($id = CrearPerfilBreve($nombre))
			echo "OK=$id";
		else
			echo "ERROR";
			
		exit();
		break;
		
	case "update":
		$nombre	 	= CleanNombre($_POST["NombrePerfil"]);
		$perfil	= CleanNombre($_POST["perfil"]);
		$pass	 	= CleanNombre($_POST["pass"]);
		$esActivo 	= CleanInt($_POST["activo"]);
		$esAdmin	= CleanInt($_POST["admin"]);
		
		$IdPerfil 	= CleanID($_POST["IdPerfil"]);		
		if ( ModificarPerfil( $IdPerfil, $nombre, $perfil, $pass,$esActivo, $esAdmin) ){
			echo "OK=$Idperfil";
		} else {
			echo "ERROR, datos: $IdPerfil, $nombre, $perfil, $pass,$esActivo, $esAdmin";
		}
		exit();
		break;
	
	case "CargarPerfil":
		//OBSOLETO
		$id 		= CleanID($_GET["IdPerfil"]);
		$sep = "#";
		if ($row = DatosPerfil($id)){
			echo $row["NombrePerfil"] . $sep . $row["perfil"] . $sep . $row["pass"] . $sep . $row["esActivo"] . $sep . $row["esAdmin"];
		} else{
			echo "ERROR";		
		}			
		exit();				
		break;		
	case "JSONCargarPerfil":
		$id 		= CleanID($_REQUEST["IdPerfil"]);
		$sep = "#";
		if($row = DatosPerfil($id)){
			$json = new Services_JSON();
			$output = $json->encode($row);	
			echo $output;					
		} else{
			echo "ERROR";		
		}			
		exit();				
		break;			
						
}



?>