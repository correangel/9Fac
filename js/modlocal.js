	
var moduloAjax ="modlocal.php";

var esAlta = true;

var Local = new Object();
Local.IdLocal = 1;



function CargarDatosLocal(valor){

	var idlocal = valor;
	
	if (!idlocal)
		idlocal = Local.IdLocal;

	var obj = new XMLHttpRequest();
	var url = moduloAjax;
	var data = "&modo=JSONCargarLocal&IdLocal=" + idlocal;
	obj.open("POST",url,false);
	obj.send(data);
	var text = obj.responseText;
	
	if (text=="ERROR"){
		//alert("ERRR:"+text);
		return false;
	}
	
	try {		
		var obj = eval( "(" + text+ ")" );
		//alert("VA:"+text);
	} catch(e) {	
		//alert("NOVA:"+text);
		return;//no se pudo actualizar
	}		
	
	//alert( "Cargando.."+obj.toString() );
	VerObj(obj);
	
	ActualizarDatosFactura(obj);
}

function ActualizarDatosFactura(obj){
	//NombreComercial,NombreLegal,NFiscal,DireccionFactura,
	//Telefono,Fax,Movil,Email,IdTipoNumeracionFactura,CuentaBancaria
	setDatoURL("NombreComercial",obj);	
	setDatoURL("NombreLegal",obj);
	setDatoURL("NFiscal",obj);
	setDatoURL("DireccionFactura",obj);
	setDatoURL("Telefono",obj);
	setDatoURL("Fax",obj);
	setDatoURL("Movil",obj);
	setDatoURL("Email",obj);
	//setDatoURL("IdTipoNumeracionFactura",obj);//TODO
	setDatoURL("CuentaBancaria",obj);
	alert("datos cargados del local");
}


function UpdateLocal(){


}