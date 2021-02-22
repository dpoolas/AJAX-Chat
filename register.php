<?php
	// Include the global PHP file
	include_once("includes/global.php");
	
	// Set the title to Register
	$title = "Register";
	
	$content = "";
	
	// If the user is currently logged in
	if ($LoggedIn)
	{
		// Display an error that they need to log out in order to view this page.
		$content = create_error("Logout in order to access this form.");
	}
	
	// Get the submit post variable
	$Submit = postvariable("submit");
	
	// Get the username post variable
	$Username = postvariable("username");
	
	// Get the password post variable
	$Password = postvariable("password");
	
	// Get the confirm password post variable
	$Confirm = postvariable("confirm");
	
	// Get the email post variable
	$Email = postvariable("email");

	// If we are hitting the submit button
	if ($Submit != false)
	{
		// Check that the username, password, confirm password, and email variables aren't false.
		if ($Username != false && $Password != false && $Confirm != false && $Email != false)
		{
			// preg_match - http://php.net/manual/en/function.preg-match.php
			// Make sure it passed uppercase requirements
			$Uppercase = preg_match('@[A-Z]@', $Password);
			
			// Make sure it passed lowercase requirements
			$Lowercase = preg_match('@[a-z]@', $Password);
			
			// Make sure it passed number requirements
			$Number    = preg_match('@[0-9]@', $Password);
			
			// Escape the username
			$Username = $Database->escape($Username);
			
			// Make sure there is not already a user with the username supplied
			if ($Database->getUser($Username))
			{
				// Create an error if there is already a user with that name
				$Notification = create_error("There is already a user with that name.");
			}
			elseif ($Password != $Confirm)
			{
				// Create error if the password and confirm password do not match.
				$Notification = create_error("Your password and confirm password field do not match.");
			}
			elseif(!$Uppercase || !$Lowercase || !$Number || strlen($Password) < 8) 
			{
				// Create an error if it does not meet password requirements
				$Notification = create_error("Password must have an uppercase letter, lowercase letter, a number, and must be 8 characters.");
			}
			elseif (!filter_var($Email, FILTER_VALIDATE_EMAIL))
			{
				// Create an error if it is not a valid e-mail
				$Notification = create_error("You must provide a valid e-mail address.");
			}
			else
			{
				// Create a hash for the password
				$Password = password_hash($Password, PASSWORD_DEFAULT);
				
				// Create the user
				$Database->createUser($Username, $Password, $Email);
				
				// Create a success notification
				$Notification = create_success("Success, login with the credentials you provided.");
				
				// Redirect the user to the login page
				redirect(1,"login.php");
			}
		}
		else
		{
			// Create an error that they must fill in all fields
			$Notification = create_error("You must fill in all the fields.");
		}
	}

	// If the user isn't logged in, display the register content
	if (!$LoggedIn || $LoggedIn === false || empty($LoggedIn))
	{
		eval("\$content = \"".$template->get("register")."\";");
	}
	
	// Create the main html output
	eval("\$index = \"".$template->get("main")."\";");
	
	// Output all contents
	html_output($index);
?>