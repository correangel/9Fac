

var moduloAjax ="modusuarios.php";



var xlistadoUsuarios = id("listadoUsuarios");
var xnombreUsuario = id("nombreUsuario");
var ajaxCargaUsuario = new XMLHttpRequest();

function genRandomID() {
	return "R"+Math.random()*90000;	
}

//Agnade usuario en el interface
function xAddUser(IdUsuario, vnombreUsuarioAlta) {
	var xitem = document.createElement("listitem");
	var rid = "user_" + IdUsuario;
		
	xitem.setAttribute("label",vnombreUsuarioAlta);	
	xitem.setAttribute("id",rid);	
	xitem.setAttribute("onclick","EditarUser("+IdUsuario+", this)");	
	
	xitem.setAttribute("image","img/cliente16.png");
	xitem.setAttribute("class","listitem-iconic");
	
	
	xlistadoUsuarios.appendChild(xitem);
}

function xDelUser(IdUsuario){
	if (!IdUsuario)
	if (!IdUsuario)
		return;
	var rid = "user_" + IdUsuario;	
	var xus = id(rid);
	
	if (xus){
		xlistadoUsuarios.removeChild(xus);	
		editandoIdUsuario = 0;
	} else {
		//alert("no existe "+rid);
	}
}



//Intenta una alta de usuario, y si tiene exito, lo da de alta en las X.
function AddNewUser(){
	var vnombreUsuarioAlta = id("nombreUsuarioAlta").value;	
	
	if ( vnombreUsuarioAlta.length < 1)
		return alert("Nombre demasiado corto!");		
			
	//Construimos envio de datos
	
	var url = moduloAjax;
	var data = "&modo=alta&nombre=" + encodeURIComponent(vnombreUsuarioAlta);
			
	var ajax = new XMLHttpRequest();
	ajax.open("POST",url,false);
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded; charset=UTF-8');
	ajax.send(data);
	resultado = ajax.responseText.split("=");
	if (resultado[0] != "OK"){
		return alert("Datos no validos, pruebe otro nombre");
	}	
	
//	alert( resultado[1]+vnombreUsuarioAlta);
	xAddUser(resultado[1],vnombreUsuarioAlta);
	
	id("nombreUsuarioAlta").value  = "";
	
	Blink("CreandoNuevo","groupbox");
}

var editandoIdUsuario = 0;

function EditarUser(IdUsuario, xsender){
	//alert(IdUsuario+ sender.label);
	var xnombreUser = id("Nombre");
	if (!xnombreUser)
		return alert("error: es necesario recargar la pagina");
	
	//xnombreUser.setAttribute("value",xsender.label);
	xnombreUser.value = xsender.getAttribute("label");
	editandoIdUsuario = IdUsuario;
	AjaxCargarUsuario(IdUsuario);
	//alert(IdUsuario);
	Blink("ModificandoUsuario","groupbox");	
}

function AjaxCargarUsuario(IdUsuario){

	
	var url = moduloAjax + "?&modo=JSONCargarUsuario&IdUsuario="+IdUsuario + "&r="+Math.random();
				
	ajaxCargaUsuario.open("GET",url,true);
	ajaxCargaUsuario.onreadystatechange = CargarUsuario;
	ajaxCargaUsuario.send(null)
	
}

function preparaData( datos ){	
	var out;
	for(var t=0;t< datos.length;t=t+2){
		out = out + "&" +datos[t]+"="+encodeURIComponent(datos[t+1]);
	}		
	return out;
}

function CargarUsuario(){
	ocupado = 0;
	if (ajaxCargaUsuario.readyState==4) {
		var rawtext = ajaxCargaUsuario.responseText;	
		if (rawtext=="error")		return;				
		InterpretarDatosUsuario(rawtext);				
	}
}

function setDatoURL(nombreDato,obj,filtro){
	var campo = id(nombreDato)	
	if (campo){
		var dato = obj[nombreDato];
		//HACK eliminado datos inexistentes mediante un filtro
		if (dato && dato!="false" && dato!="FALSE")
			dato = obj[nombreDato];
		else
			dato = "";
	
		if (filtro){			
			dato = filtro(dato); 			
		}		
	
		campo.setAttribute("value", dato);
		campo.value = dato;	
	} else {
		alert("no campo!"+nombreDato);
	}
}



function InterpretarDatosUsuario(rawtext){
	if (!rawtext)	return;
	
	var obj = eval( "(" + rawtext+ ")" );	
	//prompt("test",obj.toSource());

	//Identificacion,Password,Nombre,Direccion,Localidad,Telefono,FechaNacim
	
	//setDatoURL("Password",obj);
	setDatoURL("Nombre",obj);
	setDatoURL("Identificacion",obj);
	setDatoURL("Password",obj);
	//setDatoURL("NumeroFiscal",obj);	
	setDatoURL("Direccion",obj);	
	//setDatoURL("CP",obj);
	//setDatoURL("Localidad",obj);
	//setDatoURL("CuentaBancaria",obj);	
	setDatoURL("Telefono",obj);
	//setDatoURL("Telefono2",obj);
	setDatoURL("FechaNacim",obj,FiltraFecha);
	//setDatoURL("PaginaWeb",obj);
	//setDatoURL("FechaNacim",obj);
	//setDatoURL("Cargo",obj);
	//setDatoURL("Contacto",obj);
	//setDatoURL("Comentarios",obj);
	//setDatoURL("IdPerfil",obj);
	setDatoURL("CuentaBanco",obj);

}

function FiltraFecha(fechaSQL){
	if (!fechaSQL) return "00-00-0000";
	
	var partes = fechaSQL.split("-");
	
	return partes[2] + "-"+partes[1]+"-"+partes[0];	
}




function BorrarUser(){
	if (!editandoIdUsuario)
		return;	

	if (!confirm("¿Esta seguro de que quiere borrar este usuario?")){
		return;
	}

	//confirm("HOLA MUNDO!");

	var url= moduloAjax + "?modo=borrar&id=" +editandoIdUsuario;
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
		
	xDelUser( editandoIdUsuario );
	
	LimpiarFormulario();
	Blink("ModificandoUsuario","groupbox");	
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



//Intenta una alta de usuario, y si tiene exito, lo da de alta en las X.
function UpdateUser(){

	xnombreUsuario = id("Nombre");
	var vnombreUsuario = xnombreUsuario.value;
	if ( vnombreUsuario.length < 1)
		return alert("Nombre demasiado corto!");	

	//var esActivo = 0; if ( id("activo").checked ) esActivo = 1;		
	//var esAdmin = 0; if ( id("administrador").checked ) esAdmin = 1;				

	//Construimos envio de datos
	var data = "&";
	var cr = "&";	
	
	//alert(editandoIdUsuario);
	
	//Identificacion,Password,Nombre,Direccion,Localidad,Telefono,FechaNacim
	
	data =  data + "IdUsuario=" + editandoIdUsuario  +cr;		
	data =  data + "Identificacion=" + getDatoURL("Identificacion") + cr;
	data =  data + "Nombre=" + getDatoURL("Nombre") + cr;       
	data =  data + "Password=" + getDatoURL("Password") + cr;
	data =  data + "Direccion=" + getDatoURL("Direccion") + cr;    
	//data =  data + "Localidad=" + getDatoURL("Localidad") + cr;    	
//	data =  data + "Poblacion=" + encodeURIComponent(id("Poblacion").value) + cr;
//	data =  data + "CP=" + getDatoURL("CP") + cr;    
	data =  data + "Telefono=" + getDatoURL("Telefono") + cr;    		
//	data =  data + "Telefono2=" + encodeURIComponent(id("Telefono2").value) + cr;
		//data =  data + "Cargo=" + encodeURIComponent(id("Cargo").value) + cr;
	data =  data + "CuentaBanco=" + getDatoURL("CuentaBanco") + cr;    
//	data =  data + "NumeroFiscal=" + getDatoURL("NumeroFiscal") + cr;    
//	data =  data + "Comentarios=" + getDatoURL("Comentarios") + cr;    
		//data =  data + "TipoCliente=" + encodeURIComponent(id("TipoCliente").value) + cr;
//	data =  data + "IdModPagoHabitual=" + encodeURIComponent(id("IdModPagoHabitual").value) + cr;
//	data =  data + "Pais=" + getDatoURL("Pais") + cr;    
//	data =  data + "PaginaWeb=" + getDatoURL("PaginaWeb") + cr;    
	data =  data + "FechaNacim=" + getDatoURL("FechaNacim") + cr;    							
//	data =  data + "Cargo=" + getDatoURL("Cargo") + cr;    
//	data =  data + "Contacto=" + getDatoURL("Contacto") + cr;    							

		
	var url = moduloAjax;
	data += "modo=modificarusuario";

	var ajax = new XMLHttpRequest();
	ajax.open("POST",url,false);
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded; charset=UTF-8');
	ajax.send(data);
	
	resultado = ajax.responseText.split("=");
	if (resultado[0] != "OK"){	
		return alert("Datos no validos, pruebe otro nombre."+resultado[0]);
	}

		
	var usuarioNombreEnListado = id("user_"+ editandoIdUsuario);
	if (usuarioNombreEnListado){
		oldnombre	 = usuarioNombreEnListado.label;
		usuarioNombreEnListado.setAttribute("label",vnombreUsuario);
	}	
	
	Blink("ModificandoUsuario","groupbox");
}


function LimpiarFormulario(){
	id("nombreUsuario").value 	= "";
	id("usuario").value 		= "";
	id("pass").value 			= "";
	id("activo").setAttribute("checked","false");	
	id("administrador").setAttribute("checked","false");	
}

