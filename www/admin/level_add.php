<?
include('header.php');

	$auth = $_POST['auth'];
	$level = $_POST['level'];
	
if(isset($_POST['add_level'])) {

	$auth = $_POST['auth'];
	$level = $_POST['level'];
	
	// DEMO RESTRICTION
	$demo = 'demo';
	if($demo == 'demo') {
    	$error = '<div class="error_message">Add levels has been disabled in the Live Demo.</div>';
    }
	
	if(trim($level) == '') {
    	$error = '<div class="error_message">Attention! You must enter a level name.</div>';
    } else if(!is_numeric($auth)) {
    	$error = '<div class="error_message">Attention! Auth level is a numeric field.</div>';
    }
	
	$sql = "SELECT * FROM login_levels WHERE level_level = '$auth'";
	$query = mysql_query($sql);
	$row = mysql_fetch_array($query);
	
	$count = mysql_num_rows($query);
	
	if($count != 0) { 
		$error = '<div class="error_message">Attention! Auth level <b>'.$auth.'</b> already exists, please use or edit <b>'.$row['level_name'].'</b>.</div>';
    }
		
	if($error == '') {

	$sql = "INSERT INTO login_levels (level_name, level_level, level_disabled)
				VALUES ('$level', '$auth', '0')";
	
	$query = mysql_query($sql) or die("Fatal error: ".mysql_error());

	echo "<h2>Success!</h2>";	
	echo "<div class='success_message'>Successfully added level <b>$level</b> to the database.</div>";
	
	echo "<h2>What to do now?</h2><br />";
	echo "Go <a href='manage_levels.php'>back</a> to the manage level page.</li>";
	
	}

}

if(!isset($_POST['add_level']) || $error != '') {

echo $error;

?>

<h2>Add User Level</h2>

<form action="" method="post">

<label>Level Name</label><input type="text" name="level" id="level" value="<?=$level;?>" onchange="toggle_level('level')" /><br />

<script type="text/javascript"> 
function toggle_level(level) { 
    if (window.XMLHttpRequest) { 
        http = new XMLHttpRequest(); 
    } else if (window.ActiveXObject) { 
        http = new ActiveXObject("Microsoft.XMLHTTP"); 
    } 
    handle = document.getElementById(level); 
    var url = '../ajax.php?'; 
    if(handle.value.length > 0) { 
        var fullurl = url + 'do=check_level_exists&level=' + encodeURIComponent(handle.value);
        http.open("GET", fullurl, true); 
        http.send(null); 
        http.onreadystatechange = statechange_level; 
    }else{ 
        document.getElementById('level').className = ''; 
    } 
} 

function statechange_level() { 
    if (http.readyState == 4) { 
        var xmlObj = http.responseXML; 
        var html = xmlObj.getElementsByTagName('result').item(0).firstChild.data; 
        document.getElementById('level').className = html; 
    } 
} 
</script>

<label>Auth Level</label><input id="username" type="text" name="auth" value="<?=$auth;?>" /><br /> 

<input type="submit" value="Continue" name="add_level" />

</form>

<? }

include('../footer.php');

?>