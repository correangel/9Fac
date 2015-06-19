<?php


function Factura($id){
	if (!$id)
		return false;

	$o = new factura();
	
	if ($o->Load($id))
		return $o;				
}
 

 
 
class factura extends Cursor {

	function factura() {
		return $this;
	}
	
	function Load($id) {
		$id = CleanID($id);
		$this->setId($id);
		$this->LoadTable("ges_facturas", "IdFactura", $id);
		return $this->getResult();
	}
  	
  	function setNombre($nombre) {
  		$this->set("NombreComercial",$nombre,FORCE);	
  	}
  	
  	function Crea(){
		$this->setNombre(_("Nuevo factura"));
	}  
    
	function Alta(){
	
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
	
		$sql = "INSERT INTO ges_facturas ( $listaKeys ) VALUES ( $listaValues )";
		
		return query($sql);
						 	
	}	
	
	function Listado($lang,$min=0){
			
    	if (!$lang)
    		$lang = getSesionDato("IdLenguajeDefecto");
    
		$sql = "SELECT		
		ges_facturas.*		
		FROM
		ges_facturas 		
		WHERE
		ges_facturas.Eliminado = 0
		";
		
		$res = $this->queryPagina($sql, $min, 10);
		if (!$res) {
			$this->Error(__FILE__ . __LINE__ ,"Info: fallo el listado");
		}		
				
		return $res;
	}
	
	function SiguienteFactura() {
		$res = $this->LoadNext();
		if (!$res) {
			return false;
		}
		$this->setId($this->get("IdFactura"));		
		return true;			
	}
	
	function Modificacion () {
		
		$data = $this->export();				
		
		$sql = CreaUpdateSimple($data,"ges_facturas","IdFactura",$this->get("IdFactura"));
		
		$res = query($sql);
		if (!$res) {			
			$this->Error(__FILE__ . __LINE__ , "W: no actualizo factura");
			return false;
		}		
		return true;
	}
	
	
}




?>