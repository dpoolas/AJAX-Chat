<form action="member.php" method="post" enctype="multipart/form-data">
	<div class="col-xs-12 col-12 col-md-12, col-sm-12">
		<div class="well col-md-4 top-buffer" style="padding-right: 15px">
			<p class="text-center text-primary"><strong>{$Username}</strong></p>
			<div>
				<img class="img-responsive top-buffer img-rounded center-block" src={$Avatar} alt=''/>
			</div>
		</div>
		<div class="well col-md-7 col-md-offset-1 top-buffer">
			<p class="text-center text-primary"><strong>Info</strong></p>
			<div class="table-responsive">
				<table class="table table-bordered table-white">
					<tr>
						<td class="text-center" style="width: 30%">Join Date</td>
						<td class="text-center">{$Joined}</td>
					</tr>
					<tr>
						<td class="text-center">Chat History</td>
						<td class="text-center text-primary"><a href="history.php?id={$MemberID}">Link</a></td>
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