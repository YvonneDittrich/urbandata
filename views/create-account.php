<div class="middle-part other-page">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<div class="row">
					<div class="col-sm-8 col-sm-offset-2">
						<div class="sidebar">
							<h1>Create New Account</h1>
							<span class="error-msg-top" data-ng-show="message">{{message}}</span>
							<form class="frm-main" name="signup" id="signup" ng-submit="submitCreateAccountForm(signup.$valid)" novalidate="novalidate" autocomplete="off" ng-model="signup">
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
										<span class="error-msg" ng-show="signup.first_name.$touched && signup.first_name.$error.required">First name is Required.</span>
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
										<span class="error-msg" ng-show="signup.last_name.$touched && signup.last_name.$error.required">Last name is Required.</span>
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
										<span class="error-msg" ng-show="signup.organisation.$touched && signup.organisation.$error.required">Organisation is Required.</span>
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
									<div class="col-sm-6">
										<label>Username: <span class="red-txt">*</span></label>
										<input type="text" name="username" id="username" placeholder="Username" value="" ng-required="true" ng-model="formData.username" ng-change="usernameError=''" />
										<span class="error-msg" ng-show="signup.username.$touched && signup.username.$error.required">Username is Required.</span>
										<span class="error-msg" ng-show="usernameError">{{usernameError}}</span>
									</div>
									<div class="col-sm-6">
										<label>Email Address: <span class="red-txt">*</span></label>
										<input type="email" name="email" id="email" placeholder="Email Address" value="" ng-required="true" ng-model="formData.email" ng-change="emailError=''" />
										<span class="error-msg" ng-show="signup.email.$touched && signup.email.$error.required">Email is Required.</span>
										<span class="error-msg" ng-show="signup.$error.email && !signup.email.$pristine">Email is invalid.</span>
										<span class="error-msg" ng-show="emailError">{{emailError}}</span>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-6">
										<label>Password: <span class="red-txt">*</span></label>
										<input type="password" name="password" id="password" placeholder="Password" value="" ng-required="true" ng-model="formData.password" ng-minlength="8" ng-maxlength="20" ng-pattern="/(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z])/" ng-change="passwordError=''" />
										<span class="error-msg" ng-show="signup.password.$touched && signup.password.$error.required">Password is Required.</span>
										<span class="error-msg" ng-show="signup.password.$error.minlength || signup.password.$error.maxlength">Passwords must be between 8 and 20 characters.</span>
										<span class="error-msg" ng-show="!signup.password.$error.minlength && !signup.password.$error.maxlength && signup.password.$error.pattern">Must contain one lower &amp; uppercase letter, and one non-alpha character (a number or a symbol.)</span>
										<span class="error-msg" ng-show="passwordError">{{passwordError}}</span>
									</div>
									<div class="col-sm-6">
										<label>Confirm Password: <span class="red-txt">*</span></label>
										<input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password"  value="" ng-required="true" ng-model="formData.confirm_password" ng-minlength="8" ng-maxlength="20" ng-pattern="/(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z])/" valid-password-c="formData.password" ng-change="confirmPasswordError=''" />
										<span class="error-msg" ng-show="signup.confirm_password.$touched && signup.confirm_password.$error.required">Confirm password is Required.</span>
										<span class="error-msg" ng-show="signup.confirm_password.$error.noMatch && !signup.confirm_password.$error.required">Password mismatch</span>
										<span class="error-msg" ng-show="confirmPasswordError">{{confirmPasswordError}}</span>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12 register-terms-chk">
										<input type="checkbox" name="chkTerms" id="chkTerms" value="Y" ng-model="formData.chkTerms" ng-required="!formData.chkTerms" /> By clicking this box, you agree to our <a ui-sref="terms-and-conditions">Terms and Conditions</a> and <a ui-sref="privacy-policy">Privacy Policy</a>.<br/><br/>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12">
										<button type="submit" class="btn btn-md btn-primary" data-ng-disabled="signup.$pending || loading || signup.$invalid" ng-class="{ 'disabled-button' : signup.$invalid }"><i class="glyphicon glyphicon-refresh glyphicon-refresh-animate" data-ng-show="signup.$pending || loading"></i> Register</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>