<? echo '<?xml version="1.0" encoding="utf-8"?>'; ?>
<rss version="2.0">
<channel>


<title>Keg Server RSS Feed</title>
<link>http://www.kegserver.com</link>
<description></description>
<lastBuildDate>Mon, 12 Sep 2009 18:37:00 GMT</lastBuildDate>
<language>en-us</language>

<?
include('functions/dbconn.php');

	$sql = "SELECT * FROM keg_stats ORDER BY timestamp DESC LIMIT 20"; 
	$result = mysql_query($sql);
	while($row = mysql_fetch_array($result)) {
		$sql = "SELECT * FROM login_kegs WHERE keg_id='".$row['keg_id']."'"; 
		$result2 = mysql_query($sql);
		$keg_row = mysql_fetch_array($result2);
		$kegname = $keg_row['name'];
		
		echo "<item><title>".date('g:ia l',strtotime($row['timestamp'])).", ".$row['user']." poured ".$row['quantity']."mL of ".$kegname."</title><link>http://www.kegserver.com</link><guid>http://www.kegserver.com</guid><pubDate>".date('D, d M Y H:i:s T',strtotime($row['timestamp']))."</pubDate>";
		echo "<description></description></item>";
	}


?>


</channel>
</rss>
