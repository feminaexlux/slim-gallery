(function() {
    var app = angular.module('galleryApp', []);

    app.controller('LatestController', ['$http', function($http){
        var latest = this;
        latest.tree = [];
        $http.get('/gallery/latest').success(function(data) {
            latest.tree = data;
            console.log(data);
        });
    }]);
})();
