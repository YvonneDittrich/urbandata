<div class="middle-part">
	<div class="container">
		<div class="row">
			<div class="col-sm-9">
				<div class="search-heading">
					<div class="row">
						<div class="col-sm-8">Decision Spaces</div>
						<div class="col-sm-4">
							<form name="search" id="search" method="post" action="" novalidate="novalidate" autocomplete="off" ng-model="search" onsubmit="return false;">
								<input type="text" name="keyword" id="keyword" placeholder="Type to search.." value="" onkeyup="execKeywordSearch();" />
								<input type="submit" class="hide" name="btn-search" id="btn-search" ng-click="exec_search(1)" /> 
							</form>
						</div>
					</div>
				</div>
				<div class="search-results">
					<p class="no-content">Please select criteria from above to search.</p>
				</div>
			</div>
			<div class="col-sm-3">
				<div class="widget">
					<h2>My Decision Spaces</h2>
					<div class="my-decision-spaces"><div class="loading-icon"><img src="images/loading.gif" /></div></div>
				</div>
				<div class="widget">
					<h2>Mostly Viewed</h2>
					<div class="mostly-viewed"><div class="loading-icon"><img src="images/loading.gif" /></div></div>
				</div>
			</div>
		</div>
	</div>
</div>
<input type="hidden" name="page" id="page" value="1" />