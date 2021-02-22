<form action="login.php" method="post">
	<div class="container">
		<div class="col-sm-8 col-md-6">
			<h3 class="dark-grey">Login</h3>
			<div class="control-group form-group">
				<label>Username</label>
				<div class="controls">
					<input type="text" id="usernameLogin" name="username" class="form-control" value='{$Username}'/>
				</div>
			</div>
			<div class="control-group form-group">
				<label>Password</label>
				<div class="controls">
					<input type="password" name="password" id="passwordLogin" class="form-control"/>
				</div>
			</div>
			<div class="control-group form-group">
				<div class="controls">
					<input type="submit" name="submit" ID="buttonLogin" class="btn btn-primary" value="Login"/>
				</div>
			</div>
		</div>
	</div>
</form>