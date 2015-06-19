<?php

define ("BUSCACLIENTEPOPUP",1);

include("tool.php");

switch($modo){
	case "JSONBuscaFactura":
		$cadena = $_REQUEST["cadena"];
		$cadena_s = CleanRealMysql($cadena);

	
		
		
		$otrasCondiciones = "";
		$otrasCondiciones .= " OR ( SerieFactura = '%$cadena_s%'  )";
		//$otrasCondiciones .= " OR ( NFactura = '%$cadena_s%'  )";
		$otrasCondiciones .= " OR ( IdCliente LIKE '%$cadena_s%'  )";
		$otrasCondiciones .= " OR ( ObraRealizada LIKE '%$cadena_s%'  )";
		$otrasCondiciones .= " OR ( NombreComercial LIKE '%$cadena_s%'  )";
		$otrasCondiciones .= " OR ( Poblacion LIKE '%$cadena_s%'  )";
		$otrasCondiciones .= " OR ( Direccion LIKE '%$cadena_s%'  )";
		$otrasCondiciones .= " OR ( CIF LIKE '%$cadena_s%'  )";
		$otrasCondiciones .= " OR ( Observaciones LIKE '%$cadena_s%'  )";
		
						
		$sql = "SELECT * FROM ges_facturas WHERE (NFactura = '%$cadena_s%' ) $otrasCondiciones ".
			" ORDER BY SerieFactura ASC, NFactura DESC ";
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

StartXul();


?>
<!--  Modo:'<?php echo $modo ?>' -->

<groupbox flex="1"> 
<label value="Nombre/Cod/Otro:"/>
<hbox>
<textbox flex="1" id="cadenaBusqueda"  onchange="lanzaBusqueda()"/>
<toolbarbutton  image="img/busca1.gif" oncommand="lanzaBusqueda()"/>
</hbox>
<listbox flex="1" id="listaFacturas">
</listbox>
</groupbox>

<script><![CDATA[

 var lista = "listaFacturas";


 document.gClonedListbox = false;
 
 function Startup() {
      document.gClonedListbox = $(lista).cloneNode(true);
	  $("cadenaBusqueda").focus();
 }

 function clearListbox() {
     var oldListbox = $(lista);         
     var newListbox = document.gClonedListbox.cloneNode(true);
     oldListbox.parentNode.replaceChild( newListbox,oldListbox);
 }
 
 function lanzaBusqueda(){
 	clearListbox();
	var cadenaBusqueda = $("cadenaBusqueda").value;

	var obj = new XMLHttpRequest();
	var url = "buscafactura.popup.php";
	var data ="&modo=JSONBuscaFactura&cadena=" + encodeURIComponent(cadenaBusqueda);
	
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

	for (idfactura in obj){
	   factura = obj[idfactura] ;
	   
	   AddFacturaToList(factura["IdFactura"], factura["SerieNumeroFactura"]);
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
 	 	
 	$(lista).appendChild( xitem ); 		
 } 
 
 
 function AddFacturaToList(IdFactura, NombreFactura){
 	//IdFactura =ParseInt(IdFactura);
 
 	var xitem = document.createElement("listitem");
 	var xcell = document.createElement("listcell");
 	xcell.setAttribute("label",NombreFactura);
	xcell.setAttribute("flex","1");
	xcell.setAttribute("style","min-width: 26em");

 	var xcell1 = document.createElement("listcell");
 	xcell1.setAttribute("label",IdFactura);
 	xcell1.setAttribute("flex","0"); 	 	
 	
 	xitem.appendChild(xcell1);
 	xitem.appendChild(xcell);
 	
 	xitem.setAttribute("onclick","InvocarFactura('"+IdFactura+"')");
 	
 	$(lista).appendChild( xitem ); 	
 	
 	
 	
 	
 }
 
function InvocarFactura(IdFactura){
	window.parent.UsarFactura(IdFactura);
	window.close();
}


 Startup();

]]></script>
</window>