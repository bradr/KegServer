<?
include('functions/dbconn.php');
include('header.php');
	
	if(isset($_SESSION['fname'])) {
	echo "<div class='success_message'>Welcome back, you are logged in as <b>" . $_SESSION['fname'] . "</b></div>";
	}
	
	echo "<h2>Stats</h2>";
	
	if(isset($_GET['keg_id'])) {
		$keg_id = $_GET['keg_id'];
		$sql = "SELECT * FROM login_kegs WHERE keg_id='$keg_id'"; 
		$result = mysql_query($sql) or die("Fatal error: ".mysql_error());
		if (mysql_num_rows($result) > 0)
		{
			$row = mysql_fetch_array($result);
			echo "<div style='text-align:center'><h3>". $row['name'] . "</a></h3><p>". date("F j, Y",strtotime($row['timestamp']))."<p>";
			if ($row['pos']!=''){
				//Show current stats
			}
			$sql = "SELECT * FROM keg_stats WHERE keg_id='$keg_id'"; 
			$result = mysql_query($sql) or die("Fatal error: ".mysql_error());
			if (mysql_num_rows($result) > 0){
				while($row2 = mysql_fetch_array($result)) {
					$drinker[$row2['user']] = $drinker[$row2['user']] + $row2['quantity']/1000;
				}
				asort($drinker);
				foreach($drinker as $name => $a) {
					$a = round($a*10)/10;
					if($a>($row['size'])*.02){
						$data = $data . $a . ',';
						$label = $label . $name . '|';
					} else {
						$other = $other + $a;
					}
					//$total = $total + $a;
				}
				
				if ($other>0){
					$data = $data . $other . ',';
					$label = $label . 'Other'.'|';
				}
				
				$waste = $row['remaining'];
				if ($waste<0) {$waste=0;}
				if($waste>1) {
					$data = $data . $waste;
					if($row['pos']=='') {
						$label = $label . 'Waste'.'|';
					} else { $label = $label . 'Remaining'.'|'; }
				}
				$data = substr_replace($data ,"",-1);
				$label = substr_replace($label ,"",-1);
			} else {
				$data = 100;
				$label = 'Remaining';
				$waste = $row['size'];
			}
			echo "<p><img src = 'http://chart.apis.google.com/chart?chs=500x250&cht=p&chd=t:$data&chl=$label'>";
			if($row['pos']!='') {
				echo "<br>".number_format($waste*100/$row['size'],1)."% Remaining<br>";
			}
			echo "</div><br>";
		}
	}

include('footer.php');

?>