<?

include('config.php');

// Important Configuration Option
// e.g. dbconn('localhost','your_database','your_login','your_pass');

$db = dbconn('97.74.149.146','kegserver','kegserver','BeerServer1');

// No need to edit below this line.
	
function dbconn($server,$database,$user,$pass){
	// Connect and select database.
	$db = mysql_connect($server,$user,$pass);
	$db_select = mysql_select_db($database,$db);
	return $db;
}
	
?>
