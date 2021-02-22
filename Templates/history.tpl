<div class="col-xs-12 col-12 col-md-12, col-sm-12">
	<div class="float-right top-buffer">
		<form>
			<input type="text" name="id" id="searchHistory" class="form-control" placeholder="Search...">
		</form>
	</div>
</div>
<div class="col-xs-12 col-12 col-md-12, col-sm-12">
		<div class="top-buffer table-responsive colm-sm-12">

			<table class="table table-bordered">
				{$historydata}
			</table>
			
			<ul class="pager top-buffer">{$prev}{$next}</ul>
		</div>
	</div>
</div>