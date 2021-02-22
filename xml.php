<?php
	// Include the global file
	include_once("includes/global.php");
	
	// Get the database connection
	global $Database;
	
	// Get the LoggedIn session variable
	global $LoggedIn;
	
	
	// If the client is logged in
	if ($LoggedIn)
	{
		// Get the message from post
		$Message = postvariable("message");
		
		// See if they are trying to retrieve the latest chat messages.
		$Latest = postvariable("retrievelatest");
		
		// Check if the client is trying to get an update.
		$Update = postvariable("update");
		
		// Get the ID of the last message they've received.
		$ID = postvariable("id");
		
		// If the message is not equal to false.
		if ($Message != false)
		{
			// Get the client's IP
			$forwarded = $_SERVER["HTTP_CF_CONNECTING_IP"];
			
			// Convert html characters to entities
			$Message = htmlspecialchars($Message);
			
			// Parse the BBCode (Function in functions.php)
			$Message = parsebb($Message);
			
			// Get the UID session variable
			$UID = sessionvariable("UID");
			
			// Check to make sure the UID is valid
			if ($UID != false)
			{
				// Prepared Update - 2 Integers and 2 Strings - Integer Unix Timestamp (Current Time), Integer UID, String Message, String IP Address
				$Database->preparedUpdate("INSERT INTO chat_history (timestamp, uid, message, ip) VALUES(?,?,?,?);", "iiss", time(), $UID, $Message, $forwarded);
			}
		}
		elseif($Latest != false)
		{
			// Getting the latest data
			// Prepared Select Array - 1 Integer - Integer Unix Timestamp (Current Time)
			$data = $Database->preparedSelectArray("SELECT chat_users.username, chat_users.uid, chat_users.avatar, chat_history.id, chat_history.timestamp, chat_history.message FROM chat_history, chat_users WHERE chat_history.uid = chat_users.uid and timestamp > (? - 3600) ORDER BY id ASC", "i", time());
			
			// Encode the data into json format
			$data = json_encode($data);
			
			// Send the data to the client
			echo $data;
		}
		elseif($Update != false && $ID != false)
		{
			// Getting an update with the chat messages after the last chat message received
			// Prepared Select Array - 2 Integers - Integer ID, Integer Unix Timestamp (Current Time)
			$data = $Database->preparedSelectArray("SELECT chat_users.username, chat_users.uid, chat_users.avatar, chat_history.id, chat_history.timestamp, chat_history.message FROM chat_history, chat_users WHERE chat_history.uid = chat_users.uid AND id > ? and timestamp > (? - 3600) ORDER BY id ASC", "ii", $ID, time());
			
			// Encode the data into json format
			$data = json_encode($data);
			
			// Send the data to the client
			echo $data;
		}
	}
?>