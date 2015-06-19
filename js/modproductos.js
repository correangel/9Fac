

var moduloAjax ="modproductos.php";

var esAlta = true;

var Producto = new Object();
Producto.IdProducto = 0;


function ClearCampos(){
	reset("Referencia");
	reset("Nombre");
	reset("RefProvHab");	
	reset("Descripcion");
	reset("CosteSinIVA");
	reset("PrecioVenta");	
}

	
/*----------Busca producto----------------------*/
 
 
 
 function Accion_BorrarProducto(){
 
 	if (!confirm("�Esta seguro de que quiere borrar este perfil?")){
		return;
	}


	var url= moduloAjax + "?modo=borrar&id=" + Producto.IdProducto; 
	var ajax = new XMLHttpRequest();
	ajax.open("GET",url,false);
	ajax.send(null);
	
	if (!ajax.responseText){
		return alert("Intentelo mas tarde. Servidor ocupado");
	}
	
	resultado = ajax.responseText.split("=");
	
	if (resultado[0] != "OK"){	
		return alert("Datos no validos, no se pudo completar la operacion");
	}

 	BotonEsAlta();//tambien borra campos 
 }
 
 
function BotonEsModificar(){
	var xboton = $("boton-guardar");
	xboton.setAttribute("label","Guardar cambios");
	xboton.setAttribute("oncommand","Guardar_Modificacion()");	
	esAlta = false; 
}

function BotonEsAlta(){
	var xboton = $("boton-guardar");
	xboton.setAttribute("label","Alta");	
	xboton.setAttribute("oncommand","Guardar_Alta()");	
	esAlta = true; 
	ClearCampos();
} 
 
function Accion_BuscarProducto(){
	var fichero = "buscaproducto.popup.php";
	AbrirVentanaYBuscar(fichero);	
}

 
function UsarProducto(IdProducto){
	//var xcode = $("CodProducto");
	//xcode.value = IdProducto;	
	//AutoActualizar( xcode );
	AutoActualizar( IdProducto );	
	//alert(IdProducto);
	BotonEsModificar();	
} 



function AutoActualizar(id){
	
	var url = moduloAjax + "?modo=JSONCargar&id="+id;
				
	ajaxCarga.open("GET",url,true);
	ajaxCarga.onreadystatechange = Cargar;
	ajaxCarga.send(null)
	
}


function Cargar(){
	ocupado = 0;
	if (ajaxCarga.readyState==4) {
		var rawtext = ajaxCarga.responseText;	
		if (rawtext=="error")		return;				
		InterpretarDatos(rawtext);				
	}
}


function InterpretarDatos(rawtext){
	if (!rawtext)	return;
	
	var obj = eval( "(" + rawtext+ ")" );	

	//Referencia,Nombre,Descripcion,Coste,PrecioVenta		
	setDatoURL("Referencia",obj);
	setDatoURL("Nombre",obj);
	setDatoURL("RefProvHab",obj);	
	setDatoURL("Descripcion",obj);
	setDatoURL("CosteSinIVA",obj);
	setDatoURL("PrecioVenta",obj);	
	Producto.IdProducto = obj.IdProducto;

}
/*--------------------------------*/


/*--------------Alta/Modificacion------------------*/

function Guardar(){
	if (esAlta)
		Guardar_Alta();
	else
		Guardar_Modificacion();
}


function Guardar_Modificacion(){
	var data = "&";	
	var cr = "&";	
	
	//Referencia,Nombre,Descripcion,Coste,PrecioVenta		
	data =  data + "id=" + Producto.IdProducto + cr;
	data =  data + "Referencia=" + getDatoURL("Referencia") + cr;     
	data =  data + "RefProvHab=" + getDatoURL("RefProvHab") + cr;  
	data =  data + "Nombre=" + getDatoURL("Nombre") + cr;
	data =  data + "Descripcion=" + getDatoURL("Descripcion") + cr;
	data =  data + "CosteSinIVA=" + getDatoURL("CosteSinIVA") + cr;
	data =  data + "PrecioVenta=" + getDatoURL("PrecioVenta") + cr;
	
	var url = moduloAjax;
		
	data +=  "&modo=modificacion";
			
	var ajax = new XMLHttpRequest();
	ajax.open("POST",url,false);
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded; charset=UTF-8');
	ajax.send(data);
	
	resultado = ajax.responseText.split("=");
	
	//alert( "resultado:" + ajax.responseText );
	VerObj( ajax.responseText );	
	
	if (resultado[0] != "OK"){
		return alert("Modificaci�n: Datos no validos, pruebe otro nombre");
	}	

	//id("nombrePerfilAlta").value  = "";	
	Blink( "datosProducto","groupbox" );
}



//Intenta una alta de perfil, y si tiene exito, lo da de alta en las X.
function Guardar_Alta(){	
	var vnombreNombreAlta = id("Nombre").value;	
	
	if ( vnombreNombreAlta.length < 1)
		return alert("Nombre demasiado corto!");		
			
	var data = "&";
	var cr = "&";	

	//Construimos envio de datos
	var data = "&"; 
	
	//Referencia,Nombre,Descripcion,Coste,PrecioVenta			
	data =  data + "Referencia=" + getDatoURL("Referencia") + cr;       
	data =  data + "Nombre=" + getDatoURL("Nombre") + cr;
	data =  data + "RefProvHab=" + getDatoURL("RefProvHab") + cr;
	data =  data + "Descripcion=" + getDatoURL("Descripcion") + cr;
	data =  data + "CosteSinIVA=" + getDatoURL("CosteSinIVA") + cr;
	data =  data + "PrecioVenta=" + getDatoURL("PrecioVenta") + cr;
	
	var url = moduloAjax;	

	data += "&modo=alta";

	var ajax = new XMLHttpRequest();
	ajax.open("POST",url,false);
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded; charset=UTF-8');
	ajax.send(data);
	
	resultado = ajax.responseText.split("=");
	
	//alert( "resultado:" + ajax.responseText );
	//VerObj( ajax.responseText );	
	
	if (resultado[0] != "OK"){
		return alert("Datos no validos, pruebe otro nombre");
	}	else {
		alert("Datos guardados");
		ClearCampos();
	}

	//id("nombrePerfilAlta").value  = "";	
	Blink( "datosProducto","groupbox" );
}


function Codifica(nombre) {
	return encodeURIComponent(nombre);
}


function getDatoURL(nombre, getFilter) {	
	var obj = id(nombre);
	if(obj) {
		if (!getFilter){
			return Codifica(obj.value);
		}	else {
			return getFilter(obj);
		}
	}		
	return false;
}


/*--------------------------------*/
