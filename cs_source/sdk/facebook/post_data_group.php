<?php
ini_set( 'default_charset', 'UTF-8' );

require 'facebook-php-sdk-master/src/facebook.php';

// Create our Application instance (replace this with your appId and secret).
$facebook = new Facebook(array(
  'appId'  => '296166450514839',
  'secret' => 'b1120eec7624941d38b64db17f1c3004',
));

$grupo_post_id = $_GET['id'];//'214267468618834' ; //$_SESSION['gidp']; 

if ( !isset( $grupo_post_id) ) die(); 

// Get User ID
$user = $facebook->getUser();

// We may or may not have this data based on whether the user is logged in.
//// If we have a $user id here, it means we know the user is logged into
// Facebook, but we don't know if the access token is valid. An access
// token is invalid if the user logged out of Facebook.

if ($user) {
  try {
    // Proceed knowing you have a logged in user who's authenticated.
    $user_profile = $facebook->api('/me');
  } catch (FacebookApiException $e) {
    error_log($e);
    $user = null;
  }

  
$feed_dir       = "/214267468618834/feed/";
$msg_body       = array (
        'access_token'  =>  $facebook->getAccessToken(),           
        'message'       => 'Jingle bell jingle bell jingle all the way',            
        'picture'       => 'http://assets.kompas.com/data/photo/2012/08/26/0958534620X310.jpg',
    );

try {
    $result = $facebook->api($feed_dir, 'post', $msg_body);
    var_dump($result);
} catch (Exception $e) {
    $err_str = $e->getMessage();
    var_dump($err_str);
}  

 
}


// Login or logout url will be needed depending on current user state.
if ($user) {
  $logoutUrl = $facebook->getLogoutUrl();
} else {
  $loginUrl = $facebook->getLoginUrl(array(
			'scope' => 'user_groups,friends_groups,user_website,user_work_history,user_relationships,publish_actions'
  ));
  //$loginUrl = $facebook->getLoginUrl();
}

if ($user)  echo '<a href="'.$logoutUrl.'">logoutUrl</a>'; 
 else echo '<a href="'.$loginUrl.'">loginUrl</a>'; 

//print_r($_SESSION); 
//print_r($user_profile); 



function check_post_exists( $table, $id ){
	$link = mysql_connect('localhost', 'root', '');
	if (!$link) {
		die('Could not connect: ' . mysql_error());
	}
	if (!mysql_select_db('cslatam')) {
		die('Could not select database: ' . mysql_error());
	}
	$result = mysql_query('SELECT * FROM `'.$table.'` where id = "'.$id.'"');
	if (!$result) {
		die('Could not query:' . mysql_error());
	}
	 if(mysql_fetch_array($result) !== false)
        return true;
    return false;
}

function check_if_column_exists( $table, $column ){
	$link = mysql_connect('localhost', 'root', '');
	if (!$link) {
		die('Could not connect: ' . mysql_error());
	}
	if (!mysql_select_db('cslatam')) {
		die('Could not select database: ' . mysql_error());
	}
	if (false === mysql_query('select  '.$column .'  from `'.$table.'` limit 0'))  return false;
    return true;
}
function insert_value( $column , $value , $super_id  ){}

function func($value) {
		return @mysql_real_escape_string( $value );
};
function insert_values( $table,  $super_id , $_fields  ){
	$link = mysql_connect('localhost', 'root', '');
	if (!$link) {
		die('Could not connect: ' . mysql_error());
	}
	if (!mysql_select_db('cslatam')) {
		die('Could not select database: ' . mysql_error());
	}
	mysql_query('SET NAMES "utf8"'); 
	//$escapedfieldValues = array_map(create_function('$e', 'return mysql_real_escape_string(((get_magic_quotes_gpc()) ? stripslashes($e) : $e));'), array_values($_fields));
    $escapedfieldValues = array_map( 	'func' 
										, array_values($_fields)
									);
    $sql = sprintf('INSERT INTO `'.$table.'` (`%s`) VALUES ( "%s")', implode('`,`',array_keys($_fields)), implode('","',$escapedfieldValues));
	$result = mysql_query( $sql );
	if (!$result) {
		die('Could not query:' . mysql_error());
	}
	 if(@mysql_fetch_array($result) !== false)
        return true;
    return false;	
}

function create_new_column( $table, $column ){
	$link = mysql_connect('localhost', 'root', '');
	if (!$link) {
		die('Could not connect: ' . mysql_error());
	}
	if (!mysql_select_db('cslatam')) {
		die('Could not select database: ' . mysql_error());
	}
	if (false === mysql_query( ' ALTER TABLE `'.$table.'` ADD `'.$column .'` VARCHAR(245)  ' ))  return false;
    return true;
}