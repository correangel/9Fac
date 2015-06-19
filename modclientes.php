<?php

include("tool.php");
include_once("clases/json.class.php");
include_once("inc/clientes.inc.php");	
	
	
StartXul();

?>   
<hbox flex="1">
<groupbox flex="1">
<caption label="Listado"/>
<listbox  flex="1" id="listadoUsuarios">
</listbox>
</groupbox>
<groupbox flex="4">
<caption label="Gestion" />

<hbox align="center">
	<spacer flex="1"/><image style="width: 48px; height: 48px" src="img/toctoc.gif"/>
	<description style="font-size: 120%">GESTION DE CLIENTES</description>
	<spacer flex="1"/>
</hbox>

<groupbox id="CreandoNuevo">
<caption label="Nuevo"/>
<hbox>	
	<button image="img/addcliente.png" label="Nuevo cliente" oncommand="AddNewUser()"/>
	<textbox id="nombreUsuarioAlta" value=""/>	
	<spacer flex="1"/>
</hbox>
</groupbox>

<spacer style="height: 8px"/>

<groupbox id="ModificandoUsuario" pack="center" flex="1">
<caption label="Editar"/>

<hbox flex="1">
<vbox flex="1"> 
<hbox><checkbox id="activo" label="Cuenta activada"/></hbox>
<hbox align="center"><caption class="labelCliente" label="Nombre comercial"/>
	<textbox id="NombreComercial" class="datoNombre"/></hbox>
<hbox align="center"><caption class="labelCliente" label="Nombre legal" />
	<textbox id="NombreLegal" class="datoNombre"/></hbox>
<hbox align="center"><caption  class="labelCliente" label="Codigo postal"/><textbox id="CP"  class="datoCP"/></hbox>
<hbox align="center"><caption class="labelCliente"  label="Telf.(1)"/><textbox id="Telefono1"  class="datoTelefono"/></hbox>
<hbox align="center"><caption class="labelCliente"  label="Telf.(2)"/><textbox id="Telefono2"  class="datoTelefono"/></hbox>
<hbox align="center"><caption class="labelCliente" label="CC"/><textbox id="CuentaBancaria"/></hbox>
<hbox align="center"><caption  class="labelCliente" label="Numero fiscal"/><textbox id="NumeroFiscal"/></hbox>
<!--<hbox align="center"><caption  class="labelCliente" label="Fecha nacimiento"/><textbox value="DD/MM/AA"  id="FechaNacim"/></hbox>-->
<!-- <hbox align="center"><caption class="labelCliente"  label="Pais"/><textbox value="España" id="Pais"/></hbox> -->
</vbox>
<vbox flex="1">
<hbox><caption label=" "/></hbox>
<hbox align="center"><caption  class="labelCliente" label="Direccion"/><textbox id="Direccion" class="datoDireccion"/></hbox>
<hbox align="center"><caption  class="labelCliente" label="Localidad"/><textbox id="Localidad"  class="datoLocalidad"/></hbox>
<hbox align="center"><caption class="labelCliente" label="Pagina web"/><textbox id="PaginaWeb" flex="1"/></hbox>
<html:hr/>
<hbox align="center"><caption class="labelCliente" label="Contacto"/><textbox id="Contacto"/></hbox>
<hbox align="center"><caption class="labelCliente" label="Cargo"/><textbox id="Cargo"/></hbox>
<hbox align="center"><caption class="labelCliente" label="Comentarios"/><textbox flex="1" class="datoComentarios" multiline="true" id="Comentarios"/></hbox>	
<hbox align="center">
</hbox>
</vbox>
</hbox>


<hbox pack="center">
<!--<caption class="labelCliente"/>-->	
	<button class="simpleButton" image="img/modcliente.png" label="Guardar cambios" oncommand="UpdateUser()"/>
   <button class="simpleButton" image="img/borrarcliente.png" label="Eliminar" oncommand="BorrarUser()"/>
</hbox>
<html:hr/>
<spacer flex="8"/>

</groupbox>

</groupbox>
</hbox>
<script type="application/x-javascript" src="js/ilumina.js?ver=1/<?php echo rand(0,99999999); ?>"/>
<script type="application/x-javascript" src="js/modclientes.js?ver=1/<?php echo rand(0,99999999); ?>"/>
<script>//<![CDATA[

// <?php echo $modo ?>
// <?php echo $_REQUEST["modo"] ?>

<?php

GenerarJSUsers();

?>

//]]></script>
</window>	