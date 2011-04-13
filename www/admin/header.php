<? // Important there are no spaces to line breaks here
session_start();
include('../check.php');
check_login(1);
include('includes/functions.php');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>PsiU KegServer | Control Panel</title>
<meta name="robots" content="index,follow">
<meta name="author" content="Christopher Balchin | Jigowatt">
<link rel="stylesheet" href="../stylesheet.css" /> <!-- Main Stylesheet -->
<link rel="stylesheet" href="stylesheet.css" /> <!-- Admin Panel Stylesheet -->
<script language="JavaScript" type="text/javascript" src="js/ajax_search.js"></script>

</head>
<body>

<div id="admin_header">
    
    <div id="admin_title"><a href="index.php"><h1>PsiU KegServer Control Panel</h1></a></div>
    
    <div id='logout'><a href='../index.php'>Home</a> | <a href='../logout.php'>Logout (<?=$_SESSION['fname'];?>)</a></div>

</div>

<div id="admin_main">