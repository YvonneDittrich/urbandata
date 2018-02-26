<div class="middle-part other-page" ng-app="profile">
	<div class="container">
		<div class="row">
			<div class="col-sm-3">
    			<div ng-include src="'views/common/sidebar.html'"></div>
			</div>
			<div class="col-sm-9">
				<h1>Update Profile</h1>
				<div class="row">
					<div class="col-sm-12">
						<span class="error-msg-top" data-ng-show="message">{{message}}</span>
						<form class="frm-main" name="profile" id="profile" ng-submit="updateProfileForm(profile.$valid)" novalidate="novalidate" autocomplete="off" ng-model="profile">
							<div class="row">
								<div class="col-sm-6">
									<label>Title: <span class="red-txt">*</span></label>
									<select name="title" id="title" ng-model="formData.title" ng-init="formData.title='Mr.'">
										<option value="Mr.">Mr.</option>
										<option value="Mrs.">Mrs.</option>
										<option value="Miss.">Miss.</option>
										<option value="Dr.">Dr.</option>
									</select>
								</div>
								<div class="col-sm-6">
									<label>First Name: <span class="red-txt">*</span></label>
									<input type="text" name="first_name" id="first_name" placeholder="First Name" value="" ng-required="true" ng-model="formData.first_name" />
									<span class="error-msg" ng-show="profile.first_name.$touched && profile.first_name.$error.required">First name is Required.</span>
									<span class="error-msg" ng-show="firstNameError">{{firstNameError}}</span>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-6">
									<label>Middle Name: </label>
									<input type="text" name="middle_name" id="middle_name" placeholder="Middle Name" value="" ng-model="formData.middle_name" />
								</div>
								<div class="col-sm-6">
									<label>Last Name: <span class="red-txt">*</span></label>
									<input type="text" name="last_name" id="last_name" placeholder="Last Name" value="" ng-required="true" ng-model="formData.last_name" />
									<span class="error-msg" ng-show="profile.last_name.$touched && profile.last_name.$error.required">Last name is Required.</span>
									<span class="error-msg" ng-show="lastNameError">{{lastNameError}}</span>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-6">
									<label>Gender: </label>
									<select name="gender" id="gender" ng-model="formData.gender" ng-init="formData.gender='Male'">
										<option value="Male">Male</option>
										<option value="Female">Female</option>
									</select>
								</div>
								<div class="col-sm-6">
									<label>Organisation: <span class="red-txt">*</span></label>
									<input type="text" name="organisation" id="organisation" placeholder="Organisation" value="" ng-required="true" ng-model="formData.organisation" />
									<span class="error-msg" ng-show="profile.organisation.$touched && profile.organisation.$error.required">Organisation is Required.</span>
									<span class="error-msg" ng-show="organisationError">{{organisationError}}</span>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-6">
									<label>Street:</label>
									<input type="text" name="street" id="street" placeholder="Street" value="" ng-model="formData.street" />
								</div>
								<div class="col-sm-6">
									<label>City:</label>
									<input type="text" name="city" id="city" placeholder="City" value="" ng-model="formData.city" />
								</div>
							</div>
							<div class="row">
								<div class="col-sm-6">
									<label>State:</label>
									<input type="text" name="state" id="state" placeholder="State" value="" ng-model="formData.state" />
								</div>
								<div class="col-sm-6">
									<label>Country:</label>
									<input type="text" name="country" id="country" placeholder="Country" value="" ng-model="formData.country" />
								</div>
							</div>
							<div class="row">
								<div class="col-sm-6">
									<label>Postcode:</label>
									<input type="text" name="postcode" id="postcode" placeholder="Postcode" ng-model="formData.postcode" />
								</div>
								<div class="col-sm-6">
									<label>Phone:</label>
									<input type="text" name="phone" id="phone" placeholder="Phone" value="" ng-model="formData.phone" />
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12">
									<button type="submit" class="btn btn-md btn-primary" data-ng-disabled="profile.$pending || loading || profile.$invalid" ng-class="{ 'disabled-button' : profile.$invalid }"><i class="glyphicon glyphicon-refresh glyphicon-refresh-animate" data-ng-show="profile.$pending || loading"></i> Update</button>
								</div>
							</div>
						</form>	
					</div>
				</div>
			</div>
		</div>
	</div>
</div>