/**
 * Created by pc3 on 12.02.15.
 */
angular.module('app')
    .factory('LinkService', function($http){
        var getLinks = {
            async: function() {
                var promise = $http.get('/cabinet/links').then(function (res) {
                    return res.data;
                });
                return promise;
            }
        };
        var addLinks = {
            async: function(param) {
                var promise = $http.get('http://api.bit.ly/v3/shorten', param).then(function (res) {
                    return res.data;
                });
                return promise;
            }
        };
        var editProfile = {
            async: function() {
                var promise = $http.get('/cabinet/edit_user').then(function (res) {
                    return res.data;
                });
                return promise;
            }
        };
        var updateProfile = {
            async: function(param) {
                var promise = $http.put('/cabinet/edit_user', param).then(function (res) {
                    return res.data;
                });
                return promise;
            }
        };
        var checkedLinks = {
            async: function(id) {
                var promise = $http.put('/cabinet/links', {params: {id: id }}).then(function (res) {
                    return res.data;
                });
                return promise;
            }
        };
        var deleteLink = {
            async: function(param) {
                var promise = $http.delete('/cabinet/links/', param).then(function (res) {
                    return res.data;
                });
                return promise;
            }
        };
        return {
            getLinks: getLinks,
            addLinks: addLinks,
            editProfile: editProfile,
            updateProfile: updateProfile,
            checkedLinks: checkedLinks,
            deleteLink: deleteLink
        };
    })
