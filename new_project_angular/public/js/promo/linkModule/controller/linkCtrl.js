/**
 * Created by pc3 on 13.01.15.
 */
angular.module('app')
    .controller('MainController', function ($scope, $http, LinkService) {

        var currentPage = 1,
            numPerPage = 10,
            linksstate = "",
            status = "all",
            user = "",
            links = "";

        $scope.currentPage = currentPage;
        $scope.numPerPage = numPerPage;
        $scope.linksstate = linksstate;
        $scope.status = status;
        $scope.user = user;
        $scope.links = links;

        /**
         * get Links work
         */
        LinkService.getLinks.async().then(function(data) {
            $scope.totalItems = data.length;
            linksstate = data;
            pagination(data);
        });
        LinkService.editProfile.async().then(function(data){
            user = data;
        })

        $scope.addlink = function(){
            var param = {
                params: {
                    longUrl: $scope.link,
                    apiKey:'R_a504087bf6004b44a4c9026f77e65aa5',
                    login:'reexar'
                }
            }
            LinkService.addLinks.async(param).then(function(data){
                $scope.shortLink = data.data.url;
                $http.post("/cabinet/links/",{params: {
                    longUrl: $scope.link,
                    shortUrl: data.data.url
                }
                }).success(function(data) {
                    $scope.totalItems = data.length;
                    linksstate = data;
                    pagination(data)
                }).error(function(data, error, errorMessage) {
                    console.error(arguments);
                    alert("Неудалось сохранить ссылки в БД");
                    $("html").html(data.responseText);
                })
            })
        }
        /**
         * checked Links work
         * @param id
         */
        $scope.checked = function(id) {
            LinkService.checkedLinks.async(id).then(function(data){
                linksstate = data;
                links = data;
                if(status == "checked"){
                    links = $.grep(links, function( link ) {
                        if(link.checked)
                            return link;
                    });
                    $scope.totalItems = links.length;
                }
                if(status == "unchecked"){
                    links = $.grep(links, function( link ) {
                        if(!link.checked){
                            return link;
                        }
                    });
                    $scope.totalItems = links.length;
                }
                pagination(links)
            });
        }
        /**
         * deleteLinks
         */
        $scope.deleteLinks = function(){
            $scope.links = $.grep($scope.links, function( links ) {
                if(links.checked){
                    var param = {params: {id:  links._id}};
                    LinkService.deleteLink.async(param).then(function(data){
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
                    });
                }else{
                    return $scope.links;
                }
            });
        }
        /**
         * deleteAllLinks
         */
        $scope.deleteAllLinks = function(){
            $scope.links = $.grep($scope.links, function( links ) {
                var param = {params: {id:  links._id}};
                LinkService.deleteLink.async(param).then(function(data) {
                    $scope.totalItems = data.length;
                    $scope.linksstate = data;
                    $scope.links = data;
                    if ($scope.status == "checked") {
                        $scope.links = $.grep($scope.links, function (links) {
                            if (links.checked)
                                return links;
                        });
                        $scope.totalItems = $scope.links.length;
                    }
                    if ($scope.status == "unchecked") {
                        $scope.links = $.grep($scope.links, function (links) {
                            if (!links.checked) {
                                return links;
                            }
                        });
                        $scope.totalItems = $scope.links.length;
                    }
                    pagination($scope.links)
                })
            });
        }
        /**
         *
         */
        $scope.showall = function(){
            links = linksstate;
            $scope.totalItems = links.length;
            status = "all";
            pagination(links)
        }

        $scope.showchecked = function(){
            links = linksstate;
            status = "checked";
            links = $.grep(links, function( link ) {
                if(link.checked)
                    return link;
            });
            $scope.totalItems = links.length;
            pagination(links)
        }

        $scope.showunchecked = function(){
            links = linksstate;
            status = "unchecked";
            links = $.grep(links, function( link ) {
                if(!link.checked){
                    return link;
                }
            });
            $scope.totalItems = links.length;
            pagination(links)
        }

        $scope.update_user = function(id){
            var param = {
                params: {
                        id: id,
                        username: $scope.user.username,
                        hender: $scope.user.gender,
                        password: $scope.user.password
                    }
                }
            LinkService.updateProfile.async(param).then(function(data){})
        }

        function pagination(data){
            $scope.setPage = function (pageNo) {
                $scope.currentPage = pageNo;
            };
            $scope.pageChanged = function() {
                var begin = (($scope.currentPage - 1) * $scope.numPerPage),
                    end = begin + $scope.numPerPage;
                $scope.links = data.slice(begin, end);
            };
            var begin = (($scope.currentPage - 1) * $scope.numPerPage),
                end = begin + $scope.numPerPage;
            $scope.links = data.slice(begin, end);
        }
})
    .controller('MenuCtrl', function ($scope, $http, $window){
        $scope.logout = function(){
            $window.location.href = '/logout';
        }
    })