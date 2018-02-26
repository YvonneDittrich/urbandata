'use strict';

var app = angular.module("UrbanData", ["ui.router","ui.bootstrap"]);

app.config(function($stateProvider, $urlRouterProvider, $locationProvider) {
    
	/*$locationProvider.html5Mode({
	  enabled: true,
	  requireBase: false,
	  rewriteLinks: false
	});*/
	
    $urlRouterProvider.otherwise('/login');
    
    $stateProvider
        
        .state('login', {
            url: '/login',
            templateUrl: 'views/login.php',
			controller  : "LoginController",
			data: { pageTitle: 'UrbanData - Login' }
        })
		.state('create-account', {
            url: '/create-account',
            templateUrl: 'views/create-account.php',
			controller  : "CreateAccountController",
			data: { pageTitle: 'UrbanData - Create New Account' }     
        })
		.state('forgot-password', {
            url: '/forgot-password',
            templateUrl: 'views/forgot-password.php',
			controller  : "ForgotPasswordController",
			data: { pageTitle: 'UrbanData - Forgot Password' }     
        })
		.state('register-success', {
            url: '/register-success',
            templateUrl: 'views/register-success.html',
			data: { pageTitle: 'UrbanData - Successful Registration' }     
        })
		.state('logout', {
            url: '/logout',
			controller  : "LogoutController",
			data: { pageTitle: 'UrbanData - Logout' }     
        })
		.state('home', {
            url: '/home',
            templateUrl: 'views/home.php',
			controller  : "HomeController",
			data: { pageTitle: 'UrbanData | Urban Decision Maker' }
        })
		.state('decision-space', {
            url: '/decision-space/:dsId',
            templateUrl: 'views/decision-space.php',
			controller  : "DecisionSpaceController",
			data: { pageTitle: 'UrbanData | Decision Space' }
        })
		.state('notifications', {
            url: '/notifications',
            templateUrl: 'views/notifications.php',
			controller  : "NotificationsController",
			data: { pageTitle: 'UrbanData - Notifications' }     
        })
		.state('profile', {
            url: '/profile',
            templateUrl: 'views/profile.php',
			controller  : "ProfileController",
			data: { pageTitle: 'UrbanData - Profile' }     
        })
		.state('change-password', {
            url: '/change-password',
            templateUrl: 'views/change-password.php',
			controller  : "ChangePasswordController",
			data: { pageTitle: 'UrbanData - Change Password' }     
        })
		.state('urbandata-visualiser', {
            url: '/urbandata-visualiser',
            templateUrl: 'views/urbandata-visualiser.php',
			controller  : "UrbanDataVisualiserController",
			data: { pageTitle: 'UrbanData - UrbanData Visualiser' }     
        })
		.state('decision-spaces', {
            url: '/decision-spaces',
            templateUrl: 'views/decision-spaces.php',
			controller  : "DecisionSpacesController",
			data: { pageTitle: 'UrbanData - Decision Spaces' }     
        })
		.state('add-decision-space', {
            url: '/ds/add',
            templateUrl: 'views/add-decision-space.php',
			controller  : "AddDecisionSpaceController",
			data: { pageTitle: 'UrbanData - Add Decision Space' }     
        })
		.state('edit-decision-space', {
            url: '/ds/:dsId',
            templateUrl: 'views/edit-decision-space.php',
			controller  : "UpdateDecisionSpaceController",
			data: { pageTitle: 'UrbanData - Update Decision space' }     
        })
		.state('dashboard', {
            url: '/dashboard',
            templateUrl: 'views/dashboard.php',
			controller  : "DashboardController",
			data: { pageTitle: 'UrbanData - Dashboard' }     
        })
		.state('export-pool', {
            url: '/export-pool',
            templateUrl: 'views/export-pool.php',
			controller  : "ExportPoolController",
			data: { pageTitle: 'UrbanData - Export Pool' }     
        })
		.state('help', {
            url: '/help',
            templateUrl: 'views/help.php',
			data: { pageTitle: 'UrbanData - Help' }     
        })
		.state('terms-and-conditions', {
            url: '/terms-and-conditions',
			templateUrl: 'views/terms-and-conditions.php',
			data: { pageTitle: 'Tutor Choice - Terms and Conditions' }     
        })
		.state('privacy-policy', {
            url: '/privacy-policy',
			templateUrl: 'views/privacy-policy.php',
			data: { pageTitle: 'Tutor Choice - Privacy Policy' }     
        });
});