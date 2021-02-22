<form action="member.php" method="post" enctype="multipart/form-data">
	<div class="col-xs-12 col-12 col-md-12, col-sm-12">
		<div class="well col-md-4 top-buffer" style="padding-right: 15px">
			<p class="text-center text-primary"><strong>{$Username}</strong></p>
			<div>
				<img class="img-responsive top-buffer img-rounded center-block" src={$Avatar} alt=''/>
			</div>
			<div id="info">
				<div class="text-center top-buffer">
					<a href="member.php?removeavatar=1">Remove Avatar</a>
				</div>
				<div class="text-center top-buffer">
					<label>Avatar Path</label>
					<input type="text" name="filepath"></input>
				</div>
				<div
				<div class="text-center top-buffer">
					<label class="btn btn-default btn-file">
						Upload Avatar
						<input name="fileToUpload" type="file" style="display: none;">
					</label>
				</div>
				<div class="text-center top-buffer">
					<input name="submit" class="btn btn-primary" type="submit" value="Submit"></input>
				</div>
			</div>
		</div>
		<div class="well col-md-7 col-md-offset-1 top-buffer">
			<p class="text-center text-primary"><strong>Info</strong></p>
			<div class="table-responsive">
				<table class="table table-bordered table-white text-center text-bold">
					<tr>
						<td width="30%">Join Date</td>
						<td>{$Joined}</td>
					</tr>
					<tr>
						<td>Chat History</td>
						<td><a href="history.php?id={$MemberID}">Link</a></td>
					</tr>
					<tr>
						<td class="text-center">Messages</td>
						<td class="text-center">{$msgCount}</td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</form>
