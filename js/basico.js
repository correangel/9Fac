

var ajaxCarga = new XMLHttpRequest();


function id(nombre) { return document.getElementById(nombre); }

function Aviso(){
	//alert("Su sesion ha caducado, salga y vuelva a entrar");	
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
			dato = filtro(dato,campo); 			
		}		
	
		campo.setAttribute("value", dato);
		campo.value = dato;	
	} else {
		alert("no campo!"+nombreDato);
	}
}



function FiltraCheckbox(dato,campo){
	if (dato=="1" && dato!="0"){
		campo.checked = true;
	} else {
		campo.checked = false;
	}
}


function FiltraFecha(fechaSQL){
	if (!fechaSQL) return "00-00-0000";
	
	var partes = fechaSQL.split("-");
	
	return partes[2] + "-"+partes[1]+"-"+partes[0];	
}


function AbrirVentanaYBuscar(fichero, forceAlto){ 
    var width = 400;
    var height = 600;
    if (forceAlto)
    	height = forceAlto;
    
    var left = parseInt((screen.availWidth/2) - (width/2));
    var top = parseInt((screen.availHeight/2) - (height/2));

    var windowFeatures = "dialog=yes,minimizable=no,chrome=yes,centerscreen=yes,dependent=yes,width=" + width + ",height=" + height + ",status,resizable,left=" + left + ",top=" + top + "screenX=" + left + ",screenY=" + top;
   
	var ventana = window.open(fichero,"Buscar",windowFeatures,"text/html");
	ventana.parent = window; 			
}





function setDatoURL2(nombreDato,nombreDB,obj){
	var campo = id(nombreDato)	
	if (campo){
		var dato = obj[nombreDB];
		//HACK eliminado datos inexistentes mediante un filtro
		if (dato && dato!="false" && dato!="FALSE")
			dato = obj[nombreDB];
		else
			dato = "";
	
		campo.setAttribute("value", dato);
		campo.value = dato;	
	} else {
		alert("no campo!"+nombreDato);
	}
}

function setDatoURL2_fecha(nombreDato,nombreDB,obj){
	var campo = id(nombreDato)
	if (campo){
		var dato = obj[nombreDB];
		//HACK eliminado datos inexistentes mediante un filtro
		if (dato && dato!="false" && dato!="FALSE")
			dato = FiltraFecha(obj[nombreDB]);
		else
			dato = "";

		campo.setAttribute("value", dato);
		campo.value = dato;
	} else {
		alert("no campo!"+nombreDato);
	}
}





//window.onerror = Aviso;

function FiltraEntero(elemento) {
	var res = parseInt(elemento.value);
	if (isNaN(res))
		elemento.value = "0";
	else
		elemento.value = res;

}

function VaciarElemento(element){
	while(element.hasChildNodes())
	  element.removeChild(element.firstChild);
}
  
  
function Eliminar(tpadre,thijo){

	var xpadre = id(tpadre);
	var xhijo = id(thijo);
	
	if (!xhijo) return;
	
	if (!xpadre.firstChild)
		return;
	
	xhijo.parentNode.removeChild(xhijo);
}





function reset(campo){
	var xitem = $(campo);	
	xitem.value = "";
	xitem.setAttribute("value","");
}


