(function() {
    var app = angular.module('galleryApp', []);

    app.controller('LatestController', ['$http', function($http){
        var latest = this;
        latest.images = [];
        $http.get('/gallery/latest').success(function(data) {
            latest.images = data;
        });
    }]);
})();
