<?

include('header.php');

	// Remember any variables incase the form validation finds errors.
	$name = $_POST['name'];
	$shortname = $_POST['shortname'];
	$size = $_POST['size'];
	$cost = $_POST['cost'];
	$pos = $_POST['pos'];

if(isset($_POST['add_keg'])) {

	$name = $_POST['name'];
	$shortname = $_POST['shortname'];
	$size = $_POST['size'];
	$cost = $_POST['cost'];
	$pos = $_POST['pos'];
	
	$count = mysql_num_rows(mysql_query("SELECT * FROM login_kegs WHERE pos='".$pos."'"));
	
	if(trim($name) == '') {
    	$error = '<div class="error_message">Attention! You must enter the keg name.</div>';
    } else if(trim($shortname) == '') {
    	$error = '<div class="error_message">Attention! You must enter a short name.</div>';
	} else if(trim($size) == '') {
		$error = '<div class="error_message">Attention! You must enter keg capacity.</div>';
	} else if(trim($cost) == '') {
		$error = '<div class="error_message">Attention! You must enter keg cost.</div>';
	} else if(trim($pos) == '') {
		$error = '<div class="error_message">Attention! You must select which tap the keg is connected to.</div>';
	} else 	if($count > 0) {
    	$error = '<div class="error_message">Sorry, there is already a keg connected to <b>Tap '.$pos.'</b>. Delete this keg before continuing.</div>';
	}
	//if(document.form1.position.checked) { $pos='A'; } else { $pos='B'; }

		
	if($error == '') {

	$remaining=$size;
	$costperL=$cost/$size;
	$sql = "INSERT INTO login_kegs (name,shortname,size,remaining,cost,costperL,pos)
				VALUES ('$name', '$shortname', '$size', '$remaining', '$cost', '$costperL', '$pos')";
	
	$query = mysql_query($sql) or die("Fatal error: ".mysql_error());

	echo "<h2>Success!</h2>";	
	echo "<div class='success_message'>Successfully added <b>$name</b> into <b>Tap $pos</b>.</div>";
	
	echo "<h2>Details</h2>";
	
	echo "<ul class='success-reg'>";
	echo "<li><span class='success-info'><b>Name</b></span>$name ($shortname)</li>";
	echo "<li><span class='success-info'><b>Capacity</b></span>$size L</li>";
	echo "<li><span class='success-info'><b>Cost</b></span>$ $cost</li>";
	echo "</ul>";
	
	echo "<h2>What to do now?</h2><br />";
	echo "Go to the <a href='index.php'>main</a> page.</li>";
	
	}

}

if(!isset($_POST['add_keg']) || $error != '') {

echo $error;

?>

<h2>Add Keg</h2>
	
<form name="form1" action="" method="post">

<script type="text/javascript"> 
function update_cost() { 
    	handle1 = document.getElementById('cost');
    	handle2 = document.getElementById('size');
    	a = parseFloat(handle1.value);
    	b = parseFloat(handle2.value);
    	if (isNaN(a) || isNaN(b))
    	{
    		return;
    	} else {
    		c = a/b;
    		d = a/b/2.0;
    		document.getElementById('costdiv').innerHTML = 'Cost = $' + c.toFixed(2) + ' per L or $' + d.toFixed(2) + ' per 500mL pint';	
    	} 
} 

function clickA() {
	document.getElementById('pos').value="A";
}

function clickB() {
	document.getElementById('pos').value="B";
}
	

</script> 

<label>Beer Name</label><input type="text" name="name" value="<?=$name;?>" /><br />
<label>Short Name For the LCD Screen (Limited to 15 Characters)</label><input type="text" name="shortname" value="<?=$shortname;?>" maxlength="15" style="width:180px" /><br />
<label>Capacity (Usually 30L or 50L)</label><input type="text" name="size" id='size' value="<?=$size;?>" maxlength="4" style="width:40px" onChange="update_cost()" />L<br />
<label>Cost</label>$<input type="text" name="cost" id="cost" value="<?=$cost;?>" maxlength="6" style="width:60px" onchange="update_cost()" />
<div id='costdiv'> </div>
<br />
<label>Tap Selection</label><div style="width:100px;"><input type="radio" style="width:20px" name="position" value="A" onClick="clickA()" />A
<?
$count = mysql_num_rows(mysql_query("SELECT * FROM login_kegs WHERE pos='A'"));
if ($count>0) echo "<img src='assets/cross.gif' width='12px'>In Use";
?>
<p><input type="radio" style="width:20px" name="position" value="B" onClick="clickB()" />B
<?
$count = mysql_num_rows(mysql_query("SELECT * FROM login_kegs WHERE pos='B'"));
if ($count>0) echo "<img src='assets/cross.gif' width='12px'>In Use";
?>
</div><br />
<input type="hidden" name="pos" id="pos">


<input type="submit" value="Continue" name="add_keg" />

</form>

<? } include('../footer.php'); ?>