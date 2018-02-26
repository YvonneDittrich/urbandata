<div class="middle-part other-page" ng-app="profile">
	<div class="container">
		<div class="row">
			<div class="col-sm-8 col-sm-offset-2">
				<h1>Create Decision Space</h1>
				<span class="error-msg-top" data-ng-show="message">{{message}}</span>
				<form method="post" class="frm-main" name="profile" id="profile" novalidate="novalidate" autocomplete="off" ng-model="profile" enctype="multipart/form-data" action="scripts/process.php">
					<input type="hidden" name="act" id="act" value="create-decision-space" />
					<div class="row">
						<div class="col-sm-12">
							<label>Type: <span class="red-txt">*</span></label>
							<select name="type" id="type" ng-model="formData.type" ng-init="formData.type='Public'">
								<option value="Public">Public (Can be accessed by anyone)</option>
								<option value="Private">Private (Can be accessed only by members)</option>
							</select>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<label>Title: <span class="red-txt">*</span></label>
							<input type="text" name="title" id="title" placeholder="Title" value="" ng-required="true" ng-model="formData.title" />
							<span class="error-msg" ng-show="profile.title.$touched && profile.title.$error.required">Title is Required.</span>
							<span class="error-msg" ng-show="titleError">{{titleError}}</span>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<label>Description: </label>
							<textarea class="wysiswyg-editor" name="description" id="description" placeholder="Description" value="" ng-model="formData.description"></textarea>
						</div>
					</div>
					
					<div class="row" style="margin-top:15px;">
						<div class="col-sm-12">
							<label class="width-auto">Add file:</label> 
							<input type="file" name="path" id="path" ng-model="formData.path" valid-file />
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<label>Invite members:</label>
							<div class="members_list_outer">
								<div class="row">
									<div class="col-sm-3">
										<input type="text" name="member_name[]" placeholder="Name" value="" />
									</div>
									<div class="col-sm-3">
										<input type="email" name="member_email[]" placeholder="Email" value="" />
									</div>
									<div class="col-sm-3">
										<select name="member_type[]">
											<option value="Viewer">Viewer</option>
											<option value="Editor">Editor</option>
										</select>
									</div>
									<div class="col-sm-3">
										<a href="javascript:;" class="add_more add_more_member">Add More</a>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<button type="submit" class="btn btn-md btn-primary" data-ng-disabled="profile.$pending || profile.$invalid || loading" ng-class="{ 'disabled-button' : profile.$invalid }"><i class="glyphicon glyphicon-refresh glyphicon-refresh-animate" data-ng-show="profile.$pending || loading"></i> Submit</button>
						</div>
						<div class="col-sm-6">
							<a ui-sref="decision-spaces" class="btn btn-md">Cancel</a>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<span class="success-msg-bottom" data-ng-show="success_message">{{success_message}}</span>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>