<?php

define("TALLAJE_VARIOS",5);
define("TALLAJE_VARIOS_TALLA",4);

function AccionesTrasAlta(){
	global $action;
	$ot = getTemplate("AccionesTrasAlta");
			
	if (!$ot){	
		error(__FILE__ . __LINE__ ,"Info: template busqueda no encontrado");
		return false; }
	
	$IdProducto = getSesionDato("UltimaAltaProducto");
				
	$ot->fijar("IdProducto", $IdProducto);
	

	$ot->fijar("action", $action);	
				
	echo $ot->Output();												
}


function getValorImpuestoDefectoCentral(){
	return 16;
}



function AltaDesdePostProducto($esMudo=false) {
	
		$nombre 		= CleanText($_POST["Nombre"]);			
		$referencia 	= CleanReferencia($_POST["Referencia"]);
		$descripcion 	= CleanText($_POST["Descripcion"]);
		
		$precioventa 	= CleanDinero($_POST["PrecioVenta"]);
		$precioonline 	= CleanDinero($_POST["PrecioOnline"]);
		$coste 			= CleanDinero($_POST["CosteSinIVA"]);
		$idfamilia 		= CleanID($_POST["IdFamilia"]);
		$idsubfamilia 	= CleanID($_POST["IdSubFamilia"]);
		$idprovhab 		= CleanID($_POST["IdProvHab"]);			
		if (!isset($_POST["IdProvHab"])){		
			$idprovhab 		= CleanID($_POST["ProvHab"]);
		}				
		$codigobarras 	= CleanCB($_POST["CodigoBarras"]);
		$refprovhab 	= CleanReferencia($_POST["RefProv"]);
		if (!isset($_POST["RefProv"])){
			$refprovhab 	= CleanReferencia($_POST["RefProvHab"]);
		}
	
		$idcolor 	= CleanID($_POST["IdColor"]);
		$idtalla 	= CleanID($_POST["IdTalla"]);
		$idmarca 	= CleanID($_POST["IdMarca"]);
		if (!isset($_POST["IdMarca"])){
			$idmarca = CleanID($_POST["Marca"]);
			if ($idmarca<1){
				$idmarca = getIdMarcaFromMarca($_POST["Marca"]);
			}
		}		
		
		if ($id = CrearProducto($esMudo,$nombre,$referencia,
				$descripcion, $precioventa,
				$precioonline,$coste,$idfamilia,$idsubfamilia,$idprovhab,
				$codigobarras,$idtalla,$idcolor,
				$idmarca,$refprovhab)) {
					
			if(!$esMudo)
				AccionesTrasAlta();
			return $id;
		} else {
			//INFO: no llega aqui, porque remuestra un formulario erroneo dentro de CrearProducto
			return false;
		} 
}

		
/* LISTADO COMBINADO */


function GetOrdenVacio($arreglo, $posicion=0){
	//Auxiliar.
	// Busca un slot vacio para colocar una talla.
	// Aunque las tallas tienen un orden 
	// este orden puede corromperse, y perderiamos tallas.
	
	if (!isset($arreglo[$posicion])){
		return $posicion;	
	}
	while( isset($arreglo[$posicion])){
		$posicion = $posicion + 1;	 
	}
	
	return $posicion;	
}



/* LISTADO COMBINADO */

/* CARRITO DE COMPRA */

function ActualizarCantidades() {
		$data = getSesionDato("CarritoCompras");
		$data2 = getSesionDato("CarroCostesCompra");
		
		for($t=1;$t<200;$t++){
			if (isset($_POST["Id$t"])){
				$id = $_POST["Id$t"];
				$unid =$_POST["Cantidad$t"];
				$coste =$_POST["Precio$t"];
				
				if ($id) {
					$preval = $data[$id];
					$data[$id] = $unid;
					$data2[$id] = $coste;
				}
				
				//echo "Desde data[id]=$preval, para id=$id, cargando Cantidad$t=$valor<br>";
			}			
		}
		setSesionDato("CarritoCompras",$data);		
		setSesionDato("CarroCostesCompra",$data2);
}

/* CARRITO DE COMPRA */

/* BUSQUEDA DE DATOS*/

function getIdProductoFromIdArticulo($id){
	$id = CleanID($id);
	
	if ( isset($_SESSION["tIDALMACEN2IDPRODUCTO_$id"]) and intval($_SESSION["tIDALMACEN2IDPRODUCTO_$id"]) > 0 ) {
		return $_SESSION["tIDALMACEN2IDPRODUCTO_$id"];
	}
	

	$sql = "SELECT IdProducto FROM ges_almacenes WHERE Id = '$id'";
	$row = queryrow($sql);
	
	if (!$row)	return false;
	
	$idprod = $row["IdProducto"];
	
	$_SESSION["tIDALMACEN2IDPRODUCTO_$id"] = $idprod;
	
	return $idprod;		
}

function getIdFromReferencia ($ref){
	if (!$ref)
		return false;
	
	$ref= CleanReferencia($ref);
	return genReferencia2IdProducto($ref);
}

function getProdBaseFromId($id){
	$id = CleanID($id);
	
	$key ="tPRODBASEFROMID_" . $id;
	
	if ( isset($_SESSION[$key]) and intval($_SESSION[$key]) > 0 ) {
		return $_SESSION[$key];
	}
	
	
	$sql = "SELECT IdProdBase FROM ges_productos WHERE IdProducto = '$id'";
	$row = queryrow($sql);
	if (!$row)
		return false;
	
	$_SESSION[$key] = $row["IdProdBase"];
	
	return $row["IdProdBase"];
}

function getCosteDefectoProducto($id) {
	$id = CleanID($id);
	$sql = "SELECT CosteSinIVA FROM ges_productos WHERE IdProducto = '$id'"; 
	$row = queryrow($sql);
	if (!$row) return false;
	
	return $row["CosteSinIVA"]; 	
}

function getIdProveedorFromIdProducto($id){	
	$sql = "SELECT IdProvHab FROM ges_productos WHERE IdProducto='$id' ";
	$row = queryrow($sql);
	
	return $row["IdProvHab"];	
}


function AgnadirCarritoCompras($id,$unidades=1) {
	
	if(!$id)
		return;
	
	$actual = getSesionDato("CarritoCompras");
	$costes = getSesionDato("CarroCostesCompra");
	
	$val = $actual[$id] + $unidades;	
	$actual[$id] = $val;	
	
	if(!$costes[$id]) {
		$costes[$id] = getCosteDefectoProducto($id);
	}
				
	setSesionDato("CarritoCompras",$actual);
	setSesionDato("CarroCostesCompra",$costes);
}

function ProductoFactory($res) {
	if(!$res){
		error(__FILE__ . __LINE__ ,"ERROR en factory");
		return false;	
	}
	
	$row = Row($res);
	if (!is_array($row))
		return false;	
	$id = $row["IdProducto"];
	
	$oProducto = new producto;
		
	if ($oProducto->Load($id))
		return $oProducto;
		
	error(__FILE__ . __LINE__ ,"ERROR no pudo cargar id '$id'");
		
	return false;
}


function CrearProducto($mudo,$nombre,$referencia,
				$descripcion, $precioventa,
				$precioonline,$coste,$idfamilia,$idsubfamilia,$idprovhab,
				$codigobarras,$idtalla,$idcolor,$idmarca,$refprovhab){
	global $action;
	$oProducto = new producto;

	$oProducto->Crea();
	
	if (!$idfamilia)	$idfamilia = getParametro("IdFamiliaDefecto");
	if (!$idsubfamilia)	$idfamilia = getParametro("IdFamiliaDefecto");
	
	$oProducto->setNombre($nombre);
	$oProducto->setReferencia($referencia);	
	$oProducto->setDescripcion($descripcion);
	$oProducto->setLang(getSesionDato("IdLenguajeDefecto"));	
	$oProducto->setPrecioVenta($precioventa);
	$oProducto->setPrecioOnline($precioonline);
	$oProducto->set("CosteSinIVA",$coste,FORCE);
	$oProducto->set("IdFamilia",$idfamilia,FORCE);
	$oProducto->set("IdSubFamilia",$idsubfamilia,FORCE);
	$oProducto->set("IdProvHab",$idprovhab,FORCE);
	$oProducto->set("CodigoBarras",$codigobarras,FORCE);
	$oProducto->set("RefProvHab",$refprovhab,FORCE);			
	
	$oProducto->set("IdTalla",$idtalla,FORCE);
	$oProducto->set("IdColor",$idcolor,FORCE);
	
	$oProducto->set("IdMarca",$idmarca,FORCE);
	
	//		
	if ($oProducto->Alta()){
			
		//Guardamos el id de la ultima alta para procesos posteriores 
		// que quieran usarlo (encadenacion de acciones)
		setSesionDato("UltimaAltaProducto",$oProducto->getId());
		
		//TODO
		// una vez creado el producto, lo vamos a stockar en los almacenes
		// con cantidad cero
		
		$alm = getSesionDato("Almacen");
		
		error(__FILE__ . __LINE__ ,"Infor: Precio aqui es ". $oProducto->getPrecioVenta());
		
		$alm->ApilaProductoTodos($oProducto);
		return $oProducto->getId();
						
	} else {
		setSesionDato("UltimaAltaProducto",false);//por si acaso
		//setSesionDato("FetoProducto",$oProducto);
		if (!$mudo)
			echo $oProducto->formEntrada($action,false);	
		//echo gas("aviso",_("No se ha podido registrar el nuevo producto"));
		return false;
	}
}


function productoEnAlmacen($id) {
	global $FilasAfectadas;
	$sql = "SELECT Id FROM ges_almacenes WHERE Unidades>0 and IdProducto = '$id'";	
	$res = query($sql);
	$num = intval($FilasAfectadas);
	
	if (!$res){
		error(__FILE__ . __LINE__ ,"E: no se pudo contar en almacenes para $sql");
		return true;	
	}		
//	error(0,"Info: num es $num, con sql $sql"); 
	return ($num > 0);		
}

//eliminar uno de los dos

function getIdFromCodigoBarras($cb){
	$cb = CleanCB($cb);	
	if (!$cb or $cb=="")
		return false;
	
	$sql = 	"SELECT IdProducto FROM ges_productos WHERE (CodigoBarras = '$cb')";
	$row = queryrow($sql);
	if (!$row){ 
		return false;
	}
	return $row["IdProducto"];
}

function getCBfromIdProducto($IdProducto) {
	$IdProducto = CleanID($IdProducto);	
	$sql = 	"SELECT CodigoBarras FROM ges_productos WHERE IdProducto = '$IdProducto'";
	$row = queryrow($sql,"Busca CB de producto");
	if (!$row){ 
		return false;
	}
	return $row["CodigoBarras"];
}


function genReferencia2IdProducto($ref){
	
	$sql = 	"SELECT IdProducto FROM ges_productos WHERE (Referencia = '$ref')";
	$row = queryrow($sql);
	if (!$row){
		return false;
	}
	
	$id = $row["IdProducto"];
	
	return $id ;
}

function BuscaProductoPorReferencia($ref){	
	$sql = "SELECT IdProducto FROM ges_productos WHERE (Referencia='$ref')";
	$row = queryrow($sql);
	if ($row){
		return $row["IdProducto"];	
	}	else {
		return false;	
	}
}

/*
    * Tipo Impuesto - Obligatorio - 

    No se Almacena, es indicativo para dar de alta el producto en almacenes. 
    Por defecto se tomara valor "TipoImpuesto" de la tabla "ges_paises". 
    Un nuevo producto toma el tipo por defecto del pais en que esta el almacén central. 
	
	Producto->TipoImpuesto = AlmacenCentral->Pais->TipoImpuesto
	
	* Impuesto - Obligatorio - 

    Se almacena en "ges_productos_idioma". 
    Por defecto se tomara el valor "Impuesto" de la tabla "ges_productos_idioma". 
    Producto->Idioma->Impuesto = ??? Producto->Idioma->Impuesto
    
*/

function getTipoImpuesto($oProducto=false,$local=false) {
		$key = "tIMPUESTOCENTRALTIPO";

		if( isset($_SESSION[$key]))
			return $_SESSION[$key];


	/*
		$central = new local;
		if(!$central->LoadCentral())
			return false;
			
		
		$IdPais = CleanID($central->get("IdPais"));
		$sql = "SELECT TipoImpuestoDefecto FROM ges_paises WHERE IdPais='$IdPais'";
		$row = queryrow($sql,"Cargando TIPO impuesto de la central");
		
		if ($row) {
			$val = $row["TipoImpuestoDefecto"];
			$_SESSION[$key] = $val;
			return $val;
		}*/

	
			
		return "IVA";	
}


function getFirstNotNull($tabla,$id){
	$sql = "SELECT $id as IdCosa FROM $tabla WHERE Eliminado=0";
	$row = queryrow($sql);
	if (!$row) return 0;
	return $row["IdCosa"];
}


/* BUSQUEDA DE DATOS*/

/* CLASE */

class producto extends Cursor {
	
	var $lastLang;
	var $ges_productos;
	var $ges_productos_idioma;
	var $_fallodeintegridad;
	var $gestionAlmacenes;
	
    function producto() {
    	return $this;
    }
      
      
      
    function Load($id,$lang=false){
    	$this->Init();
    	$id = CleanID($id);
    	if (intval($id)==0){
    		error(__FILE__ . __LINE__ , "Info: cargando id, pero '$id' es cero");
    		return false;    		
    	}   
    	    	
    	if (!$lang)
    		$lang = getSesionDato("IdLenguajeDefecto");
    
    /*
		$sql = "SELECT
		ges_productos.*,
		ges_productos_idioma.IdProdIdioma,
		ges_productos_idioma.Nombre,
		ges_productos_idioma.Descripcion
		
		FROM
		ges_productos INNER JOIN ges_productos_idioma ON
		ges_productos.IdProducto = ges_productos_idioma.IdProducto
		
		WHERE
		ges_productos_idioma.IdIdioma = '$lang'
		AND ges_productos.Eliminado = 0
		AND ges_productos_idioma.Eliminado = 0
		AND ges_productos.IdProducto = '$id'";*/
		
		/*$sql = "SELECT
		ges_productos.*,
		ges_productos_idioma.IdProdIdioma,
		ges_productos_idioma.Nombre,
		ges_productos_idioma.Descripcion,
		ges_familias.Familia,
		ges_subfamilias.SubFamilia
		
		FROM
		ges_productos
		INNER JOIN ges_productos_idioma ON ges_productos.IdProdBase =
		ges_productos_idioma.IdProdBase
		INNER JOIN ges_familias ON ges_productos.IdFamilia = ges_familias.IdFamilia
		INNER JOIN ges_subfamilias ON (ges_productos.IdSubFamilia =
		ges_subfamilias.IdSubFamilia AND ges_productos.IdFamilia =
		ges_subfamilias.IdFamilia)
		
		WHERE
		ges_productos_idioma.IdIdioma = '$lang'
		AND ges_familias.IdIdioma = '$lang'
		AND ges_subfamilias.IdIdioma = '$lang'
		AND ges_productos.Eliminado = 0
		AND ges_productos.IdProducto = '$id' ";*/


		$sql = "SELECT
		*
				
		FROM
		ges_productos INNER JOIN ges_productos_idioma ON
		ges_productos.IdProdBase =	ges_productos_idioma.IdProdBase
		
		WHERE
		ges_productos_idioma.IdIdioma = '$lang'
		AND ges_productos.Eliminado = 0
		AND ges_productos.IdProducto = '$id' ";		
		
		
		
		$res = $this->queryrow($sql);//pregunta e importa fila
		if (!$res){
			$this->Error(__FILE__ . __LINE__ , "E: cargando producto");
			return false;			
		}				
		$this->setId($id);
		//$this->set("IdProducto",$id);
		$this->setLang($lang);				
    	return true;
    }
        
              
      
    function Init(){
    	$this->ges_productos = array("Referencia","CodigoBarras","RefProvHab",
			"IdProdBase","IdProvHab","IdTalla","IdNumeroZapato","IdColor",
			"IdFamilia","CosteSinIVA","IdSubFamilia","IdProvHab","IdMarca","IdTallaje","PrecioVenta");	
		$this->ges_productos_idioma = array("IdProdBase","IdIdioma","Nombre","Descripcion");	
		    	
    }  
      
    function SiguienteProducto() {
		$res = $this->LoadNext();
		if (!$res) {
			return false;
		}
		$this->setId($this->get("IdProducto"));		
		return true;			
	}

    function setLang($lang){    	    	
		$this->set("IdIdioma",$lang,FORCE);
		$this->lastLang = $lang;    	
    }           
    
    function getLang(){
    	return $this->lastLang;	
    }
    
    function getNombre(){
    	if (!getParametro("ProductosLatin1"))
    		return $this->get("Nombre");//ya es UTF8
    	else
    		return iso2utf($this->get("Nombre"));//requiere conversion a utf8
    }

	function getReferencia(){
		return $this->get("Referencia");	
	}

	function getDescripcion(){
    	return $this->get("Descripcion");					
	}

	function getPrecioVenta(){		
		return (float)$this->get("PrecioVenta");	
	}
	
	function getPrecio(){		
		return (float)$this->get("PrecioVenta");	
	}
	
	function getPrecioFormat(){
		return money_format('%!i &euro;', $this->getPrecioVenta());	
	}

	function getPrecioOnline(){
		return $this->get("PrecioOnline");	
	}

	function getTipoImpuesto(){
		return $this->get("TipoImpuesto");		
	}
	
	function getImpuesto(){
		return $this->get("Impuesto");	
	}
	
	function getCB(){
		return $this->get("CodigoBarras");	
	}
	
	function Crea(){
		$this->Init();
		$this->regeneraCodigos();
						
		$this->setNombre(_("Nuevo producto"));
									
		$this->setPrecioVenta(0);
		$this->setPrecioOnline(0);
		$this->set("CosteSinIVA",0,FORCE);
		
		$fam = getFirstNotNull("ges_familias","IdFamilia");
		$this->set("IdFamilia",$fam,FORCE);
		$this->set("IdSubFamilia",$this->getSubFamiliaAleatoria($fam), FORCE);
		$this->set("IdProvHab",getFirstNotNull("ges_proveedores","IdProveedor"),FORCE);
		$this->set("IdMarca",getFirstNotNull("ges_marcas","IdMarca"),FORCE);
		
		$this->set("IdTallaje",TALLAJE_VARIOS,FORCE);
		$this->set("IdTalla",TALLAJE_VARIOS_TALLA,FORCE);
		
		$oAlmacen = getSesionDato("AlmacenCentral");
		
		if ($oAlmacen){
			//$this->set("");
			$this->set("TipoImpuesto",getTipoImpuesto(),FORCE);	
			$this->set("Impuesto",getValorImpuestoDefectoCentral(),FORCE);
		}
		//$this->set("IdProvHab",
		
	}
		
	function regeneraCodigos() {
		$minval = "0000";					
		$sql = "SELECT Max(IdProducto) as RefSugerido, Max(CodigoBarras) as MaxBarras FROM ges_productos";
		$row = queryrow($sql,"Imaginando referencia apropiada");
		if ($row) {
			$sugerido =  $row["RefSugerido"];
			$maxbarras = $row["MaxBarras"];		
			$minval = $sugerido + 1001;
		}							
		
		$letra = strtoupper(chr(ord('a')+rand()%25));
		$this->setReferencia($letra . $minval); 
		
		$this->regeneraCB();
	}
	
	function CBRepetido(){
	
		$cb = $this->get("CodigoBarras");
		$sql = "SELECT IdProducto FROM ges_productos WHERE (CodigoBarras='$cb') AND Eliminado=0";
		$row = queryrow($sql,"¿Esta repetido?");
		if (!$row)
			return false;
			
		return (intval($row["IdProducto"])>0);		
	}
	
	
	function regeneraCB() {
		$minval = 0;					
		$sql = "SELECT Max(IdProducto) as RefSugerido, Max(CodigoBarras+1001) as MaxBarras FROM ges_productos";
		$row = queryrow($sql,"Sugiriendo CB Valido");
		if ($row) {
			$sugerido 	= intval($row["RefSugerido"]);
			$maxbarras 	= intval($row["MaxBarras"]);
			if (intval($maxbarras) > intval($sugerido))
				$minval = intval($maxbarras);
			else
				$minval = intval($sugerido) + 90000001;
											
		} else {
			$minval = 90000001+ rand()*10000;	
		}
				
		$extra = 1001;
		$cb = intval($minval)+intval($extra);
		$this->set("CodigoBarras", $cb,FORCE);
		
		while($this->CBRepetido()){
			$extra = $extra + 1001;		
			$cb = intval($minval) + intval($extra);
			$this->set("CodigoBarras", $cb ,FORCE);
		}  
	}
	
	function Alta(){
		global $UltimaInsercion;

		$this->Init();	//antibug squad		
		
		
		//$sql = "SELECT Max(IdProdBase) FROM ges_productos_idioma";
		
		$ref = CleanRef( $this->get("Referencia") );
		$sql = "SELECT IdProdBase FROM ges_productos WHERE Referencia='$ref'";	
		$row = queryrow($sql);
		
		if ($row) {
			//Ya conocemos esta referencia, luego le corresponde este prodbase
			$this->set("IdProdBase",$row["IdProdBase"],FORCE);
			error(0,"Info: prodbase fue " . $row["IdProdBase"] );			
			$existeIdioma = true;
		} else 	{
			//No conocemos esta referencia, luego es un nuevo prodbase		
			$sql = "SELECT Max(IdProdBase) as IdProdBase FROM ges_productos";
			$row = queryrow($sql);
			if ($row){
				$IdProdBase = intval($row["IdProdBase"]) + 1;	
			} else {
				error (__FILE__ . __LINE__ , "E: $sql no saco idprodbase adecuado");
				return false;	
			} 
			error(0,"Info: prodbase sera " . $IdProdBase );			
			$this->set("IdProdBase",$IdProdBase,FORCE);
			$existeIdioma = false;
		}
	
		

		$sql = CreaInsercion($this->ges_productos,$this->export(),"ges_productos");
		
		$res = query($sql,"alta producto");
		$IdProducto = $UltimaInsercion;
		$this->setId($IdProducto);
		
		if (!$res) {
			$this->Error(__FILE__ . __LINE__ ,"E: no pudo insertar el producto");
			return false;
		}		
					
		if (!$existeIdioma) {
			//Solo creamos idioma cuando es primera vez para este prodbase
			$sql = CreaInsercion($this->ges_productos_idioma,$this->export(),"ges_productos_idioma");
			$res = query($sql,"alta producto idioma");
			if (!$res) {
				$this->Error(__FILE__ . __LINE__ ,"E: no pudo insertar el producto");
				return false;
			}		
		}
		return true;		 						 	
	}		
		
	function Clon(){
		global $UltimaInsercion;

		$this->Init();		
		
		if (!$this->AutoIntegridadClon()){
			return false;
		}				
		
		$sql = CreaInsercion($this->ges_productos,$this->export(),"ges_productos");
		$res = query($sql,"clon producto");
		$IdProducto = $UltimaInsercion;
		$this->setId($IdProducto);
		
		if (!$res) {
			$this->Error(__FILE__ . __LINE__ ,"E: no pudo insertar el producto");
			return false;
		}		
		
		return true;		 						 	
	}		
		
	function setFallo($fallo=false){
		$this->_fallodeintegridad = $fallo;
	}	
	
	function getFallo(){
		return $this->_fallodeintegridad;
	}			


	function Modificacion(){

		$this->Init();						
		
		$sql = CreaUpdate($this->ges_productos,$this->export(),"ges_productos","IdProducto",$this->getId());
		$res = query($sql);
		
		if (!$res) {
			$this->Error(__FILE__ . __LINE__, "E: no pudo modificar producto");
			return false;	
		}				
		
		$sql = CreaUpdate($this->ges_productos_idioma,$this->export(),"ges_productos_idioma","IdProdIdioma",$this->get("IdProdIdioma"));
		$res = query($sql);
		if (!$res){
			$this->Error(__FILE__ . __LINE__, "E: no pudo modificar producto, datos idioma");
			return false;	
		}		

		return true;		
	}		
				
	function setNombre($nombre){
		$this->set("Nombre",$nombre,FORCE);						
	}	

	function setReferencia($ref){
		$this->set("Referencia",$ref, FORCE);						
	}	
	
	function setDescripcion($Descripcion){
		$this->set("Descripcion",$Descripcion,FORCE);									
	}
	
	function EliminarProducto(){
		$id = $this->getId();
		
		$sql = "UPDATE ges_productos SET Eliminado = 1 WHERE IdProducto = '$id'";
		$res = query($sql);
		if (!$res)
			error(__FILE__ . __LINE__ , "W: no pudo borrar registro");
			
		$idbase = $this->get("IdProdBase"); 
					
		$sql = "SELECT IdProducto FROM ges_productos WHERE (IdProdBase='$idbase') AND Eliminado=0";
		$row = queryrow($sql);
		
		$existe = false;
		if ($row)
			$existe = $row["IdProducto"];
		
		if (!$existe) {
			//Ya no quedan prodictos para este prodbase				
			$sql = "UPDATE ges_productos_idioma SET Eliminado = 1 WHERE IdProdBase = '$id'";
			$res = query($sql);
			if (!$res)
				error(__FILE__ . __LINE__ , "W: no pudo borrar registro en idioma");
		}
				
		if ( $this->gestionAlmacenes ){				
			$sql = "UPDATE ges_almacenes SET Eliminado = 1 WHERE IdProducto = '$id'";
			$res = query($sql);
			if (!$res)
				error(__FILE__ . __LINE__ , "W: no pudo borrar registros de almacen");
		}			
									
	}	
		
	function setPrecioVenta($value){
		$this->set("PrecioVenta",$value,FORCE);	
	}
				
	function setPrecioOnline($value){
		$this->set("PrecioOnline",$value,FORCE);	
	}

	function getSubFamiliaAleatoria($IdFamilia){

		$sql = "SELECT IdSubFamilia as IdCosa FROM ges_subfamilias WHERE IdFamilia='$IdFamilia' AND Eliminado=0";
		$row = queryrow($sql);
		if (!$row) return 0;
		return $row["IdCosa"];
	}

}

/* CLASE */

?>
