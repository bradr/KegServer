<?
include('functions/dbconn.php');
include('header.php');

	
	if(isset($_SESSION['fname'])) {
	echo "<div class='success_message'>Welcome back, you are logged in as <b>" . $_SESSION['fname'] . "</b></div>";
	}
	
	echo "<h2>Currently on Tap</h2>";
	
	echo "<p>Here's what's on tap at the Psi U Bar:</p>";
	
	$sql = "SELECT * FROM login_kegs WHERE pos!='' ORDER BY pos"; 
	$result = mysql_query($sql);
	// Check that at least one row was returned 
	$rowCheck = mysql_num_rows($result); 
	if($rowCheck > 0) { 
	// Show Current Kegs
	
		while($keg_row = mysql_fetch_array($result)) { 
			$remaining = round($keg_row['remaining']*10)/10;
			if ($remaining<0) { $remaining=0;}
			$percentage = round($remaining*100*10/$keg_row['size'])/10;
			$perpint = number_format($keg_row['costperL']/2,2);
			if ($rowCheck==1) {
				echo "<div style='width:90%; float:left; text-align:center; padding:0 5px; margin:10px 5px; text-decoration: none; color: #777;'><h3><a href='stats.php?keg_id=".$keg_row['keg_id']."'>". $keg_row['name'] . "</a></h3><p>". date("F j, Y",strtotime($keg_row['timestamp']))."<p><img src='http://chart.apis.google.com/chart?chs=180x30&cht=bhs&chco=4D89F9,E1E4EB&chd=t:".$percentage."|100'><p><strong>".$remaining."L Remaining </strong>(".$percentage."%)<p><strong>$".$perpint." per pint</strong> (500mL)</div>"; 
			} else if($rowCheck==2) {
				echo "<div style='width:45%; float:left; text-align: center; padding:0 5px; margin:10px 5px; border-right:1px dotted #ccc;'><h3><a href='stats.php?keg_id=".$keg_row['keg_id']."'>". $keg_row['name'] . "</a></h3><p>". date("F j, Y",strtotime($keg_row['timestamp']))."<p><img src='http://chart.apis.google.com/chart?chs=180x30&cht=bhs&chco=4D89F9,E1E4EB&chd=t:".$percentage."|100'><p><strong>".$remaining."L Remaining </strong>(".$percentage."%)<p><strong>$".$perpint." per pint </strong>(500mL)</div>"; 
			}
		}
	}
	

	echo "<h2>Recent Activity</h2><p>";	
	
	echo "<div id='time_div' style='width: 500px; height: 200px;'></div> ";

	$sql = "SELECT * FROM keg_stats ORDER BY timestamp"; 
	$result = mysql_query($sql);
	// Check that at least one row was returned 
	$rowCheck = mysql_num_rows($result); 
	if($rowCheck > 0) { 
		while($row = mysql_fetch_array($result)) { 	
			$startTime = mktime() - 365*24*3600;
			$endTime = mktime();
			$interval = 3600;
			$time = strtotime($row['timestamp']);
			$count[0]=0;
			if ($time > $startTime){  // && $row['timestamp'] < $endTime) {
				$i = floor((float)($time-$startTime)/3600);
				$count[$i] = $count[$i]+$row['quantity']/1000;
				$count[$i] = number_format($count[$i],2);
				if (!isset($count[$i-1])){ $count[$i-1]=0; }
				$count[$i+1]=0;
			} 
		}
	
	}
	if (!isset($count[(int)(mktime()-$startTime)/3600])) { $count[(int)(mktime()-$startTime)/3600]=0; }

?>




<script type="text/javascript" src="http://www.google.com/jsapi"></script>
    <script type="text/javascript">
   
      google.load("visualization", "1", {packages:["annotatedtimeline"]});
      google.setOnLoadCallback(drawData);
      function drawData() {
        var data = new google.visualization.DataTable();
        data.addColumn('date', 'Date');
        data.addColumn('number', 'Quantity');
<?

	echo "      data.addRows([\n";
	foreach ($count as $n => $value) {
		$month = (int)date('m',$startTime+3600*$n);//mktime()+3600*($n-$max));
		$month--;
		$date = date('Y,',$startTime+3600*$n) . $month. date(',d,H',$startTime+3600*$n);
		echo "		[new Date(".$date."), ". $value. "],\n";
	}

	echo "]);";
	
	$start=mktime()-3600*24*3;
	$month = (int)date('m',$start);
	$month--;
	$startDate = date('Y,',$start) . $month. date(',d',$start);
	
	$month = (int)date('m',mktime());
	$month--;
	$endDate = date('Y,',mktime()) . $month. date(',d',mktime());

?>
		startTime = new Date(<? echo $startDate; ?>);
		endTime = new Date(<? echo $endDate; ?>);
        var time = new google.visualization.AnnotatedTimeLine(document.getElementById('time_div'));
        time.draw(data, {displayExactValues:true, displayZoomButtons:true, displayRangeSelector:false, thickness:2, zoomStartTime:startTime });

      }
    </script> 

<?
		
	
	
	
	echo "<ul>";
	$sql = "SELECT * FROM keg_stats WHERE id!='0' ORDER BY timestamp DESC LIMIT 10 "; 
	$result = mysql_query($sql);
	while($row = mysql_fetch_array($result)) {
		$sql = "SELECT * FROM login_kegs WHERE keg_id='".$row['keg_id']."'"; 
		$result2 = mysql_query($sql);
		$keg_row = mysql_fetch_array($result2);
		$kegname = $keg_row['name'];
		echo "<li>".date('D, M j - g:ia',strtotime($row['timestamp'])).", ".$row['user']." poured ".$row['quantity']."mL of ".$kegname."</li>";
	}
	echo "</ul><br><p>";
	echo "<h2>Previous Kegs</h2>";
	
	$sql = "SELECT * FROM login_kegs WHERE pos='' ORDER BY timestamp DESC"; 
	$result = mysql_query($sql);
	// Check that at least one row was returned 
	$rowCheck = mysql_num_rows($result); 
	if($rowCheck > 0) { 

	// Show Previous Kegs
	
		echo "<div class='keglist'><ul>";
		while($row = mysql_fetch_array($result)) { 
		//	$percentage = ($row['remaining'])*100/$row['size'];
		//	$perpint = round($row['costperL']*100/2)/100;
			if ($rowCheck>0) {
				echo '<li><a href="stats.php?keg_id='.$row["keg_id"].'">'. $row['name'].' | '. date("F j, Y",strtotime($row['timestamp'])).'</a></li>';
			}
		}
		echo "</ul></div>";
	}

include('footer.php');

?>