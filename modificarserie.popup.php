<?php

include("tool.php");

function Recodifica($serie,$numero,$atomo){		
	$atomo = str_replace("N","$numero",$atomo);
	$atomo = str_replace("Y",date("Y"),$atomo);
	$atomo = str_replace("S","$serie",$atomo);
	return $atomo;			
}


switch($modo){	
	case "JSONBuscaProducto":
	$serie = $_REQUEST["serie"];
	$numero  = $_REQUEST["numero"];
	
	$sql = "SELECT * FROM ges_factura_formatos ORDER BY IdFormato ASC";
									
	$res = query($sql);
	if(!$res){
		echo "0";
		exit();	
	}
	
	$rows = array();	

	$num = 0;
	while($row = Row($res) ){
		$datos = array();
 						
		$datos["Descripcion"] = Recodifica($serie,$numero,$row["Dato1"]) .
			Recodifica($serie,$numero,$row["Simbolo1"]) .
			Recodifica($serie,$numero,$row["Dato2"]) .
			Recodifica($serie,$numero,$row["Simbolo2"]) .
			Recodifica($serie,$numero,$row["Dato3"]) ;
								
		$rows[$num++] = $datos;	
	} 
	
	$json = new Services_JSON();
	$output = $json->encode($rows);	
	echo $output;					
			
			
	exit();	
	

	default:							
	break;	
}

StartXul("num-factura");

?>
<groupbox flex="1"> 
<hbox>
<vbox>
	<label value="Serie"/>
	<textbox flex="1" id="serie"  onchange="lanzaBusqueda()"/>
</vbox>
<vbox>
	<label value="Número"/>
	<textbox flex="1" id="numero"  onchange="lanzaBusqueda()"/>
</vbox>
<toolbarbutton  image="img/busca1.gif" oncommand="lanzaBusqueda()"/>
</hbox>
<listbox flex="1" id="listaFormatos">
</listbox>
</groupbox>

<script><![CDATA[

 document.gClonedListbox = false;
 
 function Startup() {
      document.gClonedListbox = $('listaFormatos').cloneNode(true);
      try {
      	$("num-factura").setAttribute("onload","focusBuscador()");
      } catch(e){      
      }               
 }

 function focusBuscador(){
 	 $("serie").focus();
 	  	 
 	 $("serie").value = "<?php echo $_REQUEST["serie"]  ?>";
 	 $("numero").value = "<?php echo $_REQUEST["numero"]  ?>";
 	 
 	 lanzaBusqueda();
 } 

 function clearListbox() {
     var oldListbox = $('listaFormatos');         
     var newListbox = document.gClonedListbox.cloneNode(true);
     oldListbox.parentNode.replaceChild( newListbox,oldListbox);
 }
 
 function lanzaBusqueda(){
 	clearListbox();
	var serie = $("serie").value;
	var numero = $("numero").value;

	var obj = new XMLHttpRequest();
	var url = "modificarserie.popup.php";
	var data = "&modo=JSONBuscaProducto&serie=" + encodeURIComponent(serie);
	url += "&numero=" + encodeURIComponent(numero);
	obj.open("POST",url,false);
	obj.send(data);
	var text = obj.responseText;
	var obj = eval( "(" + text+ ")" );	 
	
	//alert( text);
	
	var num = 0;
	
	for (idproducto in obj){
	   producto = obj[idproducto] ;
	   num++;
	   
	   AddProductToList(producto["Descripcion"]);
	}
	
	if (!num){
		AddMarcaVacio();	
	}		
	
}
 
 
function AddMarcaVacio(){
 	var xitem = document.createElement("listitem");
 	var xcell = document.createElement("listcell");
 	
 	xcell.setAttribute("label","No se encontraron formatos");
 	xitem.appendChild(xcell);
 	 	
 	$("listaFormatos").appendChild( xitem ); 		
}
 
 
function AddProductToList(DescripcionFormato){
 
 	var xitem = document.createElement("listitem");
 	var xcell = document.createElement("listcell");
 	xcell.setAttribute("label",DescripcionFormato);
	xcell.setAttribute("flex","1");
	xcell.setAttribute("style","min-width: 26em");
	
 	xitem.appendChild(xcell);
 	
 	xitem.setAttribute("onclick","InvocarFormatoNumfactura('"+DescripcionFormato+"')");
 	
 	$("listaFormatos").appendChild( xitem ); 	 	 	 	 	
}
 
function InvocarFormatoNumfactura(DescripcionFormato){
	var serie = $("serie").value;
	var numero = $("numero").value;
	window.parent.UsarFormatoNumfactura(DescripcionFormato, serie, numero);
	window.close();
}


 Startup();

]]></script>
</window>