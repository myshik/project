/**
 * Created by pc3 on 13.01.15.
 */
var app = angular.module('MyApp');
app.config(['$routeProvider', '$locationProvider',
    function($routeProvider , $locationProvider) {
        $routeProvider.
            when('/private', {
                templateUrl: 'mylinks',
                controller: 'MainController'
            }).
            when('/all', {
                templateUrl: 'all_links',
                controller: 'MainController'
            }).
            when('/edit', {
                templateUrl: 'edit_profile',
                controller: 'MainController'
            })
        $locationProvider.html5Mode(true);
    }]);
app.controller('MainController', function ($scope, $http) {
    $scope.selected = {};
    $scope.status = "all";
    $http.get('/links')
        .success(function(data) {
            $scope.totalItems = data.length;
            $scope.currentPage = 1;
            $scope.numPerPage = 10;
            $scope.linksstate = data;
            pagination(data);
        })
        .error(function(data) {
            console.log('Error: ' + data);
        });
    $http.get('/edit_user')
        .success(function(data) {
            $scope.user = data;
        })
        .error(function(data) {
            console.log('Error: ' + data);
        });

    $scope.addlink = function(){
        $http.get('http://api.bit.ly/v3/shorten', {
            params: {
                longUrl: $scope.link,
                apiKey:'R_a504087bf6004b44a4c9026f77e65aa5',
                login:'reexar'
            }
        }).
            success(function(data) {
                $scope.shortLink = data.data.url
                $http.post("/links/",{params: {
                    longUrl: $scope.link,
                    shortUrl: data.data.url
                }
                }).success(function(data) {
                    $scope.totalItems = data.length;
                    $scope.linksstate = data;
                    pagination(data)
                }).error(function(data, error, errorMessage) {
                    console.error(arguments);
                    alert("Неудалось сохранить ссылки в БД");
                    $("html").html(data.responseText);
                })
            }).
            error(function(data) {
                alert("Не получилос создать короткую ссылку");
            });
    }

    $scope.checked = function(id){
        $http.put('/links', {params: {
            id: id }
        })
            .success(function(data) {
                $scope.linksstate = data;
                $scope.links = data;
                if($scope.status == "checked"){
                    $scope.links = $.grep($scope.links, function( links ) {
                        if(links.checked)
                            return links;
                    });
                    $scope.totalItems = $scope.links.length;
                }
                if($scope.status == "unchecked"){
                    $scope.links = $.grep($scope.links, function( links ) {
                        if(!links.checked){
                            return links;
                        }
                    });
                    $scope.totalItems = $scope.links.length;
                }
                pagination($scope.links)
            })
            .error(function(data) {
                console.log('Error: ' + data);
            });
    }

    $scope.deletelinks = function(){
        $scope.links = $.grep($scope.links, function( links ) {
            if(links.checked){
                $http.delete('/links/', {params: {
                    id:  links._id}
                })
                    .success(function(data) {
                        $scope.totalItems = data.length;
                        $scope.linksstate = data;
                        $scope.links = data;
                        if($scope.status == "checked"){
                            $scope.links = $.grep($scope.links, function( links ) {
                                if(links.checked)
                                    return links;
                            });
                            $scope.totalItems = $scope.links.length;
                        }
                        if($scope.status == "unchecked"){
                            $scope.links = $.grep($scope.links, function( links ) {
                                if(!links.checked){
                                    return links;
                                }
                            });
                            $scope.totalItems = $scope.links.length;
                        }
                        pagination($scope.links)
                    })
                    .error(function(data) {
                        console.log('Error: ' + data);
                    });
            }else{
                return $scope.links;
            }
        });
    }

    $scope.deleteAlllinks = function(){
        $scope.links = $.grep($scope.links, function( links ) {
            $http.delete('/links/', {params: {
                id:  links._id}
            })
                .success(function(data) {
                    $scope.totalItems = data.length;
                    $scope.linksstate = data;
                    $scope.links = data;
                    if($scope.status == "checked"){
                        $scope.links = $.grep($scope.links, function( links ) {
                            if(links.checked)
                                return links;
                        });
                        $scope.totalItems = $scope.links.length;
                    }
                    if($scope.status == "unchecked"){
                        $scope.links = $.grep($scope.links, function( links ) {
                            if(!links.checked){
                                return links;
                            }
                        });
                        $scope.totalItems = $scope.links.length;
                    }
                    pagination($scope.links)
                })
                .error(function(data) {
                    console.log('Error: ' + data);
                });
        });
    }

    $scope.showall = function(){
        $scope.links = $scope.linksstate;
        $scope.totalItems = $scope.links.length;
        $scope.status = "all";
        pagination($scope.links)
    }

    $scope.showchecked = function(){
        $scope.links = $scope.linksstate;
        $scope.status = "checked";
        $scope.links = $.grep($scope.links, function( links ) {
            if(links.checked)
                return links;
        });
        $scope.totalItems = $scope.links.length;
        pagination($scope.links)
    }

    $scope.showunchecked = function(){
        $scope.links = $scope.linksstate;
        $scope.status = "unchecked";
        $scope.links = $.grep($scope.links, function( links ) {
            if(!links.checked){
                return links;
            }
        });
        $scope.totalItems = $scope.links.length;
        pagination($scope.links)
    }

    $scope.update_user = function(id){
        $http.put('/edit_user', {params: {
            id: id,
            username: $scope.user.username,
            hender: $scope.user.gender,
            password: $scope.user.password}
        })
            .success(function(data) {

            })
            .error(function(data) {
                console.log('Error: ' + data);
            });
    }

    function pagination(data){
        $scope.setPage = function (pageNo) {
            $scope.currentPage = pageNo;
        };
        $scope.pageChanged = function() {
            var begin = (($scope.currentPage - 1) * $scope.numPerPage)
                , end = begin + $scope.numPerPage;
            $scope.links = data.slice(begin, end);
        };
        var begin = (($scope.currentPage - 1) * $scope.numPerPage)
            , end = begin + $scope.numPerPage;
        $scope.links = data.slice(begin, end);
    }
});

app.controller('LinkController', function ($scope, $http, $window){
    $scope.logout = function(){
        $window.location.href = '/logout';
    }
})