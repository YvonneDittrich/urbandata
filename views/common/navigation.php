<!-- header starts -->
<div class="navbar navbar-default">
	<div class="navbar-main-outer">
	  <div class="nav-top-bar">
		<div class="container">
			<div class="row">
				<div class="col-sm-12">
					<div class="left-top-nav">
						<a ui-sref="notifications" ng-show="user_type"><i class="fa fa-bell" aria-hidden="true"></i> Notifications <span ng-show="numUnreadMsgs > 0">({{numUnreadMsgs}})</span></a>&nbsp;
					</div>
					<div class="logo-cont">
						<a ui-sref="login">
							<span>UrbanData</span>2<span>Decide</span>
						</a>
					</div>
					<div class="right-top-nav hide-on-tab" ng-show="user_type">
						<a ui-sref="logout"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a>
						<a ui-sref="preferences"><i class="fa fa-bell" aria-hidden="true"></i> Preferences</a>
					</div>
					<div class="right-top-nav" ng-show="!user_type">
						<a ui-sref="login"><i class="fa fa-lock" aria-hidden="true"></i> Login</a>
					</div>
					<button ng-show="user_type" type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
				</div>
			</div>
		</div>
	  </div>
	  <div class="container" ng-show="user_type">
		<div class="header-container">
			<div class="navbar-collapse collapse">
			  <ul class="nav navbar-nav">
				<li><a ui-sref="home">Home</a></li>
				<li class="dropdown">
					<a href="javascript:;">Profile</a>
					<ul class="dropdown-menu">
						<li><a ui-sref="profile">Profile</a></li>
						<li><a ui-sref="change-password">Change Password</a></li>
					</ul>
				</li>
				<li class="dropdown">
					<a href="javascript:;">UrbanDecisionMaker</a>
					<ul class="dropdown-menu">
						<li><a href="{{SITE_URL}}scripts/process.php?act=goto-decision-spaces">My Decision Spaces</a></li>
						<li><a href="{{SITE_URL}}scripts/process.php?act=goto-decision-spaces&type=other">Other Decision Spaces</a></li>
						<li><a ui-sref="dashboard">Dashboard</a></li>
						<li><a ui-sref="export-pool">Expert Pool</a></li>
					</ul>
				</li>
				<li><a ui-sref="help">Help</a></li>
				<li><a ui-sref="notifications" class="show-on-tab">Notifications (3)</a></li>
				<li><a ui-sref="preferences" class="show-on-tab">Preferences</a></li>
				<li><a ui-sref="logout" class="show-on-tab">Logout</a></li>
			  </ul>
			</div><!--/.nav-collapse -->
		</div>
	  </div>
	</div>
</div>
<!-- header ends -->