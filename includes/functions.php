<?php
	// Output the data given to the function
	function html_output($variable)
	{
		// Echo prints the data on the client
		echo $variable;
	}
	
	// Get the variable from the $_GET table (Requests data from a specified source.)
	function getvariable($var)
	{
		if (isset($_GET[$var]))
		{
			return $_GET[$var];
		}
		
		return false;
	}
	
	// Get a variable from the sessions table (Unique for each client)
	function sessionvariable($var)
	{
		if (isset($_SESSION[$var]))
		{
			return $_SESSION[$var];
		}
		
		return false;
	}
	
	// Requests a variable from the $_POST table (Request or push data)
	function postvariable($var)
	{
		if (isset($_POST[$var]))
		{
			return $_POST[$var];
		}
		
		return false;
	}
	
	// Creates a popup alert with the message given. (Pushes a javascript alert function to the client)
	function alert($msg)
	{
		echo "<script type='text/javascript'>alert('{$msg}');</script>";
	}
	
	// Includes javascript files on the client.
	function add_includes()
	{
		$return = "";
		$args = func_get_args();
		
		foreach($args as $key => $value)
		{
			$return.= "<script src='js/{$value}' defer></script>\r\n";
		}
		
		return $return;
	}
	
	// Redirect the client to another page.
	function redirect($time, $link)
	{
		 header( "refresh:{$time}; url='{$link}'"); 
	}
	
	// Create an error prompt on the page.
	function create_error($str)
	{
		return "<div class='alert alert-danger top-buffer'><strong>{$str}</strong></div>";
	}
	
	// Create a success prompt on the page
	function create_success($str)
	{
		return "<div class='alert alert-success top-buffer'><strong>{$str}</strong></div>";
	}
	
	// Create a reference array (References access the same variables with different names.) - http://php.net/manual/en/language.references.whatare.php
	function referenceArray($arr)
	{
		// Initialize empty array
		$refs = array();
		// Loop through the array 
		foreach($arr as $key => $value)
		{
			// Put the reference in the array
			$refs[$key] = &$arr[$key];
		}
		
		// Return the references
		return $refs;
	}
	
	// Generate who is currently online
	function whosonline()
	{
		// Get the database initialized in global
		global $Database;
		
		// Prepared Select Array - Parameter Integer - Integer unix timestamp
		$data = $Database->preparedSelectArray("SELECT uid, username from chat_users where lastactive >= (? - 1800);", "i", time());
		
		// Initialize a string that is empty.
		$str = "";
		
		// Loop through the data and get each user
		foreach($data as $key => $value)
		{
			
			// Get the UID and Username
			$uid = $value["uid"];
			$username = $value["username"];
			
			// Create links that redirect to the member profiles for each user.
			$str.= "<a href='member.php?id={$uid}'>{$username}</a> ";
		}
		
		
		// Return the string
		return $str;
	}
	
	// Parses BBCode so users can customize text, links, etc using preg_replace. - http://php.net/manual/en/function.preg-replace.php
	function parsebb($text) 
	{
		// BBcode array (Bold, italics, underline, color, and link)
		$find = array(
			'~\[b\](.*?)\[/b\]~s',
			'~\[i\](.*?)\[/i\]~s',
			'~\[u\](.*?)\[/u\]~s',
			'~\[color=(.*?)\](.*?)\[/color\]~s',
			'~((?:ftp|https?)://.[a-zA-Z0-9./]*)~'
		);
		
		// Replace the bbcode with their respective HTML equivalents. (Specified in document)
		$replace = array(
			'<b>$1</b>',
			'<i>$1</i>',
			'<span style="text-decoration:underline;">$1</span>',
			'<span style="color:$1;">$2</span>',
			'<a href=$1 target="_blank">$1</a>'
		);
		
		// Return the replaced text
		return preg_replace($find,$replace,$text);
	}
?>