
var moduloAjax ="modfacturas.php";

var esAlta = true;

var Factura = new Object();
Factura.IdFactura = 0;


/*----------  Listado de productos que se compra ----------------------*/

function VaciarListaProductos(){
	var xcosa = $("listaProductos");
	alert(xcosa);
	xcosa.childNodes = false;
}


 document.gClonedListbox = false;
 
 function Startup() {
     document.gClonedListbox = $('listaProductos').cloneNode(true);
     
     $("datoFecha").value = Factura.fecha;
     $("datoNumfactura").value = Factura.numerofactura;          
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
	
	var megaTotalConIva		= 0;
	var megaTotalIva		= 0;
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
	//baseimponible,importeiva,totalfactura
	
	$("totalfactura").value 	= formatoBonito(megaTotalConIva);
	$("totalfactura").setAttribute("valor",megaTotalConIva);
	
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
	CargarSiguientesDatosDeFactura();
}


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
	Factura.IdCliente = obj["IdCliente"];

	$("NombreComercial").value = obj["NombreComercial"];	
	$("Direccion").value = obj["Direccion"];
	$("CodigoPostal").value = obj["CP"];
	$("Poblacion").value = obj["Localidad"];
	$("CIF").value = obj["NumeroFiscal"];	
}


function BorrarDatosCliente(){
	Factura.IdCliente = 0;

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
 
function CancelarFactura(){
	document.location = moduloAjax + "?r="+Math.random(); //fuerza la recarga	
} 
 
 
/*--------------------------------*/ 

var cadenaEnvio = "";

function EnviarFactura(){
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

	var totalFactura = $("totalfactura").getAttribute("valor");
		//si, 'valor' ahi se almacen el valor sin formateado bonito que daria
		// problemas en su conversion a numero				
	var importeIva = $("importeiva").getAttribute("valor");
	var baseimponible = $("baseimponible").getAttribute("valor");
	
	cadenaEnvio += urlVar("totalfactura",totalFactura);
	cadenaEnvio += urlVar("importeiva",importeIva);
	cadenaEnvio += urlVar("baseimponible",baseimponible);				
	
	cadenaEnvio += urlVar("obrarealizada",$("obraRealizada").value);
	
	cadenaEnvio += urlVar("SerieFactura",Factura.serie);
	cadenaEnvio += urlVar("NFactura",Factura.numero);
	
	cadenaEnvio += urlVar("IdCliente",Factura.IdCliente);

	//datoNumfactura, datoFecha
	var valorCampo = $("datoNumfactura").value;		 		
	cadenaEnvio += urlVar("SerieNumeroFactura",valorCampo);

	var valorCampo = $("datoFecha").value;		 		
	cadenaEnvio += urlVar("FechaFactura",valorCampo);

	var obj = new XMLHttpRequest();
	var url = moduloAjax;
		
	cadenaEnvio +="&modo=EnviarFactura";

	obj.open("POST",url,false);
	obj.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	obj.send(cadenaEnvio);

	InterpretarDatosCreacionFactura(obj.responseText);
}

/* ------------------------------------ */

function InterpretarDatosCreacionFactura(rawtext){
    
    if (!rawtext)	return alert("El servidor no respondio a la lectura");
	
	try {	
		obj = eval( "(" + rawtext+ ")" );

		alert("Factura creada con exito");

		if (confirm("¿Crear otra?")){
			BorrarTodoIniciarSiguiente();
		} else {
			//Despues de haberla creado, la carga en modo edición
			UsarFactura( obj["IdFactura"] );
		}

	} catch(e){
		return alert("Error de servidor, por favor intentelo mas tarde." + rawtext);
	}

}



function urlVar( variable, valor){	
	return "&" + variable + "=" + encodeURIComponent(valor);//el CR es para debug
}

  

/*--------------------------------*/
 
function quitarElementoFactura(elemento){
	lista = $("listaProductos");
	
	var  iitem = lista.selectedCount;
	
	if (!iitem){
		alert("selecciona alguno!");
	}
	
	var item = lista.selectedItem;
	
	var idex = parseInt(item.getAttribute("indice"));	

//	var prod = ListadoProductos[idex];
	
	ListadoProductos[idex] = false;
	
	lista.removeChild( lista.selectedItem ) ;
}

/*--------------------------------*/

function AbrirVentanaNumfactura(){
	var fichero = "modificarserie.popup.php?" +
		"&serie=" + encodeURIComponent(Factura.serie) +
		"&numero=" + encodeURIComponent(Factura.numero);
		
	AbrirVentanaYBuscar(fichero,300);
}


function UsarFormatoNumfactura(DescripcionFormato, serie, numero){
	//datoNumfactura
	var xcode = $("datoNumfactura");
	xcode.value = DescripcionFormato;	
	
	Factura.serie = serie;
	Factura.numero = numero;
	Factura.numerofactura = DescripcionFormato;
}


/*--------------------------------*/

 function Accion_BuscarFactura(){
 
	var fichero = "buscafactura.popup.php?";
		
	AbrirVentanaYBuscar(fichero,600);
 
 }
 
 /*--------------------------------*/
 
 function Accion_ModoAlta(){
 	BotonEsAlta();
 }
 
 function BotonEsAlta(){
 	esAlta = true;

	$("cajaModoModificacion").setAttribute("collapsed","true");
	$("cajaModoAlta").setAttribute("collapsed","false");

 	LimpiarCamposFactura();
	BorrarLista();//Borra carrito invisible de la compra
	CargarSiguientesDatosDeFactura();
 }
 
 function LimpiarCamposFactura(){

 	var campos = new String("baseimponible,importeiva,totalfactura,CodCliente,codigo,referencia,descripcion,unid,precio,dto,impuesto,NombreComercial,Direccion,Poblacion,CIF,CodigoPostal,datoNumfactura,datoFecha,obraRealizada");
	var resetear = campos.split(",");
	
	for(x in resetear){
		reset(resetear[x]);
	}
 	clearListbox();
 }
  
 /*--------------------------------*/
 


function CargarSiguientesDatosDeFactura(){
	var url = moduloAjax;
	var data = "&modo=IniciarSiguienteFactura";
				
	ajaxCarga.open("POST",url,true);
	ajaxCarga.onreadystatechange = IniciarSiguieteFactura;
	ajaxCarga.setRequestHeader('Content-Type','application/x-www-form-urlencoded; charset=UTF-8');
	ajaxCarga.send(data)
}

function IniciarSiguieteFactura(){
	ocupado = 0;
	if (ajaxCarga.readyState==4) {
		var rawtext = ajaxCarga.responseText;	
		if (rawtext=="ERROR")		return;	

		InterpretarDatosSiguienteFactura(rawtext);				
	}
} 

function InterpretarDatosSiguienteFactura(rawtext){
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
            
        setDatoURL2("datoNumfactura","expresionNumeroFactura",obj);	
    
     
        Factura.fecha = fecha;
        Factura.numerofactura = obj["expresionNumeroFactura"];
        Factura.numero = obj["NFactura"];
        Factura.serie = obj["Serie"];
        
		
		console.log("[InterpretarDatosSiguienteFactura]factura:");
		console.dir( Factura);

} 


 //Solicitud para cargar una factura en concreto
 function UsarFactura(IdFactura){

	//Modo modificacion
	$("cajaModoModificacion").setAttribute("collapsed","false");
	$("cajaModoAlta").setAttribute("collapsed","true");

 	AutoActualizarFactura( IdFactura );	 
 }	
 
 
function AutoActualizarFactura(id){
	
	var url = moduloAjax + "?modo=JSONCargarFactura&id="+id;
				
	ajaxCarga.open("GET",url,true);
	ajaxCarga.onreadystatechange = CargarFactura;
	ajaxCarga.send(null)
	
}

 
function CargarFactura(){
	ocupado = 0;
	if (ajaxCarga.readyState==4) {
		var rawtext = ajaxCarga.responseText;	
		if (rawtext=="error")		return;	

		InterpretarDatosFactura(rawtext);				
	}
}
 

function InterpretarDatosFactura(rawtext){
	if (!rawtext)	return;
	
	var obj = eval( "(" + rawtext+ ")" );	

	clearListbox();
	BorrarTodo();//borra historias en el interface
	BorrarLista();//Borra carrito invisible de la compra

	setDatoURL2("baseimponible","ImporteNeto",obj);
	setDatoURL2("importeiva","IvaImporte",obj);
	setDatoURL2("totalfactura","TotalImporte",obj);
		
	setDatoURL2("CodCliente","IdCliente",obj);
	setDatoURL2("codigo","IdCliente",obj);
		
	setDatoURL("NombreComercial",obj);	
	setDatoURL("Direccion",obj);
	setDatoURL("Poblacion",obj);
	setDatoURL("CIF",obj);	
	setDatoURL("CodigoPostal",obj);
	
	setDatoURL2("datoNumfactura","NFactura",obj);
	setDatoURL2_fecha("datoFecha","FechaFactura",obj);

	setDatoURL2("obraRealizada","ObraRealizada",obj);	

	Factura.IdFactura = obj["IdFactura"];
	Factura.serie = obj["SerieFactura"];
	Factura.numero = obj["NFactura"];
	Factura.IdCliente = obj["IdCliente"];

	//alert("factura cargada:"+ Factura.IdFactura);

	//TODO: cargar lineas de factura

	AutoActualizarLineasFactura( obj.IdFactura );

	//No nos fiamos de los datos que vienen de la base de datos, asi que actualizamos 
	ActualizarTotalesProductos();
} 

function AutoActualizarLineasFactura(id){
	
	var url = moduloAjax + "?modo=JSONCargarLineasFactura&id="+id;
				
	ajaxCarga.open("GET",url,true);
	ajaxCarga.onreadystatechange = CargarLineasFactura;
	ajaxCarga.send(null)
	
}
 

 
function CargarLineasFactura(){
	ocupado = 0;
	if (ajaxCarga.readyState==4) {
		var rawtext = ajaxCarga.responseText;	
		if (rawtext=="ERROR") {
			alert("Ha ocurrido un error en el servidor, y no se ha podido completar la operación");
			return;
		}	

		InterpretarLineasFactura(rawtext);				
	}
}

function InterpretarLineasFactura(rawtext){

	function ActualizaCamposProductoDesdeLineaFactura(obj){
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
	//...
/*
	VerObj( obj );
	VerObj( obj[2] );
	0 ->2 IdFacturaDet ->2 1 ->8 IdFactura ->8 2 ->0 IdProducto ->0
	3 -> Referencia -> 4 -> CodigoBarras -> 5 ->Horas de trabajo 
	Concepto ->Horas de trabajo
	6 -> Talla -> 7 -> Color -> 8 ->10 Cantidad ->10 9 ->34.8 Precio ->34.8 10 ->0
	Descuento ->0 11 ->348 Importe ->348 12 ->16 Iva ->16 13 ->0 Eliminado ->0 */

	console.log("llega:");
	console.dir(obj);
	//console.log("era:");
	//console.dir(rawtext);


	for( t in obj){
		//Populamos los campos
		ActualizaCamposProductoDesdeLineaFactura(obj[t]);
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

    
function VentanaImprimirFactura(){
   //   VerObj(Factura);
        AbrirVentanaImprimir("moddocumento.php?modo=factura&id="+Factura.IdFactura);        
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

	var totalFactura = $("totalfactura").getAttribute("valor");
		//si, 'valor' ahi se almacen el valor sin formateado bonito que daria
		// problemas en su conversion a numero
	var importeIva = $("importeiva").getAttribute("valor");
	var baseimponible = $("baseimponible").getAttribute("valor");

	cadenaEnvio += urlVar("totalfactura",totalFactura);
	cadenaEnvio += urlVar("importeiva",importeIva);
	cadenaEnvio += urlVar("baseimponible",baseimponible);

	cadenaEnvio += urlVar("obrarealizada",$("obraRealizada").value);

	cadenaEnvio += urlVar("SerieFactura",Factura.serie);
	cadenaEnvio += urlVar("NFactura",Factura.numero);

	cadenaEnvio += urlVar("IdCliente",Factura.IdCliente);

	//datoNumfactura, datoFecha
	var valorCampo = $("datoNumfactura").value;
	cadenaEnvio += urlVar("SerieNumeroFactura",valorCampo);

	var valorCampo = $("datoFecha").value;
	cadenaEnvio += urlVar("FechaFactura",valorCampo);

	cadenaEnvio += "&IdFactura="+ Factura.IdFactura;


	var obj = new XMLHttpRequest();
	var url = moduloAjax;

	cadenaEnvio +="&modo=ModificarFactura";

	alert("cEmodificar: "+cadenaEnvio);

	obj.open("POST",url,false);
	obj.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	obj.send(cadenaEnvio);

	//alert( obj.responseText);

	InterpretarDatosModFactura(obj.responseText);

}
 

function InterpretarDatosModFactura(rawtext){

    if (!rawtext)	return alert("El servidor no respondio a la lectura");

	try {

		alert(rawtext);
		
		obj = eval( "(" + rawtext+ ")" );

		alert("Factura modificada con exito");
		UsarFactura( obj["IdFactura"] );

		/*
		if (confirm("¿Crear otra?")){
			BorrarTodoIniciarSiguiente();
		} else {
			//Despues de haberla creado, la carga en modo edición
			UsarFactura( obj["IdFactura"] );
		}*/

	} catch(e){
		return alert("Error de servidor, por favor intentelo mas tarde." + rawtext);
	}

}
  
Startup();


