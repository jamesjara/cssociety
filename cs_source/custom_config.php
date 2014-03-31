<?php

//obtiene el subdominio si no existe, toma los datos por default.
$domain = array_shift(explode(".",$_SERVER['HTTP_HOST']));
if( file_exists( sprintf ('clients/%s.a',$domain) ) ){
	define("conf_domain",  $domain );
	define("conf_css",    sprintf ('clients/%s.c',$domain)  );
	define("conf_a",     sprintf ('clients/%s.a',$domain)  );
} else {
	define("conf_domain", 'default' );
	define("conf_css",    'clients/default.c' );
	define("conf_a",      'clients/default.a' );
	define("conf_cdn",   "" );
	if(!file_exists( sprintf ('clients/%s.a', conf_domain ) ))die("error app");
}

//obtener brand css
$CSS_CUSTOM = file_get_contents( conf_css );
$ini_array = parse_ini_file(  conf_a  );

//obtener brand title
define("conf_brand", $ini_array['brand'] );

//obtener brand logo
define("conf_logo", $ini_array['logo'] );

//obtener maximo de fondos
define("conf_max", $ini_array['max'] );
define("EW_CONN_USER", $ini_array['u']     );
define("EW_CONN_DB", $ini_array['d']     );

//API Key - see http://admin.mailchimp.com/account/api
define("conf_apikey", 		$ini_array['apikey'] );

// A List Id to run examples against. use lists() to view all
// Also, login to MC account, go to List, then List Tools, and look for the List ID entry

define("conf_listId", 		$ini_array['listId'] );

// A Campaign Id to run examples against. use campaigns() to view all
define("conf_campaignId", 	$ini_array['campaignId'] );
define("conf_apiUrl", 		$ini_array['apiUrl'] );


define("conf_leftmenu", 	$ini_array['leftmenu'] );


define("conf_cdn",   $ini_array['cdn'] );
define("conf_cdn_path",   $ini_array['cdn2'] );
define("conf_cdn_full",   conf_cdn.conf_domain );
define("conf_cdn_photos",   $ini_array['cdn3'] );

define("cdn_a", 'http://cdn.registrodemascotas.co.cr/a/');
define("cdn_www", 'http://cdn.registrodemascotas.co.cr/d/');
define("cdn_law", 'http://ley.registrodemascotas.co.cr');
define("cdn_logo", 'http://localhost/cssociety/cs_source//uploads/default/logo.png');
	
	
?>