<?

include('check.php');
check_login('2');
// '2' above states the minimum user level a logged in user MUST have to view the page.

include('header.php');
	
	echo "<h1 style='font-size: 20px; margin: 0; padding: 0;'>Congratulations!</h1>";
	echo "<h2>You are viewing a protected page! (Admin, Special Users)</h2>";
	echo "<p>You will only be able to see this page if you are logged in to the website. Both 'admin' and 'special' users can view this page.
	<br /><br />Standard users will be directed to a page telling them they don't have sufficiant privilages to view this page.</p>";
	
	echo "<br />";
	echo "<h2>What to do now?</h2><br />";
	echo "If you are testing the demo you might want to go <a href='home.php'>back</a> to home and try out another page?";

include('footer.php');

?>