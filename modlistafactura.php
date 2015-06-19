<?php

include("tool.php");

include("inc/modfacturas.inc.php");

StartXul("modfacturas",false,false,true);

?>
<toolbar id="a-toolbar" >
	 	<toolbarseparator />
     <vbox>
     <label value="Serie"/>
    <menulist id="serie" label="Serie">
     <menupopup>
      <menuitem label="Serie"/>
<?php


    /*
      <menuitem label="Normal"/>
      <menuitem label="Serie" selected="true" />
      <menuitem label="A" type="checkbox" />
      <menuitem label="B" type="checkbox" />
      */
    
    $sql = "SELECT SerieFactura FROM `ges_facturas` GROUP BY SerieFactura ORDER BY SerieFactura ASC";
    
    $res = query($sql);
    
    while( $row = Row($res) ){
        $serie = $row["SerieFactura"];
        echo "<menuitem value='$serie' label='$serie' />";
    }
        
      
    
?>    
     </menupopup>
    </menulist>
    </vbox>
    <vbox>
     <label value="nº"/>
     <textbox id="numero" value="" style="width: 4em" />
    </vbox>
    <vbox>
     <label value="Nombre cliente"/>
     <textbox id="nombre" value=""   style="width: 20em"/>
    </vbox>
<spacer flex="1"/>

		<button image="img/listado.png" label="Buscar" oncommand="Accion_Buscar()"/>		
	</toolbar>		

<listbox id="busquedaVentas" contextmenu="AccionesBusquedaVentas" flex="1">
     <listcols flex="1">
		<listcol  flex="1"/>		
		<splitter class="tree-splitter" />
		<listcol  flex="1"/>
		<splitter class="tree-splitter" />
		<listcol  flex="1"/>
		<splitter class="tree-splitter" />
		<listcol  flex="1"/>
		<splitter class="tree-splitter" />

		<listcol  flex="1"/>
		<splitter class="tree-splitter" />
		<listcol  flex="1"/>		
		<splitter class="tree-splitter" />
		<listcol  flex="1"/>				
		<splitter class="tree-splitter" />
		<listcol  flex="1"/>				
		<splitter class="tree-splitter" />
		<listcol/>	
     </listcols>
     <listhead>

		<listheader label="Vendedor"/>
		<listheader label="Serie"/>
		<listheader label="Factura"/>
		<listheader label="Fecha factura"/>
		<listheader label="Total Importe"/>
		<listheader label="Importe Pendiente"/>
		<listheader label="Status"/>
		<listheader label="Cliente"/>		
     </listhead>

</listbox>

<script><![CDATA[
    
function $(cosa){
  return document.getElementById(cosa);
}
    
    
function Accion_Buscar(){
    var datos = new Object();
    datos.nombre = $("nombre").value; 
    datos.numero = $("numero").value;
    datos.serie = $("serie").value;
    
    alert( datos.toSource() );
        
        
}
    
    
]]></script>


</window>