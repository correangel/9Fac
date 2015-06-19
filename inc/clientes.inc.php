<?php

function GenerarJSUsers(){
	$sql = "SELECT IdCliente,NombreComercial FROM ges_clientes WHERE Eliminado=0 ORDER BY NombreComercial ASC";
	$res = query($sql);
	if ($res) {
		while($row = Row($res)){
			$id = CleanID($row["IdCliente"]);
			$nombre = addslashes((CleanNombre($row["NombreComercial"])));
			echo "xAddUser($id,'$nombre');\n";
		}
	}
}	

function ModificarCliente($IdCliente, $NuevoNombre, $usuario, $pass,$esActivo,$esAdmin){	
	if (!$IdCliente)	
		return false;
	
	$NuevoNombre = CleanNombre($NuevoNombre);		
	$sql = "UPDATE ges_clientes SET NombreComercial ='$NuevoNombre', usuario = '$usuario', pass = '$pass', esActivo='$esActivo', esAdmin='$esAdmin' WHERE IdCliente = '$IdCliente' ";	
	return query($sql, "cambiando datos usuario");
}



function DatosCliente($IdCliente){
	$IdCliente	= CleanID($IdCliente);
	$sql 		= "SELECT * FROM ges_clientes WHERE IdCliente ='$IdCliente'";	
	return queryrow($sql);
}

function BorrarCliente($id){
	$id = CleanID($id);
	$sql = "UPDATE ges_clientes SET Eliminado=1 WHERE (IdCliente='$id')";
	return query($sql);
}



switch($modo){
	
	case "modificarcliente":	

		$idcliente = CleanID($_POST["IdCliente"]);		
		$comercial = $_POST["NombreComercial"];
		$legal 	   = $_POST["NombreLegal"];
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
		
		$oCliente = new cliente();
		if(!$oCliente->Load($idcliente)) {
			echo "ERROR: fallo al cargar el cliente($idcliente)";
			exit();	
		}
		
		$oCliente->setIfData("NombreLegal", $legal, FORCE);
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
			BorrarCliente($id);
			echo "OK=ok";
		}		
		exit();
		break;
	case "alta":
		$nombre = CleanNombre($_POST["nombre"]);

		if ($id = CrearClienteBreve($nombre))
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
		
		$IdCliente 	= CleanID($_POST["IdCliente"]);		
		if ( ModificarCliente( $IdCliente, $nombre, $usuario, $pass,$esActivo, $esAdmin) ){
			echo "OK=$Idusuario";
		} else {
			echo "ERROR, datos: $IdCliente, $nombre, $usuario, $pass,$esActivo, $esAdmin";
		}
		exit();
		break;
	
	case "CargarCliente":
		//OBSOLETO
		$id 		= CleanID($_GET["IdCliente"]);
		$sep = "#";
		if ($row = DatosCliente($id)){
			echo $row["Nombre"] . $sep . $row["usuario"] . $sep . $row["pass"] . $sep . $row["esActivo"] . $sep . $row["esAdmin"];
		} else{
			echo "ERROR";		
		}			
		exit();				
		break;		
	case "JSONCargarCliente":
		$id 		= CleanID($_REQUEST["IdCliente"]);
		$sep = "#";
		if($row = DatosCliente($id)){
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