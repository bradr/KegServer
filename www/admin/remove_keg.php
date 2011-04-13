<?
include('header.php');

if($_GET['id'] && !isset($_GET['confirm'])) {
	$id = $_GET['id'];
?>

<h2>Confirmation</h2>


<form action="" method="post">

	<h3>Are you sure you want to remove this keg from the tap?</h3>
	(This can't be undone!)
    <div style="position:relative; left:30px; top:10px">
    <input type="button" name="Yes" value="Yes" style="width:200px" onClick="window.location='?id=<? echo $id; ?>&confirm=1'" />
	<input type="button" name="Cancel" value="Cancel" style="width:200px" onClick="window.location='index.php'" /></div>
	<br>
	<br>
</form>
<br>
<?

}

else if($_GET['id'] && isset($_GET['confirm'])) {
	$id = $_GET['id'];
	//$search_q = $_POST['id'];
	
	$sql = "UPDATE login_kegs SET pos='' WHERE keg_id = '$id'"; 
	$query = mysql_query($sql) or die("Fatal error: ".mysql_error());

	echo "<h3>Removed</h3>";
	echo "<div class='success_message'>Keg Successfully Removed from the Tap.</div>";
	
	echo "<h2>What to do now?</h2><br />";
	echo "Go to the <a href='index.php'>main</a> page.</li>";
		
} 
 include('../footer.php'); ?>