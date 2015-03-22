(function () {
    "use strict";

    document.getElementById('current_year').textContent = '' + new Date().getFullYear();

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
                var container = document.getElementsByTagName('html')[0];

                return {
                    'request': function(config) {
                        container.style.background = "url('images/heart.svg') no-repeat center center fixed";
                        return config;
                    },
                    'response': function(response) {
                        container.style.background = "none";
                        return response;
                    }
                };
            });
        }])
        .controller('LatestController', ['$http', '$scope', function ($http, $scope) {
            $http.get('/gallery/latest').success(function (data) {
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
