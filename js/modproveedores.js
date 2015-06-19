

var moduloAjax ="modproveedores.php";



var xlistadoProveedores = id("listadoProveedores");
var xnombreProveedor = id("nombreProveedor");
var ajaxCargaProveedor = new XMLHttpRequest();

function genRandomID() {
	return "R"+Math.random()*90000;	
}

//Agnade proveedor en el interface
function xAddUser(IdProveedor, vnombreProveedorAlta) {
	var xitem = document.createElement("listitem");
	var rid = "user_" + IdProveedor;
		
	xitem.setAttribute("label",vnombreProveedorAlta);	
	xitem.setAttribute("id",rid);	
	xitem.setAttribute("onclick","EditarUser("+IdProveedor+", this)");	
	
	xitem.setAttribute("image","img/cliente16.png");
	xitem.setAttribute("class","listitem-iconic");
	
	
	xlistadoProveedores.appendChild(xitem);
}

function xDelUser(IdProveedor){
	if (!IdProveedor)
	if (!IdProveedor)
		return;
	var rid = "user_" + IdProveedor;	
	var xus = id(rid);
	
	if (xus){
		xlistadoProveedores.removeChild(xus);	
		editandoIdProveedor = 0;
	} else {
		//alert("no existe "+rid);
	}
}



//Intenta una alta de proveedor, y si tiene exito, lo da de alta en las X.
function AddNewUser(){
	var vnombreProveedorAlta = id("nombreProveedorAlta").value;	
	
	if ( vnombreProveedorAlta.length < 1)
		return alert("Nombre demasiado corto!");		
			
	//Construimos envio de datos
	var data = "&nombre=" + Codifica(vnombreProveedorAlta) + "&modo=alta";
	var url = moduloAjax;	
			
	var ajax = new XMLHttpRequest();
	ajax.open("POST",url,false);
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded; charset=UTF-8');
	ajax.send(data);
	resultado = ajax.responseText.split("=");
	if (resultado[0] != "OK"){
		return alert("Datos no validos, pruebe otro nombre"+resultado[0]);
	}	
	
//	alert( resultado[1]+vnombreProveedorAlta);
	xAddUser(resultado[1],vnombreProveedorAlta);
	
	id("nombreProveedorAlta").value  = "";
	
	Blink("CreandoNuevo","groupbox");
}

var editandoIdProveedor = 0;

function EditarUser(IdProveedor, xsender){
	//alert(IdProveedor+ sender.label);
	var xnombreUser = id("NombreComercial");
	if (!xnombreUser)
		return alert("error: es necesario recargar la pagina");
	
	//xnombreUser.setAttribute("value",xsender.label);
	xnombreUser.value = xsender.getAttribute("label");
	editandoIdProveedor = IdProveedor;
	AjaxCargarProveedor(IdProveedor);
	//alert(IdProveedor);
	Blink("ModificandoProveedor","groupbox");	
}

function AjaxCargarProveedor(IdProveedor){

	
	var url = moduloAjax + "?modo=JSONCargarProveedor&IdProveedor="+IdProveedor;
				
	ajaxCargaProveedor.open("GET",url,true);
	ajaxCargaProveedor.onreadystatechange = CargarProveedor;
	ajaxCargaProveedor.send(null)
	
}

function preparaData( datos ){	
	var out;
	for(var t=0;t< datos.length;t=t+2){
		out = out + "&" +datos[t]+"="+encodeURIComponent(datos[t+1]);
	}		
	return out;
}

function CargarProveedor(){
	ocupado = 0;
	if (ajaxCargaProveedor.readyState==4) {
		var rawtext = ajaxCargaProveedor.responseText;	
		if (rawtext=="error")		return;				
		InterpretarDatosProveedor(rawtext);				
	}
}

function setDatoURL(nombreDato,obj){
	var campo = id(nombreDato)	
	if (campo){
		var dato = obj[nombreDato];
		//HACK eliminado datos inexistentes mediante un filtro
		if (dato && dato!="false" && dato!="FALSE")
			dato = obj[nombreDato];
		else
			dato = "";
	
		campo.setAttribute("value", dato);
		campo.value = dato;	
	} else {
		alert("no campo!"+nombreDato);
	}
}



function InterpretarDatosProveedor(rawtext){
	if (!rawtext)	return;
	
	var obj = eval( "(" + rawtext+ ")" );	
	//prompt("test",obj.toSource());

	//setDatoURL("Password",obj);
	setDatoURL("NombreComercial",obj);
	setDatoURL("NombreLegal",obj);
	setDatoURL("NumeroFiscal",obj);	
	setDatoURL("Direccion",obj);	
	setDatoURL("CP",obj);
	setDatoURL("Localidad",obj);
	setDatoURL("CuentaBancaria",obj);	
	setDatoURL("Telefono1",obj);
	setDatoURL("Telefono2",obj);
	setDatoURL("PaginaWeb",obj);
	//setDatoURL("FechaNacim",obj);
	setDatoURL("Cargo",obj);
	setDatoURL("Contacto",obj);
	setDatoURL("Comentarios",obj);
	//setDatoURL("IdPerfil",obj);
	///setDatoURL("CuentaBanco",obj);

}

function BorrarUser(){
	if (!editandoIdProveedor)
		return;	

	if (!confirm("¿Esta seguro de que quiere borrar este proveedor?")){
		return;
	}

	//confirm("HOLA MUNDO!");

	var url= moduloAjax + "?modo=borrar&id=" +editandoIdProveedor; 
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
		
	xDelUser( editandoIdProveedor );
	
	LimpiarFormulario();
	Blink("ModificandoProveedor","groupbox");	
}

function Codifica(nombre) {
	return encodeURIComponent(nombre);
}

function getDatoURL(nombre) {	
	var obj = id(nombre);
	if(obj)
		return Codifica(obj.value);
		
	return false;
}



//Intenta una alta de proveedor, y si tiene exito, lo da de alta en las X.
function UpdateUser(){

	xnombreProveedor = id("NombreComercial");
	var vnombreProveedor = xnombreProveedor.value;
	if ( vnombreProveedor.length < 1)
		return alert("Nombre demasiado corto!");	

	//var esActivo = 0; if ( id("activo").checked ) esActivo = 1;		
	//var esAdmin = 0; if ( id("administrador").checked ) esAdmin = 1;				

	//Construimos envio de datos
	var data = "&";
	var cr = "&";	
	
	//alert(editandoIdProveedor);
	
	data =  data + "IdProveedor=" + editandoIdProveedor  +cr;		
	data =  data + "NombreComercial=" + getDatoURL("NombreComercial") + cr;       
	data =  data + "NombreLegal=" + getDatoURL("NombreLegal") + cr;
	data =  data + "Direccion=" + getDatoURL("Direccion") + cr;    
	data =  data + "Localidad=" + getDatoURL("Localidad") + cr;    	
//	data =  data + "Poblacion=" + encodeURIComponent(id("Poblacion").value) + cr;
	data =  data + "CP=" + getDatoURL("CP") + cr;    
	data =  data + "Telefono1=" + getDatoURL("Telefono1") + cr;    	
	data =  data + "Telefono2=" + encodeURIComponent(id("Telefono2").value) + cr;
		//data =  data + "Cargo=" + encodeURIComponent(id("Cargo").value) + cr;
	data =  data + "CuentaBancaria=" + getDatoURL("CuentaBancaria") + cr;    
	data =  data + "NumeroFiscal=" + getDatoURL("NumeroFiscal") + cr;    
	data =  data + "Comentarios=" + getDatoURL("Comentarios") + cr;    
		//data =  data + "TipoCliente=" + encodeURIComponent(id("TipoCliente").value) + cr;
//	data =  data + "IdModPagoHabitual=" + encodeURIComponent(id("IdModPagoHabitual").value) + cr;
	data =  data + "Pais=" + getDatoURL("Pais") + cr;    
	data =  data + "PaginaWeb=" + getDatoURL("PaginaWeb") + cr;    
	//data =  data + "FechaNacim=" + getDatoURL("FechaNacim") + cr;    							
	data =  data + "Cargo=" + getDatoURL("Cargo") + cr;    
	data =  data + "Contacto=" + getDatoURL("Contacto") + cr;    							

	data += "&modo=modificarproveedor";

	var url = moduloAjax;
			
	var ajax = new XMLHttpRequest();
	ajax.open("POST",url,false);
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded; charset=UTF-8');
	ajax.send(data);
	
	resultado = ajax.responseText.split("=");
	if (resultado[0] != "OK"){	
		return alert("Datos no validos, pruebe otro nombre"+resultado[0]);
	}

		
	var proveedorNombreEnListado = id("user_"+ editandoIdProveedor);
	if (proveedorNombreEnListado){
		oldnombre	 = proveedorNombreEnListado.label;
		proveedorNombreEnListado.setAttribute("label",vnombreProveedor);
	}	
	
	Blink("ModificandoProveedor","groupbox");
}


function LimpiarFormulario(){
	id("nombreProveedor").value 	= "";
	id("proveedor").value 		= "";
	id("pass").value 			= "";
	id("activo").setAttribute("checked","false");	
	id("administrador").setAttribute("checked","false");	
}

