<?php


include("inc/albarans.inc.php");
//include("inc/negocio.inc.php");

echo str_replace("#","?",'<#xml version="1.0" encoding="UTF-8"#>');


$IdAlbaran = CleanID($_REQUEST["id"]);

$d = queryrow("SELECT * FROM ges_albarans WHERE IdAlbaran='$IdAlbaran'");


$row = DatosNegocio();
//$serie = $row["SerieDefecto"];
$IdFormato = $row["IdTipoNumeracionAlbaran"];

$serie = $d["SerieAlbaran"];
$numero = $d["NAlbaran"];
$IdFormato = $row["IdTipoNumeracionAlbaran"];

$numeroseriealbaran = getAlbaranFormateada($serie,$numero,$IdFormato);

$numeroseriealbaran = $d["SerieNumeroAlbaran"];


function PrintMoney($money){
	return sprintf("%.02f",$money);
}


$format = "<tr><td colspan='2'>&gt;&gt; %s</td><td><p align='right'><nobr>%s &euro;</nobr></p></td><tr>\n";

$componente = 0;

$out = "";
$sql = "SELECT * FROM ges_albarans_det WHERE IdAlbaran='$IdAlbaran'";

$res = query($sql);

while($row = Row($res)){
	$out .= sprintf($format,$row["Concepto"], PrintMoney($row["Importe"])  );
	$componente += (float)$row["Importe"];
}


$htmlLineasAlbaran = $out;


$totalMasIva = $componente;
$totalIva = $totalMasIva * 0.16;
$totalAlbaran = $totalMasIva - $totalIva;




?>
<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es" lang="es">
<head>
<title>Albaran </title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<style>

* {
 font-size: 12px;
}

body {
    background-color: white;
    
}

.tengobackground_nova {
    display: list-item;               
    list-style-image: url(img/bgray.gif);
    list-style-position: inside;           
    overflow: none;
}

.headerImg {
position:relative;
left:0px;
top:16px;
z-index:-20;
}

.headerText {
position:relative;
left:0px;
top:-16px;
color:#FFFFFF;
font-size:16px;
z-index:800;
}


</style>

</head>
<body>
    
<pre>

<?php

//secho print_r($d);

?>

</pre>

<table width="100%">
<tr><td><img src="http://www.servicios-dpi.com/imgnew/logocenter190w.jpg"/></td><td></td><td></td><td></td></tr>
<tr><td width="60%"></td><td>NºFACTURA</td><td width="10%"></td><td><p align="right"><?php echo html($numeroseriealbaran) ?></p></td></tr>
<tr><td></td><td>FECHA</td><td></td><td><p align="right"><?php echo CleanFechaFromDB($d["FechaAlbaran"]) ?></p></td></tr>
<tr><td></td><td><nobr>IMPORTE A PAGAR</nobr></td><td></td><td><p align="right"><?php echo PrintMoney($totalMasIva) ?> &euro;</p></td></tr>
</table>
<hr>
<p>
Cliente:<br>
<table>
<tr><td></td><td width="5%"></td></tr>
<tr><td>&gt;&gt; Nombre:</td><td></td><td colspan="2"><nobr><?php echo html($d["NombreComercial"] ) ?> </nobr></td></tr>
<tr><td>&gt;&gt; Dirección:</td><td></td><td><?php echo $d["Direccion"] ?></td></tr>
<tr><td>&gt;&gt; NIF/CIF:</td><td></td><td><?php echo $d["CIF"] ?></td><td></td><td></td><td></td></tr>
<!-- <tr><td>&gt;&gt; Telefono:</td><td></td><td>976 71 30 25</td><td>&gt;&gt; Poblacion:</td><td></td><td>Zaragoza</td></tr> -->
<tr><td>&gt;&gt; Cod.Postal:</td><td></td><td><?php echo $d["CodigoPostal"] ?></td><td>&gt;&gt; Poblacion:</td><td></td><td><?php echo $d["Poblacion"] ?></td></tr>

</table>
<hr>
<p>&nbsp;</p>
<table width="100%">
<tr>
<td  class="tengobackground" width="100%" bgcolor="gray" color="white" colspan="4"  background="img/bgray.gif" style="background-image: url(img/bgray.gif)">
   <div class="tengobackground">
  <table width="100%"> <td width="100%"></td>
  <tr><td>
  &nbsp;&nbsp;&nbsp;<b><font color="white">Concepto: <?php echo html($d["ObraREalizada"]) ?></font></b></td>
  <td color="white">&nbsp;&nbsp;&nbsp;<b><font color="white"><nobr>Precio Sin Iva</nobr></font></b></td>
  </table>
  </div>
</tr>
<tr><td width="100%"></td><td></td><tr>
<!--
<tr><td colspan="2">&gt;&gt; ALOJAMIENTO WEB ENERO</td><td><p align="right"><nobr>65,00 &euro;</nobr></p></td><tr>

<tr><td colspan="2">&gt;&gt; ALOJAMIENTO WEB FEBRERO</td><td><p align="right"><nobr>135,00 &euro;</nobr></p></td><tr>

 -->

<?php echo  $htmlLineasAlbaran; ?>

<tr><td colspan="3"><hr></td><tr>
<tr><td></td><td><p align="right"><nobr>Importe Neto: </nobr></p></td><td><p align="right"><nobr><?php echo PrintMoney($totalAlbaran) ?> &euro;</nobr></p></td><tr>
<tr><td></td><td><p align="right"><nobr>Iva (16%): </nobr></p></td><td><p align="right"><nobr><?php echo PrintMoney($totalIva) ?> &euro;</nobr></p></td><tr>
<tr><td></td><td colspan="2"><hr></td><tr>
<tr><td></td><td><p align="right"><nobr>Importe Total: </nobr></p></td><td><p align="right"><nobr><?php echo PrintMoney($totalMasIva) ?> &euro;</nobr></p></td><tr>
<tr></tr>
</table>

<p>&nbsp;</p>
<p>&nbsp;</p>
<p>Pagos por transferencia: <b> LA CAIXA: 2100 4518 50 220002518</b></p>
<p><small>No se olvide de especificar el nº de albaran en la transferencia.</small></p>




</p>

</body>
</html>
