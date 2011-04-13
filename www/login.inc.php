<?

session_start();

$ref = $_SERVER['HTTP_REFERER'];

// User is already logged in, they don't need to se this page.

if(isset($_SESSION['username'])) {

	include('header.php');
	echo '<div class="error_message">Attention! You are already logged in.</div>';
	echo "<h2>What to do now?</h2><br />";
	echo "Go <a href='javascript:history.go(-1)'>back</a> to the page you were viewing before this.</li>";
	include('footer.php');
	
	exit();
}

// Has an error message been passed to login.php?
$error = $_GET['e'];

if($error == 1) {
    $error = '<div class="error_message">Attention! You must be logged in to view this page.</div>';
}

// Only process if the login form has been submitted.

if(isset($_POST['login'])) {

	$username = $_POST['username']; 
	$password = $_POST['password']; 

	// Check that the user is calling the page from the login form and not accessing it directly 
	// and redirect back to the login form if necessary 
	if (!isset($username) || !isset($password)) { 
	header( "Location: index.php" ); 
	exit();
	
	} 
	// Check that the form fields are not empty, and redirect back to the login page if they are 
	elseif (empty($username) || empty($password)) { 
	header( "Location: index.php" );
	exit();
	
	} else { 
	
	//Convert the field values to simple variables 
	
	// Add slashes to the username and md5() the password 
	$user = addslashes($_POST['username']); 
	$pass = md5($_POST['password']); 
	
	
	$sql = "SELECT * FROM login_users WHERE username='$user' AND password='$pass'"; 
	$result = mysql_query($sql);
	
	// Check that at least one row was returned 
	$rowCheck = mysql_num_rows($result); 
	
	if($rowCheck > 0) { 
	while($row = mysql_fetch_array($result)) { 
	
	  // Start the session and register a variable 
	
	  session_start(); 
	  session_register('username'); 
	
	  //  Successful login code will go here... 
	  
	  if($user="admin")
	  {
	  	header("Location: admin/");
	  } else {
	  	header( "Location: ".$ref); 
	  }
	  exit();
	
	  } 
	
	  } else { 
	
	  // If nothing is returned by the query, unsuccessful login code goes here... 
	
	  $error = '<div class="error_message">Incorrect username or password.</div>'; 
	  } 
	}
}

if(stristr($_SERVER['PHP_SELF'], 'admin')) {
	include('../header.php');
} else {
	include('header.php');
}

echo $error;

?>

<h2>Login</h2>

<form method="POST" action=""> 
<label>Username</label><input type="text" name="username" size="20"> 
<br />
<label>Password</label><input type="password" name="password" size="20"> 
<br />
<input type="submit" value="Submit" name="login"> 
</form> 

<p>Not registered yet? It's free, quick &amp; easy to do so <a href="sign_up.php">here</a></p>

<?

if(stristr($_SERVER['PHP_SELF'], 'admin')) {
	include('../footer.php');
} else {
	include('footer.php');
}

?>