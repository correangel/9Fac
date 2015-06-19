<?php

include("tool.php");

include_once("phppdf/html2fpdf.php");

$formato = $_REQUEST["formato"];
    
    
function get_include_contents($filename) {
    if (is_file($filename)) {
        ob_start();
        include $filename;
        $contents = ob_get_contents();
        ob_end_clean();
        return $contents;
    }
    return false;
}

    
/* Sanitifacion de tipos */    
switch($modo){
  case "factura":
    $tipodocumento = "factura";
    break;      
  case "albaran":
  case "pedido":
  case "presupuesto":
	$tipodocumento = $modo;
	break;


  default:
    $tipodocumento = "desconocido";
    break;
}        
        
    
/* Estados */    
$tipo = $_REQUEST["tipo"];   
switch($modo){
  case "cargardocumento":
    if ($tipo=="factura" || $tipo=="presupuesto" || $tipo=="albaran" || $tipo=="pedido"){
          $out = get_include_contents("inc/generacion".$tipo.".inc.php");
          if ($formato=="pdf"){
                          
            $pdf = new HTML2FPDF();
            $pdf->AddPage();
            $pdf->WriteHTML($out);
            $pdf->Output($tipo."_doc.pdf","D");
          
          } else {              
            echo $out;
          }
        
        
          exit();
    }              
    break;
	default:
		//die( "ERROR: $modo" );
		break;

}        
    
    

StartXul("moddocumentos");

?>
	<toolbar id="a-toolbar" >
	 	<toolbarseparator />
		<toolbarbutton image="img/colorprint30.png" label="Imprimir" oncommand="Accion_Imprimir()"/>		
		<toolbarbutton image="img/pdf16.gif" label="Guardad como PDF" oncommand="Accion_CogerPDF()" collapsed="true"/>
                 <spacer flex="1"/>
                <toolbarbutton image="img/exit16.png" label="Cerrar" oncommand="close()"/>		
	</toolbar>		
<vbox flex="1">       
       <html:iframe name="visor" id="visor" src="" flex="1" style="border: 0px"/>
</vbox>

<script><![CDATA[

var moduloAjax ="moddocumento.php";
  
var Documento = new Object();    
Documento.tipo = "<?php echo $tipodocumento?>";
    

function Startup(){    
      try {
      	$("moddocumentos").setAttribute("onload","CargarDocumento()");
      } catch(e){      
      }      
}
/*---------- Carga de documemntos ----------------------*/    

var ventana;
    
function CargarDocumento(){

	/*
      var obj = new XMLHttpRequest();
      var url = moduloAjax;
	  var data = "&modo=cargardocumento&tipo=" + encodeURIComponent(Documento.tipo);
      obj.open("POST",url,false);
      obj.send(data);
      var text = obj.responseText;
            
      ventana =  open("about:blank","visor");
      ventana.document.writeln(text);
      ventana.document.close();
      ventana.opener = window;*/

      var url = moduloAjax + "?&modo=cargardocumento&tipo=" +
		encodeURIComponent(Documento.tipo) + "&r="+ Math.random() + "&id=" + <?php echo intval($_GET["id"]) ?>;

	  //$("visor").setAttribute("src",url);

	  ventana = open(url,"visor");


	//alert("hola");
}
    

    
function Accion_Imprimir(){
    if (!ventana) return alert("Caracteristica no soportada por su navegador");

	ventana.print();

	//$("visor").print();
}
    
    
/*---------- Carga de documemntos ----------------------*/    

/*---------- Carga PDF ----------------------*/        
 
function    Accion_CogerPDF(){
    

       
       $("visor").setAttribute("src",moduloAjax + "?modo=cargardocumento&formato=pdf&tipo=" + encodeURIComponent(Documento.tipo));
}
    
/*---------- Carga PDF ----------------------*/            
    

/*---------- Incializacion ----------------------*/    

Startup();

    
]]></script>    

</window>