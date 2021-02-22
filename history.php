<?php
	include_once("includes/global.php");
	
	// Set the title to History
	$title = "History";
	
	// If the user is not logged in
	if ($LoggedIn === false)
	{
		// Display an error
		$Notification = create_error("You must log in to view this page.");
	}
	else
	{
		// Get the id supplied to the request
		$id = getvariable("id");
		
		// If an id wasn't supplied
		if ($id === false)
		{
			$id = "";
		}
		
		// Escape the id
		$id = $Database->escape($id);
		
		// Get the current page
		$page = getvariable("page");
		
		// Initialize next and previous strings
		$next = "";
		$prev = "";
		
		// Set the lower limit (What part of chat history are we starting at)
		$lowerlimit = 0;
		
		// Set the upper limit (What part of chat history are we ending with)
		$upperlimit = 20;
		
		// If there isn't a page specified set the page to 1.
		if ($page === false)
		{
			$page = 1;
		}
		else
		{
			// Convert the page to an integer.
			$page = intval($page);
			
			// If the page didn't convert to an integer properly, set the page to one.
			if (empty($page))
			{
				$page = 1;
			}
		}
		
		// Escape the page input.
		$page = $Database->escape($page);
		
		// Set the upper limit to be the page * 20.
		$upperlimit = ($page * 20);
		
		// Set the lower limit to the upperlimit minus 20.
		$lowerlimit = ($upperlimit - 20);
		
		// Initialize an empty string for history data.
		$historydata = "";
		
		// Initialize a variable for number of history entries.
		$countHistory = 0;
		
		// If the id isn't equal to false.
		if ($id != false)
		{
			// Wild character search for the id either being a username or UID
			$search = "%" . $id . "%";
			
			// Prepared Select - 2 String parameter types - String search and String id
			$countHistory = $Database->preparedSelect("SELECT COUNT(*) as cnt from chat_history, chat_users where (chat_users.username LIKE ? or chat_users.uid = ?) and chat_history.uid = chat_users.uid", "ss", $search, $id);
			$countHistory = $countHistory["cnt"];
		}
		else
		{
			// If an id isn't specified get all history entries.
			
			// Query the database to get the number of history entries
			$countHistory = $Database->Query("SELECT COUNT(*) as cnt from chat_history");
			
			// Get the data
			$countHistory = $countHistory->fetch_assoc();
			
			// Get the count variable from the data result
			$countHistory = $countHistory["cnt"];
		}
		
		// Floor the number of pages and divide by 20 (Number of results/20) - 20 results each page
		$pages = ceil($countHistory/20);
		
		// If the page specified is more than the number of pages, specifiy no results.
		if ($page > $pages)
		{
			$Notification = create_error("No results.");
		}
		else
		{
		
			// Create the table header
			$historydata .= "<thead><tr><th style='width: 20%' class='text-center'>Time</th><th class='text-center'>User</th><th>Message</th></tr></thead>";
			
			// Set the current history to false
			$curhistory = false;
			if ($id != false)
			{
				// Wildcharacter search
				$search = "%" . $id . "%";
				
				// Prepared Select Array - 2 String Parameters and 2 Integer Parameters - String search, String ID, Integer lower limit, Integer upper limit
				$curhistory = $Database->preparedSelectArray("SELECT chat_history.timestamp, chat_history.uid, chat_history.message, chat_users.username, chat_users.avatar from chat_history, chat_users where chat_history.uid = chat_users.uid and (chat_users.username like ? or chat_users.uid = ?) ORDER BY timestamp DESC LIMIT ?, ?;", "ssii", $search, $id, $lowerlimit, $upperlimit);
			}
			else
			{
				// Prepared Select Array - 2 String Parameters and 2 Integer Parameters - String search, String ID, Integer lower limit, Integer upper limit
				$curhistory = $Database->preparedSelectArray("SELECT chat_history.timestamp, chat_history.uid, chat_history.message, chat_users.username, chat_users.avatar from chat_history, chat_users where chat_history.uid = chat_users.uid ORDER BY timestamp DESC LIMIT ?, ?;", "ii", $lowerlimit, $upperlimit);
			}
			
			// If the curhistory variable isn't empty.
			if (!empty($curhistory))
			{
				// Loop through the current history given by the prepared select array data.
				foreach($curhistory as $index=>$value)
				{
					// Set the Time variable to the timestamp given by the prepared select data
					$Time = $value["timestamp"];
					
					// Convert the unix timestamp to a date. (CST Time)
					$Date = date("F j, Y, g:i a", $Time); 
					
					
					// Set the UID variable to the UID given
					$UID = $value["uid"];
					
					// Set the Username variable to the Username given
					$Username = $value["username"];
					
					// Set the Avatar variable to the Avatar given.
					$Avatar = $value["avatar"];
					
					// If the user doesn't have an avatar, set it to the default.
					if (empty($Avatar) || $Avatar === "")
					{
						$Avatar = "images/default-avatar.png";
					}
					
					// Create the avatar link
					$AvatarLink = "<img style='display: inline; width: 50px; height: auto;margin-right: 20px;' src='{$Avatar}' alt=''/>";
					
					
					// Get the message from the data
					$Message = $value["message"];
					
					// Create the user text that links to their member profile
					$User = $AvatarLink . "<a href='member.php?id={$UID}'>{$Username}</a>";
					
					// Create the table row containing the data of the time the message was sent, the user with their avatar, and the message.
					$historydata.="<tr><td style='text-align: center'>{$Date}</td><td style='text-align: center'>{$User}</td><td>{$Message}</td></tr>";
				}
			}
			
			// If the page is more than one
			if ($page > 1)
			{
				// Create the previous button that links to the previous page.
				$prevpage = $page - 1;
				$prev = "<li class='previous'><a href='history.php?page={$prevpage}&id={$id}'>Previous</a></li>";
			}
			
			// If the current page is less than the number of pages possible.
			if ($page < $pages)
			{
				// Create the next page button that links to the next page.
				$nextpage = $page + 1;
				
				$next = "<li class='next'><a href='history.php?page={$nextpage}&id={$id}'>Next</a></li>";
			}
		}
		
		// Create the history page
		eval("\$content = \"".$template->get("history")."\";");
	}
	
	// Get the main content
	eval("\$index = \"".$template->get("main")."\";");
	
	// Output it to the client.
	html_output($index);
?>