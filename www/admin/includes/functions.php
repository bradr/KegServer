<?

function user_levels() {
	
	// Standard limit for User Levels is 15, feel free to change this limit below.
	$sql = "SELECT * FROM login_levels LIMIT 0, 15"; 
	$result = mysql_query($sql);
	
	// Check that at least one row was returned 
	$rowCheck = mysql_num_rows($result); 
	
	if($rowCheck > 0) { 
	
	// Show recently registered users
	
	echo "<ul class='column_result'>";
	
		echo '<li><span class="result_column" style="width: 15%;">User Level</span><span class="result_column" style="width: 20%;">Authority Level</span><span class="result_column" style="width: 20%;">Active Users</span><span class="result_column" style="width: 15%;">Status</span></li>';
	
		while($row = mysql_fetch_array($result)) { 
		
			$level = $row['level_level'];
			
			// Find the current amount of active users in the group.
		
			$sql2 = "SELECT user_level FROM login_users WHERE user_level = '$level'";
			$result2 = mysql_query($sql2); 
			$count = mysql_num_rows($result2);
			
			// If buts and maybes for the list
		
			if($row['level_level'] == 1) { $admin = " <span style='color: #08c;'>*</span>"; }
			if($row['level_disabled'] == 0) { $status = "Active"; } else { $status = "<span style='color: #8a1f11;'>Disabled</span>"; }
		
			echo '<li><a href="level_edit.php?lid='.$row['id'].'"><span class="result_column" style="width: 15%;">'.$row['level_name']. $admin .'</span><span class="result_column" style="width: 20%;">'.$row['level_level'].'</span><span class="result_column" style="width: 20%;">'.$count.'</span><span class="result_column" style="width: 15%;">'.$status.'</span></a></li>';
			
			// Clear the variables
			
			$level = "";
			$admin = "";
			$status = "";
			
		}
	
	echo "</ul>";	
	
	}

}

function recently_reg() {

	echo "<h2>5 Recently Registered</h2>";
	
	$sql = "SELECT * FROM login_users ORDER BY timestamp DESC LIMIT 0, 5"; 
	$result = mysql_query($sql);
	
	// Check that at least one row was returned 
	$rowCheck = mysql_num_rows($result); 
	
	if($rowCheck > 0) { 
	
	// Show recently registered users
	
	echo "<ul class='column_result'>";
	
		echo '<li><span class="result_column" style="width: 15%;">Username</span><span class="result_column" style="width: 25%;">Real Name</span><span class="result_column" style="width: 35%;">E-Mail Address</span><span class="result_column">Registered Date</span></li>';
	
		while($row = mysql_fetch_array($result)) { 
		
			if($row['user_level'] == 1) { $admin = " <span style='color: #08c;'>*</span>"; }
			if($row['restricted'] == 1) { $restrict = " <span style='color: #8a1f11;'>*</span>"; }

			$timestamp = strtotime($row['timestamp']);
			$reg_date = date('d M y @ H:i' ,$timestamp);
		
			echo '<li><a href="user_edit.php?uid='.$row['user_id'].'"><span class="result_column" style="width: 15%;">'. $row['username'] . $admin . $restrict .'</span><span class="result_column" style="width: 25%;">'.$row['fname'].' '.$row['lname'].'</span><span class="result_column" style="width: 35%;">'.$row['email'].'</span><span class="result_column">'.$reg_date.'</span></a></li>';
			
			// Clear the variable
			
			$admin = "";
			$restrict = "";
		
		}
	
	echo "</ul>";	
	
	} else { echo "Sorry, there are no recently registered users."; }
	
}

function list_all() {

	echo "<h2>All Drinkers:</h2>";
	
	$sql = "SELECT * FROM login_users ORDER BY username"; 
	$result = mysql_query($sql);
	
	// Check that at least one row was returned 
	$rowCheck = mysql_num_rows($result); 
	
	if($rowCheck > 0) { 
	
	// Show All Users
	
	echo "<ul class='column_result'>";
	
		echo '<li><span class="result_column" style="width: 15%;">Name</span><span class="result_column" style="width: 35%;">FobID</span><span class="result_column" style="width: 25%;">Total Consumption</span><span class="result_column">Balance</span></li>';
	
		while($row = mysql_fetch_array($result)) { 
		
			if($row['user_id'] == '1') { $admin = " <span style='color: #08c;'>*</span>"; $link="admin_edit.php"; }
			else { $link="user_edit.php";}
		//	if($row['restricted'] == 1) { $restrict = " <span style='color: #8a1f11;'>*</span>"; }

			$timestamp = strtotime($row['timestamp']);
			$reg_date = date('d M y @ H:i' ,$timestamp);
		
			echo '<li><a href="'.$link.'?uid='.$row['user_id'].'"><span class="result_column" style="width: 15%;">'. $row['username'] . $admin . $restrict .'</span><span class="result_column" style="width: 35%;">'.$row['fname'].'</span><span class="result_column" style="width: 25%;">'.$row['cons'].' L</span><span class="result_column">$'.number_format($row['bal'],2).'</span></a></li>';
			
			// Clear the variable
			
			$admin = "";
			$restrict = "";
		
		}
	
	echo "</ul>";	
	
	} else { echo "Sorry, there are no recently registered users."; }
	
}

function list_kegs() {

	echo "<h2>Current Kegs:</h2>";
	
	$sql = "SELECT * FROM login_kegs WHERE pos!='' ORDER BY pos"; 
	$result = mysql_query($sql);
	
	// Check that at least one row was returned 
	$rowCheck = mysql_num_rows($result); 
	
	if($rowCheck > 0) { 
	
	// Show Current Kegs
	echo "<ul class='column_result'>";
	
		echo '<li><span class="result_column" style="width: 40%;">Keg</span><span class="result_column" style="width:20%;">Remaining</span><span class="result_column" style="width:90px;">Cost</span><span class="result_column"></span></li>';
	
		while($row = mysql_fetch_array($result)) { 
			$remaining = round($row['remaining']*100)/100;
			$percentage = round(($row['remaining'])*100*100/$row['size'])/100;
			$perpint = round($row['costperL']*100/2)/100;
		
			//if($row['user_id'] == '1') { $admin = " <span style='color: #08c;'>*</span>"; $link="admin_edit.php"; }
			//else { $link="user_edit.php";}
		//	if($row['restricted'] == 1) { $restrict = " <span style='color: #8a1f11;'>*</span>"; }

			//$timestamp = strtotime($row['timestamp']);
			//$reg_date = date('d M y @ H:i' ,$timestamp);
		
		//	echo '<li><a href="'.$link.'?uid='.$row['user_id'].'"><span class="result_column" style="width: 15%;">'. $row['username'] . $admin . $restrict .'</span><span class="result_column" style="width: 35%;">'.$row['fname'].'</span><span class="result_column" style="width: 25%;">'.$row['cons'].'</span><span class="result_column">'.$row['bal'].'</span></a></li>';
		
		echo '<li><span class="result_column" style="width: 40%;">'. $row['pos'] . ' -  ' .$row['name'] .'</span><span class="result_column" style="width: 20%;">'.$remaining.' L ('.$percentage.'%)</span><span class="result_column" style="width:20%;">$'.number_format($perpint,2).' per pint</span><span style="width:80px" class="result_column"><img src="http://chart.apis.google.com/chart?chs=80x22&cht=bhs&chco=4D89F9,E1E4EB&chd=t:'.$percentage.'|100" align="middle"></span><span class="result_column"><a href="remove_keg.php?id='.$row['keg_id'].'"><img src="../assets/cross.gif" width="16px" border="0" align="middle"></a></span></li>';

		
		}
	
	echo "</ul>";	
	
	} else { echo "Sorry, there are kegs on tap."; }
	echo "<br>";
	
}

function usr_total() {
	
	$sql = "SELECT COUNT(*) FROM login_users WHERE user_level= '3'"; 
	$result = mysql_query($sql);
	$row = mysql_fetch_array($result);
	
	return '<span class="totals"><i><b>'.$row['COUNT(*)'].'</b> Drinkers</i></span>';
	
}

function usr_active_total() {
	
	$sql = "SELECT COUNT(*) FROM login_users WHERE user_level= '3'"; 
	$result = mysql_query($sql);
	$row = mysql_fetch_array($result);
	
	return '<span class="totals"><i><b>'.$row['COUNT(*)'].'</b> Active Members</i></span>';
	
}

function usr_levels_total() {
	
	$sql = "SELECT level_name, level_level FROM login_levels"; 
	$result = mysql_query($sql);
	
	while($row = mysql_fetch_array($result)) {
	echo '<div class="totals"><i><b>'.level_total($row['level_level']).'</b> '.$row['level_name'].' Users</i></div>';
	}
	
}

function level_total($id) {

	$sql = "SELECT COUNT(*) FROM login_users WHERE user_level = '$id'"; 
	$result = mysql_query($sql);
	$row = mysql_fetch_array($result);
	
	return $row['COUNT(*)'];

}

?>