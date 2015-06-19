<?php

define ("BUSCACLIENTEPOPUP",1);

include("tool.php");

switch($modo){
	case "JSONBuscaPresupuesto":
		$cadena = $_REQUEST["cadena"];
		$cadena_s = CleanRealMysql($cadena);

		$otrasCondiciones = "";
		$otrasCondiciones .= " OR ( SeriePresupuesto = '%$cadena_s%'  )";

	//$otrasCondiciones .= " OR ( NPresupuesto = '%$cadena_s%'  )";
		$otrasCondiciones .= " OR ( IdCliente LIKE '%$cadena_s%'  )";
		$otrasCondiciones .= " OR ( ObraRealizada LIKE '%$cadena_s%'  )";
		$otrasCondiciones .= " OR ( NombreComercial LIKE '%$cadena_s%'  )";
		$otrasCondiciones .= " OR ( Poblacion LIKE '%$cadena_s%'  )";
		$otrasCondiciones .= " OR ( Direccion LIKE '%$cadena_s%'  )";
		$otrasCondiciones .= " OR ( CIF LIKE '%$cadena_s%'  )";
		$otrasCondiciones .= " OR ( Observaciones LIKE '%$cadena_s%'  )";

		$sql = "SELECT * FROM ges_presupuestos WHERE (NPresupuesto = '%$cadena_s%' ) $otrasCondiciones ORDER BY IdPresupuesto DESC LIMIT 50";
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
<listbox flex="1" id="listaPresupuestos">
</listbox>
</groupbox>

<script><![CDATA[

 var lista = "listaPresupuestos";


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
	var url = "buscapresupuesto.popup.php";
	var data ="&modo=JSONBuscaPresupuesto&cadena=" + encodeURIComponent(cadenaBusqueda);

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

	   AddPresupuestoToList(factura["IdPresupuesto"], factura["SerieNumeroPresupuesto"]);
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


 function AddPresupuestoToList(IdPresupuesto, NombrePresupuesto){
 	//IdPresupuesto =ParseInt(IdPresupuesto);

 	var xitem = document.createElement("listitem");
 	var xcell = document.createElement("listcell");
 	xcell.setAttribute("label",NombrePresupuesto);
	xcell.setAttribute("flex","1");
	xcell.setAttribute("style","min-width: 26em");

 	var xcell1 = document.createElement("listcell");
 	xcell1.setAttribute("label",IdPresupuesto);
 	xcell1.setAttribute("flex","0");

 	xitem.appendChild(xcell1);
 	xitem.appendChild(xcell);

 	xitem.setAttribute("onclick","InvocarPresupuesto('"+IdPresupuesto+"')");

 	$(lista).appendChild( xitem );




 }

function InvocarPresupuesto(IdPresupuesto){
	window.parent.UsarPresupuesto(IdPresupuesto);
	window.close();
}


 Startup();

]]></script>
</window>