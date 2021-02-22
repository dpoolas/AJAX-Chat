<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel='stylesheet' href='CSS/chat.css' type='text/css'/>
		
		<title>Chatizzle - {$title}</title>
	</head>
	<body id={$Page}>
		{$header}
		<div id="wrap">
			<div class="container">
				{$Notification}
				{$content}
			</div>
		</div>

		{$footer}
		
		<script src="js/main.js" defer></script>
		{$Includes}
	</body>
</html>