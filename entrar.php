<?php

include("tool.php");

//SimpleAutentificacionAutomatica("visual-xul");


$_motivoFallo = "";


$NombreEmpresa = $_SESSION["GlobalNombreNegocio"];


$modo = $_REQUEST["modo"];

$_log = "";

function AddLog($text){
	global $_log;
	$_log = $_log . $text . "\n";
}

function identificaUsuarioValido($usuario,$pass){
	if (!$usuario or !$pass)
		return 0;

	$s_usuario = sql(trim($usuario));
	$s_pass	   = sql(trim($pass));

	$sql = "SELECT IdUsuario as id FROM ges_usuarios WHERE ((Identificacion='$s_usuario') AND (Password='$s_pass'))";

	$row = queryrow($sql);

	return $row["id"];
}



AddLog("Empieza modo es '$modo'");



switch($modo){
    case "avisoUsuarioIncorrecto":
	case "login-usuario":
	case "login-user":
	case "login-tpv":
	case "login-admin":
	case "tiendaDesconocida":
	case "login-local":
		$login = CleanLogin($_POST["login"]);
		$pass =  CleanPass($_POST["pass"]);
		//die("Cargando login/pass '$login/$pass'");

		$user = true;
		if ($login and $pass){
			//$id = identificacionUsuarioValidoMd5($login,md5($pass));
			$id = identificaUsuarioValido($login,$pass);
			if ($id){

				$_SESSION["usuarioLogueado"] = $id;


		//		die("hola!");
                session_write_close();
                header("Location: interface.php?r=" . rand(900000,999999) ."&id=" . $id);
                exit;
			} else {
				$fail = "Nombre ('$login') o password ('$pass') incorrectas: $_motivoFallo";
				AddLog("Falla identificacion.");
			}
		}
		break;
	default:
		break;
}

StartXul("Login Programa");


?>
<box flex="1" style="background-image: url(img/mega2.png)">
<spacer flex="1" />
   <vbox>
   <spacer flex="1" />
	<groupbox style="width: 400px;height: 200px;background-color: #ECE8DE">
	 	<spacer flex="1"/>
		<vbox>
			<description style="font-weight: bold;color: #e96f00">Servicios - DPI:  Programa de Facturación</description>
			<grid>
				<columns>
					<column style="width: 200px"/>
					<column/>
					<column flex="1"/>
				</columns>
				<rows>
					<row>
					<hbox><spacer flex="1" style="width: 40px"/>
											<description>Usuario</description>
					</hbox>
						<textbox id='nombrelocal' type="normal"
						 onkeypress="if (event.which == 13) document.getElementById('passlocal').focus()"/>
					</row>
					<row>
					<hbox><spacer flex="1"/>
						<description>
						<?php	echo _("Contraseña");?>
						</description>
					</hbox>
                        <textbox id='passlocal' type='password'/>
					</row>
					<row  align="start">
						<description><image style="width: 48px; height: 48px" src="img/toctoc.gif" /></description>
                            <hbox flex='1' >
                          <?php
                            echo "<button   label=\"". _("Entrar") ."\" oncommand=\"SaltaLogin('login-local')\"/>";
							?>
                            </hbox>
					</row>
				</rows>
			</grid>
		</vbox>
		<spacer flex="1"/>
   <!-- Es de buen nacido el ser agradecido -->
   <?php if ($config["mostrarbannerdga"] or 1): ?>
   <groupbox>
   <hbox style="background-color: white;padding: 8px">
   <label value="Desarrollado por "  style="margin:0px;border: 1px solid white"/>
   <label value="Servicios-DPI"      style="margin:0px;border: 1px solid white;text-decoration: underline; color: blue"
                                 onclick="open('http://www.servicios-dpi.com')"
                      />
   <label value=", liberado como LGPL. " style="margin:0px;border: 1px solid white"/>                       
   <label value=" Subvencionado por el Gobierno de Aragón "  style="margin:0px;border: 1px solid white"/>
   </hbox>
   </groupbox>
   <?php endif; ?>
   <!-- /Es de buen nacido el ser agradecido -->

	</groupbox>
	<spacer flex="1"/>
	</vbox>
<spacer flex="1"/>
</box>

<box collapsed="true" hidden="true">
<html:form  collapsed="true" hidden="true"
	id="form-enviar" action="entrar.php" method="post">
<html:input collapsed="true" hidden="true" style="visibility: none"
	id="form-empresa" name="login" type="hidden" value=""/>
<html:input collapsed="true" hidden="true" style="visibility: none"
	id="form-pass" name="pass"  type="hidden" value=""/>
<html:input collapsed="true" hidden="true" style="visibility: none"
   	id="form-modo" name="modo" type="hidden" value=""/>
</html:form>
</box>
<script><![CDATA[

function id(nombreEntidad){
 return document.getElementById(nombreEntidad);
}


var findex = 1;
function SaltaLogin(pasoActual){
  var local = document.getElementById("nombrelocal").value;
  var pass = document.getElementById("passlocal").value;
  id("form-empresa").value = local;
  id("form-pass").value = pass;
  id("form-modo").value = pasoActual;
  id("form-enviar").submit();
}


function VisitarLoginEmpresa(){
	document.location = "login.php";
}


// Corregimos el foco para situarse en el primer input box
var ventanamaestra = document.getElementById("login-programa");
ventanamaestra.setAttribute("onload","FixFocus()");

function FixFocus(){
	document.getElementById("nombrelocal").focus();
}



]]></script>
<?php

EndXul();

?>
