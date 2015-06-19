
var moduloAjax ="modpresupuestos.php";

var esAlta = true;

var Presupuesto = new Object();
Presupuesto.IdPresupuesto = 0;


/*----------  Listado de productos que se compra ----------------------*/

function VaciarListaProductos(){
	var xcosa = $("listaProductos");
	alert(xcosa);
	xcosa.childNodes = false;
}


 document.gClonedListbox = false;

 function Startup() {
     document.gClonedListbox = $('listaProductos').cloneNode(true);

     $("datoFecha").value = Presupuesto.fecha;
     $("datoNumpresupuesto").value = Presupuesto.numeropresupuesto;
 }


function Rellena(){

     $("cxcv").setAttribute("label","fiasco");
     return;

     var oldListbox = $('listaProductos');

     xlistitem = document.createElement("listitem");
     xcell = document.createElement("listcell");
     xlistitem.appendChild(xcell);

     $('listaProductos').appendChild(xlistitem);
}

 function clearListbox() {
     var oldListbox = $('listaProductos');

     var newListbox = document.gClonedListbox.cloneNode(true);
     oldListbox.parentNode.replaceChild( newListbox,oldListbox);
 }


function cE(elemento){
	return document.createElement(elemento);
}

function Producto(){
  this.datos = new Array();
  this.set = function(name,valorCampo) {
    this.datos[name] = valorCampo;
  }
  this.get = function(name) {
  	return this.datos[name];
  }

  this.getDatosRelevantes = function (){
  	//datos que interesa enviar al servidor
  	return new Array("codigo","descripcion","unid","precio","dto","impuesto");
  }

}

var ListadoProductos = new Array();
var indiceProductos = 0;

function AddProductoLista(codigo,descripcion,unid,precio,dto,impuesto){
	var prod = new Producto();
	prod.set("codigo",codigo);
	prod.set("descripcion",descripcion);
	prod.set("unid",unid);
	prod.set("precio",precio);
	prod.set("dto",dto);
	prod.set("impuesto",impuesto);

	var currentIntex = indiceProductos;

	ListadoProductos[indiceProductos++] = prod;

	return currentIntex;
}

function ActualizarTotalesProductos(){

	var megaTotalConIva = 0;
	var megaTotalIva = 0;
	var totalFinal			= 0;
	var aportacionImpuesto	= 0;

	for( t=0;t<indiceProductos;t++){
		var prod = ListadoProductos[t];

		if (prod){
			try {
				totalFinal = CalculoTotalFinal(	prod.get("precio"),
											prod.get("unid"),
											prod.get("impuesto"),
											prod.get("dto")
											);
				aportacionImpuesto = prod.get("unid") * prod.get("precio") * (prod.get("impuesto")/100);
			} catch(e) {
				alert("prod: "+prod+",e:"+e);
			}
		}

		megaTotalIva += aportacionImpuesto;
		megaTotalConIva += totalFinal;
	}
	//baseimponible,importeiva,totalpresupuesto

	$("totalpresupuesto").value 	= formatoBonito(megaTotalConIva);
	$("totalpresupuesto").setAttribute("valor",megaTotalConIva);

	var xii = $("importeiva");
	if (!xii){
		return alert("xii no esta!");
	}

	$("importeiva").value 		= formatoBonito(megaTotalIva);
	$("importeiva").setAttribute("valor",megaTotalIva);

	var xbi = $("baseimponible");

	if (!xbi){
		return alert("xbi no esta!");
	}

	$("baseimponible").value 	= formatoBonito(megaTotalConIva - megaTotalIva);
	$("baseimponible").setAttribute("valor",megaTotalConIva - megaTotalIva);
}

function formatoBonito(num){
	return parseInt (num * 100)/100 ;
}



function CalculoTotalFinal( precio, unid, impuesto, dto){
	var totalBase = precio * unid;

	totalFinal = totalBase + totalBase * (impuesto/100);

	if (dto>0)
		totalFinal = totalFinal - totalFinal * (dto/100);

	return totalFinal;
}


function AddLista(){
	var codigo, descripcion, unid,precio,dto,impuesto;
	codigo = $("codigo").value;
	descripcion = $("descripcion").value;
	unid = $("unid").value;
	precio = $("precio").value;
	dto = $("dto").value;
	impuesto = $("impuesto").value;
	referencia = $("referencia").value;

	if (!unid) {
		alert("Debe especificar mas de una unidad");
		$("unid").focus();
		return ;
	}

	indice = AddProductoLista(codigo,descripcion,unid,precio,dto,impuesto);


	var totalFinal = CalculoTotalFinal(precio,unid,impuesto,dto);

	var xlist = cE("listitem");

	xlist.setAttribute("indice",indice);

	var xlc1 = cE("listcell");var xlc1_2 = cE("listcell");xlc1_2.setAttribute("label",codigo);
	xlc1.appendChild(xlc1_2);
	var xlc2 = cE("listcell");var xlc2_2 = cE("listcell");xlc2_2.setAttribute("label",referencia);
	xlc2.appendChild(xlc2_2);
	var xlc3 = cE("listcell");var xlc3_2 = cE("listcell");xlc3_2.setAttribute("label",descripcion);
	xlc3.appendChild(xlc3_2);
	var xlc4 = cE("listcell");var xlc4_2 = cE("listcell");xlc4_2.setAttribute("label",unid);
	xlc4.appendChild(xlc4_2);
	var xlc5 = cE("listcell");var xlc5_2 = cE("listcell");xlc5_2.setAttribute("label",precio);
	xlc5.appendChild(xlc5_2);
	var xlc6 = cE("listcell");var xlc6_2 = cE("listcell");xlc6_2.setAttribute("label",dto);//+ " %");
	xlc6.appendChild(xlc6_2);
	var xlc7 = cE("listcell");var xlc7_2 = cE("listcell");xlc7_2.setAttribute("label",impuesto);//+ " %");
	xlc7.appendChild(xlc7_2);
	var xlc8 = cE("listcell");var xlc8_2 = cE("listcell");xlc8_2.setAttribute("label",formatoBonito(totalFinal));
	xlc8.appendChild(xlc8_2);

	xlist.appendChild(xlc1);
	xlist.appendChild(xlc2);
	xlist.appendChild(xlc3);
	xlist.appendChild(xlc4);
	xlist.appendChild(xlc5);
	xlist.appendChild(xlc6);
	xlist.appendChild(xlc7);
	xlist.appendChild(xlc8);

	$("listaProductos").appendChild(xlist);


	ActualizarTotalesProductos();
	BorrarDatosProductoAgnadido();
}

function BorrarDatosProductoAgnadido(){
	$("codigo").value="";
	$("descripcion").value="";
	$("unid").value="";
	$("precio").value="";
	$("dto").value="";
	$("impuesto").value="16";
	$("referencia").value="";

	$("codigo").focus();
}


function BorrarLista(){
	ListadoProductos = new Array();
	indiceProductos = 0;
	clearListbox();
}


function BorrarTodo(){
	BorrarDatosProductoAgnadido();
	BorrarLista();
	BorrarDatosCliente();

}

function BorrarTodoIniciarSiguiente(){
	BorrarTodo();
	CargarSiguientesDatosDePresupuesto();
}


/*
function CancelarPresupuesto(){
	BorrarLista();
}*/

/*--------------------------------*/


/*-------------Datos de cliente-------------------*/

function AutoActualizarCliente( elementoIdCliente ){

    var idcliente = elementoIdCliente.value;

	var obj = new XMLHttpRequest();
	var url = moduloAjax;
	var data = "&modo=JSONCargarCliente&IdCliente=" + encodeURIComponent(idcliente);
	obj.open("POST",url,false);
	obj.setRequestHeader('Content-Type','application/x-www-form-urlencoded; charset=UTF-8');
	obj.send(data);
	var text = obj.responseText;

	if (text=="ERROR"){
		return false;
	}

	var obj = eval( "(" + text+ ")" );

	ActualizarDatosCliente(obj);
}

function ActualizarDatosCliente(obj){
	Presupuesto.IdCliente = obj["IdCliente"];

	$("NombreComercial").value = obj["NombreComercial"];
	$("Direccion").value = obj["Direccion"];
	$("CodigoPostal").value = obj["CP"];
	$("Poblacion").value = obj["Localidad"];
	$("CIF").value = obj["NumeroFiscal"];
}


function BorrarDatosCliente(){
	Presupuesto.IdCliente = 0;

	$("NombreComercial").value = "";
	$("Direccion").value = "";
	$("CodigoPostal").value = "";
	$("Poblacion").value = "";
	$("CIF").value = "";

	$("obraRealizada").value = "";//NOTA: no tiene sentido aqui
}

/*--------------------------------*/

/*----------Busca cliente----------------------*/

function Accion_BuscarCliente(){
	var fichero = "buscacliente.popup.php";
	AbrirVentanaYBuscar(fichero);
}

function UsarCliente(IdCliente){
	var xcode = $("CodCliente");
	xcode.value = IdCliente;
	AutoActualizarCliente( xcode );
}
/*--------------------------------*/

/*-----------Busca producto---------------------*/

function Accion_BuscarProducto(){
	AbrirVentanaYBuscar("buscaproducto.popup.php");
}

function UsarProducto(IdProducto){
	var xcode = $("codigo");
	xcode.value = IdProducto;
	AutoActualizarProducto( xcode );
}


function AutoActualizarProducto ( elementoIdProducto ){

	var IdProducto = elementoIdProducto.value;

	var obj = new XMLHttpRequest();
	var url = moduloAjax;
	var data = "&modo=JSONCargarProducto&IdProducto=" + encodeURIComponent(IdProducto);
	obj.open("POST",url,false);
	obj.setRequestHeader('Content-Type','application/x-www-form-urlencoded; charset=UTF-8');
	obj.send(data);
	var text = obj.responseText;

	if (text=="ERROR"){
		return false;
	}

	try {
		var obj = eval( "(" + text+ ")" );
	} catch(e) {
		return;//no se pudo actualizar
	}

	ActualizarDatosProducto(obj);
}


function ActualizarDatosProducto(obj){


	$("codigo").value = obj["IdProducto"];
	$("referencia").value = obj["Referencia"];
	$("unid").value = 1;
	$("precio").value = obj["CosteSinIVA"];//mal nombre.. puede ser con el iva incluido
	$("descripcion").value = obj["Nombre"];
	$("dto").value = 0;
	$("impuesto").value = obj["Impuesto"];

	//VerObj(obj);

}

/*--------------------------------*/

 //MISC


 function habilitarEdicion(elementoDisabilitado){

 	var el = $(elementoDisabilitado);

 	if (!el) return;

 	el.setAttribute("disabled","false");//inefectivo.. pero a�adido
 		// .. por si acaso nace un bug de firefox y se queda el css erroneo
 	el.removeAttribute("disabled");

 	el.focus();
 }

function deshabilitarEdicion(elementoDisabilitado){
 	var el = $(elementoDisabilitado);
 	if (!el) return;
 	el.setAttribute("disabled","true");
 }

 //habilitarEdicionGrupoDatosCliente
 var camposCliente = new Array("NombreComercial", "Direccion", "Poblacion", "CIF",
 	"CodigoPostal");

 function habilitarEdicionGrupoDatosCliente(){
	habilitarGrupo( camposCliente );
 }

 function habilitarGrupo( campos ){
 	for (x in campos){
 		habilitarEdicion(campos[x]);
	}
 }

 function deshabilitarGrupo( campos) {
	for (x in campos){
 		deshabilitarEdicion(campos[x]);
	}

 }



/*--------------------------------*/

function CancelarPresupuesto(){
	document.location = moduloAjax + "?r="+Math.random(); //fuerza la recarga
}


/*--------------------------------*/

var cadenaEnvio = "";

function EnviarPresupuesto(){
	//vamos a construir cadena
	cadenaEnvio	= "";

	var baseprod = new Producto();
	var datosProducto = baseprod.getDatosRelevantes();

	cadenaEnvio += urlVar("numProductos",indiceProductos);

	for( t=0;t<indiceProductos;t++){
		var prod = ListadoProductos[t];

		if ( prod ) {
			var firma = "prod_"+t+"_";
			for (x in datosProducto){
				var nombreDato = datosProducto[x];
				var firmaconcreta = firma + "_" + nombreDato;
				cadenaEnvio += urlVar( firmaconcreta, prod.get(nombreDato) );
			}
		}
	}

	for (x in camposCliente){
 		var nombreCampo = camposCliente[x];

 		var valorCampo = $(nombreCampo).value;

 		cadenaEnvio += urlVar(nombreCampo,valorCampo);
	}

	//totalpresupuesto,importeiva,baseimponible
	var totalPresupuesto = $("totalpresupuesto").getAttribute("valor");
		//si, 'valor' ahi se almacen el valor sin formateado bonito que daria
		// problemas en su conversion a numero
	var importeIva = $("importeiva").getAttribute("valor");
	var baseimponible = $("baseimponible").getAttribute("valor");

	cadenaEnvio += urlVar("totalpresupuesto",totalPresupuesto);
	cadenaEnvio += urlVar("importeiva",importeIva);
	cadenaEnvio += urlVar("baseimponible",baseimponible);

	cadenaEnvio += urlVar("obrarealizada",$("obraRealizada").value);

	cadenaEnvio += urlVar("SeriePresupuesto",Presupuesto.serie);
	cadenaEnvio += urlVar("NPresupuesto",Presupuesto.numero);

	cadenaEnvio += urlVar("IdCliente",Presupuesto.IdCliente);



	//datoNumpresupuesto, datoFecha
	var valorCampo = $("datoNumpresupuesto").value;
	cadenaEnvio += urlVar("SerieNumeroPresupuesto",valorCampo);

	var valorCampo = $("datoFecha").value;
	cadenaEnvio += urlVar("FechaPresupuesto",valorCampo);

	var obj = new XMLHttpRequest();
	var url = moduloAjax;

	cadenaEnvio +="&modo=EnviarPresupuesto";

	obj.open("POST",url,false);
	obj.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	obj.send(cadenaEnvio);


	InterpretarDatosCreacionPresupuesto(obj.responseText);

}

/* ------------------------------------ */

function InterpretarDatosCreacionPresupuesto(rawtext){

     if (!rawtext)	return;

	try {
		obj = eval( "(" + rawtext+ ")" );

		alert("Presupuesto creado con exito");

		if (confirm("¿Crear otra?")){
			BorrarTodoIniciarSiguiente();
		} else {
			//Despues de haberla creado, la carga en modo edición
			UsarPresupuesto( obj["IdPresupuesto"] );
		}
	} catch(e){
		return alert("Error de servidor, por favor intentelo mas tarde." + rawtext);
	}


}






function urlVar( variable, valor){
	return "&" + variable + "=" + encodeURIComponent(valor);//el CR es para debug
}



/*--------------------------------*/

function quitarElementoPresupuesto(elemento){



	lista = $("listaProductos");

	var  iitem = lista.selectedCount;

	if (!iitem){
		alert("selecciona alguno!");
	}

	var item = lista.selectedItem;

	//alert( item.getAttribute("indice") );

	//alert( item.getAttribute("indice") );

	var prod = ListadoProductos[indice];

	//VerObj( prod.datos );

	ListadoProductos[indice] = false;

	lista.removeChild( lista.selectedItem ) ;
}

/*--------------------------------*/

function AbrirVentanaNumpresupuesto(){
	var fichero = "modificarserie.popup.php?" +
		"&serie=" + encodeURIComponent(Presupuesto.serie) +
		"&numero=" + encodeURIComponent(Presupuesto.numero);

	AbrirVentanaYBuscar(fichero,300);
}


function UsarFormatoNumpresupuesto(DescripcionFormato, serie, numero){
	//datoNumpresupuesto
	var xcode = $("datoNumpresupuesto");
	xcode.value = DescripcionFormato;

	Presupuesto.serie = serie;
	Presupuesto.numero = numero;
	Presupuesto.numeropresupuesto = DescripcionFormato;
}


/*--------------------------------*/

 function Accion_BuscarPresupuesto(){

	var fichero = "buscapresupuesto.popup.php?";

	AbrirVentanaYBuscar(fichero,600);
 }

 /*--------------------------------*/

 function Accion_ModoAlta(){
 	BotonEsAlta();
 }

 function BotonEsAlta(){
 	esAlta = true;
 	LimpiarCamposPresupuesto();

	$("cajaModoModificacion").setAttribute("collapsed","true");
	$("cajaModoAlta").setAttribute("collapsed","false");

 	LimpiarCamposPresupuesto();
	BorrarLista();//Borra carrito invisible de la compra

	CargarSiguientesDatosDePresupuesto();
 }

 function LimpiarCamposPresupuesto(){

 	var campos = new String("baseimponible,importeiva,totalpresupuesto,CodCliente,codigo,referencia,descripcion,unid,precio,dto,impuesto,NombreComercial,Direccion,Poblacion,CIF,CodigoPostal,datoNumpresupuesto,datoFecha,obraRealizada");
	var resetear = campos.split(",");

	for(x in resetear){
		reset(resetear[x]);
	}
 	clearListbox();
 }

 /*--------------------------------*/



function CargarSiguientesDatosDePresupuesto(){
	var url = moduloAjax;
	var data = "&modo=IniciarSiguientePresupuesto";

	ajaxCarga.open("POST",url,true);
	ajaxCarga.onreadystatechange = IniciarSiguietePresupuesto;
	ajaxCarga.setRequestHeader('Content-Type','application/x-www-form-urlencoded; charset=UTF-8');
	ajaxCarga.send(data)
}

function IniciarSiguietePresupuesto(){
	ocupado = 0;
	if (ajaxCarga.readyState==4) {
		var rawtext = ajaxCarga.responseText;
		if (rawtext=="ERROR")		return;

		InterpretarDatosSiguientePresupuesto(rawtext);
	}
}

function InterpretarDatosSiguientePresupuesto(rawtext){
	var obj;
	if (!rawtext)	return;

	try {
		obj = eval( "(" + rawtext+ ")" );
	} catch(e){
		return alert("Error de servidor, por favor intentelo mas tarde." + rawtext);
	}

	var hoy = new Date();

	var fecha = (hoy.getDate()) +"-"+(hoy.getMonth()+1) + "-"+hoy.getFullYear();
	$("datoFecha").value = fecha;

	setDatoURL2("datoNumpresupuesto","expresionNumeroPresupuesto",obj);

	//alert( obj.toSource() );


	Presupuesto.fecha = fecha;
	Presupuesto.numeropresupuesto = obj["expresionNumeroPresupuesto"];
	Presupuesto.numero = obj["NPresupuesto"];
	Presupuesto.serie = obj["Serie"];
}


 //Solicitud para cargar una presupuesto en concreto
 function UsarPresupuesto(IdPresupuesto){

	//Modo modificacion
	$("cajaModoModificacion").setAttribute("collapsed","false");
	$("cajaModoAlta").setAttribute("collapsed","true");

 	AutoActualizarPresupuesto( IdPresupuesto );
 }


function AutoActualizarPresupuesto(id){

	var url = moduloAjax + "?modo=JSONCargarPresupuesto&id="+id;

	ajaxCarga.open("GET",url,true);
	ajaxCarga.onreadystatechange = CargarPresupuesto;
	ajaxCarga.send(null)

}


function CargarPresupuesto(){
	ocupado = 0;
	if (ajaxCarga.readyState==4) {
		var rawtext = ajaxCarga.responseText;
		if (rawtext=="error")		return;

		InterpretarDatosPresupuesto(rawtext);
	}
}


function InterpretarDatosPresupuesto(rawtext){
	if (!rawtext)	return;

	var obj = eval( "(" + rawtext+ ")" );

	clearListbox();
	BorrarTodo();//borra historias en el interface
	BorrarLista();//Borra carrito invisible de la compra

	setDatoURL2("baseimponible","ImporteNeto",obj);
	setDatoURL2("importeiva","IvaImporte",obj);
	setDatoURL2("totalpresupuesto","TotalImporte",obj);

	setDatoURL2("CodCliente","IdCliente",obj);
	setDatoURL2("codigo","IdCliente",obj);

	setDatoURL("NombreComercial",obj);
	setDatoURL("Direccion",obj);
	setDatoURL("Poblacion",obj);
	setDatoURL("CIF",obj);
	setDatoURL("CodigoPostal",obj);

	setDatoURL2("datoNumpresupuesto","NPresupuesto",obj);
	setDatoURL2_fecha("datoFecha","FechaPresupuesto",obj);

	setDatoURL2("obraRealizada","ObraRealizada",obj);

	Presupuesto.IdPresupuesto = obj.IdPresupuesto;

	//TODO: cargar lineas de presupuesto
	AutoActualizarLineasPresupuesto( obj.IdPresupuesto );
}

function AutoActualizarLineasPresupuesto(id){

	var url = moduloAjax + "?modo=JSONCargarLineasPresupuesto&id="+id;

	ajaxCarga.open("GET",url,true);
	ajaxCarga.onreadystatechange = CargarLineasPresupuesto;
	ajaxCarga.send(null)

}



function CargarLineasPresupuesto(){
	ocupado = 0;
	if (ajaxCarga.readyState==4) {
		var rawtext = ajaxCarga.responseText;
		if (rawtext=="ERROR") {
			alert("Ha ocurrido un error en el servidor, y no se ha podido completar la operación");
			return;
		}

		InterpretarLineasPresupuesto(rawtext);
	}
}

function InterpretarLineasPresupuesto(rawtext){

	function ActualizaCamposProductoDesdeLineaPresupuesto(obj){
		$("codigo").value = obj["IdProducto"];
		$("referencia").value = obj["Referencia"];
		$("unid").value = obj["Cantidad"];
		$("precio").value = obj["Precio"];//mal nombre.. puede ser con el iva incluido
		$("descripcion").value = obj["Concepto"];
		$("dto").value = obj["Descuento"];
		$("impuesto").value = obj["Iva"];
	}

	if (!rawtext)	return;

	var obj = eval( "(" + rawtext+ ")" );

	for( t in obj){
		//Populamos los campos
		ActualizaCamposProductoDesdeLineaPresupuesto(obj[t]);
		//hacemos la entrada usando esos datos, como si hubieran sido tipeados tal cual
		AddLista();
	}


}





function AbrirVentanaImprimir(fichero, forceAlto){
    var width = 1000;
    var height = 700;
    if (forceAlto)
    	height = forceAlto;

    var left = parseInt((screen.availWidth/2) - (width/2));
    var top = parseInt((screen.availHeight/2) - (height/2));

    var windowFeatures = "dialog=yes,minimizable=no,chrome=yes,centerscreen=yes,dependent=yes,width=" + width + ",height=" + height + ",status,resizable,left=" + left + ",top=" + top + "screenX=" + left + ",screenY=" + top;

	var ventana = window.open(fichero,"Documento",windowFeatures,"text/html");
	ventana.parent = window;
}


function VentanaImprimirPresupuesto(){
   //   VerObj(Presupuesto);
        AbrirVentanaImprimir("moddocumento.php?modo=presupuesto&id="+Presupuesto.IdPresupuesto +"&id=" + Presupuesto.IdPresupuesto);
}


	
/*------------------------------------------*/


function GuardarCambios(){
	//vamos a construir cadena
	var cadenaEnvio	= "";
	
	var baseprod = new Producto();
	var datosProducto = baseprod.getDatosRelevantes();

	cadenaEnvio += urlVar("numProductos",indiceProductos);

	for( t=0;t<indiceProductos;t++){
		var prod = ListadoProductos[t];

		if ( prod ) {
			var firma = "prod_"+t+"_";
			for (x in datosProducto){
				var nombreDato = datosProducto[x];
				var firmaconcreta = firma + "_" + nombreDato;
				cadenaEnvio += urlVar( firmaconcreta, prod.get(nombreDato) );
			}
		}
	}

	for (x in camposCliente){
 		var nombreCampo = camposCliente[x];

 		var valorCampo = $(nombreCampo).value;

 		cadenaEnvio += urlVar(nombreCampo,valorCampo);
	}

	var totalPresupuesto = $("totalpresupuesto").getAttribute("valor");
		//si, 'valor' ahi se almacen el valor sin formateado bonito que daria
		// problemas en su conversion a numero
	var importeIva = $("importeiva").getAttribute("valor");
	var baseimponible = $("baseimponible").getAttribute("valor");

	cadenaEnvio += urlVar("totalpresupuesto",totalPresupuesto);
	cadenaEnvio += urlVar("importeiva",importeIva);
	cadenaEnvio += urlVar("baseimponible",baseimponible);

	cadenaEnvio += urlVar("obrarealizada",$("obraRealizada").value);

	cadenaEnvio += urlVar("SeriePresupuesto",Presupuesto.serie);
	cadenaEnvio += urlVar("NPresupuesto",Presupuesto.numero);

	cadenaEnvio += urlVar("IdCliente",Presupuesto.IdCliente);

	//datoNumpresupuesto, datoFecha
	var valorCampo = $("datoNumpresupuesto").value;
	cadenaEnvio += urlVar("SerieNumeroPresupuesto",valorCampo);

	var valorCampo = $("datoFecha").value;
	cadenaEnvio += urlVar("FechaPresupuesto",valorCampo);

	cadenaEnvio += "&IdPresupuesto="+ Presupuesto.IdPresupuesto;


	var obj = new XMLHttpRequest();
	var url = moduloAjax;

	cadenaEnvio +="&modo=ModificarPresupuesto";

	alert("cEmodificar: "+cadenaEnvio);

	obj.open("POST",url,false);
	obj.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	obj.send(cadenaEnvio);

	//alert( obj.responseText);

	InterpretarDatosModPresupuesto(obj.responseText);

}
 

function InterpretarDatosModPresupuesto(rawtext){

    if (!rawtext)	return alert("El servidor no respondio a la lectura");

	try {

		alert(rawtext);
		
		obj = eval( "(" + rawtext+ ")" );

		alert("Presupuesto modificada con exito");
		UsarPresupuesto( obj["IdPresupuesto"] );

		/*
		if (confirm("¿Crear otra?")){
			BorrarTodoIniciarSiguiente();
		} else {
			//Despues de haberla creado, la carga en modo edición
			UsarPresupuesto( obj["IdPresupuesto"] );
		}*/

	} catch(e){
		return alert("Error de servidor, por favor intentelo mas tarde." + rawtext);
	}

}
  



Startup();


