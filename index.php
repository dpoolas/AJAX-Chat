<?php
	// Include the global file
	include_once("includes/global.php");
	
	// Create the who's online information.
	$whosonline = whosonline();
	
	if ($LoggedIn)
	{
		// Include the Chatbox javascript file (Used for ajax communication)
		$Includes = add_includes("chatbox.js");
		
		// Output the logged in index page
		eval("\$content = \"".$template->get("indexlogged")."\";");
	}
	else
	{
		// Create an error
		$Notification = create_error("You must log in to see the chat.");
		
		// Output the not logged in index page
		eval("\$content = \"".$template->get("indexmain")."\";");
	}

	// Output the main html content
	eval("\$index = \"".$template->get("main")."\";");
	
	// Output all the content specified
	html_output($index);
?>