'use strict';

sevenMonitor.factory('StatService',function( $q, $http) {

 var getStats = function () {
       return $http.get('/protected/stats');
   };
 return {
     getStats: getStats
 };
});