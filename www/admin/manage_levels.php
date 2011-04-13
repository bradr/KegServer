<?
include('header.php');
	
	echo "<span class='add_ul'><a href='level_add.php' title='Add User Level'>Add User Level</a></span>";
	echo "<span class='edit_ul'><a href='level_edit.php' title='Edit User Level'>Edit User Level</a></span>";
	
	echo "<h2>Manage Levels</h2>";
		
	// Include the list of User Levels
	user_levels();

include('../footer.php');
?>