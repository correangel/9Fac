<?php

function GenerarJSUsers(){
	$sql = "SELECT IdUsuario,Nombre FROM ges_usuarios WHERE Eliminado=0 ORDER BY Nombre ASC";
	$res = query($sql);
	if ($res) {
		while($row = Row($res)){
			$id = CleanID($row["IdUsuario"]);
			$nombre = addslashes((CleanNombre($row["Nombre"])));
			echo "xAddUser($id,'$nombre');\n";
		}
	}
}	

function ModificarUsuario($IdUsuario, $NuevoNombre, $usuario, $pass,$esActivo,$esAdmin){	
	if (!$IdUsuario)	
		return false;
	
	$NuevoNombre = CleanNombre($NuevoNombre);		
	$sql = "UPDATE ges_usuarios SET Nombre ='$NuevoNombre', usuario = '$usuario', pass = '$pass', esActivo='$esActivo', esAdmin='$esAdmin' WHERE IdUsuario = '$IdUsuario' ";	
	return query($sql, "cambiando datos usuario");
}



function DatosUsuario($IdUsuario){
	$IdUsuario	= CleanID($IdUsuario);
	$sql 		= "SELECT * FROM ges_usuarios WHERE IdUsuario ='$IdUsuario'";	
	return queryrow($sql);
}

function BorrarUsuario($id){
	$id = CleanID($id);
	$sql = "UPDATE ges_usuarios SET Eliminado=1 WHERE (IdUsuario='$id')";
	return query($sql);
}



switch($modo){
	
	case "modificarusuario":	
		//Identificacion,Password,Nombre,Direccion,Localidad,Telefono,FechaNacim
 
		$idusuario = CleanID($_POST["IdUsuario"]);		
		$nombre = $_POST["Nombre"];
		$identificacion = $_POST["Identificacion"];
		//$legal 	   = $_POST["NombreLegal"];
		$password = $_POST["Password"];
		//$poblacion = $_POST["Localidad"];
		$direccion = $_POST["Direccion"];
		//$cp = CleanCP($_POST["CP"]);
		//$email = CleanEmail($_POST["Email"]);
		$telefono = CleanTelefono($_POST["Telefono"]);
		//$telefono2 = CleanTelefono($_POST["Telefono2"]);
		//$contacto = $_POST["Contacto"];
		//$cargo = $_POST["Cargo"];
		$cuentabancaria = $_POST["CuentaBanco"];
		//$numero = $_POST["NumeroFiscal"];
		//$comentario = $_POST["Comentarios"];
		//$tipousuario = $_POST["TipoUsuario"];
		//$IdModPagoHabitual = CleanID($_POST["IdModPagoHabitual"]);
		//$idpais 	= CleanID($_POST["IdPais"]); 
		//$paginaweb  = $_POST["PaginaWeb"];
		$nace  = CleanFechaES($_POST["FechaNacim"]);
		
		$oUsuario = new usuario();
		if(!$oUsuario->Load($idusuario)) {
			echo "ERROR: fallo al cargar el usuario($idusuario)";
			exit();	
		}
		
		$oUsuario->setIfData("Nombre", $nombre, FORCE);
		$oUsuario->setIfData("Identificacion", $identificacion, FORCE);
		$oUsuario->setIfData("Password", $password, FORCE);
		$oUsuario->setIfData("Direccion", $direccion, FORCE);
		//$oUsuario->setIfData("Localidad", $poblacion, FORCE);
		//$oUsuario->setIfData("CP", $cp, FORCE);
		//$oUsuario->setIfData("Email", $email, FORCE);
		$oUsuario->setIfData("Telefono", $telefono, FORCE);
		$oUsuario->setIfData("FechaNacim", $nace, FORCE);
		//$oUsuario->setIfData("Telefono2", $telefono2, FORCE);
		//$oUsuario->setIfData("Contacto", $contacto, FORCE);
		//$oUsuario->setIfData("Cargo", $cargo, FORCE);	
		$oUsuario->setIfData("CuentaBanco", $cuentabancaria, FORCE);
		//$oUsuario->setIfData("NumeroFiscal", $numero, FORCE);
		//$oUsuario->setIfData("Comentarios", $comentario, FORCE);
		//$oUsuario->setIfData("TipoUsuario", $tipousuario, FORCE);
		//$oUsuario->setIfData("IdPais", $idpais, FORCE);
		//$oUsuario->setIfData("PaginaWeb", $paginaweb, FORCE);
		//$oUsuario->setIfData("IdLocal", CleanID(getSesionDato("IdTienda")), FORCE);
		
		if( $oUsuario->Save()){
			echo "OK=$idusuario";
		} else {
			echo "ERROR, fallo al salvar el usuario($idusuario)";
		}
		
		break;	
		
	
	
	case "borrar":
		$id = CleanID($_GET["id"]);
		
		if (!$id)
			echo "ERROR";
		else {
			BorrarUsuario($id);
			echo "OK=ok";
		}		
		exit();
		break;
	case "alta":
		$nombre = CleanNombre($_POST["nombre"]);

		if ($id = CrearUsuarioBreve($nombre))
			echo "OK=$id";
		else
			echo "ERROR";
			
		exit();
		break;
		
	case "update":
		$nombre	 	= CleanNombre($_POST["nombre"]);
		$usuario	= CleanNombre($_POST["usuario"]);
		$pass	 	= CleanNombre($_POST["pass"]);
		$esActivo 	= CleanInt($_POST["activo"]);
		$esAdmin	= CleanInt($_POST["admin"]);
		
		$IdUsuario 	= CleanID($_POST["IdUsuario"]);		
		if ( ModificarUsuario( $IdUsuario, $nombre, $usuario, $pass,$esActivo, $esAdmin) ){
			echo "OK=$Idusuario";
		} else {
			echo "ERROR, datos: $IdUsuario, $nombre, $usuario, $pass,$esActivo, $esAdmin";
		}
		exit();
		break;
	
	case "CargarUsuario":
		//OBSOLETO
		$id 		= CleanID($_GET["IdUsuario"]);
		$sep = "#";
		if ($row = DatosUsuario($id)){
			echo $row["Nombre"] . $sep . $row["usuario"] . $sep . $row["pass"] . $sep . $row["esActivo"] . $sep . $row["esAdmin"];
		} else{
			echo "ERROR";		
		}			
		exit();				
		break;		
	case "JSONCargarUsuario":
		$id 		= CleanID($_REQUEST["IdUsuario"]);
		$sep = "#";
		if($row = DatosUsuario($id)){
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