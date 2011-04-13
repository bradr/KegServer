<?
include('header.php');

if(!$_GET['uid'] && !isset($_POST['do_edit']) && !isset($_POST['edit_user'])) {

echo $error;

?>

<h2>Edit User</h2>

<form action="" method="post">

	<label>Username / Fname / Lname</label>
    <input type="text" id="username" name="username" alt="Search Criteria" onkeyup="searchSuggest();" autocomplete="off" />
	<div id="search_suggest"></div>
	<input type="submit" class="suggest_button" value="Search" name="edit_user" /> <!-- Hidden Search Button (for Prettyness) -->

</form>

<?

}

if(isset($_POST['edit_user'])) {
	
	$search_q = $_POST['username'];
	
	$sql = "SELECT * FROM login_users WHERE username LIKE '" . $search_q . "%' or fname LIKE '" . $search_q . "%' or lname LIKE '" . $search_q . "%' ORDER BY username LIMIT 0, 10"; 
	$result = mysql_query($sql);
	
	$count = mysql_num_rows($result);
	
	if($count == '1') { // Only 1 search result found, direct straight to edit page.
		
		$row = mysql_fetch_array($result);
	
		$user_id = $row['user_id'];
		redirect('user_edit.php?uid='. $user_id);
		
	} elseif(strlen($search_q) <= 2) { // Search at least 2 characters validation.
        
		$s_error = '<div class="error_message">Attention! Please be more specific in your search, at least 3 characters.</div>';
		echo $s_error;
      
	} else {
	
		echo "<h2>Top 10 Search Results</h2>";
		
		echo "<p>You have searched for <b>$search_q</b>, found <b>$count</b> results that match this criteria.</p>";
		
		echo "<ul class='column_result'>";
	
		echo '<li><span class="result_column" style="width: 15%;">Username</span><span class="result_column" style="width: 25%;">Real Name</span><span class="result_column" style="width: 35%;">E-Mail Address</span><span class="result_column">Registered Date</span></li>';
				
		while($row = mysql_fetch_array($result)) {
			if($row['user_level'] == 1) { $is_admin = " <span style='color: #08c;'>*</span>"; }
		
			$timestamp = strtotime($row['timestamp']);
			$reg_date = date('d M y @ H:i' ,$timestamp);
		
			echo '<li><a href="?uid='.$row['user_id'].'"><span class="result_column" style="width: 15%;">'.$row['username'].$is_admin.'</span><span class="result_column" style="width: 25%;">'.$row['fname'].' '.$row['lname'].'</span><span class="result_column" style="width: 35%;">'.$row['email'].'</span><span class="result_column">'.$reg_date.'</span></a></li>';
			
			// Clear the variable
			
			$is_admin = "";
		}
		
		echo "</ul>";
		
	}
}


// Has the edit form been submitted?

if(isset($_POST['do_edit'])) {
	
	$id = $_POST['user_id'];
	$fname = $_POST['fname'];
	$lname = $_POST['lname'];
	$email = $_POST['email'];
	$password = $_POST['password'];
	$password2 = $_POST['confirm'];
	$level = $_POST['user_level'];
	$restrict = $_POST['restrict'];
	
	$delete = $_POST['delete'];

	
	// Validate the submitted information
	
	/*	if(trim($id) == '1') {
        	$error = '<div class="error_message">Attention! You cannot edit the main Administrator, use database.</div>';
        if(trim($fname) == '') {
        	$error = '<div class="error_message">Attention! You must enter a first name.</div>';
        } else if(trim($lname) == '') {
        	$error = '<div class="error_message">Attention! You must enter a last name.</div>';
        } else if(!isEmail($email)) {
        	$error = '<div class="error_message">Attention! You have entered an invalid e-mail address, try again.</div>';
        } else if(trim($level) == '') {
        	$error = '<div class="error_message">Attention! No user level has been selected.</div>';
        }
		*/
	// Password been entered? If so, validate and update information.
	
		if($password != '') {
		
			if($password != $password2) {
				$error = '<div class="error_message">Attention! Your passwords did not match.</div>';
			}
			
			if(strlen($password) < 5) {
				$error = '<div class="error_message">Attention! Your password must be at least 5 characters.</div>';
			}
			
			if($error == '') {
		
			$sql = "UPDATE login_users SET restricted='$restrict', fname='$fname', lname='$lname', email='$email', user_level='$level', password = MD5('$password') WHERE user_id = '$id'";
			$query = mysql_query($sql) or die("Fatal error: ".mysql_error());
		
			echo "<h2>Updated</h2>";
			echo "<div class='success_message'>Admin Password Changed</b>.</div>";
			
			echo "<h2>What to do now?</h2><br />";
			echo "Go to the <a href='index.php'>user list</a> page.</li>";
			
			}
	
	// Password has not been entered don't update password fields.
		
		} else {
		
			if($error == '') {
		
			$sql = "UPDATE login_users SET restricted='$restrict', fname='$fname', lname='$lname', email='$email', user_level='$level' WHERE user_id = '$id'";
			$query = mysql_query($sql) or die("Fatal error: ".mysql_error());
		
			echo "<h2>Updated</h2>";
			echo "<div class='success_message'>User information updated for <b>$fname $lname</b>.</div>";
			
			echo "<h2>What to do now?</h2><br />";
			echo "Go to the <a href='index.php'>user list</a> page.</li>";
			
			}
		
		}
		
}

// Has a user been selected to edit?

if($_GET['uid'] && !isset($_POST['do_edit']) && !isset($_POST['edit_user']) || $error != '') {

	$user_id = $_GET['uid'];
	
	$sql = "SELECT * FROM login_users WHERE user_id='$user_id'"; 
	$result = mysql_query($sql);
	
	$row = mysql_fetch_array($result);
	
	$user_level = $row['user_level'];
	$restricted = $row['restricted'];
	
	$sql2 = "SELECT * FROM login_levels WHERE level_disabled != 1 AND level_level NOT LIKE '$user_level'"; 
	$result2 = mysql_query($sql2);
	
	$sql3 = "SELECT level_name FROM login_levels WHERE level_level='$user_level'"; 
	$result3 = mysql_query($sql3);
	
	$row3 = mysql_fetch_array($result3);
		
	$user_level = $row3['level_name'];
	
	echo $error;
			
	echo "<h2>User Information ( ".stripslashes($row['username'])." )</h2>";
	
?>

<form action="" method="post">
<input type="hidden" name="user_id" value="<?=$row['user_id'];?>" />
<!--
<label>First / Last Name</label>
<input type="text" name="fname" value="<?=stripslashes($row['fname']);?>" style="width: 46%;" />&nbsp;<input type="text" name="lname" value="<?=stripslashes($row['lname']);?>" style="width: 46%;" /><br />

<label>E-Mail</label>
<input type="text" name="email" value="<?=stripslashes($row['email']);?>" /><br />
-->
<label>Password (Blank to not edit)</label>
<input type="password" name="password" value="" /><br />

<label>Confirm</label>
<input type="password" name="confirm" value="" /><br />
<!--
<label style="width: 50%;">User Level</label>
<select name="user_level">
<option selected value="<?=stripslashes($row['user_level']);?>"><?=$user_level ?></option>
<?
while($level = mysql_fetch_array($result2)) {
	echo '<option value="'.stripslashes($level['level_level']).'">'.stripslashes($level['level_name']).'</option>';
}
?>
</select>

<label style="width: 50%;">User Access</label>
<select name="restrict">
<? if($restricted != 0) { ?>
<option selected value="1">Restricted</option>
<option value="0">Default</option>
<? } else { ?>
<option selected value="0">Default</option>
<option value="1">Restricted</option>
<? } ?>
</select>

<br /><br />
<div class="error_message">Delete this user? (Cannot be undone!) <input type="checkbox" class="checkbox" name="delete" value="delete_uid"></div>
-->
<input type="submit" value="Confirm" name="do_edit" />
</form>

<? } include('../footer.php'); ?>