<? 

include('functions/dbconn.php');

///Make sure that a value was sent.
if (isset($_GET['search']) && $_GET['search'] != '') {

	//Add slashes to any quotes to avoid SQL problems.
	$search = trim(addslashes($_GET['search']));

	// Get every page title for the site.
		
	$sql = "SELECT distinct username as suggest, user_id, fname, lname FROM login_users WHERE username LIKE '" . $search . "%' or fname LIKE '" . $search . "%' or lname LIKE '" . $search . "%' ORDER BY username LIMIT 0, 5";
	
	$suggest_query = mysql_query($sql);
	
	$count = mysql_num_rows($suggest_query);
	
	if($count == 0){
	
		echo "<div class='suggestions' style='color: #08c;'>No suggestions</div>\n";
	
	} else { // Display suggestions found.
	
		echo "<div class='suggestions''>Suggestions</div>\n";
		
		while($suggest = mysql_fetch_array($suggest_query)) {
			//Return each page title seperated by a newline.
			echo "<div class='suggest_link'><a href='user_edit.php?uid=" . $suggest['user_id'] . "'>" . $suggest['suggest'] . "</a></div>\n";
		}
		
	}
}

$do = $_GET['do'];
 
switch($do) {
 
    case 'check_username_exists': 
        
		if(get_magic_quotes_gpc()) { 
            $username = $_GET['username']; 
        }else{ 
            $username = addslashes($_GET['username']); 
        } 
		
        $count = mysql_num_rows(mysql_query("SELECT * FROM login_users WHERE username='".$username."'"));
		 
        header('Content-Type: text/xml'); 
        header('Pragma: no-cache'); 
        echo '<?xml version="1.0" encoding="UTF-8"?>';
		 
        echo '<result>'; 
        if($count > 0) { echo 'exists'; } else { echo 'avail'; } 
        echo '</result>'; 
		
    break; 
	
    default: echo 'Error, invalid action'; 
    break;
	
	case 'check_level_exists': 
        
		if(get_magic_quotes_gpc()) { 
            $level = $_GET['level']; 
        }else{ 
            $level = addslashes($_GET['level']); 
        } 
		
        $count = mysql_num_rows(mysql_query("SELECT * FROM login_levels WHERE level_name='".$level."'"));
		 
        header('Content-Type: text/xml'); 
        header('Pragma: no-cache'); 
        echo '<?xml version="1.0" encoding="UTF-8"?>';
		 
        echo '<result>'; 
        if($count == 0) { echo 'avail'; } else { echo 'exists'; } 
        echo '</result>'; 
		
    break; 
	
    default: echo 'Error, invalid action'; 
    break; 
    
    case 'update_cost': 
        
		if(get_magic_quotes_gpc()) { 
            $cost = $_GET['cost']; 
            $size = $_GET['size'];
        }else{ 
            $cost = addslashes($_GET['cost']); 
            $size = addslashes($_GET['size']); 
        } 
		
        //$count = mysql_num_rows(mysql_query("SELECT * FROM login_users WHERE username='".$username."'"));
		 
        header('Content-Type: text/xml'); 
        header('Pragma: no-cache'); 
        echo '<?xml version="1.0" encoding="UTF-8"?>';
		 
        echo '<result>'; 
        //if($count > 0) { echo 'exists'; } else { echo 'avail'; } 
        $a = parseFloat($cost);
        $b = parseFloat($size);
        if ($a == 'Nan' || $b == 'NaN' ) { echo 'Not a Number'; }
        else { echo $b/$a/1000; }
        echo '</result>'; 
		
    break; 
	
    default: echo 'Error, invalid action'; 
    break;
    
} 

?>