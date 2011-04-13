<? session_start(); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>

<title>PsiU KegServer</title>
<META HTTP-EQUIV="Pragma" CONTENT="no-cache"> <!-- Important for security -->
<META HTTP-EQUIV="Expires" CONTENT="-1">

<meta name="robots" content="index,follow">
<meta name="author" content="Christopher Balchin | Jigowatt">
<link rel="stylesheet" href="stylesheet.css" /> <!-- Main Stylesheet -->
<link href="rss.php" type="application/rss+xml" rel="alternate" title="Keg Server RSS Feed" />

</head>
<body>

<div id="header">
    
    <div id="title"><a href="index.php"><h1>PsiU KegServer</h1></a></div>
    
<? 
	// If the user is logged in, display the logout link.
	if(session_is_registered('username')) {
    	echo "<div id='logout'><a href='admin'>Admin </a> | <a href='logout.php'>Logout (".$_SESSION['fname'].")</a></div>";
    } else {
    	echo "<div id='login'><a href='login.php'>Login</a></div>";
    }
?>

</div>

<div id="main">