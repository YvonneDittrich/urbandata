<div class="middle-part other-page">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<div class="row">
					<div class="col-sm-4 col-sm-offset-4">
						<h1>Forgot Password</h1>
						<span class="error-msg-top" data-ng-show="message">{{message}}</span>
						<span class="success-msg-top" data-ng-show="success_message">{{success_message}}</span>
						<form class="frm-main" name="forgot" id="forgot" ng-submit="submitForgotForm(forgot.$valid)" novalidate="novalidate" autocomplete="off" ng-model="forgot">
							<div class="row">
								<div class="col-sm-12">
									<label>Email:</label>
									<input type="text" name="email" id="email" placeholder="Email" value="" ng-required="true" ng-model="formData.email" />
									<span class="error-msg" ng-show="forgot.email.$touched && forgot.email.$error.required">Email is Required.</span>
										<span class="error-msg" ng-show="forgot.$error.email && !forgot.email.$pristine">Email is invalid.</span>
										<span class="error-msg" ng-show="emailError">{{emailError}}</span>
								</div>
								
							</div>
							<div class="row">
								<div class="col-sm-12">
									<button type="submit" class="btn btn-md btn-primary" data-ng-disabled="forgot.$pending || forgot.$invalid || loading" ng-class="{ 'disabled-button' : forgot.$invalid }"><i class="glyphicon glyphicon-refresh glyphicon-refresh-animate" data-ng-show="forgot.$pending || loading"></i> Submit</button>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12 auth-links">
									<div><a ui-sref="login">Back to Login</a></div>
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