const myTest = angular.module('mytest', []);

myTest.controller('getdata', ['$scope','$http', function($scope,$http){
	$http.get("http://localhost/cor_php/og.json").then(function (response) {
		$scope.myData=response.data;
	});
}]);