<div class="middle-part other-page">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<div class="row">
					<div class="col-sm-4 col-sm-offset-4">
						<h1>Member Login</h1>
						<span class="error-msg-top" data-ng-show="message">{{message}}</span>
						<span class="success-msg-top" data-ng-show="success_message">{{success_message}}</span>
						<form class="frm-main" name="login" id="login" ng-submit="submitLoginForm(login.$valid)" novalidate="novalidate" autocomplete="off" ng-model="login">
							<div class="row">
								<div class="col-sm-12">
									<label>Username/Email:</label>
									<input type="text" name="username" id="username" placeholder="Username/Email" value="" ng-required="true" ng-model="formData.username" />
									<span class="error-msg" ng-show="login.username.$touched && login.username.$error.required">Username is Required.</span>
									<span class="error-msg" ng-show="usernameError">{{usernameError}}</span>
								</div>
								
							</div>
							<div class="row">
								<div class="col-sm-12">
									<label>Password:</label>
									<input type="password" name="password" id="password" placeholder="Password" value="" ng-required="true" ng-model="formData.password" />
									<span class="error-msg" ng-show="login.password.$touched && login.password.$error.required">Password is Required.</span>
									<span class="error-msg" ng-show="passwordError">{{passwordError}}</span>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12">
									<button type="submit" class="btn btn-md btn-primary" data-ng-disabled="login.$pending || login.$invalid || loading" ng-class="{ 'disabled-button' : login.$invalid }"><i class="glyphicon glyphicon-refresh glyphicon-refresh-animate" data-ng-show="login.$pending || loading"></i> Login</button>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12 auth-links">
									<div><a ui-sref="forgot-password">Forgot Password?</a></div>
									<div><a ui-sref="create-account">Create Account</a></div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>