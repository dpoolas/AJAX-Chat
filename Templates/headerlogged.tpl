<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="index.php">Chatizzle</a>
		</div>
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav navbar-right">
				<li id="index">
					<a href="index.php">Home</a>
				</li>
				
				<li id ="history">
					<a href="history.php">History</a>
				</li>
				
				 <li id="member">
					<a href="member.php">Welcome {$UserIn}</a>
				</li>
				
				<li id="logout">
					<a href="login.php?logout=1">Logout</a>
				</li>
			</ul>
		</div>
	</div>
</nav>