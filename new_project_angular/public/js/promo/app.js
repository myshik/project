angular.module('app',['ui.bootstrap' , 'ngRoute'])
    .config(['$routeProvider', '$locationProvider',
    function($routeProvider , $locationProvider) {
        $routeProvider.
            when('/cabinet', {
                templateUrl: 'cabinet/links_view',
                controller: 'MainController'
            }).
            when('/cabinet/all', {
                templateUrl: '/cabinet/all_links_view',
                controller: 'MainController'
            }).
            when('/edit_profile', {
                templateUrl: '/cabinet/edit_profile',
                controller: 'MainController'
            })
        $locationProvider.html5Mode(true);
    }]);