<?
include('check.php');
check_login('1');

include('header.php');
	
	echo "<h1 style='font-size: 20px; margin: 0; padding: 0;'>Congratulations!</h1>";
	echo "<h2>You are viewing a protected page! (Admin Only)</h2>";
	echo "<p>You will only be able to see this page if you have an administrator user level.</p>";

	echo "<br />";
	echo "<h2>What to do now?</h2><br />";
	echo "If you are testing the demo you might want to go <a href='home.php'>back</a> to home and try out another page?";

include('footer.php');

?>