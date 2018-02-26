<div class="middle-part other-page" ng-app="profile">
	<div class="container">
		<div class="row">
			<div class="col-sm-3">
    			<div ng-include src="'views/common/sidebar.html'"></div>
			</div>
			<div class="col-sm-9">
				<h1>Change Password</h1>
				<div class="row">
					<div class="col-sm-6 col-sm-offset-3">
						<span class="error-msg-top" data-ng-show="message">{{message}}</span>
						<form class="frm-main" name="profile" id="profile" ng-submit="submitChangePasswordForm(profile.$valid)" novalidate="novalidate" autocomplete="off" ng-model="profile">
							<div class="row">
								<div class="col-sm-12">
									<label>Old Password:</label>
									<input type="password" name="old_password" id="old_password" placeholder="Old Password" value="" ng-required="true" ng-model="formData.old_password" ng-minlength="8" ng-maxlength="20" ng-pattern="/(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z])/" />
									<span class="error-msg" ng-show="profile.old_password.$touched && profile.old_password.$error.required">Old password is Required.</span>
									<span class="error-msg" ng-show="profile.old_password.$error.minlength || profile.old_password.$error.maxlength">Passwords must be between 8 and 20 characters.</span>
									<span class="error-msg" ng-show="!profile.old_password.$error.minlength && !profile.old_password.$error.maxlength && profile.old_password.$error.pattern">Must contain one lower &amp; uppercase letter, and one non-alpha character (a number or a symbol.)</span>
									<span class="error-msg" ng-show="oldPasswordError">{{oldPasswordError}}</span>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12">
									<label>Password:</label>
									<input type="password" name="password" id="password" placeholder="Password" value="" ng-required="true" ng-model="formData.password" ng-minlength="8" ng-maxlength="20" ng-pattern="/(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z])/" />
									<span class="error-msg" ng-show="profile.password.$touched && profile.password.$error.required">Password is Required.</span>
									<span class="error-msg" ng-show="profile.password.$error.minlength || profile.password.$error.maxlength">Passwords must be between 8 and 20 characters.</span>
									<span class="error-msg" ng-show="!profile.password.$error.minlength && !profile.password.$error.maxlength && profile.password.$error.pattern">Must contain one lower &amp; uppercase letter, and one non-alpha character (a number or a symbol.)</span>
									<span class="error-msg" ng-show="passwordError">{{passwordError}}</span>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12">
									<label>Confirm Password:</label>
									<input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password"  value="" ng-required="true" ng-model="formData.confirm_password" ng-minlength="8" ng-maxlength="20" ng-pattern="/(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z])/" valid-password-c="formData.password" />
									<span class="error-msg" ng-show="profile.confirm_password.$touched && profile.confirm_password.$error.required">Confirm password is Required.</span>
									<span class="error-msg" ng-show="profile.confirm_password.$error.noMatch && !profile.confirm_password.$error.required">Password mismatch</span>
									<span class="error-msg" ng-show="confirmPasswordError">{{confirmPasswordError}}</span>
								</div>
							</div>
							
							<div class="row">
								<div class="col-sm-12">
									<button type="submit" class="btn btn-md btn-primary" data-ng-disabled="profile.$pending || profile.$invalid || loading" ng-class="{ 'disabled-button' : profile.$invalid }"><i class="glyphicon glyphicon-refresh glyphicon-refresh-animate" data-ng-show="loading"></i> Update</button>
									
									<span class="success-msg-bottom" data-ng-show="success_message">{{success_message}}</span>
								</div>
							</div>
						</form>	
					</div>
				</div>
			</div>
		</div>
	</div>
</div>