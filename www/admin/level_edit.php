<?

include('header.php');

if(!$_GET['lid'] && !isset($_POST['do_edit']) && !isset($_POST['edit_level'])) {
	
	echo $error;
	echo "<h2>Edit User Level</h2>";
	
	// Include the list of User Levels
	user_levels();

}

if(isset($_POST['edit_level'])) {
	
	$search_q = $_POST['level'];
	
	$sql = "SELECT * FROM login_levels WHERE level LIKE '" . $search_q . "%' or fname LIKE '" . $search_q . "%' or lname LIKE '" . $search_q . "%' ORDER BY level LIMIT 0, 10"; 
	$result = mysql_query($sql);
	
	$count = mysql_num_rows($result);
	
	if($count == '1') { // Only 1 search result found, direct straight to edit page.
		
		$row = mysql_fetch_array($result);
	
		$level_id = $row['level_id'];
		redirect('user_edit.php?lid='. $level_id);
		
	} elseif(strlen($search_q) <= 2) { // Search at least 2 characters validation.
        
		$s_error = '<div class="error_message">Attention! Please be more specific in your search, at least 3 characters.</div>';
		echo $s_error;
      
	} else {
	
		echo "<h2>Top 10 Search Results</h2>";
		
		echo "<p>You have searched for <b>$search_q</b>, found <b>$count</b> results that match this criteria.</p>";
		
		echo "<ul class='column_result'>";
	
		echo '<li><span class="result_column" style="width: 15%;">level</span><span class="result_column" style="width: 25%;">Real Name</span><span class="result_column" style="width: 35%;">E-Mail Address</span><span class="result_column">Registered Date</span></li>';
				
		while($row = mysql_fetch_array($result)) {
			if($row['user_level'] == 1) { $is_admin = " <span style='color: #08c;'>*</span>"; }
		
			$timestamp = strtotime($row['timestamp']);
			$reg_date = date('d M y @ H:i' ,$timestamp);
		
			echo '<li><a href="?lid='.$row['level_id'].'"><span class="result_column" style="width: 15%;">'.$row['level'].$is_admin.'</span><span class="result_column" style="width: 25%;">'.$row['fname'].' '.$row['lname'].'</span><span class="result_column" style="width: 35%;">'.$row['email'].'</span><span class="result_column">'.$reg_date.'</span></a></li>';
			
			// Clear the variable
			
			$is_admin = "";
		}
		
		echo "</ul>";
		
	}
}


// Has the edit form been submitted?

if(isset($_POST['do_edit'])) {
	
	$id = $_POST['level_id'];
	$level = $_POST['level'];
	$auth = $_POST['auth'];
	$original = $_POST['original'];
	
	$disable = $_POST['disable'];
	
	// DEMO RESTRICTION
	$demo = 'demo';
	if($demo == 'demo') {
    	$error = '<div class="error_message">Edit levels has been disabled in the Live Demo.</div>';
    }
	
	// Validate the submitted information
	
	if($original == 1) {
       	$error = '<div class="error_message">Attention! You cannot edit the Administrator level, use database.</div>';
    }
	
	// Ticked the 'disable user level' box? If so, disable and echo message.

	if($disable == 'disable_lid') {
	
	if($error == '') {
		
	$sql = "UPDATE login_levels SET level_disabled='1' WHERE id = '$id'";
	$query = mysql_query($sql) or die("Fatal error: ".mysql_error());

	echo "<h3>Disabled</h3>";
	echo "<div class='success_message'>User level <b>$level</b> has been disabled, group resricted from viewing protected pages.</div>";
	
	echo "<h2>What to do now?</h2><br />";
	echo "Go <a href='manage_levels.php'>back</a> to the manage level page.</li>";
	
	}
		
	} else {
	
	if(trim($level) == '') {
       	$error = '<div class="error_message">Attention! You must enter a level name.</div>';
    }
		
	$sql = "SELECT * FROM login_levels WHERE level_level = '$auth'";
	$query = mysql_query($sql);
	$row = mysql_fetch_array($query);
	
	$count = mysql_num_rows($query);
	
	if($count != 0 && $auth != $original) { 
		$error = '<div class="error_message">Attention! Auth level <b>'.$auth.'</b> already exists, please use or edit <b>'.$row['level_name'].'</b>.</div>';
	}
	
	if($error == '') {

	$sql = "UPDATE login_levels SET level_name='$level', level_level='$auth', level_disabled='0' WHERE id = '$id'";
	$query = mysql_query($sql) or die("Fatal error: ".mysql_error());
	
	echo "<h2>Updated</h2>";
	echo "<div class='success_message'>Information updated for user level <b>$level</b>.</div>";
	
	echo "<h2>What to do now?</h2><br />";
	echo "Go <a href='manage_levels.php'>back</a> to the manage level page.</li>";
	
	}		
  }
}

// Has a user been selected to edit?

if($_GET['lid'] && !isset($_POST['do_edit']) && !isset($_POST['edit_level']) || $error != '') {

	$level_id = $_GET['lid'];
	
	$sql = "SELECT * FROM login_levels WHERE id = '$level_id'"; 
	$result = mysql_query($sql);
	
	$row = mysql_fetch_array($result);
	
	$level_level = $row['level_level'];
	
	if($row['level_disabled'] == '1') { $disabled = "checked"; }
	
	echo $error;
			
	echo "<h2>Level Information ( ".stripslashes($row['level_name'])." )</h2>";
	
?>

<form action="" method="post">
<input type="hidden" name="level_id" value="<?=stripslashes($row['id']);?>" />
<input type="hidden" name="original" value="<?=stripslashes($row['level_level']);?>" />

<label>Level Name</label>
<input type="text" name="level" value="<?=stripslashes($row['level_name']);?>" /><br />

<label>Auth Level</label>
<input type="text" name="auth" value="<?=stripslashes($row['level_level']);?>" /><br />

<label>Disable</label>
<div class="error_message">Disable this User level? ( All users Restricted )<input type="checkbox" class="checkbox" name="disable" <?=$disabled ?> value="disable_lid"></div>

<input type="submit" value="Update" name="do_edit" />
</form>

<? } include('../footer.php'); ?>