<div class="middle-part other-page">
	<div class="container">
		<h2>
			{{pageTitle}}
			<a ui-sref="add-decision-space" ng-show="{{type==''}}"><i class="fa fa-plus-circle" aria-hidden="true"></i><span class="hide-on-mobile"> Create decision space</span></a>
		</h2>
		
		<table id="dataTable" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
			<thead>
				<tr>
					<th>Name</th>
					<th>Update</th>
					<th>Members</th>
					<th>Invite</th>
					<th>Action</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th>Name</th>
					<th>Update</th>
					<th>Members</th>
					<th>Invite</th>
					<th>Action</th>
				</tr>
			</tfoot>
		</table>
	</div>
</div>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modal Header</h4>
      </div>
      <div class="modal-body">
        <p>Some text in the modal.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>