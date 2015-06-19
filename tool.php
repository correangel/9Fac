<?php

ini_set("session.gc_maxlifetime",    "86400");
ini_alter("session.cookie_lifetime", "86400" );
ini_alter("session.entropy_file","/dev/urandom" );
ini_alter("session.entropy_length", "512" );

session_start();

$modo = (isset($_REQUEST["modo"])?$_REQUEST["modo"]:false);



include_once("config/config.php");
include_once("inc/debug.inc.php");
include_once("inc/clean.inc.php");
include_once("inc/db.inc.php");
include_once("inc/xul.inc.php");
include_once("inc/supersesion.inc.php");

include_once("clases/json.class.php");//comunicacion


include_once("clases/cursor.class.php");
include_once("clases/cliente.class.php");
include_once("clases/proveedor.class.php");
include_once("clases/factura.class.php");
include_once("clases/usuario.class.php");
include_once("clases/perfil.class.php");
include_once("clases/producto.class.php");
include_once("inc/negocio.inc.php");





?>