<?php
	// Include the global php file
	include_once("includes/global.php");
	
	// Include the avatar php file
	include_once("includes/avatar.php");
	
	// Set the title to member.
	$title = "Member";
	
	// Set the noshow variable to false
	$NoShow = false;
	$content = "";
	
	// Get the UID variable
	$UID = sessionvariable("UID");
	
	// Check if the user is logged in
	if ($LoggedIn != false)
	{	
	
		// See if the user is logged in and UID is false
		if ($LoggedIn and $UID != false)
		{
			// See if the client is trying to remove avatar
			if (getvariable("removeavatar") != false)
			{
				// Get the user by the UID 
				$Data = $Database->getUserByID($UID);
				
				// See if their current avatar is blank
				if ($Data["avatar"] && $Data["avatar"] != "")
				{
					// If not blank, set the avatar to none
					$Database->setAvatar($UID, "");
				}
				else
				{
					// Create error
					$Notification = create_error("Avatar not currently set.");
				}
			}
			elseif (postvariable("submit") != false)
			{
				// Get the file to upload
				$file = $_FILES["fileToUpload"];
				
				// Make sure the file size is not zero.
				if ($file && $file["size"] != 0)
				{
					// See if the path is valid
					$path = validImage($file);
					
					
					if ($path != false)
					{
						// Set the avatar with the given path and UID
						$Database->setAvatar($UID, $path);
					}
					else
					{
						// Create an error
						$Notification = create_error("Could not set avatar.");
					}
				}
				elseif ($filepath = postvariable("filepath"))
				{
					// See if the file path is valid (Not uploading but linking)
					$path = validImage($filepath);
					
					// If the path is not equal to false
					if ($path != false)
					{
						// Set the avatar
						$Database->setAvatar($UID, $path);
					}
					else
					{
						// Create an error
						$Notification = create_error("Could not set avatar, invalid link.");
					}
				}
			}
		}
		
		// Get the id variable
		$MemberID = getvariable("id");
		
		// If the user is logged in and didn't specify an ID
		if($LoggedIn && $MemberID === false)
		{
			$MemberID = sessionvariable("UID");
		}
		elseif ($MemberID === false)
		{
			$NoShow = true;
		}
		
		// If the noshow variable isn't false
		if (!$NoShow)
		{
			// If the MemberID is valid
			if ($MemberID)
			{
				// Get the user data
				$Data = $Database->getUserByID($MemberID);
				
				// If the data is valid
				if ($Data)
				{
					// Get the username 
					$Username = $Data["username"];
					
					// Get the avatar
					if (isset($Data["avatar"]) && $Data["avatar"] != "")
					{
						$Avatar = $Data["avatar"];
					}
					else
					{
						$Avatar = "images/default-avatar.png";
					}
					
					// Get the joined date
					$Joined = $Data["joindate"];
					$Joined = date( "n/d/y g:i A", ($Joined));
					
					// Get the number of messages
					$msgCount = $Database->getNumMessages($MemberID);
					$msgCount = $msgCount["cnt"];
					
				}
				else
				{
					$NoShow = true;
				}
			}
			else
			{
			}
			
			if (!$NoShow)
			{
				// If logged in and the memberid specified is equal to the UID
				if ($LoggedIn && ($MemberID == $UID))
				{
					// Display logged in member content
					eval("\$content = \"".$template->get("membercontentlogged")."\";");
				}
				else
				{
					// Display member content
					eval("\$content = \"".$template->get("membercontent")."\";");
				}
			}
			else
			{
				// create error for no profile data to display
				$Notification = create_error("No profile data to display.");
			}
		}
		else
		{
			// Create error for no profile data to display
			$Notification = create_error("No profile data to display.");
		}
	}
	else
	{
		// Create error to login
		$Notification = create_error("You must login to view this page.");
	}
	
	// Output main content
	eval("\$main = \"".$template->get("main")."\";");
	
	// Output all content
	html_output($main);
?>