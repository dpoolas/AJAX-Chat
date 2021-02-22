<?php
	// Include the global php file
	include_once("includes/global.php");
	
	// Set the title to login.
	$title = "Login";
	
	// See if the client wants to logout
	$Logout = getvariable("logout");
	if ($Logout != false)
	{
		// Clear the session array
		$_SESSION = array();
		
		// Destroy the session
		session_destroy();
		
		// Redirect the user to the main page
		redirect(0,"index.php");
		return;
	}
	
	// If the user is logged in and goes to this page and isn't logging out
	if ($LoggedIn)
	{
		// Create an error
		$content = create_error("You are already logged in.");
	}
	else
	{
		// If the user is logging in and specifies a username and password
		$Username = postvariable("username");
		$Password = postvariable("password");
		
		// Make sure there's a valid username and password
		if ($Username != false && $Password != false)
		{
			// Escape the username
			$Username = $Database->escape($Username);
			
			// Escape the password
			$Password = $Database->escape($Password);
			
			// Confirm the login
			$LogData = $Database->confirmLogin($Username, $Password);
			
			// If login data is false, it's an unsuccessful login
			if ($LogData == false)
			{
				// Create an error
				$Notification = create_error("Invalid credentials!");
			}
			else
			{
				// Create a success notification
				$Notification = create_success("Success, you will be redirected shortly.");
				
				// Set the session variable LoggedIn to be true
				$_SESSION["LoggedIn"] = true;
				
				// Set the session variable Username to be the username returned
				$_SESSION["Username"] = $LogData["username"];
				
				// Set the session variable UID to be the UID returned
				$_SESSION["UID"] = $LogData["uid"];
				
				// Redirect to main page
				redirect(0,"index.php");
			}
		}
		
		// Output the login page
		eval("\$content = \"".$template->get("login")."\";");
	}
	
	// Output the main html
	eval("\$index = \"".$template->get("main")."\";");
	
	// Output all contents
	html_output($index);
?>