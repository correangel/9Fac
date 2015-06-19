

var moduloAjax ="modperfiles.php";



var xlistadoPerfiles = id("listadoPerfiles");
var xnombrePerfil = id("nombrePerfil");
var ajaxCargaPerfil = new XMLHttpRequest();

function genRandomID() {
	return "R"+Math.random()*90000;	
}

//Agnade perfil en el interface
function xAddUser(IdPerfil, vnombrePerfilAlta) {
	var xitem = document.createElement("listitem");
	var rid = "user_" + IdPerfil;
		
	xitem.setAttribute("label",vnombrePerfilAlta);	
	xitem.setAttribute("id",rid);	
	xitem.setAttribute("onclick","EditarUser("+IdPerfil+", this)");	
	
	xitem.setAttribute("image","img/cliente16.png");
	xitem.setAttribute("class","listitem-iconic");
	
	
	xlistadoPerfiles.appendChild(xitem);
}

function xDelUser(IdPerfil){
	if (!IdPerfil)
	if (!IdPerfil)
		return;
	var rid = "user_" + IdPerfil;	
	var xus = id(rid);
	
	if (xus){
		xlistadoPerfiles.removeChild(xus);	
		editandoIdPerfil = 0;
	} else {
		//alert("no existe "+rid);
	}
}



//Intenta una alta de perfil, y si tiene exito, lo da de alta en las X.
function AddNewUser(){
	var vnombrePerfilAlta = id("nombrePerfilAlta").value;	
	
	if ( vnombrePerfilAlta.length < 1)
		return alert("Nombre demasiado corto!");		
			
	//Construimos envio de datos	
	var url = moduloAjax;
	var data = "&NombrePerfil=" + Codifica(vnombrePerfilAlta);
	data += "&modo=alta";
			
	var ajax = new XMLHttpRequest();
	ajax.open("POST",url,false);
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded; charset=UTF-8');
	ajax.send(data);
	resultado = ajax.responseText.split("=");
	if (resultado[0] != "OK"){
		return alert("Datos no validos, pruebe otro nombre");
	}	
	
//	alert( resultado[1]+vnombrePerfilAlta);
	xAddUser(resultado[1],vnombrePerfilAlta);
	
	id("nombrePerfilAlta").value  = "";
	
	Blink("CreandoNuevo","groupbox");
}

var editandoIdPerfil = 0;

function EditarUser(IdPerfil, xsender){
	//alert(IdPerfil+ sender.label);
	var xnombreUser = id("NombrePerfil");
	if (!xnombreUser)
		return alert("error: es necesario recargar la pagina");
	
	//xnombreUser.setAttribute("value",xsender.label);
	xnombreUser.value = xsender.getAttribute("label");
	editandoIdPerfil = IdPerfil;
	AjaxCargarPerfil(IdPerfil);
	//alert(IdPerfil);
	Blink("ModificandoPerfil","groupbox");	
}

function AjaxCargarPerfil(IdPerfil){

	
	var url = moduloAjax + "?modo=JSONCargarPerfil&IdPerfil="+IdPerfil;
				
	ajaxCargaPerfil.open("GET",url,true);
	ajaxCargaPerfil.onreadystatechange = CargarPerfil;
	ajaxCargaPerfil.send(null)
	
}

function preparaData( datos ){	
	var out;
	for(var t=0;t< datos.length;t=t+2){
		out = out + "&" +datos[t]+"="+encodeURIComponent(datos[t+1]);
	}		
	return out;
}

function CargarPerfil(){
	ocupado = 0;
	if (ajaxCargaPerfil.readyState==4) {
		var rawtext = ajaxCargaPerfil.responseText;	
		if (rawtext=="error")		return;				
		InterpretarDatosPerfil(rawtext);				
	}
}




function InterpretarDatosPerfil(rawtext){
	if (!rawtext)	return;
	
	var obj = eval( "(" + rawtext+ ")" );	
	//prompt("test",obj.toSource());

	//NombrePerfil,Administracion,Informes,Productos,Proveedores,Compras,Clientes
		
	setDatoURL("NombrePerfil",obj);
	setDatoURL("Administracion",obj,FiltraCheckbox);
	setDatoURL("Productos",obj,FiltraCheckbox);
	setDatoURL("Proveedores",obj,FiltraCheckbox);
	setDatoURL("Compras",obj,FiltraCheckbox);
	setDatoURL("Informes",obj,FiltraCheckbox);
	setDatoURL("Clientes",obj,FiltraCheckbox);
}


function BorrarUser(){
	if (!editandoIdPerfil)
		return;	

	if (!confirm("�Esta seguro de que quiere borrar este perfil?")){
		return;
	}

	//confirm("HOLA MUNDO!");

	var url= moduloAjax + "?modo=borrar&id=" +editandoIdPerfil; 
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
		
	xDelUser( editandoIdPerfil );
	
	LimpiarFormulario();
	Blink("ModificandoPerfil","groupbox");	
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

function getFilterCheckbox(checkbox){
	return (checkbox.checked?"1":"0");
}



//Intenta una alta de perfil, y si tiene exito, lo da de alta en las X.
function UpdateUser(){

	xnombrePerfil = id("NombrePerfil");
	var vnombrePerfil = xnombrePerfil.value;
	if ( vnombrePerfil.length < 1)
		return alert("Nombre demasiado corto!");	

	//var esActivo = 0; if ( id("activo").checked ) esActivo = 1;		
	//var esAdmin = 0; if ( id("administrador").checked ) esAdmin = 1;				

	//Construimos envio de datos
	var data = "&";
	var cr = "&";	
	
	//alert(editandoIdPerfil);
		
	//NombrePerfil,Administracion,Informes,Productos,Proveedores,Compras,Clientes
	data =  data + "IdPerfil=" + editandoIdPerfil  +cr;		
	//data =  data + "Identificacion=" + getDatoURL("Identificacion") + cr;
	data =  data + "NombrePerfil=" + getDatoURL("NombrePerfil") + cr;       
	data =  data + "Administracion=" + getDatoURL("Administracion",getFilterCheckbox) + cr;
	data =  data + "Informes=" + getDatoURL("Informes",getFilterCheckbox) + cr;
	data =  data + "Productos=" + getDatoURL("Productos",getFilterCheckbox) + cr;
	data =  data + "Administracion=" + getDatoURL("Administracion",getFilterCheckbox) + cr;
	data =  data + "Proveedores=" + getDatoURL("Proveedores",getFilterCheckbox) + cr;
	data =  data + "Compras=" + getDatoURL("Compras",getFilterCheckbox) + cr;
	data =  data + "Clientes=" + getDatoURL("Clientes",getFilterCheckbox) + cr;	
	   							

	data += "&modo=modificarperfil";
		
	var url = moduloAjax;
			
	var ajax = new XMLHttpRequest();
	ajax.open("POST",url,false);
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded; charset=UTF-8');
	ajax.send(data);
	
	resultado = ajax.responseText.split("=");
	if (resultado[0] != "OK"){	
		return alert("Datos no validos, pruebe otro nombre."+resultado[0]);
	}

		
	var perfilNombreEnListado = id("user_"+ editandoIdPerfil);
	if (perfilNombreEnListado){
		oldnombre	 = perfilNombreEnListado.label;
		perfilNombreEnListado.setAttribute("label",vnombrePerfil);
	}	
	
	Blink("ModificandoPerfil","groupbox");
}


function LimpiarFormulario(){
	id("NombrePerfil").value 	= "";
	id("perfil").value 			= "";
	id("pass").value 			= "";
	id("activo").setAttribute("checked","false");	
	id("administrador").setAttribute("checked","false");	
}

