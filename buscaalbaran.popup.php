<?php

define ("BUSCACLIENTEPOPUP",1);

include("tool.php");

switch($modo){
	case "JSONBuscaAlbaran":
		$cadena = $_REQUEST["cadena"];
		$cadena_s = CleanRealMysql($cadena);


		$otrasCondiciones = "";
		$otrasCondiciones .= " OR ( SerieAlbaran = '%$cadena_s%'  )";
		//$otrasCondiciones .= " OR ( NAlbaran = '%$cadena_s%'  )";
		$otrasCondiciones .= " OR ( IdCliente LIKE '%$cadena_s%'  )";
		$otrasCondiciones .= " OR ( ObraRealizada LIKE '%$cadena_s%'  )";
		$otrasCondiciones .= " OR ( NombreComercial LIKE '%$cadena_s%'  )";
		$otrasCondiciones .= " OR ( Poblacion LIKE '%$cadena_s%'  )";
		$otrasCondiciones .= " OR ( Direccion LIKE '%$cadena_s%'  )";
		$otrasCondiciones .= " OR ( CIF LIKE '%$cadena_s%'  )";
		$otrasCondiciones .= " OR ( Observaciones LIKE '%$cadena_s%'  )";

		$sql = "SELECT * FROM ges_albarans WHERE (NAlbaran = '%$cadena_s%' ) $otrasCondiciones ORDER BY IdAlbaran DESC LIMIT 50";
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
<listbox flex="1" id="listaAlbarans">
</listbox>
</groupbox>

<script><![CDATA[

 var lista = "listaAlbarans";


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
	var url = "buscaalbaran.popup.php";
	var data ="&modo=JSONBuscaAlbaran&cadena=" + encodeURIComponent(cadenaBusqueda);

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

	for (idalbaran in obj){
	   albaran = obj[idalbaran] ;

	   AddAlbaranToList(albaran["IdAlbaran"], albaran["SerieNumeroAlbaran"]);
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


 function AddAlbaranToList(IdAlbaran, NombreAlbaran){
 	//IdAlbaran =ParseInt(IdAlbaran);

 	var xitem = document.createElement("listitem");
 	var xcell = document.createElement("listcell");
 	xcell.setAttribute("label",NombreAlbaran);
	xcell.setAttribute("flex","1");
	xcell.setAttribute("style","min-width: 26em");

 	var xcell1 = document.createElement("listcell");
 	xcell1.setAttribute("label",IdAlbaran);
 	xcell1.setAttribute("flex","0");

 	xitem.appendChild(xcell1);
 	xitem.appendChild(xcell);

 	xitem.setAttribute("onclick","InvocarAlbaran('"+IdAlbaran+"')");

 	$(lista).appendChild( xitem );

 }

function InvocarAlbaran(IdAlbaran){
	window.parent.UsarAlbaran(IdAlbaran);
	window.close();
}


 Startup();

]]></script>
</window>