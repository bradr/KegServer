<?
include('functions/dbconn.php');


if (isset($_GET["transaction"]) && isset($_GET["fobID"]) && isset($_GET["tap"]) && isset($_GET["quantity"]) && $_GET["password"]==MD5('diamond')) //'75c6f03161d020201000414cd1501f9f'
{
	$fob_id = $_GET['fobID'];
	$tap = $_GET["tap"];
	$quantity = $_GET["quantity"];

	$sql = "SELECT * FROM login_kegs WHERE pos='".$tap."'"; 
	$result = mysql_query($sql) or die("Error: ".mysql_error());
	$row = mysql_fetch_array($result);
	
	$keg_id = $row['keg_id'];
	$costperL = $row['costperL'];
	$remaining = $row['remaining'];

	$sql = "SELECT * FROM login_users WHERE fname='".$fob_id."'"; 
	$result = mysql_query($sql) or die("Error: ".mysql_error());
	$row = mysql_fetch_array($result);	
	
	$user_id = $row['user_id'];
	$user = $row['username'];
	$cost = $quantity*$costperL/1000;
	$balance = $row['bal'];
	$consumption = $row['cons'];
	
	$sql = "INSERT INTO keg_stats (keg_id,user_id,user,quantity,cost)
				VALUES ('$keg_id','$fob_id','$user','$quantity','$cost')";
	$query = mysql_query($sql) or die("Fatal error: ".mysql_error());
				
	$newbalance = $balance - $cost;
	$newremaining = $remaining - $quantity/1000;
	$newconsumption = round(($consumption + $quantity/1000)*100)/100;
	
	$sql = "UPDATE login_users SET bal = '$newbalance', cons='$newconsumption' WHERE user_id = '$user_id'";
	$query = mysql_query($sql) or die("Fatal error: ".mysql_error());
	$sql = "UPDATE login_kegs SET remaining = '$newremaining' WHERE keg_id = '$keg_id'";
	$query = mysql_query($sql) or die("Fatal error: ".mysql_error());
	
	echo "Success";
	
}
else if (!isset($_GET["transaction"]) && isset($_GET["kegstatus"])) {
	$sql = "SELECT * FROM login_kegs WHERE pos!='' ORDER BY pos"; 
	$result = mysql_query($sql) or die("Error: ".mysql_error());
	while ($row = mysql_fetch_array($result)){
		$percentage = round(($row['remaining'])*100*10/$row['size'])/10;
		echo $row['pos'].",".$percentage.",".$row['shortname'].",".$row['costperL']." ";
	}
}
else if (!isset($_GET["transaction"]) && isset($_GET["price"])) {
	if ($_GET["price"]=='A' || $_GET["price"]=='B')
	{
		$sql = "SELECT * FROM login_kegs WHERE pos='".$_GET['price']."'"; 
		$result = mysql_query($sql) or die("Error: ".mysql_error());
		$row = mysql_fetch_array($result);
		echo $row['costperL'];
	} else
	{
		$sql = "SELECT * FROM login_kegs WHERE pos!='' ORDER BY pos"; 
		$result = mysql_query($sql) or die("Error: ".mysql_error());
		while ($row = mysql_fetch_array($result)){
		echo $row['pos'].",".$row['costperL']." ";
		}
	}
}
else if(!isset($_GET["transaction"]) && isset($_GET["fobID"])) {
	$fobID = $_GET["fobID"];
	if ($fobID=='') { echo "?"; return; }
	$sql = "SELECT * FROM login_users WHERE fname='".$fobID."'"; 
	$result = mysql_query($sql) or die("Error: ".mysql_error());
	$rowCheck = mysql_num_rows($result); 
	if($rowCheck == 1) { 
		$row = mysql_fetch_array($result);
		echo $row['bal'].",".$row['username'];
	}
	else {
		echo "?";
		
		$time = date('Y-m-d H:i:s');		
		$sql = "UPDATE keg_stats SET quantity='0', cost='0',user_id='$fobID',timestamp='$time' WHERE id = '0'";
		$query = mysql_query($sql) or die("Fatal error: ".mysql_error());
		
	}
} else { echo "SERVER_OK"; }


?>