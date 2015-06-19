

function isString(o) {
  return (typeof(o)=="string");
}



function VerObj(obj){
	var wF = "centerscreen=yes,dependent=yes,resizable=yes";
	var visor = open("about:blank","pagina",wF,"text/plain");

	if (isString(obj))
		var text = obj;
	else
		var text = props(obj);	
	
	visor.document.writeln( "obj:" + typeof(obj) + "<body style='overflow:scroll'><br><xmp style='overflow:scroll'>"+ text +"</xmp></body>" );
	visor.document.close();	
}

 
function props(obj){
	var str=""; 
	for(prop in obj){
		str+=prop + " ->"+ obj[prop]+"\n";
	}
	return str;
}
 
/*--------------------------------*/





 
