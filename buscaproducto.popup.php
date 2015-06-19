<?php

include("tool.php");

switch($modo){
	case "JSONBuscaProducto":
		$cadena = $_REQUEST["cadena"];
		$cadena_s = CleanRealMysql($cadena);
		
		 	     	      	  	  		
		$otrasCondiciones .= " OR ( Descripcion LIKE '%$cadena_s%'  )";
		$otrasCondiciones .= " OR ( CodigoBarras = '$cadena_s'  )";
		$otrasCondiciones .= " OR ( IdProducto = '$cadena_s'  )";
		$otrasCondiciones .= " OR ( Nombre LIKE '%$cadena_s%'  )";
		$otrasCondiciones .= " OR ( Referencia LIKE '%$cadena_s%'  )";
		$otrasCondiciones .= " OR ( CodigoBarras LIKE '%$cadena_s%'  )";		
		
		$sql = "SELECT ges_productos.*, ges_productos_idioma.Nombre FROM 
		ges_productos INNER JOIN ges_productos_idioma ON  ges_productos.IdProdBase = ges_productos_idioma.IdProdBase
		WHERE ges_productos_idioma.IdIdioma = 1 AND 
		(ges_productos_idioma.Nombre = '$nombre_s' $otrasCondiciones)
		AND
		ges_productos.Eliminado = 0
		AND
		ges_productos_idioma.Eliminado = 0";
								
		//$sql = "SELECT * FROM ges_productos WHERE (Referencia LIKE '%$cadena_s%') $otrasCondiciones ORDER BY Referencia ASC"; 
		$res = query($sql);
		if(!$res){
			echo "0";
			exit();	
		}
		
		$rows = array();	
	
		$num = 0;
		while($row = Row($res) ){			
			$rows[$num++] = $row;	
		} 
		
		$json = new Services_JSON();
		$output = $json->encode($rows);	
		echo $output;					
				
				
		exit();	
		
		break;	
}

StartXul("Buscar producto");

?>
<groupbox flex="1"> 
<label value="Nombre/Cod/Otro:" />
<hbox>
<textbox flex="1" id="cadenaBusqueda"  onchange="lanzaBusqueda()"/>
<toolbarbutton  image="img/busca1.gif" oncommand="lanzaBusqueda()"/>
</hbox>
<listbox flex="1" id="listaProductos">
</listbox>
</groupbox>

<script><![CDATA[

 document.gClonedListbox = false;
 
 function Startup() {
      document.gClonedListbox = $('listaProductos').cloneNode(true);
      try {
      	$("buscar-producto").setAttribute("onload","focusBuscador()");
      } catch(e){      
      }               
 }

 function focusBuscador(){
 	 $("cadenaBusqueda").focus();
 } 

 function clearListbox() {
     var oldListbox = $('listaProductos');         
     var newListbox = document.gClonedListbox.cloneNode(true);
     oldListbox.parentNode.replaceChild( newListbox,oldListbox);
 }
 
 function lanzaBusqueda(){
 	clearListbox();
	var cadenaBusqueda = $("cadenaBusqueda").value;

	var obj = new XMLHttpRequest();
	var url = "buscaproducto.popup.php";
	var data = "&modo=JSONBuscaProducto&cadena=" + encodeURIComponent(cadenaBusqueda);
	obj.open("POST",url,false);
	obj.setRequestHeader('Content-Type','application/x-www-form-urlencoded; charset=UTF-8');
	obj.send(data);
	var text = obj.responseText;
	
	var obj =  null;
	
	try {
		obj = eval( "(" + text+ ")" );
	} catch(e){
		return alert("e:"+e+",info:"+ text);
	}
	
	var num = 0;
	
	for (idproducto in obj){
	   producto = obj[idproducto] ;
	   num++;
	   
	   AddProductToList(producto["IdProducto"], producto["Referencia"]+" - "+ producto["Nombre"]);
	}
	
	if (!num){
		AddMarcaVacio();	
	}		
	
}
 
 
function AddMarcaVacio(){
 	var xitem = document.createElement("listitem");
 	var xcell = document.createElement("listcell");
 	
 	xcell.setAttribute("label","No se encontraron datos");
 	xitem.appendChild(xcell);
 	 	
 	$("listaProductos").appendChild( xitem ); 		
}
 
 
 function AddProductToList(IdProducto, NombreProducto){
 
 	var xitem = document.createElement("listitem");
 	var xcell = document.createElement("listcell");
 	xcell.setAttribute("label",NombreProducto);
	xcell.setAttribute("flex","1");
	xcell.setAttribute("style","min-width: 26em");
	
 	var xcell1 = document.createElement("listcell");
 	xcell1.setAttribute("label",IdProducto);
 	xcell1.setAttribute("flex","0");
 	 	 	
 	xitem.appendChild(xcell1);
 	xitem.appendChild(xcell);
 	
 	xitem.setAttribute("onclick","InvocarProducto('"+IdProducto+"')");
 	
 	$("listaProductos").appendChild( xitem ); 	 	 	 	 	
 }
 
function InvocarProducto(IdProducto){
	window.parent.UsarProducto(IdProducto);
	window.close();
}


 Startup();

]]></script>
</window>