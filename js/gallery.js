(function () {
    var app = angular.module('galleryApp', ['ngRoute']);

    app
        .config(['$routeProvider', function ($routeProvider) {
            $routeProvider
                .when('/', {
                    controller: 'LatestController',
                    templateUrl: 'view/partial/latest.html'
                })
                .when('/album/:name', {
                    controller: 'AlbumController',
                    templateUrl: 'view/partial/album.html'
                })
                .otherwise({
                    redirectTo: '/'
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
})();
