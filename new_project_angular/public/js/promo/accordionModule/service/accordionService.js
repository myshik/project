/**
 * Created by pc3 on 11.02.15.
 */
angular.module('app')
    .factory('accordionService', function($http){
        var getUser = {
            async: function() {
                var promise = $http.get('/get_user').then(function (res) {
                    return res.data;
                });
                return promise;
            }
        };
        var getLinks = {
            async: function() {
                var promise = $http.get('/cabinet/get_links').then(function (res) {
                    return res.data;
                });
                return promise;
            }
        };
        return {
            getUser: getUser,
            getLinks: getLinks
        };
    });