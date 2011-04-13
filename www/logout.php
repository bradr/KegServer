<?

//start the session 
session_start(); 

// For re-direction after logout. Because the form self-submits we need to keep the referal to the form.
$ref = "http://psiu.kegserver.com";//getenv('HTTP_REFERER');

//check to make sure the session variable is registered 
if(session_is_registered('username')){ 

//session variable is registered, the user is ready to logout 

session_unset(); 
session_destroy();

header("Location: ".$ref); 

} else { header("Location: ".$ref); }

?>