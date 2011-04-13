<?

// Fix for admin include header loop.

if(stristr($_SERVER['PHP_SELF'], 'admin')) {
	include('../header.php');
} else {
	include('header.php');
}
	
	echo "<h1 style='margin: 0; padding: 0; font-size: 20px;'>Oops, Access Denied</h1>";
	echo "<p>We have detected that your user level does not entitle you to view the page requested.<br /><br />";
	echo "Please contact the website administrator if you feel this is in error.</p>";
	echo "<br />";
	echo "<h2>What to do now?</h2><br />";
	echo "To see this page you must <a href='logout.php'>logout</a> and login with sufficiant privileges.</li>";


include('footer.php');

?>