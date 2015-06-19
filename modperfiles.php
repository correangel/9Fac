<?php

include_once("tool.php");
include_once("clases/json.class.php");
include_once("inc/perfiles.inc.php");	
		
StartXul();

?>   
<hbox flex="1">
<groupbox flex="1">
<caption label="Listado"/>
<listbox  flex="1" id="listadoPerfiles">
</listbox>
</groupbox>
<groupbox flex="4">
<caption label="Gestion" />

<hbox align="center">
	<spacer flex="1"/><image style="width: 48px; height: 48px" src="img/toctoc.gif"/>
	<description style="font-size: 120%">GESTION DE PERFILES</description>
	<spacer flex="1"/>
</hbox>

<groupbox id="CreandoNuevo">
<caption label="Nuevo"/>
<hbox>	
	<button image="img/addcliente.png" label="Nuevo perfil" oncommand="AddNewUser()"/>
	<textbox id="nombrePerfilAlta" value=""/>	
	<spacer flex="1"/>
</hbox>
</groupbox>

<spacer style="height: 8px"/>

<groupbox id="ModificandoPerfil" pack="center" flex="1">
<caption label="Editar"/>

<hbox flex="1">
<vbox flex="1">
<hbox align="center"><caption class="labelCliente"  label="Nombre"/><textbox id="NombrePerfil" class="datoNombre flat"/></hbox>
<hbox align="center"><caption class="labelCliente" label=" "/><checkbox  label="administracion" id="Administracion"/></hbox>
<hbox align="center"><caption class="labelCliente" label=" "/><checkbox  label="informes" id="Informes"/></hbox>
<!--<hbox align="center"><checkbox  label="informeslocal" id=""/></hbox>-->
<hbox align="center"><caption class="labelCliente" label=" "/><checkbox  label="productos" id="Productos"/></hbox>
<hbox align="center"><caption class="labelCliente" label=" "/><checkbox  label="proveedores" id="Proveedores"/></hbox>
<hbox align="center"><caption class="labelCliente" label=" "/><checkbox  label="compras" id="Compras"/></hbox>
<!--<hbox align="center"><checkbox  label="stocks" id=""/></hbox>
<hbox align="center"><caption class="labelCliente" label=" "/><checkbox  label="ver stocks" id=""/></hbox>-->
<hbox align="center"><caption class="labelCliente" label=" "/><checkbox  label="clientes" id="Clientes"/></hbox>
<!--<hbox align="center"><checkbox  label="tpv" id=""/></hbox>-->
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
<script type="application/x-javascript" src="js/basico.js?ver=1/<?php echo rand(0,99999999); ?>"/>
<script type="application/x-javascript" src="js/ilumina.js?ver=1/<?php echo rand(0,99999999); ?>"/>
<script type="application/x-javascript" src="js/modperfiles.js?ver=1/<?php echo rand(0,99999999); ?>"/>
<script>//<![CDATA[

// <?php echo $modo ?>
// <?php echo $_REQUEST["modo"] ?>

<?php

GenerarJSUsers();

?>

//]]></script>
</window>	