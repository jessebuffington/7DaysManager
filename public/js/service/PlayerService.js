'use strict';

sevenMonitor.factory('PlayerService',function( $q, $http) {

 var getPlayers = function () {
       return $http.get('/protected/players');
   };
 return {
   getPlayers: getPlayers
 };
});