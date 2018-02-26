app.controller("MainController", function ($scope,$rootScope,$http,$location,$window) {
    $rootScope.pageTitle = "UrbanData";
	
	$rootScope.isActive = function(destination) {
        return destination === $location.path();
    }
	
	/*$rootScope.$on("CallParentMethod", function(){
	   $scope.parentmethod();
	});
	
	$scope.parentmethod = function() {
		alert('hi');
	};*/
	
	//////////for checking the load time events///////////
	$rootScope.$on('$viewContentLoaded', function (event) {  //$stateChangeSuccess
		
		//////////for checking user logged in///////////
		$http({
			method : "post",
			url    : 'scripts/process.php?act=check-login',
			headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
		}).then(function successCallback(response) {
			
			if(!response.data.success)
			{
				$rootScope.user_type = false;
				// redirect to login page if not logged in
				if ($location.path() === '/home' || $location.path() === '/notifications' || $location.path().indexOf('/preferences') >= 0 || $location.path().indexOf('/urbandata-visualiser') >= 0 || $location.path().indexOf('/decision-space') >= 0 || $location.path().indexOf('/dashboard') >= 0 || $location.path().indexOf('/export-pool') >= 0 || $location.path().indexOf('/help') >= 0 || $location.path().indexOf('/profile') >= 0 || $location.path().indexOf('/change-password') >= 0) 
				{
					$location.path('/login');
				} 
			}
			else
			{
				$rootScope.user_type = response.data.user_type;
				$rootScope.loggedin_name = response.data.loggedin_name;
				$rootScope.user_arr = response.data.user_arr;
				$rootScope.numUnreadMsgs = response.data.numUnreadMsgs;
				
				//redirect to specific page if logged in
				if ($location.path() === '/login' || $location.path() === '/create-account') 				
				{
					$location.path('/home');
				}
			}
			$scope.loading = false;
			
		}, function errorCallback(response) {
			
		});
		//////////////////////////////////////////////////////
		
		//////////for checking success or error msg///////////
		$http({
			method : "post",
			url    : 'scripts/process.php?act=check-success-error-msg',
			headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
		}).then(function successCallback(response) {
			
			if(response.data.success)
			{
				swal(response.data.msg_type_full, response.data.msg, response.data.msg_type);
			}
			
			$rootScope.TODAY_DATE = response.data.TODAY_DATE;
			$rootScope.SITE_URL = response.data.SITE_URL;
			$rootScope.ROOT_PATH = response.data.ROOT_PATH;
			
		}, function errorCallback(response) {
			
		});
		//////////////////////////////////////////////////////
	});
});

//////////controllers for update profile starts//////////
app.controller("ProfileController", function ($scope,$rootScope,$http,$location,$timeout) {
    $rootScope.pageTitle = "Tutor Choice - Update Profile";
	
	if($rootScope.ROOT_PATH==undefined)
	{	
		$location.path('home');
	}
	
	$scope.formData = $rootScope.user_arr;
	
	$scope.loading = false;
	
	$scope.updateProfileForm = function(isValid) {

		if (isValid)
		{
			$scope.loading = true;
			
			$http({
				method : "post",
				data   : $.param($scope.formData),
				url    : 'scripts/process.php?act=updateProfile',
				headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
			}).then(function successCallback(response) {
				
				if(!response.data.success) {
				  
				  $timeout(function () {
						$scope.message = '';
				  }, 4000);
				  
				  // if not successful, bind errors to error variables
				  //document.profile.reset();
				  $scope.loading = false;
				  $scope.message = response.data.msg;
				  $scope.firstNameError = response.data.errors.first_name;
				  $scope.lastNameError = response.data.errors.last_name;
				  $scope.organisationError = response.data.errors.organisation;
				}
				else
				{
				  // if successful, redirect to success page
				  $scope.loading = false;
				  
				 swal('Success',response.data.msg,'success');
				}
				
			}, function errorCallback(response) {
				
				$scope.message = response.data.msg;
				$scope.loading = false;
			});
		}
	};
});
//////////controllers for update profile ends//////////

//////////controllers for change password starts//////////
app.controller("ChangePasswordController", function ($scope,$rootScope,$http,$location,$timeout) {
    $rootScope.pageTitle = "Urban Data - Change password";
	
	if($rootScope.ROOT_PATH==undefined)
	{	
		$location.path('home');
	}
	
	$scope.userData = $rootScope.user_arr;
	
	$scope.loading = false;
	
	$scope.submitChangePasswordForm = function(isValid) {

		if (isValid)
		{
			$scope.loading = true;
			
			$http({
				method : "post",
				data   : $.param($scope.formData),
				url    : 'scripts/process.php?act=updateChangePassword',
				headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
			}).then(function successCallback(response) {
				
				if(!response.data.success) {
				  
				  $timeout(function () {
						$scope.message = '';
				  }, 4000);
				  
				  // if not successful, bind errors to error variables
				  //document.profile.reset();
				  $scope.loading = false;
				  $scope.message = response.data.msg;
				  $scope.oldPasswordError = response.data.errors.old_password;
				  $scope.passwordError = response.data.errors.password;
				  $scope.confirmPasswordError = response.data.errors.confirm_password;
				}
				else
				{
				  // if successful, redirect to success page
				  document.profile.reset();
				  $scope.loading = false;
				  
				 swal('Success',response.data.msg,'success');
				}
				
			}, function errorCallback(response) {
				
				$scope.message = response.data.msg;
				$scope.loading = false;
			});
		}
	};
});
//////////controllers for change password ends//////////

app.controller("NotificationsController", function ($scope,$rootScope,$http,$location,$timeout) {
    $scope.page = "notifications";
	
	$scope.show_notifications = function(page) {
		
		if(page==undefined || page=='')
			page = 1;
		
		page = $('#page').val();
		
		$scope.page = page;
		
		$rootScope.numUnreadMsgs = 0;
		
		$('.notifications-cont').html('<div class="preloader">&nbsp;&nbsp;&nbsp;Loading...<br/><img src="images/loading.gif" /></div>');
		
		$http({
			method : 'post',
			data   : {srch_type: $scope.srch_type},
			url    : 'scripts/process.php?act=getNotifications&page='+page,
			headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
		}).then(function successCallback(response) {
			if(response.data.success)
			{
				//alert(response.data.query);
				$('.notifications-cont').html(response.data.listing);
			}
			else
			{
				
			}
		}, function errorCallback(response) {
			
		});
	};
	
	$timeout(function() {
		angular.element('#btn-search').triggerHandler('click');
	}, 0);
});

app.controller("HomeController", function ($scope,$rootScope,$http,$location,$timeout) {
    $scope.page = "home";
	$scope.home_class = 'active';
	
	//$rootScope.$emit("CallParentMethod", {});
	
	$scope.exec_search = function(page) {
		
		if(page==undefined || page=='')
			page = 1;
		
		page = $('#page').val();
		
		$scope.page = page;
		
		var keyword = $('#keyword').val();
		
		$('.search-results').html('<div class="preloader">&nbsp;&nbsp;&nbsp;Loading...<br/><img src="images/loading.gif" /></div>');
		
		$http({
			method : 'post',
			data   : {srch_type: $scope.srch_type},
			url    : 'scripts/process.php?act=getSearchResults&keyword='+keyword+'&page='+page,
			headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
		}).then(function successCallback(response) {
			if(response.data.success)
			{
				//alert(response.data.query);
				$('.search-results').html(response.data.listing);
			}
			else
			{
				
			}
		}, function errorCallback(response) {
			
		});
	};
	
	loadTopTopics();
	
	$timeout(function() {
		angular.element('#btn-search').triggerHandler('click');
	}, 0);
});

//////////controllers for decision space detail page starts//////////
app.controller("DecisionSpaceController", function ($scope,$rootScope,$http,$location,$stateParams) {
    $scope.page = "decision-space";
	
	///////////for getting decision space by slug////////////
	$http({
			method : "post",
			url    : 'scripts/process.php?act=get-decision-space&id='+$stateParams.dsId+'&type=enc',
			headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
	}).then(function successCallback(response) {
		if(response.data.success)
		{
			$scope.rec = response.data.rec;
			$scope.title = response.data.title;
			$scope.path = response.data.rec.path;
			$scope.user_type = response.data.user_type;
			$scope.author = response.data.author;
			$scope.created_on = response.data.created_on;
			$scope.last_updated = response.data.last_updated;
			$('.img-cont').html(response.data.img_tag);
			$('.staticContent').html(response.data.description);
			/*$('.rating-cont').html(response.data.rating);
			
			if(response.data.rec.chk_rating_added==1 || response.data.rec.chk_likedislikes_added==1)
			{
				$('.other-meta').show();
				
				if(response.data.rec.chk_rating_added==1)
					$('.other-meta .rating-cont').show();
				if(response.data.rec.chk_likedislikes_added==1)
					$('.other-meta .like-dislike-outer').show();
			}*/
			
			//loadDsComments(response.data.rec.id,1,0);
			
			loadDsVisualisations(response.data.rec.id);
			
			$http({
					method : "post",
					url    : 'scripts/process.php?act=get-visualisations&id='+response.data.rec.id,
					headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
			}).then(function successCallback(res) {
				if(res.data.success)
				{
					$('.visualisations-ul').html(res.data.listing);
					
					//$('.visualisations-ul li').draggable({ revert: "invalid" });
					//$('.visualisations-ul').sortable();
					//$('.drag-drop-cont').droppable();					
				}
				else
				{
					$('.visualisations-ul').html('<li class="no-recs"><a>No visualisation(s) yet.</a></li>');
				}
			}, function errorCallback(res) {
		
			});
		}
		else
		{
			$location.path('home');
		}
	}, function errorCallback(response) {
		
	});
	//////////////////////////////////////////////////
	
	$scope.submitCommentForm = function(isValid) {
		
		if (isValid)
		{
			$scope.loading1 = true;
			
			var dspid = $('#dspid').val();
			
			$http({
				method : "post",
				data   : $.param($scope.commentData),
				url    : 'scripts/process.php?act=submitDSComment&dspid='+dspid,
				headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
			}).then(function successCallback(response) {
				
				if(!response.data.success)
				{
				  swal('Error',response.data.msg,'error');
				}
				else
				{
				  document.getElementById('comment').reset();
				  loadDsComments(dspid,1,0);
				}
				$scope.loading1 = false;
			}, function errorCallback(response) {
				$scope.message = response.data.msg;
				$scope.loading1 = false;
			});
		}
	};
});

//////////controllers for decision space starts//////////
app.controller("DecisionSpacesController", function ($scope,$rootScope,$http,$location) {
    $scope.page = "decision-spaces";
	
	var loc = $location.search();
	var qstr = '';
	$scope.type = '';
	$rootScope.pageTitle = 'My Decision Spaces';
	
	if(loc.type!='' && loc.type!=undefined)
	{
		qstr += '&type='+loc.type;
		$scope.type = loc.type;
		
		$rootScope.pageTitle = 'Other Decision Spaces';
	}
	
	//////////for getting already added records///////////
	$('#dataTable').DataTable( {
		"dom": 'Tfgtip',
		"responsive": true,
        "processing": true,
        "serverSide": true,
        "iDisplayLength": 25,
		"ajax": {
					url: "scripts/process.php?act=get-decision-spaces"+qstr,
					complete: function(){$('#action').removeClass('sorting');}
				}
    } );
	//////////////////////////////////////////////////////
	
});

app.controller("AddDecisionSpaceController", function ($scope,$rootScope,$http,$location) {
    $scope.pageTitle = "Decisoin Space- Add Decision Space";
	
	initiateWysiswyg();
});

app.controller("UpdateDecisionSpaceController", function ($scope,$rootScope,$http,$location,$stateParams) {
    $scope.pageTitle = "Decisoin Space- Update Decision Space";
	
	var loc = $location.search();
	$scope.type = '';
	if(loc.type!='' && loc.type!=undefined)
	{
		$scope.type = loc.type;
	}
	
	///////////for getting record////////////
	$http({
			method : "post",
			url    : 'scripts/process.php?act=get-decision-space&id='+$stateParams.dsId,
			headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
	}).then(function successCallback(response) {
		if(response.data.success)
		{
			$scope.formData = response.data.rec;
			$scope.path = response.data.rec.path;
			
			initiateWysiswyg();
			$(".wysiswyg-editor").val(response.data.description);
			$(".wysiswyg-editor").cleditor()[0].updateFrame(); 
		}
	}, function errorCallback(response) {
		
	});
	//////////////////////////////////////////////////
});
//////////controllers for decision space ends//////////

/*Auth functions*/
app.controller("CreateAccountController", function ($scope,$rootScope,$http,$location) {
    $scope.page = "create-account";
	$scope.pageTitle = "UrbanData - Create New Account";
	
	$scope.submitCreateAccountForm = function(isValid) {

		if (isValid)
		{
			$scope.loading = true;
			
			$http({
				method : "post",
				data   : $.param($scope.formData),
				url    : 'scripts/process.php?act=register-user',
				headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
			}).then(function successCallback(response) {
				
				if(!response.data.success) {
				  // if not successful, bind errors to error variables
				  $scope.firstNameError = response.data.errors.first_name;
				  $scope.lastNameError = response.data.errors.last_name;
				  $scope.usernameError = response.data.errors.username;
				  $scope.emailError = response.data.errors.email;
				  $scope.passwordError = response.data.errors.password;
				  $scope.confirmPasswordError = response.data.errors.confirm_password;
				}
				else
				{
				  // if successful, redirect to success page
				  $location.path('register-success');
				}
				$scope.loading = false;
				
			}, function errorCallback(response) {
				
				$scope.message = response.data.msg;
				$scope.loading = false;
				
			});
		}
	};
});

app.controller("LoginController", function ($scope,$rootScope,$http,$location) {
    $scope.page = "login";
	$scope.pageTitle = "UrbanData - Login";
	
	$scope.submitLoginForm = function(isValid) {
		
		if (isValid)
		{
			$scope.loading = true;
			
			$http({
				method : "post",
				data   : $.param($scope.formData),
				url    : 'scripts/process.php?act=member-login',
				headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
			}).then(function successCallback(response) {
				
				if(!response.data.success)
				{
				  // if not successful, bind errors to error variables
				  if(response.data.errors.msg=='Error')
				  {
					  $scope.message = response.data.msg;
				  }
				  else
				  {
				  	$scope.usernameError = response.data.errors.username;
				  	$scope.passwordError = response.data.errors.password;
				  }
				}
				else
				{
				  	$rootScope.user_type = response.data.user_type;
					$rootScope.loggedin_name = response.data.loggedin_name;
					$rootScope.user_arr = response.data.user_arr;
					
					if(response.data.user_type=='Admin')
					{
						$rootScope.adminSidebar = true;
						$rootScope.userSidebar = false;
					}
					else
					{
						$rootScope.adminSidebar = false;
						$rootScope.userSidebar = true;
					}
				  	$scope.success_message = response.data.msg;
				  	
					$location.path('home');
				}
				$scope.loading = false;
			}, function errorCallback(response) {
				$scope.message = response.data.msg;
				$scope.loading = false;
			});
		}
	};
});

app.controller("ForgotPasswordController", function ($scope,$rootScope,$http,$location) {
    $scope.page = "forgot-password";
	$scope.pageTitle = "UrbanData - Forgot Password";
	
	$scope.submitForgotForm = function(isValid) {

		if (isValid)
		{
			$scope.loading = true;
			
			$http({
				method : "post",
				data   : $.param($scope.formData),
				url    : 'scripts/process.php?act=forgot-password',
				headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
			}).then(function successCallback(response) {
				
				if(!response.data.success) {
				  // if not successful, bind errors to error variables
				  if(response.data.errors.msg=='Error')
				  {
					  $scope.message = response.data.msg;
				  }
				  else
				  {
				  	$scope.emailError = response.data.errors.email;
				  }
				}
				else
				{
				  	$scope.success_message = response.data.msg;
				}
				$scope.loading = false;
			}, function errorCallback(response) {
				$scope.message = response.data.msg;
				$scope.loading = false;
			});
		}
	};
});

app.controller("LogoutController", function ($scope,$rootScope,$http,$location) {
    $scope.pageTitle = "UrbanData - Logout";
	
	$http({
			method : "post",
			url    : 'scripts/process.php?act=logout',
			headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
	}).then(function successCallback(response) {
		if(response.data.success)
		{
			window.location.href = response.data.logout_url;
		}
	}, function errorCallback(response) {
		
	});
});
