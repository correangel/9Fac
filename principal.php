<?php


include("tool.php");


echo rand();
echo "<hr>";

$cliente = Cliente(1);

var_export($cliente);

echo "<hr>";

$proveedor = Proveedor(1);

var_export($proveedor);

echo "<hr>";

$factura = Factura(1);

var_export($factura);



?>
