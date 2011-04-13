<?

include('header.php');

	// Remember any variables incase the form validation finds errors.
	$fname = $_POST['fname'];
	$lname = $_POST['lname'];
	$username = $_POST['username'];
	$email = $_POST['email'];
	$password = $_POST['password'];
	$password2 = $_POST['password_confirm'];
	$weight = $_POST['weight'];
	$bal = $_POST['bal'];
	
	$sql = "SELECT * FROM keg_stats WHERE id='0'"; 
	$result = mysql_query($sql);
	$row = mysql_fetch_array($result);
	if (strtotime($row['timestamp'])>(time()-5)) {
		$fname = $row[user_id];
	}

if(isset($_POST['add_user'])) {

	$fname = $_POST['fname'];
	$lname = $_POST['lname'];
	$username = $_POST['username'];
	$email = $_POST['email'];
	$password = $_POST['password'];
	$password2 = $_POST['password_confirm'];
	$weight = $_POST['weight'];
	$bal = $_POST['bal'];

	if(trim($fname) == '') {
    	$error = '<div class="error_message">Attention! You must enter a FobID.</div>';
//    } else if(trim($lname) == '') {
//    	$error = '<div class="error_message">Attention! You must enter your last name.</div>';
//    } else if(!isEmail($email)) {
//   	$error = '<div class="error_message">Attention! You have entered an invalid e-mail address, try again.</div>';
    }
	
	if($password != $password2) {
    	$error = '<div class="error_message">Attention! Your passwords did not match.</div>';
	}
	
	if(strlen($password) < 5) {
    	$error = '<div class="error_message">Attention! Your password must be at least 5 characters.</div>';
	}
	
	$count = mysql_num_rows(mysql_query("SELECT * FROM login_users WHERE username='".$username."'"));
	
	if($count > 0) {
    	$error = '<div class="error_message">Sorry, username already taken.</div>';
	}
	
	$count2 = mysql_num_rows(mysql_query("SELECT * FROM login_users WHERE fname='".$fname."'"));
	
	if($count2 > 0) {
    	$error = '<div class="error_message">Sorry, fobID already registered to someone.</div>';
	}
	
	if(strlen($weight) > 3) {
    	$error = '<div class="error_message">Attention! You are too fat.</div>';
	}
	if(strlen($fname) < 10) {
    	$error = '<div class="error_message">Attention! FobID number must be 10 digits.</div>';
	}
	if((trim($weight) == '')) {
    	$weight='200';
	}
	if((trim($username) == '')) {
    	$error = '<div class="error_message">Attention! You must enter a name!.</div>';
	}
		
	if($error == '') {

	$password = '4j4kl3l3jklh554lkj5ljk5';

	$sql = "INSERT INTO login_users (user_level, fname, weight, bal, username, password)
				VALUES ('3', '$fname', '$weight', '$bal', '$username', MD5('$password'))";
	
	$query = mysql_query($sql) or die("Fatal error: ".mysql_error());

	echo "<h2>Success!</h2>";	
	echo "<div class='success_message'>Successfully added <b>$username</b>, <b>$fname $lname</b> to the database.</div>";
	
	echo "<h2>User login details</h2>";
	
	echo "<ul class='success-reg'>";
	echo "<li><span class='success-info'><b>Name</b></span>$username</li>";
	echo "<li><span class='success-info'><b>FobID</b></span>$fname</li>";
	echo "<li><span class='success-info'><b>Weight</b></span>$weight</li>";
	echo "<li><span class='success-info'><b>Balance</b></span>$ $bal</li>";
	
//	echo "<li><span class='success-info'><b>E-Mail</b></span>$email</li>";
//	echo "<li><span class='success-info'><b>Password</b></span>*hidden*</li>";
	//echo "<li><span class='success-info'><b>FobID</b></span>$password</li>";
	echo "</ul>";
	
	echo "<h2>What to do now?</h2><br />";
	echo "Go to the <a href='user_edit.php'>edit users</a> page.</li>";
	
	}

}

if(!isset($_POST['add_user']) || $error != '') {

echo $error;


?>

<h2>Add User</h2>
	
<form action="" method="post" name="form">
<!--
<label>First / Last Name</label><input type="text" name="fname" value="<?=$fname;?>" style="width: 46%;" />&nbsp;<input type="text" name="lname" value="<?=$lname;?>" style="width: 46%;" /><br />
-->

<script type="text/javascript"> 
function toggle_username(userid) { 
    if (window.XMLHttpRequest) { 
        http = new XMLHttpRequest(); 
    } else if (window.ActiveXObject) { 
        http = new ActiveXObject("Microsoft.XMLHTTP"); 
    } 
    handle = document.getElementById(userid); 
    var url = '../ajax.php?'; 
    if(handle.value.length > 0) { 
        var fullurl = url + 'do=check_username_exists&username=' + encodeURIComponent(handle.value);
        http.open("GET", fullurl, true); 
        http.send(null); 
        http.onreadystatechange = statechange_username; 
    }else{ 
        document.getElementById('username').className = ''; 
    } 
} 

function statechange_username() { 
    if (http.readyState == 4) { 
        var xmlObj = http.responseXML; 
        var html = xmlObj.getElementsByTagName('result').item(0).firstChild.data; 
        document.getElementById('username').className = html; 
    } 
} 

function scan_fob(){

	document.getElementById('scan').value ="Scanning.."
	//setTimeout("location.reload(true);",4000);
	setTimeout("this.form.submit();",2000);
	
}

</script> 

<label>Name</label><input id="username" type="text" name="username" maxlength="8" value="<?=$username;?>" onchange="toggle_username('username')" /><br /> 

<!--<label>Email</label><input type="text" name="email" value="<?=$email;?>" /><br />-->
<label>FobID (Enter manually or press 'Scan')</label><input type="text" name="fname" style='width:120px' value="<?=$fname;?>" maxlength="10"/>
<input type="button" name="scan" id="scan" value="Scan" style="width:80px" onClick="scan_fob()" /><br />
<label>Approximate Weight</label><input type="text" name="weight" value="<?=$weight;?>" style="width:40px;" maxlength="3"/>lbs<br />
<label>Starting Balance</label>$<input type="text" name="bal" value="0.00" style="width:60px;" /><br />
<!--<label>Confirm</label><input type="password" name="password_confirm" value="<?=$password2;?>" /><br />-->

<input type="hidden" name="password" value="12345">
<input type="hidden" name="password_confirm" value="12345">
<input type="hidden" name="lname" value="">
<input type="hidden" name="email" value="">

<input type="submit" value="Continue" name="add_user" />

</form>

<? } include('../footer.php'); ?>