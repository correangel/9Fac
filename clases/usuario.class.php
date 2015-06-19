<?php

function Usuario($id) {
	if (!$id)
		return false;

	$oUsuario = new usuario();
	
	if ($oUsuario->Load($id))
		return $oUsuario;		
}



function CrearUsuarioBreve($nombre) {
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


function CrearUsuario($comercial, $legal, $direccion, $poblacion, $cp, $email, 
	$telefono1, $telefono2, $contacto, $cargo, $cuentabancaria, $numero, 
	$comentario,$tipousuario,$idpais,$paginaweb,$nace,$IdLocal=false) {

	$oUsuario = new usuario;
	$oUsuario->Crea();

	$oUsuario->set("Nombre", $comercial, FORCE);
	$oUsuario->set("NombreLegal", $legal, FORCE);
	$oUsuario->set("Direccion", $direccion, FORCE);
	$oUsuario->set("Localidad", $poblacion, FORCE);
	$oUsuario->set("CP", $cp, FORCE);
	$oUsuario->set("Email", $email, FORCE);
	$oUsuario->set("Telefono1", $telefono1, FORCE);
	$oUsuario->set("Telefono2", $telefono2, FORCE);
	$oUsuario->set("Contacto", $contacto, FORCE);
	$oUsuario->set("Cargo", $cargo, FORCE);	
	$oUsuario->set("CuentaBancaria", $cuentabancaria, FORCE);
	$oUsuario->set("NumeroFiscal", $numero, FORCE);
	$oUsuario->set("Comentarios", $comentario, FORCE);
	$oUsuario->set("TipoUsuario", $tipousuario, FORCE);
	$oUsuario->set("IdPais", $idpais, FORCE);
	$oUsuario->set("PaginaWeb", $paginaweb, FORCE);
	if ($IdLocal)
		$oUsuario->set("IdLocal", $IdLocal, FORCE);
	
	if ($oUsuario->Alta()) {
		//if(isVerbose())		
		//	echo gas("aviso", _("Nuevo usuario registrado"));
		return $oUsuario->get("IdUsuario");
	} else {
		//echo gas("aviso", _("No se ha podido registrar el nuevo producto"));
		return false;
	}

}


class usuario extends Cursor {
    function usuario() {
    	return $this;
    }
    
    function Load($id) {
		$id = CleanID($id);
		$this->setId($id);
		$this->LoadTable("ges_usuarios", "IdUsuario", $id);
		return $this->getResult();
	}
    
    
    // SET especializados    
    function setNombre($nombre){    	
    	$this->set("Nombre",$nombre,FORCE);	
    }
    
    function esEmpresa() {    
    	return $this->get("TipoUsuario")=="Empresa";	    	
    }
    
    
    // GET especializados
    function getNombre(){
    	return $this->get("Nombre");	
    }
    
    function getUsuario(){
    	return $this->get("Nombre");
    }
	
	//Formulario de modificaciones y altas
	function formEntrada($action,$esModificar){
			//OBSOLETO				
	}
	
	function formAlta($action){
			//OBSOLETO				
	}
	
	function Crea(){
		$this->setNombre(_("Nuevo usuario"));
		//$this->set("FechaNacim","1974-09-01",FORCE);
	}
	
	function Alta(){
		global $UltimaInsercion;
		$data = $this->export();
		
		$coma = false;
		$listaKeys = "";
		$listaValues = "";
				
		foreach ($data as $key=>$value){
			if ($coma) {
				$listaKeys .= ", ";
				$listaValues .= ", ";
			}
			
			$listaKeys .= " $key";
			$listaValues .= " '$value'";
			$coma = true;															
		}
	
		$sql = "INSERT INTO ges_usuarios ( $listaKeys ) VALUES ( $listaValues )";
		
		$res = query($sql,"Alta usuario");
		
		if ($res) {		
			$id = $UltimaInsercion;	
			$this->set("IdUsuario",$id,FORCE);
			return $id;			
		}
						
		return false;				 		
	}

	function Listado($lang,$min=0){
			
    	if (!$lang)
    		$lang = getSesionDato("IdLenguajeDefecto");
    
		$sql = "SELECT		
		ges_usuarios.*		
		FROM
		ges_usuarios 		
		WHERE
		ges_usuarios.Eliminado = 0
		";
		
		$res = $this->queryPagina($sql, $min, 10);
		if (!$res) {
			$this->Error(__FILE__ . __LINE__ ,"Info: fallo el listado");
		}		
				
		return $res;
	}
	
	function SiguienteUsuario() {
		$res = $this->LoadNext();
		if (!$res) {
			return false;
		}
		$this->setId($this->get("IdUsuario"));		
		return true;			
	}	
		
	function Modificacion () {
		
		$data = $this->export();				
		
		$sql = CreaUpdateSimple($data,"ges_usuarios","IdUsuario",$this->get("IdUsuario"));
		
		$res = query($sql,'Modificamos un usuario');
		if (!$res) {			
			$this->Error(__FILE__ . __LINE__ , "W: no actualizo proveedor");
			return false;
		}		
		return true;
	}
}
