<?

session_start();

include('functions/dbconn.php');
include('functions/functions.php');
		
if(session_is_registered('username')){

	function check_login($level) {
					
		$username_s = $_SESSION['username']; 
		if ($username_s == "admin") {include('user_level.php'); exit();}
		/*
		$sql = "SELECT user_level, restricted FROM login_users WHERE username = '$username_s'"; 
		$result = mysql_query($sql);
				
		$row = mysql_fetch_array($result);
		$user_level = $row['user_level'];
		$restricted = $row['restricted'];
		
		$sql2 = "SELECT level_disabled FROM login_levels WHERE level_level = '$user_level'"; 
		$result2 = mysql_query($sql2);
				
		$row2 = mysql_fetch_array($result2);
		
		$disabled = $row2['level_disabled'];
		
		if($disabled != 0) {
		
			include('disabled.php');
			exit();
			
		} elseif($restricted != 0) {
		
			include('disabled.php');
			exit();
			
		} elseif($user_level <= $level) {
		
			// User has authority to view this page.		
		
		} else {
		
			include('user_level.php');
			exit();
		
		}
	*/
	}

} else {

	function check_login($level) { exit(); }
	
	// Session doesn't exist (user isn't logged in), include login.
	include('login.inc.php');
	exit();
	
}

?> 