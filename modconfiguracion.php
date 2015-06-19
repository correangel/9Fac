<?php

require("tool.php");
	
	
	
StartXul();

?>   
<hbox zstyle="background-color: white">
	<hbox flex="1" collapsed="true">
		<description style="font-size: 19px;weight: bold;"> </description><description style="font-size: 18px;weight: bold;color: black">Panel de Configuración</description>
	</hbox>
	<button collapsed="true" image="img/exit16.png" label=" Cerrar sesión" oncommand="document.location ='panel.php'"/>	
</hbox>
  <tabbox flex="1">
    <tabs zstyle="background-color: white; padding-top: 4px">
		<tab image="img/cliente16.png" label="Usuarios"/>
		<tab image="img/cliente16.png" label="Perfiles"/>
	</tabs>
  
    <tabpanels flex="1" id="paneles">
     <tabpanel>
        <vbox flex="1">       
       <html:iframe  src="modusuarios.php" flex="1" style="border: 0px"/>
        </vbox>
     </tabpanel>  

     <tabpanel>
        <vbox flex="1">       
       <html:iframe  src="modperfiles.php" flex="1" style="border: 0px"/>
        </vbox>
     </tabpanel>  
        
	</tabpanels>     
  </tabbox>
<script>

function $(enticosa){
	return document.getElementById(enticosa);	
}

function pc(entidad,atributo,valor){
	var xent;
	if (!(xent = $(entidad))) return;
	xent.setAttribute(atributo,valor);		
}


</script>



</window>