<?php
	class Template 
	{ 
		public function get($name) 
		{
			$output = "";
			$file = "Templates/" . $name . ".tpl";
			
			// Check if the file exists
			if (file_exists($file))
			{
				
				// Get the file contents
				$contents = file_get_contents($file);
				$output .= $contents;
			}
			
			// Replace double slashes with a single quote after added alshes.
			$output = str_replace("\\'", "'", addslashes($output));
			
			return $output;
		}
	}
?>