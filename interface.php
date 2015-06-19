<?php

include("tool.php");

$id = $_SESSION["usuarioLogueado"];

if (!$id){
	header("Location: entrar.php?nota=volverlogin&id=$id");
	exit();
}



StartXul();


?>
<hbox style="background-color: white">
	<hbox flex="1">
		<description style="font-size: 19px;weight: bold;"> </description><description style="font-size: 18px;weight: bold;color: black">Panel de Control</description>
	</hbox>
	<button image="img/exit16.png" label=" Cerrar sesión" oncommand="document.location ='panel.php'"/>	
</hbox>
  <tabbox flex="1">
    <tabs style="background-color: white; padding-top: 4px">
		<tab image="img/cliente16.png" label="Clientes"/>
    	<tab image="img/proveedores.gif" label="Proveedores"/>
    	<tab image="img/productos.png" label="Productos"/>
		<tab image="img/facturas.png" label="Facturas"/>
		<tab image="img/facturas.png" label="Presupuestos"/>
		<tab image="img/facturas.png" label="Pedidos"/>
		<tab image="img/facturas.png" label="Albaran"/>
		<tab image="img/usuarios.gif" label="Usuarios" collapsed="true"/>
		<tab image="img/config16.png" label="Configuracion"/>
	</tabs>
  
    <tabpanels flex="1" id="paneles">
     <tabpanel>
        <vbox flex="1">       
       <html:iframe  id="page_clientes" src="modclientes.php" flex="1" style="border: 0px"/>
        </vbox>
     </tabpanel>  
     <tabpanel>
       <html:iframe  id="page_proveedores" src="modproveedores.php" flex="1"  style="border: 0px"/>
     </tabpanel> 
     
     <tabpanel>
       <html:iframe  id="page_productos" src="modproductos.php" flex="1" style="border: 0px"/>
     </tabpanel>         
     
     <tabpanel>
       <html:iframe  id="page_facturas" src="modfacturas.php" flex="1" style="border: 0px"/>
     </tabpanel>    
     
     <tabpanel>
       <html:iframe  src="modpresupuestos.php" flex="1" style="border: 0px"/>
     </tabpanel>  
     <tabpanel>
       <html:iframe  src="modpedidos.php" flex="1" style="border: 0px"/>
     </tabpanel>  
     <tabpanel>
       <html:iframe  src="modalbaran.php" flex="1" style="border: 0px"/>
     </tabpanel>  
     <tabpanel>
       <html:iframe  src="modusuarios.php" flex="1" style="border: 0px"/>
     </tabpanel>  
     <tabpanel>
       <html:iframe  src="modconfiguracion.php" flex="1" style="border: 0px"/>
     </tabpanel>  
        
	</tabpanels>     
  </tabbox>
</window>
