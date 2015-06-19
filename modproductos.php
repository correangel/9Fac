<?php

include_once("tool.php");
include_once("clases/json.class.php");
include_once("inc/productos.inc.php");	
	
	
class modproductos {
	var $text;

};	
	
StartXul();

?>  

	<toolbar id="a-toolbar" >
	 	<toolbarseparator />
		<toolbarbutton image="img/producto16.png" label="Alta" oncommand="BotonEsAlta()"/>		
		<toolbarbutton image="img/busca1.gif" label="Buscar" oncommand="Accion_BuscarProducto()"/>
		<toolbarbutton collapsed="true" image="img/producto.gif" label="Guardar cambios" oncommand="UpdateUser()"/>
		<toolbarbutton image="img/remove.gif" label="Eliminar" oncommand="Accion_BorrarProducto()"/>
	</toolbar>		
	
<groupbox>




<caption label="Caracteristicas" id="datosProducto"/>
<!-- alta de prod -->
<grid flex="1">
<rows flex="1"> 
<row><caption class="media" label="Referencia"/><textbox style="width:30em" class="media" id="Referencia" value="<?php echo $Referencia ?>"/></row>
<row><caption class="media" label="Ref Proveedor"/><textbox class="media" id="RefProvHab"/></row>
<row><caption class="media" label="Nombre"/><textbox class="media" id="Nombre" value="<?php echo $Nombre ?>"/></row>
<row><caption class="media" label="Descripcion"/><textbox class="media" multiline="true" id="Descripcion"/></row>
<row><caption class="media" label="Coste"/><textbox class="media" id="CosteSinIVA" value=""/></row>
<row><caption class="media" label="PVP"/><textbox class="media" id="PrecioVenta" value=""/></row>
<row collapsed="true"><caption class="media" label="Marca"/><box><toolbarbutton style="width: 32px !important" oncommand="CogerMarca()" label="+"/><textbox class="media" id="Marca" value="<?php echo $Marca ?>"  flex="1"/></box></row>
<row collapsed="true"><caption class="media" label="Prov. hab"/><box><toolbarbutton style="width: 32px !important" oncommand="CogeProvHab()" label="+"/><textbox class="media" id="ProvHab" readonly="true" flex="1"/></box></row>
<row collapsed="true"><caption class="media" label="Fam/Subfam"/><box><toolbarbutton style="width: 32px !important" oncommand="CogeFamilia()" label="+"/><textbox value="<?php echo $FamDefecto; ?>" flex="1" id="FamSub"/></box></row>
<row collapsed="true"><caption class="media" label="Tallaje"/><box><toolbarbutton style="width: 32px !important" oncommand="CogeTallaje()" label="+"/><textbox readonly="true" class="media" id="Tallaje" flex="1"/></box></row>
<row><box><spacer flex="1" style="height: 8px"/></box><box/></row>
<row>
	<box><spacer flex="1" style="height: 8px"/></box>	
	<hbox flex="1" >
		<button class="simpleButton" image="img/save.gif" label="Alta" id="boton-guardar" oncommand="Guardar_Alta()"/>
	</hbox>	
	
	
</row>
		
</rows>
</grid>

<hbox>

</hbox>
<!-- alta de prod -->
</groupbox>

<script type="application/x-javascript" src="js/basico.js?ver=1/<?php echo rand(0,99999999); ?>"/>
<script type="application/x-javascript" src="js/debug.js?ver=1/<?php echo rand(0,99999999); ?>"/>
<script type="application/x-javascript" src="js/ilumina.js?ver=1/<?php echo rand(0,99999999); ?>"/>
<script type="application/x-javascript" src="js/modproductos.js?ver=1/<?php echo rand(0,99999999); ?>"/>
<script>//<![CDATA[


function Foco(){




}


window.onload = Foco();

//]]></script>


</window>	