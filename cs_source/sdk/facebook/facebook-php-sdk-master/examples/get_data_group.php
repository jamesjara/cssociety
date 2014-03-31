<?php
ini_set( 'default_charset', 'UTF-8' );

require '../src/facebook.php';

// Create our Application instance (replace this with your appId and secret).
$facebook = new Facebook(array(
  'appId'  => '296166450514839',
  'secret' => '900b5988e648b860ab9ab915f21db5fb',
));

$grupo_id = $_GET['ugid'];//'214267468618834' ; //$_SESSION['gidp']; 

if ( !isset( $grupo_id) ) die(); 

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

	
  try {
    // Proceed knowing you have a logged in user who's authenticated.
	$access_token = $facebook->getAccessToken();
	$groupWall = $facebook->api(
								'/'.$grupo_id.'/feed?fields=caption,description,created_time,application,actions,coordinates,expanded_height,feed_targeting,expanded_width,height,icon,id,is_hidden,is_published,link,message,message_tags,name,object_id,parent_id,picture,place,privacy,promotion_status,properties,scheduled_publish_time,shares,source,status_type,story,story_tags,subscribed,targeting,timeline_visibility,to,type,updated_time,via,width,with_tags,from,comments.limit(500).fields(can_remove,created_time,from,id,like_count,message,message_tags,user_likes,likes,comments)&with=<value>&limit=10' 
								, array('access_token'=>$access_token)
								);
  } catch (FacebookApiException $e) {
    error_log($e);
    $groupWall = null;
  }
  
  
}

// Login or logout url will be needed depending on current user state.
if ($user) {
  $logoutUrl = $facebook->getLogoutUrl();
} else {
  $loginUrl = $facebook->getLoginUrl(array(
			'scope' => 'user_groups,friends_groups,user_website,user_work_history,user_relationships'
  ));
}


if ($user) echo $logoutUrl; 
 else echo $loginUrl; 

//print_r($_SESSION); 
//print_r($user_profile); 


//Get all data post - https://developers.facebook.com/docs/reference/api/group/
foreach ($groupWall['data'] as $data) {

	$data['id_grupo'] = $grupo_id ;

	//Get arrays first and convert to  serialized objects
	foreach( $data as $key => $value){
		if (is_array( $value ) ){
				$data[ $key ] = serialize($value) ;
		}
	}
	
	$super_id = $data['id'];
	if ( check_post_exists(  'fb_posts', $super_id ) ) {
		//echo 'extiste'; 
		//If exist , insert value into.
	} else{
		//echo ' no existe';
		insert_values( 'fb_posts', $super_id , $data );
	}
	/* foreach($data as $column => $value) { 
	
		Use this code only if you want to inteligence inserts.
		//Locate column in table
		if ( check_if_column_exists( 'fb_posts', $column ) ) {
			//If exist , insert value into.
			insert_value(  'fb_posts', $column , $value , $super_id  );
		} else
		//Else create new column.
			if (create_new_column( $column ) ) 
			insert_value( 'fb_posts', $column , $value , $super_id  );	
	} 
	*/	
}





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