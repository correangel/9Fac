<?php

include_once("tool.php");
include_once("clases/json.class.php");
include_once("inc/usuarios.inc.php");	

	
	
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
	<description style="font-size: 120%">GESTION DE USUARIOS</description>
	<spacer flex="1"/>
</hbox>

<groupbox id="CreandoNuevo">
<caption label="Nuevo"/>
<hbox>	
	<button image="img/addcliente.png" label="Nuevo usuario" oncommand="AddNewUser()"/>
	<textbox id="nombreUsuarioAlta" value=""/>	
	<spacer flex="1"/>
</hbox>
</groupbox>

<spacer style="height: 8px"/>

<groupbox id="ModificandoUsuario" pack="center" flex="1">
<caption label="Editar"/>

<hbox flex="1">
<vbox flex="1"> 

<hbox align="center"><caption class="labelCliente"  label="Usuario"/><textbox id="Identificacion"/></hbox>
<hbox align="center"><caption class="labelCliente"  label="Contraseña"/><textbox id="Password"/></hbox>
<html:hr/>
<hbox align="center"><caption class="labelCliente"  label="Nombre"/><textbox id="Nombre" class="datoNombre"/></hbox>
<hbox align="center"><caption class="labelCliente"  label="Direccion"/><textbox id="Direccion"  class="datoDireccion"/></hbox>
<!-- <hbox align="center"><caption class="labelCliente"  label="Localidad"/><textbox id="Localidad"/></hbox>-->
<!--<hbox align="center"><caption class="labelCliente"  label="Codigo postal"/><textbox id="CP"/></hbox>-->
<hbox align="center"><caption class="labelCliente"  label="Telf"/><textbox id="Telefono"  class="datoTelefono"/></hbox>
<hbox align="center"><caption class="labelCliente"  label="CC"/><textbox id="CuentaBanco"  class="datoCC"/></hbox>
<hbox align="center"><caption class="labelCliente"  label="Fecha nacim."/><textbox id="FechaNacim" class="datoFecha" /></hbox>
<!-- <hbox align="center"><caption class="labelCliente"  label="Comision"/><textbox id="Comision"/></hbox> -->
<!--<hbox align="center"><caption class="labelCliente"  label="TODO: perfil"/></hbox>-->

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
<script type="application/x-javascript" src="js/modusuarios.js?ver=1/<?php echo rand(0,99999999); ?>"/>
<script>//<![CDATA[

// <?php echo $modo ?>
// <?php echo $_REQUEST["modo"] ?>

<?php

GenerarJSUsers();

?>

//]]></script>
</window>	