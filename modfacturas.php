<?php

include("tool.php");

include("inc/modfacturas.inc.php");

StartXul("modfacturas",false,false,true);

?>
<script type="application/x-javascript" src="complementos/datepicker/calendario.js"/>		
<popup  id="oe-date-picker-popup" position="after_start" oncommand="RecibeCalendario( this )" value=""/>	

<popupset>
  <popup id="accionesLista" class="media">
	   <menuitem class="menuitem-iconic" image="img/addcart16.gif" label="Quitar"  oncommand="quitarElementoFactura(this)"/>
  </popup>
  
</popupset>  


	<toolbar id="a-toolbar" >
	 	<toolbarseparator />
		<toolbarbutton image="img/listado.png" label="Alta" oncommand="Accion_ModoAlta()"/>		
		<toolbarbutton image="img/busca1.gif" label="Buscar" oncommand="Accion_BuscarFactura()"/>
	</toolbar>		
	

<groupbox flex="1">
<caption id="captionclick" >
<description>Cabecera de factura</description>
<vbox><spacer flex="1"/>
<image style='margin-left: 8px' src="img/zoommasmenos.gif" onclick="toggleDesde()" />
<spacer flex="1"/>  
</vbox>
</caption>

<hbox flex="1" id="bloqueDesde">
<vbox flex="1">

<hbox align="center">
<label class="labelGeneral">Cod Cliente:</label>   
<textbox value="" flex="1"  emptytext="" decimalplaces="0"  ztype="number" hidespinbuttons="true"   onchange="AutoActualizarCliente(this)" id="CodCliente"/>
<toolbarbutton  image="img/addnew.gif" collapsed="true"/>
<toolbarbutton  image="img/busca1.gif" oncommand="Accion_BuscarCliente()"/>
<toolbarbutton  image="img/edit.png"  oncommand="habilitarEdicionGrupoDatosCliente()" collapsed="false"/>
</hbox>

<hbox align="center">
<label class="labelGeneral">Nombre:</label>
<textbox id="NombreComercial" disabled="true" value="" flex="1"/>
<toolbarbutton  image="img/edit.png"  oncommand="habilitarEdicion('NombreComercial')" collapsed="true"/>
</hbox>

<hbox align="center">
<label class="labelGeneral">Dirección:</label>
<textbox id="Direccion" disabled="true" value="" flex="1"/>
<toolbarbutton  image="img/edit.png"  oncommand="habilitarEdicion('Direccion')" collapsed="true"/>
</hbox>

<hbox align="center">
<label class="labelGeneral">Población:</label>
<textbox id="Poblacion" disabled="true" value="" flex="1"/>
<toolbarbutton  image="img/edit.png"  oncommand="habilitarEdicion('Poblacion')" collapsed="true"/>
<label>CP:</label>
<textbox id="CodigoPostal" disabled="true" value="" flex="1"/>
<toolbarbutton  image="img/edit.png"  oncommand="habilitarEdicion('CodigoPostal')" collapsed="true"/>
</hbox>


<hbox align="center">
<label class="labelGeneral">CIF/NIF:</label>
<textbox id="CIF" disabled="true" value="" flex="1"/>
<toolbarbutton  image="img/edit.png"  oncommand="habilitarEdicion('CIF')" collapsed="true"/>
</hbox>


</vbox>

<vbox  flex="1">

<hbox align="center">
<label class="labelGeneral">Nº Factura:</label>
<textbox disabled="true" value="" class="datoNumfactura" id="datoNumfactura" flex="0"/>
<toolbarbutton  image="img/edit.png"  oncommand="habilitarEdicion('datoNumfactura')" collapsed="false"/>
<toolbarbutton  image="img/addnew.gif" oncommand="CargarSiguientesDatosDeFactura()" zoncommand="AbrirVentanaNumfactura()" collapsed="false"/>
<spacer flex="1"/>
</hbox>

<hbox align="center">
<label class="labelGeneral">Fecha Factura:</label>
<textbox disabled="true" value="" class="datoFecha" id="datoFecha"  flex="0"/>
<toolbarbutton  image="img/edit.png" oncommand="habilitarEdicion('datoFecha')" collapsed="false"/>
<toolbarbutton image="img/calendar-up.gif" label="" onmousedown="EnviaCalendario('oe-date-picker-popup','datoFecha')" popup="oe-date-picker-popup" position="after_start" />
<spacer flex="1"/>
</hbox>

</vbox>

</hbox>

</groupbox>

<groupbox  collapsed="true">
<caption label="Título"/>
<hbox>
    <label class="labelGeneral">Trabajo:</label>
    <textbox id="obraRealizada" value="" flex="1"/>
</hbox>
</groupbox>

<groupbox flex="10">
<caption label="Conceptos"/>


<hbox>
<!--  añadir linea -->
<vbox align="left">
<label>Cod.</label> 
<hbox>
<textbox id="codigo" value="" style="max-width: 5em"  onchange="AutoActualizarProducto(this)"/>
<textbox id="referencia" value="" collapsed="true"/>
<toolbarbutton  image="img/busca1.gif" oncommand="Accion_BuscarProducto()"/>
<toolbarbutton  image="img/addnew.gif" collapsed="true"/>
</hbox>
</vbox>
<separator class="groove-thin" orient="vertical" style="width: 3px"/>
<vbox  flex="1">
<label flex="1">Descripción:</label>
<textbox id="descripcion" value="" flex="1" style="min-width: 30em"/>
</vbox>

<vbox align="left">
<label>Unid:</label>
<textbox id="unid" value=""  style="max-width: 3em"/>
</vbox>

<vbox align="left" flex="1">
<label>Precio/Unid:</label>
<textbox id="precio" value=""  style="max-width: 8em" />
</vbox>

<vbox align="left">
<label>% Dto:</label>
<textbox id="dto" value=""  style="max-width: 4em"/>
</vbox>

<vbox align="left">
<label>% Impuesto:</label>
<textbox id="impuesto" value=""  style="max-width: 8em"/>
</vbox>

<vbox>
<label style="visibility: hidden"> .</label>
<button  image="img/addnew.gif" label="Añadir" oncommand="AddLista()"/>
</vbox>

<!--  /añadir linea -->
</hbox>

<listbox flex="1" id="listaProductos"  contextmenu="accionesLista" >
     <listcols flex="1">
        <listcol style="max-width: 8em"/>
        <splitter class="tree-splitter" />
        <listcol style="max-width: 8em"/>
        <splitter class="tree-splitter" />
        <listcol style="max-width: 40em;min-width: 40em;"/>
        <splitter class="tree-splitter" />
        <listcol  style="max-width: 8em"/>
        <splitter class="tree-splitter" />
        <listcol  flex="1"/>
        <splitter class="tree-splitter" />
        <listcol style="max-width: 8em"/>
        <splitter class="tree-splitter" />
        <listcol style="max-width: 8em"/>
        <splitter class="tree-splitter" />
        <listcol/>               
     </listcols>
     <listhead>
        <listheader label="Cod.art"/>
        <listheader label="Ref."/>
        <listheader label="Descripción"/>
        <listheader label="Unid"/>
        <listheader label="Precio/Unid." />
        <listheader label="% Dto" />       
        <listheader label="% Impuestos" />       
        <listheader label="Total"  id="cxcv" />       
     </listhead>

</listbox>
</groupbox>
<groupbox flex="1">
<caption label="Resumen" />


 
<hbox>
<vbox>
<label value="Base Imponible"/>
<textbox id="baseimponible" value="" style="text-align: right;color:gray" readonly="true"/>
</vbox>
<vbox>
<label value="Importe IVA" />
<textbox id="importeiva" value="" style="text-align: right;color:gray" readonly="true"/>
</vbox>

<vbox>
<label value="Total Factura" style="font-weight: bold"/>
<textbox id="totalfactura" value="" style="text-align: right;color:gray" readonly="true"/>
</vbox>

</hbox>
</groupbox>
<hbox id="cajaModoModificacion" collapsed="true">
    <button image="img/printicon.png" label="Imprimir" oncommand="VentanaImprimirFactura()"/>
    <button image="img/save.gif" label="Guardar cambios" oncommand="GuardarCambios()"/>
    <button image="img/pdf16.gif" label="Bajar PDF"  collapsed="true"/>
    <button image="img/mensaje16.gif" label="Enviar por correo" oncommand=""/>
    <spacer flex="1"/>
</hbox>
<hbox id="cajaModoAlta">
    <spacer flex="1"/>
    <button image="img/enviar.png" label="Crear factura" oncommand="EnviarFactura()"/>
    <button image="img/button_cancel.png" label="Cancelar factura" oncommand="CancelarFactura()"/>
</hbox>
<script type="application/x-javascript" src="js/basico.js?ver=1/<?php echo rand(0,99999999); ?>"/>
<script type="application/x-javascript" src="js/debug.js?ver=1/<?php echo rand(0,99999999); ?>"/>
<script><![CDATA[


function toggleDesde(){

	if ( !this.colapsado ){
		$("bloqueDesde").setAttribute("collapsed","true");
		//$("captionclick").setAttribute("label","Cabecera de factura ∆");
		this.colapsado = true;
	} else {
		$("bloqueDesde").setAttribute("collapsed","false");


		//$("captionclick").setAttribute("label","Cabecera de factura ∇");
		this.colapsado = false;
	}
}





var Factura = new Object();

Factura.numero = 3;
Factura.serie = "A";
Factura.numerofactura= "A-3";
Factura.fecha = "<?php echo date("d-m-Y") ?>";
Factura.IdCliente = 0;
Factura.IdFactura = 0;

]]></script>
<script type="application/x-javascript" src="js/modfacturas.js?ver=1/<?php echo rand(0,99999999); ?>"/>


</window>