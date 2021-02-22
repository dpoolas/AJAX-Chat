<form action="register.php" method="post">
	<div class="col-sm-6 col-md-6">
		<h3>Registration</h3>
		<div class="form-group col-lg-12">
			<label>Username</label>
			<input type="text" name="username" id="usernameRegister" class="form-control" value={$Username}>
		</div>
			
		<div class="form-group col-lg-6">
			<label>Password</label>
			<input type="password" name="password" id="passwordRegister" class="form-control">
		</div>
			
		<div class="form-group col-lg-6">
			<label>Repeat Password</label>
			<input type="password" name="confirm" id="confirmPasswordRegister" class="form-control">              
		</div>
							
		<div class="form-group col-lg-12">
			<label>Email Address</label>
			<input type="text" name="email" id="emailRegister" class="form-control" value={$Email}>
		</div>		
		
		<div class="form-group col-lg-12">
			 <input type="submit" id="registerButton" class="btn btn-primary" name="submit" value="Register">
		</div>
	</div>

	<div class="col-sm-6 col-md-6">
		<div class="panel panel-primary top-buffer">
				<div class="panel-heading">
					<h3 class="panel-title">Registration Requirements</h3>
				</div>
				<div class="panel-body">
					<ul>
						<li>Password must be at least 8 characters.</li>
						<li>Password must contain a number, lowercase character, and an uppercase character.</li>
						<li>E-mail Address must be valid.</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</form>