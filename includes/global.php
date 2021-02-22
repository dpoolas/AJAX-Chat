<?php
	// Start the session state - https://www.w3schools.com/php/php_sessions.asp
	session_start();
	
	// Include the database class
	include("mysql.php");
	
	// Connect the database
	global $Database;
	$Database = new Database("hostname", "username", "password", "databasename");
	
	// Include the template class
	include("templates.php");
	
	// Include the functions to have proper functionality throughout the site.
	include("functions.php");

	// Create a global includes variable
	global $Includes;
	$Includes = "";
	
	// Create a global error variable
	global $Notification;
	$Notification = "";
	
	// Create a global title variable.
	global $title;
	$title = "Main";
	
	// Create a global variable LoggedIn.
	global $LoggedIn;
	
	// Check the session to see if the current client connecting has connected before.
	$LoggedIn = sessionvariable("LoggedIn");
	
	// Get the current requested page.
	global $Page;
	$Page = $_SERVER['REQUEST_URI'];
	
	// If the page is a slash, the current active page is the index.
	if ($Page === "/")
	{
		$Page = "index.php";
	}
	
	// Preg_match to get the current page requested to just be the .php file. - http://php.net/manual/en/function.preg-match.php
	$Page = preg_match("/([\w]*).php/", $Page, $match);
	
	// Get the first match
	$Page = $match[1];
	
	// If we are logged in
	if ($LoggedIn != false)
	{	
		// Get the UID from the session
		$uid = sessionvariable("UID");
		
		// Make sure the UID is valid
		if ($uid != false)
		{
			// Set the lastactive field in the database to be the current unix timestamp.
			$Database->preparedUpdate("UPDATE chat_users SET lastactive = ? WHERE uid = ?", "ii", time(), $uid);
		}
	}
	
	// Create a global template variable.
	global $template;
	
	// Create a copy of the template class.
	$template = new template();
	
	
	// If we are not logged in give the navigation bar without the proper links.
	if (!$LoggedIn || $LoggedIn === false)
	{
		eval("\$header = \"".$template->get("header")."\";");
	}
	else
	{
		// If we are logged in give the logged in header.
		$UserIn = sessionvariable("Username");
		eval("\$header = \"".$template->get("headerlogged")."\";");
	}
	
	// Create the footer
	eval("\$footer = \"".$template->get("footer")."\";");
?>
