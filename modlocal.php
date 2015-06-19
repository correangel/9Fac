<?php

include_once("tool.php");
include_once("clases/json.class.php");
include_once("inc/modlocal.inc.php");
	
	
StartXul();

?>   
<hbox flex="1">

<groupbox flex="4">
<caption label="Gestion" />

<hbox align="center">
	<spacer flex="1"/><image style="width: 48px; height: 48px" src="img/toctoc.gif"/>
	<description style="font-size: 120%">Configuración del negocio</description>
	<spacer flex="1"/>
</hbox>


<spacer style="height: 8px"/>

<groupbox id="ModificandoLocal" pack="center" flex="1">
<caption label="Editar"/>

<hbox flex="1">
<vbox flex="1"> 


<hbox align="center"><caption class="labelCliente"  label="Login"/><textbox id="Identificacion"/></hbox>
<hbox align="center"><caption class="labelCliente"  label="Contraseña"/><textbox id="Password"/></hbox>
<html:hr/>
<hbox align="center"><caption class="labelCliente"  label="Nombre comercial"/><textbox id="NombreComercial" class="NombreComercial"/></hbox>
<hbox align="center"><caption class="labelCliente"  label="Nombre legal"/><textbox id="NombreLegal" class="NombreLegal"/></hbox>
<hbox align="center"><caption class="labelCliente"  label="Nº Fiscal"/><textbox id="NFiscal"  class="NFiscal"/></hbox>
<hbox align="center"><caption class="labelCliente"  label="Direccion factura"/><textbox id="DireccionFactura"  class="DireccionFactura"/></hbox>
<hbox align="center"><caption class="labelCliente"  label="Telf"/><textbox id="Telefono"  class="Telefono"/></hbox>
<hbox align="center"><caption class="labelCliente"  label="Fax"/><textbox id="Fax"  class="Fax"/></hbox>
<hbox align="center"><caption class="labelCliente"  label="Movil"/><textbox id="Movil"  class="Movil"/></hbox>
<hbox align="center"><caption class="labelCliente"  label="Email"/><textbox id="Email"  class="Email"/></hbox>
<hbox align="center"><caption class="labelCliente"  label="Tipo numeracion"/><textbox id="IdTipoNumeracionFactura"  class="IdTipoNumeracionFactura"/></hbox>
<hbox align="center"><caption class="labelCliente"  label="C.C."/><textbox id="CuentaBancaria"  class="CuentaBancaria"/></hbox>



</vbox>
</hbox>


<hbox pack="center">
<!--<caption class="labelCliente"/>-->
	<button class="simpleButton" image="img/save.gif" label="Guardar" oncommand="UpdateLocal()"/>
	<button class="simpleButton" image="img/save.gif" label="TEST" oncommand="CargarDatosLocal(prompt('',1))"/>
	
	
</hbox>
<html:hr/>
<spacer flex="8"/>

</groupbox>

</groupbox>
</hbox>
<script type="application/x-javascript" src="js/basico.js?ver=1/<?php echo rand(0,99999999); ?>"/>
<script type="application/x-javascript" src="js/debug.js?ver=1/<?php echo rand(0,99999999); ?>"/>
<script type="application/x-javascript" src="js/ilumina.js?ver=1/<?php echo rand(0,99999999); ?>"/>
<script type="application/x-javascript" src="js/modlocal.js?ver=1/<?php echo rand(0,99999999); ?>"/>
</window>	