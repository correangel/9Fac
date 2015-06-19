<?php


function GenerarJSUsers(){
	$sql = "SELECT IdUsuario,Nombre FROM ges_usuarios WHERE Eliminado=0";
	$res = query($sql);
	if ($res) {
		while($row = Row($res)){
			$id = CleanID($row["IdUsuario"]);
			$nombre = addslashes(CleanParaXul(CleanNombre($row["Nombre"])));
			echo "xAddUser($id,'$nombre');\n";
		}
	}
}	
	
function CrearUsuario($nombre) {
	global $UltimaInsercion;
	//Comprobamos si es correcto crear usuario con este nombre
	$sql = "SELECT IdUsuario FROM ges_usuarios WHERE (Nombre='$nombre') AND (Eliminado=0)";
	$row = queryrow($sql);
	if ($row and $row["IdUsuario"]>0){
		return false;//Ya existe usuario
	}
	
	$sql = "INSERT INTO ges_usuarios ( Nombre) VALUES ('$nombre')";
	
	if(query($sql , "alta usuario")) {
		return $UltimaInsercion;
	}
	
	return 0;

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
	$sql = "UPDATE ges_usuarios SET Eliminado=1 WHERE IdUsuario='$id'";
	return query($sql);
}



switch($modo){
	
	case "modificarcliente":	

		$idcliente = CleanID($_POST["IdCliente"]);		
		$comercial = $_POST["NombreComercial"];
		$poblacion = $_POST["Localidad"];
		$direccion = $_POST["Direccion"];
		$cp = CleanCP($_POST["CP"]);
		$email = CleanEmail($_POST["Email"]);
		$telefono1 = CleanTelefono($_POST["Telefono1"]);
		$telefono2 = CleanTelefono($_POST["Telefono2"]);
		$contacto = $_POST["Contacto"];
		$cargo = $_POST["Cargo"];
		$cuentabancaria = $_POST["CuentaBancaria"];
		$numero = $_POST["NumeroFiscal"];
		$comentario = $_POST["Comentarios"];
		$tipocliente = $_POST["TipoCliente"];
		$IdModPagoHabitual = CleanID($_POST["IdModPagoHabitual"]);
		$idpais 	= CleanID($_POST["IdPais"]); 
		$paginaweb  = $_POST["PaginaWeb"];
		$nace  = $_POST["FechaNacim"];
		
		$oCliente = new cliente;
		if(!$oCliente->Load($idcliente)) {
			echo "ERROR: fallo al cargar el cliente($idcliente)";
			exit();	
		}
		
		$oCliente->setIfData("NombreComercial", $comercial, FORCE);
		$oCliente->setIfData("Direccion", $direccion, FORCE);
		$oCliente->setIfData("Localidad", $poblacion, FORCE);
		$oCliente->setIfData("CP", $cp, FORCE);
		$oCliente->setIfData("Email", $email, FORCE);
		$oCliente->setIfData("Telefono1", $telefono1, FORCE);
		$oCliente->setIfData("Telefono2", $telefono2, FORCE);
		$oCliente->setIfData("Contacto", $contacto, FORCE);
		$oCliente->setIfData("Cargo", $cargo, FORCE);	
		$oCliente->setIfData("CuentaBancaria", $cuentabancaria, FORCE);
		$oCliente->setIfData("NumeroFiscal", $numero, FORCE);
		$oCliente->setIfData("Comentarios", $comentario, FORCE);
		$oCliente->setIfData("TipoCliente", $tipocliente, FORCE);
		$oCliente->setIfData("IdPais", $idpais, FORCE);
		$oCliente->setIfData("PaginaWeb", $paginaweb, FORCE);
		//$oCliente->setIfData("IdLocal", CleanID(getSesionDato("IdTienda")), FORCE);
		
		if( $oCliente->Save()){
			echo "OK=$idcliente";
		} else {
			echo "ERROR, fallo al salvar el cliente($idcliente)";
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

		if ($id = CrearUsuario($nombre))
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
		$id 		= CleanID($_GET["IdUsuario"]);
		$sep = "#";
		if ($row = DatosUsuario($id)){
			echo $row["Nombre"] . $sep . $row["usuario"] . $sep . $row["pass"] . $sep . $row["esActivo"] . $sep . $row["esAdmin"];
		} else{
			echo "ERROR";		
		}			
		exit();				
		break;		
	case "JSONCargarCliente":
		$id 		= CleanID($_REQUEST["IdCliente"]);
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