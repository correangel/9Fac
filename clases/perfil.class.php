<?php

function Perfil($id) {
	if (!$id)
		return false;

	$oPerfil = new perfil();
	
	if ($oPerfil->Load($id))
		return $oPerfil;		
}



function CrearPerfilBreve($nombre) {
	global $UltimaInsercion;
	//Comprobamos si es correcto crear usuario con este nombre
	$sql = "SELECT IdPerfil FROM ges_perfiles WHERE (NombrePerfil='$nombre') AND (Eliminado=0)";
	$row = queryrow($sql);
	if ($row and $row["IdPerfil"]>0){
		return false;//Ya existe usuario
	}
	
	$sql = "INSERT INTO ges_perfiles ( NombrePerfil) VALUES ('$nombre')";
	
	if(query($sql , "alta usuario")) {
		return $UltimaInsercion;
	}
	
	return 0;

}


function CrearPerfil($comercial, $legal, $direccion, $poblacion, $cp, $email, 
	$telefono1, $telefono2, $contacto, $cargo, $cuentabancaria, $numero, 
	$comentario,$tipoperfil,$idpais,$paginaweb,$nace,$IdLocal=false) {

	//obsoleto

}


class perfil extends Cursor {
    function perfil() {
    	return $this;
    }
    
    function Load($id) {
		$id = CleanID($id);
		$this->setId($id);
		$this->LoadTable("ges_perfiles", "IdPerfil", $id);
		return $this->getResult();
	}
    
    
    // SET especializados    
    function setNombre($nombre){    	
    	$this->set("NombrePerfil",$nombre,FORCE);	
    }
    
    function esEmpresa() {    
    	return $this->get("TipoPerfil")=="Empresa";	    	
    }
    
    
    // GET especializados
    function getNombre(){
    	return $this->get("NombrePerfil");	
    }
    
    function getPerfil(){
    	return $this->get("NombrePerfil");
    }
	
	//Formulario de modificaciones y altas
	function formEntrada($action,$esModificar){
		//obsoleto	
	}
	
	function formAlta($action){
		//obsoleto
	}
	
	function Crea(){
		$this->setNombre(_("Nuevo perfil"));
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
	
		$sql = "INSERT INTO ges_perfiles ( $listaKeys ) VALUES ( $listaValues )";
		
		$res = query($sql,"Alta perfil");
		
		if ($res) {		
			$id = $UltimaInsercion;	
			$this->set("IdPerfil",$id,FORCE);
			return $id;			
		}
						
		return false;				 		
	}

	function Listado($lang,$min=0){
			
    	if (!$lang)
    		$lang = getSesionDato("IdLenguajeDefecto");
    
		$sql = "SELECT		
		ges_perfiles.*		
		FROM
		ges_perfiles 		
		WHERE
		ges_perfiles.Eliminado = 0
		";
		
		$res = $this->queryPagina($sql, $min, 10);
		if (!$res) {
			$this->Error(__FILE__ . __LINE__ ,"Info: fallo el listado");
		}		
				
		return $res;
	}
	
	function SiguientePerfil() {
		$res = $this->LoadNext();
		if (!$res) {
			return false;
		}
		$this->setId($this->get("IdPerfil"));		
		return true;			
	}	
		
	function Modificacion () {
		
		$data = $this->export();				
		
		$sql = CreaUpdateSimple($data,"ges_perfiles","IdPerfil",$this->get("IdPerfil"));
		
		$res = query($sql,'Modificamos un perfil');
		if (!$res) {			
			$this->Error(__FILE__ . __LINE__ , "W: no actualizo proveedor");
			return false;
		}		
		return true;
	}
}
