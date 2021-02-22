<?php
	// Validate avatar images
	function validImage($file)
	{
		// Define the path for uploading avatars
		$path = "images/Avatars/";
		
		// Set the variable inPath to the argument file
		$inPath = $file;
		
		// Check if the file variable is an array or a string (Array if uploading a file, string if just linking)
		$isArray = false;
		if (is_array($file))
		{
			// Set inPath to the termporary name.
			$inPath = $file["tmp_name"];
			$isArray = true;
		}
		
		// Get image info
		$fileSize = getimagesize($inPath);


		// If no image info is returned it's most likely not an image
		if($fileSize != false) 
		{
			// Get the file type
			$fileMime = $fileSize["mime"];
			$size = 0;
			
			// If it is an array
			if ($isArray)
			{
				// Set the filename variable to the file name given in the array.
				$fileName = $file["name"];
				
				// Set the size variable to the file size given in the array.
				$size = $file["size"];
				
				// Set the path to the avatar path and then the file name.
				$fPath = $path . $fileName;
			}

			// Make sure the file is less than 2mb
			if ($size < 2000000)
			{
				// Make sure the image is a jpg, png, or jpeg (Currently gifs not supported)
				if ($fileMime == "image/jpg" || $fileMime == "image/png" || $fileMime == "image/jpeg")
				{
					if ($isArray)
					{
						// If the file doesn't exist
						if (!file_exists($path . $fileName)) 
						{
							// Move the file to the server.
							if (move_uploaded_file($file["tmp_name"], $fPath))
							{
								// Return the path (Array = uploaded file)
								return $fPath;
							}
						}
					}
					else
					{
						// Just link the path given in the avatar path (Not uploaded)
						return $inPath;
					}
				}
			}
		}
		
		// Return false 
		return false;
	}
?>