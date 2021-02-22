<?php
	//  Database class for prepared statements - https://www.w3schools.com/php/php_mysql_prepared_statements.asp
	Class Database
	{
		private $Database;
		
		// Constructor
		function __construct($ip, $username, $password, $database)
		{
			// Connect to the database with the ip, username, password, and database provided.
			$this->Database = mysqli_connect($ip, $username, $password, $database);
		}
		
		function getDatabase()
		{
			// Get the database object.
			return $this->Database;
		}

		function Connect($ip, $username, $password, $database)
		{
			// Connect to the database with the ip, username, password, and database provided.
			$this->Database = mysqli_connect($ip, $username, $password, $database);
		}
		
		function Query($str)
		{
			// Query the database
			return $this->Database->query($str);
		}
		
		function preparedUpdate()
		{
			// Get the arguments given to the function.
			$args = func_get_args();
			
			// The first argument should be the query statement so instantiate a variable for the query.
			$query = $args[0];
			
			// The second argument should be the parameter types so instantiate it for the query.
			$paramtypes = $args[1];
			
			// Prepare the query
			$statement = $this->Database->prepare($query);
			
			// Make sure the prepared statement is valid.
			if ($statement != false)
			{
				
				// Create an array for the parameters
				$params = array();
				
				// Put the parameter types into the parameter array
				array_push($params, $paramtypes);
				
				// Get the rest of the arguments given to the function and add them to the parameters.
				for($i = 2; $i < (count($args)); $i++)
				{
					array_push($params, $args[$i]);
				}
				
				// Call bind_param on the parameter and parameter types. - http://php.net/manual/en/mysqli-stmt.bind-param.php
				call_user_func_array(array($statement, "bind_param"), referenceArray($params));
				
				// Execute the prepared statement
				$execute = $statement->execute();
			}
		}
		
		// Not a prepared statement. Does a simple query and gets the data
		function getData($str)
		{
			// Instantiate an array for data.
			$data = array();
			
			// Get a result for a database query
			$result = $this->Database->Query($str);
			
			// Loop through the result and add the data to the data array.
			while($row = $result->fetch_assoc())
			{
				$data[] = $row;
			}
			
			// Return the data array.
			return $data;
		}
		
		function preparedSelect()
		{
			$data = NULL;
			$args = func_get_args();
			
			$query = $args[0];
			$paramtypes = $args[1];
			
			$statement = $this->Database->prepare($query);
			
			if ($statement != false)
			{
				
				$params = array();
				
				for($i = 1; $i < (count($args)); $i++)
				{
					array_push($params, $args[$i]);
				}
				
				call_user_func_array(array($statement, "bind_param"), referenceArray($params));
				
				$execute = $statement->execute();
				
				
				if ($execute) 
				{
					$result = $statement->get_result();
					
					if ($result != false)
					{
						if ($result->num_rows == 1)
						{
							$data = $result->fetch_assoc();
						}
						elseif ($result->num_rows > 1)
						{
							$data = array();
							while($row = $result->fetch_assoc())
							{
								$data[] = $row;
							}
						}
						else
						{
							return "";
						}
					}
					
					return $data;
				}
			}
		}
		
		function preparedSelectArray()
		{
			$data = NULL;
			$args = func_get_args();
			
			$query = $args[0];
			$paramtypes = $args[1];
			
			$statement = $this->Database->prepare($query);
			
			if ($statement != false)
			{
				
				$params = array();
				
				for($i = 1; $i < (count($args)); $i++)
				{
					array_push($params, $args[$i]);
				}
				
				call_user_func_array(array($statement, "bind_param"), referenceArray($params));
				
				$execute = $statement->execute();
				
				
				if ($execute) 
				{
					$result = $statement->get_result();
					
					if ($result != false)
					{
						$data = array();
						while($row = $result->fetch_assoc())
						{
							$data[] = $row;
						}
					}
					
					return $data;
				}
			}
		}
		
		// Escapes a string - Protection against SQL injection. (http://php.net/manual/en/mysqli.real-escape-string.php)
		function escape($str)
		{
			// Make sure it is a valid string.
			if ((strlen($str) < 1) || !$str)
			{
				return "";
			}
			
			// Escape the string
			return $this->Database->real_escape_string($str);
		}
		
		// Get the user specified for the chat.
		function getUser($username)
		{
			// Prepared select - String parameter type - Username parameter
			$Result = $this->preparedSelect("SELECT * FROM chat_users WHERE username=?", "s", $username);
			
			// Return the result
			return $Result;
		}
		
		// Get the user by the UserID.
		function getUserByID($id)
		{
			// Prepared Select - Integer parameter type - Integer UserID parameter
			$Result = $this->preparedSelect("SELECT * FROM chat_users WHERE uid=?", "i", $id);
			
			// Return the result
			return $Result;
		}
		
		// Get the number of message for a specified UserID.
		function getNumMessages($id)
		{
			// Prepared Select - Integer parameter type - Integer UserID parameter
			$Result = $this->preparedSelect("SELECT COUNT(id) AS cnt FROM chat_history WHERE uid=?;", "i", $id);
			
			// Return the result
			return $Result;
		}
		
		// Set the avatar for a specific user.
		function setAvatar($id, $path)
		{
			// Prepared Update - String and Integer parameter types - String path and Integer UserID parameters
			$this->preparedUpdate("UPDATE chat_users SET avatar=? WHERE uid=? ", "si", $path, $id);
		}
		
		// Create the user after the registration process.
		function createUser($username, $password, $email)
		{
			// Prepared Update - 4 String Parameter Types - String username, password, email, and current unix timestamp.
			$this->preparedUpdate("INSERT INTO chat_users(username, password, email, joindate) VALUES(?, ?, ?, ?);", "ssss", $username, $password, $email, time());
		}
		
		
		// Confirm the login when given the username and password on the login page.
		function confirmLogin($username, $password)
		{
			// Get the user from the username.
			$UserData = $this->getUser($username);
			
			// See if it is a valid user.
			if ($UserData != null)
			{
				// Get the password hash from the database.
				$Hash = $UserData["password"];
				
				// Verify that the password given matches the hash.
				$LogIn = password_verify($password, $Hash);
				
				// If the password hash is equal to the hash in the database then allow the connection.
				if ($LogIn)
				{
					return $UserData;
				}
				
				// Otherwise return false.
				return false;
			}
			else
			{
				// Return false if not a valid user.
				return false;
			}
		}
	}
?>