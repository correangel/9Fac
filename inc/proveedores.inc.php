<?php

function GenerarJSUsers(){
	$sql = "SELECT IdProveedor,NombreComercial FROM ges_proveedores WHERE Eliminado=0 ORDER BY NombreComercial ASC";
	$res = query($sql);
	if ($res) {
		while($row = Row($res)){
			$id = CleanID($row["IdProveedor"]);
			$nombre = addslashes(CleanParaXul(CleanNombre($row["NombreComercial"])));
			echo "xAddUser($id,'$nombre');\n";
		}
	}
}	

function ModificarProveedor($IdProveedor, $NuevoNombre, $usuario, $pass,$esActivo,$esAdmin){	
	if (!$IdProveedor)	
		return false;
	
	$NuevoNombre = CleanNombre($NuevoNombre);		
	$sql = "UPDATE ges_proveedores SET NombreComercial ='$NuevoNombre', usuario = '$usuario', pass = '$pass', esActivo='$esActivo', esAdmin='$esAdmin' WHERE IdProveedor = '$IdProveedor' ";	
	return query($sql, "cambiando datos usuario");
}



function DatosProveedor($IdProveedor){
	$IdProveedor	= CleanID($IdProveedor);
	$sql 		= "SELECT * FROM ges_proveedores WHERE IdProveedor ='$IdProveedor'";	
	return queryrow($sql);
}

function BorrarProveedor($id){
	$id = CleanID($id);
	$sql = "UPDATE ges_proveedores SET Eliminado=1 WHERE (IdProveedor='$id')";
	return query($sql);
}



switch($modo){
	
	case "modificarproveedor":	

		$idproveedor = CleanID($_POST["IdProveedor"]);		
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
		$tipoproveedor = $_POST["TipoProveedor"];
		$IdModPagoHabitual = CleanID($_POST["IdModPagoHabitual"]);
		$idpais 	= CleanID($_POST["IdPais"]); 
		$paginaweb  = $_POST["PaginaWeb"];
		$nace  = $_POST["FechaNacim"];
		
		$oProveedor = new proveedor();
		if(!$oProveedor->Load($idproveedor)) {
			echo "ERROR: fallo al cargar el proveedor($idproveedor)";
			exit();	
		}
		
		$oProveedor->setIfData("NombreLegal", $legal, FORCE);
		$oProveedor->setIfData("NombreComercial", $comercial, FORCE);
		$oProveedor->setIfData("Direccion", $direccion, FORCE);
		$oProveedor->setIfData("Localidad", $poblacion, FORCE);
		$oProveedor->setIfData("CP", $cp, FORCE);
		$oProveedor->setIfData("Email", $email, FORCE);
		$oProveedor->setIfData("Telefono1", $telefono1, FORCE);
		$oProveedor->setIfData("Telefono2", $telefono2, FORCE);
		$oProveedor->setIfData("Contacto", $contacto, FORCE);
		$oProveedor->setIfData("Cargo", $cargo, FORCE);	
		$oProveedor->setIfData("CuentaBancaria", $cuentabancaria, FORCE);
		$oProveedor->setIfData("NumeroFiscal", $numero, FORCE);
		$oProveedor->setIfData("Comentarios", $comentario, FORCE);
		$oProveedor->setIfData("TipoProveedor", $tipoproveedor, FORCE);
		$oProveedor->setIfData("IdPais", $idpais, FORCE);
		$oProveedor->setIfData("PaginaWeb", $paginaweb, FORCE);
		//$oProveedor->setIfData("IdLocal", CleanID(getSesionDato("IdTienda")), FORCE);
		
		if( $oProveedor->Save()){
			echo "OK=$idproveedor";
		} else {
			echo "ERROR, fallo al salvar el proveedor($idproveedor)";
		}
		
		break;	
		
	
	
	case "borrar":
		$id = CleanID($_GET["id"]);
		
		if (!$id)
			echo "ERROR";
		else {
			BorrarProveedor($id);
			echo "OK=ok";
		}		
		exit();
		break;
	case "alta":
		$nombre = CleanNombre($_POST["nombre"]);

		if ($id = CrearProveedorBreve($nombre))
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
		
		$IdProveedor 	= CleanID($_POST["IdProveedor"]);		
		if ( ModificarProveedor( $IdProveedor, $nombre, $usuario, $pass,$esActivo, $esAdmin) ){
			echo "OK=$Idusuario";
		} else {
			echo "ERROR, datos: $IdProveedor, $nombre, $usuario, $pass,$esActivo, $esAdmin";
		}
		exit();
		break;
	
	case "CargarProveedor":
		//OBSOLETO
		$id 		= CleanID($_GET["IdProveedor"]);
		$sep = "#";
		if ($row = DatosProveedor($id)){
			echo $row["Nombre"] . $sep . $row["usuario"] . $sep . $row["pass"] . $sep . $row["esActivo"] . $sep . $row["esAdmin"];
		} else{
			echo "ERROR";		
		}			
		exit();				
		break;		
	case "JSONCargarProveedor":
		$id 		= CleanID($_REQUEST["IdProveedor"]);
		$sep = "#";
		if($row = DatosProveedor($id)){
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