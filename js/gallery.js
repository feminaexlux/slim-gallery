(function () {
    "use strict";

    var app = angular.module('galleryApp', ['ngRoute']);

    app
        .config(['$routeProvider', '$httpProvider', function ($routeProvider, $httpProvider) {
            $routeProvider
                .when('/', {
                    controller: 'LatestController',
                    templateUrl: 'view/partial/latest.html'
                })
                .when('/album/:name', {
                    controller: 'AlbumController',
                    templateUrl: 'view/partial/album.html'
                })
                .when('/image/:name', {
                    controller: 'ImageController',
                    templateUrl: 'view/partial/image.html'
                })
                .otherwise({
                    redirectTo: '/'
                });

            $httpProvider.interceptors.push(function() {
                var container = document.getElementById('container');

                return {
                    'request': function(config) {
                        var height = Math.max(document.documentElement.clientHeight, window.innerHeight || 0)

                        container.style.background = "url('images/heart.svg') no-repeat center center fixed";
                        container.style.minHeight = height + "px";

                        return config;
                    },
                    'response': function(response) {
                        container.style.background = "none";
                        container.style.minHeight = "auto";
                        return response;
                    }
                };
            });
        }])
        .controller('LatestController', ['$http', '$scope', function ($http, $scope) {
            $http.get('/gallery/latest').success(function (data) {
                $scope.albumWidth = Math.floor(80 / data.length);
                $scope.tree = data;
            });
        }])
        .controller('AlbumController', ['$http', '$scope', '$routeParams', function ($http, $scope, $routeParams) {
            var name = $routeParams.name;

            $http.get('/gallery/album/' + name).success(function (data) {
                $scope.album = data;
            });
        }])
        .controller('ImageController', ['$http', '$scope', '$routeParams', function ($http, $scope, $routeParams) {
            var name = $routeParams.name;

            $http.get('/gallery/image/' + name).success(function (data) {
                $scope.image = data;
            });
        }])
})();
