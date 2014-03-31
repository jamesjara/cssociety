<?php


switch ( strtolower( $_GET['tipo'] )  ){
	case "refreshgroupfromfb":
		include ('sdk/facebook/get_data_group.php');		
	break;
	case "deletepostfromfb":
		include ('sdk/facebook/delete_data_group.php');		
	break;
	default:
	break;
}

echo '<div id="actionbox" class="well"><a href="#" onclick="$(\'#dialog\').hide()">close</a>'.$data.'</div>';
?>
